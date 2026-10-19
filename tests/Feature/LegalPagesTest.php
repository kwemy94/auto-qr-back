<?php

namespace Tests\Feature;

use Tests\TestCase;

class LegalPagesTest extends TestCase
{
    // (Le client de test Symfony envoie « Accept-Language: en-us » par défaut :
    // le français est donc demandé explicitement.)
    public function test_terms_page_in_french(): void
    {
        $this->get('/terms?lang=fr')
            ->assertOk()
            ->assertSee("Conditions d'utilisation") // échappé comme Blade (&#039;)
            ->assertSee('<html lang="fr">', false);

        $this->get('/terms', ['Accept-Language' => 'fr-FR,fr;q=0.9'])
            ->assertOk()
            ->assertSee('<html lang="fr">', false);
    }

    public function test_terms_page_in_english_from_query(): void
    {
        $this->get('/terms?lang=en')
            ->assertOk()
            ->assertSee('Terms of use')
            ->assertSee('<html lang="en">', false);
    }

    public function test_terms_page_follows_browser_language(): void
    {
        $this->get('/terms', ['Accept-Language' => 'en-US,en;q=0.9'])
            ->assertOk()
            ->assertSee('Terms of use');
    }

    public function test_unknown_lang_falls_back(): void
    {
        $this->get('/terms?lang=de')->assertOk();
        $this->get('/privacy-policy?lang=de')->assertOk();
    }

    public function test_privacy_policy_in_both_languages(): void
    {
        $this->get('/privacy-policy?lang=fr')
            ->assertOk()
            ->assertSee('Politique de confidentialité')
            ->assertSee('<html lang="fr">', false);

        $this->get('/privacy-policy?lang=en')
            ->assertOk()
            ->assertSee('Privacy policy')
            ->assertSee('<html lang="en">', false);
    }

    // Aucune technologie ni fournisseur technique ne doit apparaître sur les pages publiques
    public function test_legal_pages_do_not_expose_technologies(): void
    {
        $forbidden = ['firebase', 'google', 'fcm', 'laravel', 'php', 'react', 'expo', 'sha-256',
            'alwaysdata', 'qrserver', 'jwt', 'mysql', 'notchpay', 'campay', 'cinetpay'];

        foreach (['/privacy-policy', '/terms'] as $page) {
            foreach (['fr', 'en'] as $lang) {
                // Texte visible uniquement (sans les balises ni le CSS)
                $html = $this->get("{$page}?lang={$lang}")->getContent();
                $text = strtolower(strip_tags(preg_replace('#<style.*?</style>#s', '', $html)));
                foreach ($forbidden as $word) {
                    $this->assertStringNotContainsString($word, $text, "« {$word} » visible sur {$page} ({$lang})");
                }
            }
        }
    }
}
