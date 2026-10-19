<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\FcmService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Les notifications push et l'historique ne contiennent pas d'émojis. */
class PushNotificationTextTest extends TestCase
{
    use RefreshDatabase;

    private const EMOJI = '/[\x{1F000}-\x{1FFFF}\x{2600}-\x{27BF}\x{FE0F}]/u';

    public function test_push_title_and_messages_have_no_emoji(): void
    {
        $this->assertDoesNotMatchRegularExpression(self::EMOJI, FcmService::TITLE);
        foreach (FcmService::MESSAGES as $key => $text) {
            $this->assertDoesNotMatchRegularExpression(self::EMOJI, $text, "Émoji dans le message « {$key} »");
        }
        foreach (['fr', 'en'] as $locale) {
            $this->assertDoesNotMatchRegularExpression(self::EMOJI, __('mobile.test_notification_title', [], $locale));
            $this->assertDoesNotMatchRegularExpression(self::EMOJI, __('mobile.test_notification_body', [], $locale));
        }
    }

    public function test_migration_cleans_existing_reports(): void
    {
        $owner = User::factory()->create();
        \Illuminate\Support\Facades\DB::table('qr_notifications')->insert([
            ['user_id' => $owner->id, 'message_key' => 'lights', 'message_text' => '💡 Vos phares sont allumés. Pensez à les éteindre.', 'is_read' => false, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $owner->id, 'message_key' => 'legacy', 'message_text' => '🪟 Ancien motif inconnu', 'is_read' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $migration = require database_path('migrations/migration_notifications_strip_emojis.php');
        $migration->up();

        $texts = \Illuminate\Support\Facades\DB::table('qr_notifications')->orderBy('id')->pluck('message_text')->all();
        $this->assertSame([FcmService::MESSAGES['lights'], 'Ancien motif inconnu'], $texts);
    }

    public function test_stored_report_text_has_no_emoji(): void
    {
        $owner = User::factory()->create();
        $owner->forceFill(['qr_code' => 'qr-txt', 'fcm_token' => 'device'])->save();
        $this->mock(FcmService::class, fn ($m) => $m->shouldReceive('send')->andReturn(true));

        $this->postJson('/n/qr-txt/send', ['message_key' => 'blocking'])->assertOk();

        $text = \App\Models\QrNotification::sole()->message_text;
        $this->assertSame(FcmService::MESSAGES['blocking'], $text);
        $this->assertDoesNotMatchRegularExpression(self::EMOJI, $text);
    }
}
