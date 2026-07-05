{{-- ============================================================
     resources/views/errors/404.blade.php
     Page d'erreur 404 — QR code invalide ou propriétaire sans FCM
     Charte QR Notify : #0d1f3c | #c9962a | #f0e8d5
     ============================================================ --}}
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>QR Notify — Page introuvable</title>
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg: #0d1f3c;
            --surface: #102345;
            --border: #1e3a6e;
            --accent: #c9962a;
            --accent-dk: #a07820;
            --text: #f0e8d5;
            --muted: #7a92b8;
            --radius: 12px;
            --font: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        html {
            font-size: 16px;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--font);
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem 3rem;
            gap: 2rem;
        }

        /* ── Logo ───────────────────────────────────────────────── */
        .logo-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(201, 150, 42, 0.1);
            border: 1.5px solid rgba(201, 150, 42, 0.3);
            width: 64px;
            height: 64px;
            border-radius: 18px;
        }

        .logo-wrap svg {
            width: 32px;
            height: 32px;
            stroke: var(--accent);
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .app-name {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: var(--accent);
            margin-top: 0.6rem;
            text-align: center;
        }

        /* ── Illustration 404 ───────────────────────────────────── */
        .illustration {
            position: relative;
            width: 120px;
            height: 120px;
        }

        .illustration svg {
            width: 100%;
            height: 100%;
        }

        /* ── Texte principal ────────────────────────────────────── */
        .content {
            text-align: center;
            max-width: 360px;
        }

        .badge-error {
            display: inline-block;
            background: rgba(248, 113, 113, 0.1);
            border: 1px solid rgba(248, 113, 113, 0.25);
            color: #f87171;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 3px 12px;
            border-radius: 99px;
            margin-bottom: 1rem;
        }

        .headline {
            font-size: 1.35rem;
            font-weight: 700;
            line-height: 1.35;
            color: var(--text);
            margin-bottom: 0.6rem;
        }

        .sub {
            font-size: 0.85rem;
            color: var(--muted);
            line-height: 1.65;
        }

        /* ── Séparateur ─────────────────────────────────────────── */
        .divider {
            width: 36px;
            height: 2px;
            background: var(--accent);
            border-radius: 2px;
            opacity: 0.5;
        }

        /* ── Card téléchargement ────────────────────────────────── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            width: 100%;
            max-width: 360px;
            padding: 1.4rem 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .card-title {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 0.1rem;
        }

        .card-desc {
            font-size: 0.83rem;
            color: var(--muted);
            line-height: 1.6;
        }

        /* ── Boutons store ──────────────────────────────────────── */
        .store-btns {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .store-btn {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            background: var(--bg);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            text-decoration: none;
            color: var(--text);
            transition: border-color .15s, background .15s;
        }

        .store-btn:hover {
            border-color: var(--accent);
            background: rgba(201, 150, 42, 0.06);
        }

        .store-btn svg {
            width: 22px;
            height: 22px;
            fill: var(--text);
            flex-shrink: 0;
        }

        .store-btn-text {
            display: flex;
            flex-direction: column;
        }

        .store-label {
            font-size: 0.68rem;
            color: var(--muted);
        }

        .store-name {
            font-size: 0.92rem;
            font-weight: 600;
            line-height: 1.2;
        }

        .store-arrow {
            margin-left: auto;
            color: var(--accent);
            font-size: 1rem;
        }

        /* ── Steps ──────────────────────────────────────────────── */
        .steps {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .step {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .step-num {
            flex-shrink: 0;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: rgba(201, 150, 42, 0.15);
            border: 1px solid rgba(201, 150, 42, 0.3);
            color: var(--accent);
            font-size: 0.7rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 1px;
        }

        .step-text {
            font-size: 0.83rem;
            color: var(--muted);
            line-height: 1.5;
        }

        .step-text strong {
            color: var(--text);
            font-weight: 500;
        }

        /* ── Footer ─────────────────────────────────────────────── */
        .footer {
            text-align: center;
            margin-top: 3rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
            font-size: 0.78rem;
            color: var(--muted);
            opacity: 0.7;
        }

        @media (max-width: 360px) {
            .headline {
                font-size: 1.15rem;
            }
        }
    </style>
</head>

<body>

    {{-- ── Logo ──────────────────────────────────────────────────── --}}
    <div style="display:flex;flex-direction:column;align-items:center;">
        <div class="logo-wrap">
            <svg viewBox="0 0 24 24">
                <rect x="3" y="3" width="7" height="7" rx="1" />
                <rect x="14" y="3" width="7" height="7" rx="1" />
                <rect x="3" y="14" width="7" height="7" rx="1" />
                <rect x="5" y="5" width="3" height="3" fill="#c9962a" stroke="none" />
                <rect x="16" y="5" width="3" height="3" fill="#c9962a" stroke="none" />
                <rect x="5" y="16" width="3" height="3" fill="#c9962a" stroke="none" />
                <path d="M14 14h2v2h-2zm4 0h3v3h-3zm-4 4h3v3h-3zm4 2h2v2h-2z" fill="#c9962a" stroke="none" />
            </svg>
        </div>
        <div class="app-name">QR Notify</div>
    </div>

    {{-- ── Message d'erreur ───────────────────────────────────────── --}}
    <div class="content">
        {{-- <div class="badge-error">QR code non actif</div> --}}
        <h1 class="headline">
            {{  'Ce QR code est invalide ou désactivé.' }}
        </h1>
        <p class="sub">
            Le propriétaire de ce véhicule n'a pas encore configuré<br>
            son compte QR Notify ou ses notifications sont désactivées.
        </p>
    </div>

    <div class="divider"></div>

    {{-- ── Card téléchargement ────────────────────────────────────── --}}
    <div class="card">
        <div>
            <div class="card-title">Vous avez un véhicule ?</div>
            <p class="card-desc">
                Téléchargez QR Notify, générez votre QR code et collez-le sur votre pare-brise.
                En cas de blocage, vous serez alerté instantanément.
            </p>
        </div>

        {{-- Boutons store --}}
        <div class="store-btns">
            {{-- Google Play --}}
            <a href="https://play.google.com/store/apps/details?id=com.shell_grant.qrautofront" class="store-btn"
                target="_blank" rel="noopener">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M3.18 23.76c.3.17.64.24.99.21l13.08-11.77L13.48 8.4zM.5 1.4A1.5 1.5 0 0 0 0 2.5v19a1.5 1.5 0 0 0 .5 1.1l.1.08L14.1 12v-.31L.6 1.32zM22.28 10.3l-2.8-1.6-3.18 2.86 3.18 2.86 2.82-1.6a1.6 1.6 0 0 0 0-2.52zM4.17.24L17.25 12 13.48 15.6 4.17.24z" />
                </svg>
                <div class="store-btn-text">
                    <span class="store-label">Disponible sur</span>
                    <span class="store-name">Google Play</span>
                </div>
                <span class="store-arrow">›</span>
            </a>

            {{-- App Store --}}
            <a href="https://apps.apple.com/app/qr-notify" class="store-btn" target="_blank" rel="noopener">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z" />
                </svg>
                <div class="store-btn-text">
                    <span class="store-label">Disponible sur</span>
                    <span class="store-name">App Store</span>
                </div>
                <span class="store-arrow">›</span>
            </a>
        </div>

        {{-- Étapes --}}
        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <div class="step-text"><strong>Téléchargez</strong> l'application QR Notify</div>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <div class="step-text"><strong>Créez votre compte</strong> et générez votre QR code</div>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <div class="step-text"><strong>Collez le QR code</strong> sur votre pare-brise</div>
            </div>
        </div>
    </div>

    <footer class="footer">
        QR Notify &mdash; Application de signalement anonyme de véhicules<br>
        &copy; StreetSmart &mdash; {{ date('Y') }}
    </footer>

</body>

</html>
