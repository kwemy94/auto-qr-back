<?php

namespace Tests\Feature;

use App\Models\QrNotification;
use App\Models\User;
use App\Services\FcmService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class NotifySendTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_id_is_sent_with_fcm_message(): void
    {
        $user = User::factory()->create();
        $user->forceFill(['qr_code' => 'qr-test-token', 'fcm_token' => 'device-token'])->save();

        $capturedId = null;
        $this->mock(FcmService::class, function ($mock) use (&$capturedId) {
            $mock->shouldReceive('send')
                ->once()
                ->with('device-token', 'lights', Mockery::on(function ($id) use (&$capturedId) {
                    $capturedId = $id;
                    return is_int($id);
                }))
                ->andReturn(true);
        });

        $this->postJson('/n/qr-test-token/send', ['message_key' => 'lights'])
            ->assertOk()
            ->assertJson(['success' => true, 'sent' => true]);

        $notification = QrNotification::sole();
        $this->assertSame($notification->id, $capturedId);
        $this->assertSame($user->id, $notification->user_id);
    }
}
