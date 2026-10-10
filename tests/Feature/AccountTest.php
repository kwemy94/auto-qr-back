<?php

namespace Tests\Feature;

use App\Models\QrNotification;
use App\Models\User;
use App\Services\FcmService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    private function authHeaders(User $user): array
    {
        return ['Authorization' => 'Bearer ' . JWTAuth::fromUser($user)];
    }

    public function test_update_profile_validates_and_returns_user(): void
    {
        $user = User::factory()->create(['name' => 'Old', 'email' => 'old@example.com']);
        User::factory()->create(['email' => 'taken@example.com']);

        $this->postJson('/api/profile', ['name' => 'New Name', 'email' => 'new@example.com'], $this->authHeaders($user))
            ->assertOk()
            ->assertJsonPath('user.name', 'New Name')
            ->assertJsonPath('user.email', 'new@example.com');

        $this->postJson('/api/profile', ['email' => 'taken@example.com'], $this->authHeaders($user))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_change_password(): void
    {
        $user = User::factory()->create(['password' => Hash::make('oldpassword')]);

        $this->postJson('/api/auth/change-password', [
            'current_password' => 'wrong',
            'new_password' => 'newpassword1',
            'new_password_confirmation' => 'newpassword1',
        ], $this->authHeaders($user))->assertStatus(422)->assertJsonValidationErrors(['current_password']);

        $this->postJson('/api/auth/change-password', [
            'current_password' => 'oldpassword',
            'new_password' => 'newpassword1',
            'new_password_confirmation' => 'newpassword1',
        ], $this->authHeaders($user))->assertOk();

        $this->assertTrue(Hash::check('newpassword1', $user->fresh()->password));
    }

    public function test_delete_account_requires_correct_password_and_cascades(): void
    {
        $user = User::factory()->create(['password' => Hash::make('secret123')]);
        QrNotification::create([
            'user_id' => $user->id,
            'message_key' => 'lights',
            'message_text' => 'x',
            'ip_hash' => 'h',
            'is_read' => false,
        ]);
        $headers = $this->authHeaders($user);

        $this->deleteJson('/api/auth/account', ['password' => 'bad'], $headers)->assertStatus(422);
        $this->assertDatabaseHas('users', ['id' => $user->id]);

        $this->deleteJson('/api/auth/account', ['password' => 'secret123'], $headers)->assertOk();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('qr_notifications', ['user_id' => $user->id]);
    }

    public function test_test_notification_without_device_token(): void
    {
        $user = User::factory()->create();

        $this->postJson('/api/notifications/test', [], $this->authHeaders($user))->assertStatus(422);
    }

    public function test_test_notification_is_sent_and_not_stored(): void
    {
        $user = User::factory()->create();
        $user->forceFill(['fcm_token' => 'device-token'])->save();

        $this->mock(FcmService::class, fn ($mock) =>
            $mock->shouldReceive('sendTest')->once()->with('device-token')->andReturn(true));

        $this->postJson('/api/notifications/test', [], $this->authHeaders($user))
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertDatabaseCount('qr_notifications', 0);
    }
}
