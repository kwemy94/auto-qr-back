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

    public function test_unknown_lang_falls_back_and_privacy_still_works(): void
    {
        $this->get('/terms?lang=de')->assertOk();
        $this->get('/privacy-policy')->assertOk();
    }
}
