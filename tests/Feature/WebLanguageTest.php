<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\FcmService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Toutes les pages web publiques proposent le sélecteur EN / FR ;
 * l'anglais est la langue par défaut et le choix est mémorisé.
 */
class WebLanguageTest extends TestCase
{
    use RefreshDatabase;

    private function pages(): array
    {
        $owner = User::factory()->create();
        $owner->forceFill(['qr_code' => 'qr-lang-test', 'fcm_token' => 'device'])->save();

        return [
            '/' => 200,
            '/terms' => 200,
            '/privacy-policy' => 200,
            '/n/qr-lang-test' => 200,
            '/n/unknown-token' => 404, // QR inconnu
            '/page-qui-n-existe-pas' => 404, // route inconnue
        ];
    }

    public function test_every_public_page_has_the_switch_and_defaults_to_english(): void
    {
        foreach ($this->pages() as $page => $status) {
            // Même avec un navigateur en français : anglais par défaut
            $this->get($page, ['Accept-Language' => 'fr-FR,fr;q=0.9'])
                ->assertStatus($status)
                ->assertSee('class="qn-lang"', false)
                ->assertSee('<html lang="en">', false);
        }
    }

    public function test_query_switches_every_page_to_french(): void
    {
        foreach ($this->pages() as $page => $status) {
            $this->get($page . '?lang=fr')
                ->assertStatus($status)
                ->assertSee('<html lang="fr">', false);
        }
    }

    public function test_choice_is_remembered_in_a_cookie(): void
    {
        $this->get('/?lang=fr')->assertCookie('lang', 'fr', false);

        $this->withUnencryptedCookie('lang', 'fr')->get('/terms')
            ->assertSee('<html lang="fr">', false);
        $this->withUnencryptedCookie('lang', 'fr')->get('/page-qui-n-existe-pas')
            ->assertNotFound()
            ->assertSee('<html lang="fr">', false);
    }

    public function test_switch_links_keep_other_query_parameters(): void
    {
        $this->get('/terms?lang=en&ref=app')
            ->assertSee('?lang=fr&amp;ref=app', false)
            ->assertSee('?lang=en&amp;ref=app', false);
    }

    public function test_scan_page_is_translated(): void
    {
        $this->pages();

        $this->get('/n/qr-lang-test')
            ->assertSee('Is this vehicle blocking you?')
            ->assertSee('Your lights are on.');

        $this->get('/n/qr-lang-test?lang=fr')
            ->assertSee('Ce véhicule vous bloque ?')
            ->assertSee('Vos phares sont allumés.');
    }

    public function test_send_message_follows_page_or_app_language(): void
    {
        $owner = User::factory()->create();
        $owner->forceFill(['qr_code' => 'qr-send-lang', 'fcm_token' => 'device'])->save();
        $this->mock(FcmService::class, fn ($mock) => $mock->shouldReceive('send')->andReturn(true));

        // Page web : la langue est passée dans l'URL
        $this->postJson('/n/qr-send-lang/send?lang=en', ['message_key' => 'lights'])
            ->assertJsonPath('message', 'The owner has been notified.');

        // App mobile : en-tête Accept-Language
        $this->postJson('/n/qr-send-lang/send', ['message_key' => 'window'], ['Accept-Language' => 'fr'])
            ->assertJsonPath('message', 'Le propriétaire a été notifié avec succès.');
    }
}
