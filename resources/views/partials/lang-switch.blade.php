{{-- ============================================================
     resources/views/partials/lang-switch.blade.php
     Sélecteur de langue EN / FR commun à toutes les pages publiques.
     Conserve le chemin et les autres paramètres de l'URL ; le choix
     est mémorisé par SetWebLocale (cookie « lang »).
     ============================================================ --}}
@php($current = app()->getLocale())
<style>
    .qn-lang { display: flex; justify-content: flex-end; gap: 0.4rem; width: 100%; margin-bottom: 1rem; }
    .qn-lang a {
        display: inline-flex; align-items: center; gap: 0.35rem;
        color: #7a92b8; text-decoration: none; font-family: inherit; font-size: 0.78rem; font-weight: 700;
        letter-spacing: 0.06em; padding: 0.3rem 0.75rem; border-radius: 999px; border: 1px solid #1e3a6e;
        transition: border-color .15s, color .15s;
    }
    .qn-lang a:hover { border-color: #c9962a; color: #f0e8d5; }
    .qn-lang a.active { background: #c9962a; color: #0d1f3c; border-color: #c9962a; }
    .qn-lang svg { width: 14px; height: 14px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
</style>
<nav class="qn-lang" aria-label="{{ __('web.lang_label') }}">
    @foreach (['en' => 'EN', 'fr' => 'FR'] as $code => $label)
        <a href="?{{ http_build_query(array_merge(request()->query(), ['lang' => $code])) }}"
            hreflang="{{ $code }}" lang="{{ $code }}"
            class="{{ $current === $code ? 'active' : '' }}" @if ($current === $code) aria-current="true" @endif>
            @if ($loop->first)
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18"/></svg>
            @endif
            {{ $label }}
        </a>
    @endforeach
</nav>
