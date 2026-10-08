<?php

namespace Tests\Feature;

use App\Mail\ContactMessageMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    private function authHeaders(User $user): array
    {
        return ['Authorization' => 'Bearer ' . JWTAuth::fromUser($user)];
    }

    private array $payload = [
        'email' => 'user@example.com',
        'subject' => 'Login problem',
        'message' => 'I cannot log in.',
    ];

    public function test_requires_authentication(): void
    {
        $this->postJson('/api/contact', $this->payload)->assertUnauthorized();
    }

    public function test_message_is_stored_and_mailed_to_admin(): void
    {
        Mail::fake();
        config(['mail.contact_address' => 'admin@example.com']);
        $user = User::factory()->create();

        $this->postJson('/api/contact', $this->payload, $this->authHeaders($user))
            ->assertCreated()
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('contact_messages', [
            'user_id' => $user->id,
            'email' => 'user@example.com',
            'subject' => 'Login problem',
        ]);
        $this->assertDatabaseMissing('contact_messages', ['mailed_at' => null]);

        Mail::assertSent(ContactMessageMail::class, fn ($mail) =>
            $mail->hasTo('admin@example.com') && $mail->hasReplyTo('user@example.com'));
    }

    public function test_message_is_kept_when_no_admin_address(): void
    {
        Mail::fake();
        config(['mail.contact_address' => null]);
        $user = User::factory()->create();

        $this->postJson('/api/contact', $this->payload, $this->authHeaders($user))->assertCreated();

        $this->assertDatabaseHas('contact_messages', ['subject' => 'Login problem', 'mailed_at' => null]);
        Mail::assertNothingSent();
    }

    public function test_validation_errors(): void
    {
        $user = User::factory()->create();

        $this->postJson('/api/contact', ['email' => 'not-an-email'], $this->authHeaders($user))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'subject', 'message']);
    }
}
