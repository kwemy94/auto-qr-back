<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Les pages publiques utilisent des icônes SVG, jamais d'émojis
 * (rendu inégal selon les téléphones et navigateurs).
 */
class NoEmojiInPublicPagesTest extends TestCase
{
    use RefreshDatabase;

    private const EMOJI = '/[\x{1F300}-\x{1FAFF}\x{2600}-\x{27BF}\x{1F1E6}-\x{1F1FF}\x{FE0F}]/u';

    public function test_public_pages_contain_no_emoji(): void
    {
        $owner = User::factory()->create();
        $owner->forceFill(['qr_code' => 'qr-emoji-test', 'fcm_token' => 'device'])->save();

        $pages = ['/', '/privacy-policy?lang=fr', '/privacy-policy?lang=en', '/terms?lang=fr', '/terms?lang=en', '/n/qr-emoji-test'];

        foreach ($pages as $page) {
            $html = $this->get($page)->assertOk()->getContent();
            $this->assertDoesNotMatchRegularExpression(self::EMOJI, $html, "Émoji trouvé sur {$page}");
        }
    }
}
