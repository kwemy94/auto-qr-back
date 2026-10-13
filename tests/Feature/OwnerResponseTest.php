<?php

namespace Tests\Feature;

use App\Models\QrNotification;
use App\Models\User;
use App\Services\FcmService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class OwnerResponseTest extends TestCase
{
    use RefreshDatabase;

    private function authHeaders(User $user): array
    {
        return ['Authorization' => 'Bearer ' . JWTAuth::fromUser($user)];
    }

    private function makeNotification(User $owner): QrNotification
    {
        return QrNotification::create([
            'user_id' => $owner->id,
            'message_key' => 'blocking',
            'message_text' => 'x',
            'ip_hash' => 'h',
            'is_read' => false,
        ]);
    }

    public function test_report_flow_from_send_to_owner_response(): void
    {
        $owner = User::factory()->create();
        $owner->forceFill(['qr_code' => 'qr-token', 'fcm_token' => 'device'])->save();
        $this->mock(FcmService::class, fn ($m) => $m->shouldReceive('send')->andReturn(true));

        // 1. Le signaleur envoie : il reçoit un identifiant de suivi
        $reportId = $this->postJson('/n/qr-token/send', ['message_key' => 'blocking'])
            ->assertOk()
            ->json('report_id');
        $this->assertTrue(\Illuminate\Support\Str::isUuid($reportId));

        // 2. Statut initial : non vu, pas de réponse, aucune donnée du propriétaire
        $this->getJson("/n/report/{$reportId}")
            ->assertOk()
            ->assertJson(['is_read' => false, 'response_key' => null])
            ->assertJsonMissingPath('user_id')
            ->assertJsonMissingPath('message_text');

        // 3. Le propriétaire répond « Dans 10 min »
        $notification = QrNotification::sole();
        $this->postJson("/api/notifications/{$notification->id}/respond", ['response' => 'min10'], $this->authHeaders($owner))
            ->assertOk()
            ->assertJsonPath('notification.response_key', 'min10');

        // 4. Le signaleur voit la réponse
        $this->getJson("/n/report/{$reportId}")
            ->assertOk()
            ->assertJson(['is_read' => true, 'response_key' => 'min10', 'response_minutes' => 10]);
    }

    public function test_invalid_response_is_rejected(): void
    {
        $owner = User::factory()->create();
        $notification = $this->makeNotification($owner);

        $this->postJson("/api/notifications/{$notification->id}/respond", ['response' => 'whatever'], $this->authHeaders($owner))
            ->assertStatus(422);
    }

    public function test_cannot_respond_to_someone_elses_notification(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $notification = $this->makeNotification($owner);

        $this->postJson("/api/notifications/{$notification->id}/respond", ['response' => 'coming'], $this->authHeaders($intruder))
            ->assertNotFound();

        $this->assertNull($notification->fresh()->response_key);
    }

    public function test_unknown_report_id_returns_404(): void
    {
        $this->getJson('/n/report/' . \Illuminate\Support\Str::uuid())->assertNotFound();
    }

    public function test_owner_list_includes_response(): void
    {
        $owner = User::factory()->create();
        $notification = $this->makeNotification($owner);
        $notification->update(['response_key' => 'coming', 'responded_at' => now()]);

        $this->getJson('/api/notifications', $this->authHeaders($owner))
            ->assertOk()
            ->assertJsonPath('notifications.0.response_key', 'coming');
    }
}
