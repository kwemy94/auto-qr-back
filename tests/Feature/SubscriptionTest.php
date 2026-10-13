<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\QrNotification;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\User;
use App\Services\FcmService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['payments.driver' => 'fake', 'payments.fake_confirm_after' => 0]);
    }

    private function headers(User $user): array
    {
        return ['Authorization' => 'Bearer ' . JWTAuth::fromUser($user)];
    }

    private function monthly(): Package
    {
        return Package::where('name', 'Mensuel')->firstOrFail();
    }

    private function expiredUser(): User
    {
        $user = User::factory()->create();
        $user->forceFill(['end_trial_period' => now()->subDay()->toDateString()])->save();
        return $user;
    }

    public function test_new_user_is_in_trial_and_plans_are_listed(): void
    {
        $user = User::factory()->create();

        $this->getJson('/api/subscription', $this->headers($user))
            ->assertOk()
            ->assertJsonPath('subscription.state', 'trial')
            ->assertJsonPath('payments_available', true);

        $this->getJson('/api/subscription/plans', $this->headers($user))
            ->assertOk()
            ->assertJsonCount(3, 'plans')
            ->assertJsonPath('plans.0.name', 'Mensuel')
            ->assertJsonPath('plans.0.amount', 1000);
    }

    public function test_successful_payment_activates_subscription(): void
    {
        $user = $this->expiredUser();

        $reference = $this->postJson('/api/subscription/checkout', [
            'package_id' => $this->monthly()->id,
            'operator' => 'mtn',
            'phone' => '+237677123456',
        ], $this->headers($user))
            ->assertCreated()
            ->assertJsonPath('payment.status', 'pending')
            ->assertJsonPath('test_mode', true)
            ->json('payment.reference');

        $this->getJson("/api/subscription/payments/{$reference}", $this->headers($user))
            ->assertOk()
            ->assertJsonPath('payment.status', 'success')
            ->assertJsonPath('subscription.state', 'active')
            ->assertJsonPath('subscription.plan', 'Mensuel');

        $this->assertDatabaseHas('subscription_payments', ['reference' => $reference, 'phone' => '677123456']);
        $this->assertSame(1, Subscription::where('user_id', $user->id)->count());

        // Idempotent : une nouvelle vérification ne crée pas un 2e abonnement
        $this->getJson("/api/subscription/payments/{$reference}", $this->headers($user))->assertOk();
        $this->assertSame(1, Subscription::where('user_id', $user->id)->count());
    }

    public function test_renewal_extends_current_subscription(): void
    {
        $user = $this->expiredUser();
        $end = now()->addDays(10);
        Subscription::create([
            'user_id' => $user->id,
            'package_id' => $this->monthly()->id,
            'start_date' => now()->subDays(20),
            'end_date' => $end,
        ]);

        $reference = $this->postJson('/api/subscription/checkout', [
            'package_id' => $this->monthly()->id, 'operator' => 'orange', 'phone' => '699112233',
        ], $this->headers($user))->json('payment.reference');
        $this->getJson("/api/subscription/payments/{$reference}", $this->headers($user));

        $latest = Subscription::where('user_id', $user->id)->orderByDesc('end_date')->first();
        $this->assertEquals($end->copy()->addMonth()->toDateString(), \Illuminate\Support\Carbon::parse($latest->end_date)->toDateString());
    }

    public function test_failed_payment_does_not_activate(): void
    {
        $user = $this->expiredUser();

        $reference = $this->postJson('/api/subscription/checkout', [
            'package_id' => $this->monthly()->id, 'operator' => 'mtn', 'phone' => '677123000',
        ], $this->headers($user))->json('payment.reference');

        $this->getJson("/api/subscription/payments/{$reference}", $this->headers($user))
            ->assertJsonPath('payment.status', 'failed')
            ->assertJsonPath('payment.failure_reason', 'insufficient_funds')
            ->assertJsonPath('subscription.state', 'expired');
    }

    public function test_checkout_validation(): void
    {
        $user = User::factory()->create();

        $this->postJson('/api/subscription/checkout', [
            'package_id' => $this->monthly()->id, 'operator' => 'wave', 'phone' => '12345',
        ], $this->headers($user))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['operator', 'phone']);

        $retired = Package::create(['name' => 'Ancienne offre', 'duration' => 3, 'amount' => 7000, 'is_active' => false]);
        $this->postJson('/api/subscription/checkout', [
            'package_id' => $retired->id, 'operator' => 'mtn', 'phone' => '677123456',
        ], $this->headers($user))->assertStatus(422);
    }

    public function test_fake_payments_are_refused_in_production(): void
    {
        $this->app['env'] = 'production';
        $user = User::factory()->create();

        $this->getJson('/api/subscription', $this->headers($user))
            ->assertJsonPath('payments_available', false);

        $this->postJson('/api/subscription/checkout', [
            'package_id' => $this->monthly()->id, 'operator' => 'mtn', 'phone' => '677123456',
        ], $this->headers($user))->assertStatus(503);

        $this->assertDatabaseCount('subscription_payments', 0);
    }

    public function test_cannot_read_someone_elses_payment(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $payment = SubscriptionPayment::create([
            'user_id' => $owner->id, 'package_id' => $this->monthly()->id, 'provider' => 'fake',
            'amount' => 1000, 'operator' => 'mtn', 'phone' => '677123456',
        ]);

        $this->getJson("/api/subscription/payments/{$payment->reference}", $this->headers($other))
            ->assertNotFound();
    }

    public function test_old_free_subscribe_route_is_gone(): void
    {
        $user = User::factory()->create();
        $this->postJson('/api/subscribe', ['user_id' => $user->id, 'package_id' => 1], $this->headers($user))
            ->assertNotFound();
    }

    public function test_push_is_skipped_when_trial_expired_but_report_is_recorded(): void
    {
        $owner = $this->expiredUser();
        $owner->forceFill(['qr_code' => 'qr-x', 'fcm_token' => 'device'])->save();
        $this->mock(FcmService::class, fn ($m) => $m->shouldNotReceive('send'));

        $this->postJson('/n/qr-x/send', ['message_key' => 'lights'])
            ->assertOk()
            ->assertJsonPath('sent', false);

        $this->assertSame(1, QrNotification::where('user_id', $owner->id)->count());
    }

    public function test_alerts_are_not_blocked_while_payments_are_unavailable(): void
    {
        // Production sans agrégateur : impossible de payer → on ne coupe pas les alertes
        $this->app['env'] = 'production';
        $owner = $this->expiredUser();
        $owner->forceFill(['qr_code' => 'qr-z', 'fcm_token' => 'device'])->save();
        $this->mock(FcmService::class, fn ($m) => $m->shouldReceive('send')->once()->andReturn(true));

        $this->postJson('/n/qr-z/send', ['message_key' => 'lights'])->assertJsonPath('sent', true);
    }

    public function test_push_is_sent_during_trial(): void
    {
        $owner = User::factory()->create(); // essai de 30 jours à la création
        $owner->forceFill(['qr_code' => 'qr-y', 'fcm_token' => 'device'])->save();
        $this->mock(FcmService::class, fn ($m) => $m->shouldReceive('send')->once()->andReturn(true));

        $this->postJson('/n/qr-y/send', ['message_key' => 'lights'])->assertJsonPath('sent', true);
    }
}
