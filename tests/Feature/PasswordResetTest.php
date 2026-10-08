<?php

namespace Tests\Feature;

use App\Mail\PasswordResetCodeMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    private function sendCodeFor(User $user): string
    {
        Mail::fake();
        $this->postJson('/api/auth/forgot-password', ['email' => $user->email])->assertOk();

        $code = null;
        Mail::assertSent(PasswordResetCodeMail::class, function ($mail) use (&$code, $user) {
            $code = $mail->code;
            return $mail->hasTo($user->email);
        });

        return $code;
    }

    public function test_unknown_email_gets_same_response_and_no_mail(): void
    {
        Mail::fake();

        $this->postJson('/api/auth/forgot-password', ['email' => 'nobody@example.com'])
            ->assertOk()
            ->assertJson(['success' => true]);

        Mail::assertNothingSent();
    }

    public function test_valid_code_resets_password_once(): void
    {
        $user = User::factory()->create(['email' => 'owner@example.com']);
        $code = $this->sendCodeFor($user);

        $this->assertMatchesRegularExpression('/^\d{6}$/', $code);

        $payload = [
            'email' => $user->email,
            'code' => $code,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ];

        $this->postJson('/api/auth/reset-password', $payload)->assertOk();
        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));

        // Le code est à usage unique
        $this->postJson('/api/auth/reset-password', $payload)->assertStatus(422);
    }

    public function test_wrong_code_is_rejected(): void
    {
        $user = User::factory()->create();
        $code = $this->sendCodeFor($user);

        $this->postJson('/api/auth/reset-password', [
            'email' => $user->email,
            'code' => $code === '123456' ? '654321' : '123456',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ])->assertStatus(422);
    }

    public function test_expired_code_is_rejected(): void
    {
        $user = User::factory()->create();
        $code = $this->sendCodeFor($user);

        DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->update(['created_at' => now()->subMinutes(16)]);

        $this->postJson('/api/auth/reset-password', [
            'email' => $user->email,
            'code' => $code,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ])->assertStatus(422);
    }

    public function test_message_follows_accept_language(): void
    {
        $this->postJson('/api/auth/forgot-password', ['email' => 'x@example.com'], ['Accept-Language' => 'fr'])
            ->assertJson(['message' => __('mobile.reset_code_sent', [], 'fr')]);

        $this->postJson('/api/auth/forgot-password', ['email' => 'y@example.com'], ['Accept-Language' => 'en'])
            ->assertJson(['message' => __('mobile.reset_code_sent', [], 'en')]);
    }
}
