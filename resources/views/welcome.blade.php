{{-- ============================================================
     resources/views/welcome.blade.php
     Landing page publique QR Notify
     Remplace la page par défaut Laravel
     Charte : #0d1f3c | #c9962a | #f0e8d5
     ============================================================ --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Notify — Signalement anonyme, notification instantanée</title>
    <meta name="description" content="Un QR code sur votre pare-brise. Une notification instantanée en cas de blocage. Sans confrontation, sans application pour signaler.">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #0d1f3c;
            --surface:   #102345;
            --border:    #1e3a6e;
            --accent:    #c9962a;
            --text:      #f0e8d5;
            --muted:     #7a92b8;
            --radius:    14px;
            --font:      'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        html { font-size: 16px; scroll-behavior: smooth; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--font);
            min-height: 100vh;
            line-height: 1.6;
        }

        .container {
            max-width: 480px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem 4rem;
        }

        /* ── Header ─────────────────────────────────────────────── */
        .header {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 2.5rem;
        }

        .logo-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(201, 150, 42, 0.1);
            border: 1.5px solid rgba(201, 150, 42, 0.3);
            width: 68px;
            height: 68px;
            border-radius: 18px;
            margin-bottom: 0.5rem;
        }

        .logo-wrap svg {
            width: 34px;
            height: 34px;
            stroke: var(--accent);
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .brand {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .tagline {
            font-size: 0.85rem;
            color: var(--accent);
            font-style: italic;
        }

        /* ── Hero ───────────────────────────────────────────────── */
        .hero {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .hero h1 {
            font-size: 1.6rem;
            font-weight: 700;
            line-height: 1.35;
            margin-bottom: 0.75rem;
        }

        .hero h1 span { color: var(--accent); }

        .hero p {
            font-size: 0.9rem;
            color: var(--muted);
            max-width: 360px;
            margin: 0 auto;
        }

        /* ── Steps ──────────────────────────────────────────────── */
        .steps {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 2rem;
        }

        .step {
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 0.9rem 1rem;
        }

        .step-num {
            flex-shrink: 0;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: var(--accent);
            color: var(--bg);
            font-size: 0.78rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .step-text { font-size: 0.85rem; }
        .step-text strong { display: block; margin-bottom: 0.15rem; font-weight: 600; }
        .step-text span { color: var(--muted); font-size: 0.8rem; }

        /* ── CTA Card ───────────────────────────────────────────── */
        .cta-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.5rem 1.25rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .cta-card h2 {
            font-size: 1.05rem;
            font-weight: 600;
            margin-bottom: 0.4rem;
        }

        .cta-card p {
            font-size: 0.8rem;
            color: var(--muted);
            margin-bottom: 1.1rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: opacity .15s;
        }

        .btn:hover { opacity: 0.88; }

        .btn-primary {
            background: var(--accent);
            color: var(--bg);
            margin-bottom: 0.6rem;
        }

        .btn-secondary {
            background: transparent;
            color: var(--text);
            border: 1.5px solid var(--border);
        }

        /* ── Features grid ──────────────────────────────────────── */
        .features {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.7rem;
            margin-bottom: 2rem;
        }

        .feature {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 0.9rem 0.8rem;
            text-align: center;
        }

        .feature-icon { font-size: 1.3rem; margin-bottom: 0.4rem; }
        .feature-title { font-size: 0.78rem; font-weight: 600; margin-bottom: 0.2rem; }
        .feature-desc { font-size: 0.7rem; color: var(--muted); line-height: 1.4; }

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
            .hero h1 { font-size: 1.4rem; }
        }
    </style>
</head>
<body>

    <div class="container">

        {{-- ── Header ── --}}
        <div class="header">
            <div class="logo-wrap">
                <svg viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                    <rect x="5" y="5" width="3" height="3" fill="#c9962a" stroke="none"/>
                    <rect x="16" y="5" width="3" height="3" fill="#c9962a" stroke="none"/>
                    <rect x="5" y="16" width="3" height="3" fill="#c9962a" stroke="none"/>
                    <path d="M14 14h2v2h-2zm4 0h3v3h-3zm-4 4h3v3h-3zm4 2h2v2h-2z" fill="#c9962a" stroke="none"/>
                </svg>
            </div>
            <div class="brand">QR Notify</div>
            <div class="tagline">Signalement anonyme. Notification instantanée.</div>
        </div>

        {{-- ── Hero ── --}}
        <div class="hero">
            <h1>Un véhicule vous <span>bloque</span> ?<br>Prévenez sans confrontation.</h1>
            <p>Scannez le QR code sur le pare-brise. Le propriétaire reçoit une notification en moins de 3 secondes — sans connaître votre identité.</p>
        </div>

        {{-- ── Steps ── --}}
        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <div class="step-text">
                    <strong>Scannez le QR code</strong>
                    <span>Avec la caméra native de votre téléphone — aucune app requise</span>
                </div>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <div class="step-text">
                    <strong>Choisissez un message</strong>
                    <span>Véhicule bloquant, phares allumés, vitre ouverte...</span>
                </div>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <div class="step-text">
                    <strong>Le propriétaire est notifié</strong>
                    <span>Notification push instantanée, totalement anonyme</span>
                </div>
            </div>
        </div>

        {{-- ── CTA — Propriétaires ── --}}
        <div class="cta-card">
            <h2>Vous avez un véhicule ?</h2>
            <p>Téléchargez l'application, générez votre QR code et collez-le sur votre pare-brise.</p>
            <a href="https://play.google.com/store/apps/details?id=com.shell_grant.qrautofront" class="btn btn-primary" target="_blank" rel="noopener">
                📱 Télécharger sur Google Play
            </a>
            {{-- <a href="#" class="btn btn-secondary">
                J'ai déjà un compte
            </a> --}}
        </div>

        {{-- ── Features ── --}}
        <div class="features">
            <div class="feature">
                <div class="feature-icon">🔒</div>
                <div class="feature-title">Anonymat total</div>
                <div class="feature-desc">Aucune donnée personnelle échangée entre les parties</div>
            </div>
            <div class="feature">
                <div class="feature-icon">⚡</div>
                <div class="feature-title">Instantané</div>
                <div class="feature-desc">Notification reçue en moins de 3 secondes</div>
            </div>
            <div class="feature">
                <div class="feature-icon">📵</div>
                <div class="feature-title">Sans app pour signaler</div>
                <div class="feature-desc">Le signalant utilise juste son appareil photo</div>
            </div>
            <div class="feature">
                <div class="feature-icon">🇨🇲</div>
                <div class="feature-title">Pensé pour Bertoua</div>
                <div class="feature-desc">Conçu pour le corridor Cameroun – RCA – Tchad</div>
            </div>
        </div>

        {{-- ── Footer ── --}}
        <footer class="footer">
            QR Notify &mdash; Application de signalement anonyme de véhicules<br>
            &copy; StreetSmart &mdash; {{ date('Y') }}
        </footer>

    </div>

</body>
</html>