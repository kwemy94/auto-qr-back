{{-- ============================================================
     resources/views/notify/show.blade.php
     Page publique de signalement — charte QR Notify
     Bleu marine #0d1f3c | Or #c9962a | Crème #f0e8d5
     ============================================================ --}}
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Signaler ce véhicule — QR Notify</title>
    <style>
        /* ── Reset & Base ──────────────────────────────────────── */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg: #0d1f3c;
            /* bleu marine profond */
            --surface: #102345;
            /* bleu marine légèrement plus clair */
            --border: #1e3a6e;
            /* bordure bleu */
            --accent: #c9962a;
            /* or */
            --accent-dk: #a07820;
            /* or foncé au hover */
            --accent-lt: rgba(201, 150, 42, .12);
            /* or transparent pour sélection */
            --text: #f0e8d5;
            /* crème */
            --muted: #7a92b8;
            /* bleu grisé */
            --success: #4ade80;
            --error: #f87171;
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
            justify-content: flex-start;
            padding: 1.5rem 1rem 3rem;
        }

        /* ── Header ────────────────────────────────────────────── */
        .header {
            text-align: center;
            margin-bottom: 1.75rem;
            padding-top: 0.5rem;
        }

        .logo-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--accent);
            width: 54px;
            height: 54px;
            border-radius: 13px;
            margin-bottom: 0.85rem;
        }

        .logo-wrap svg {
            width: 30px;
            height: 30px;
            stroke: #0d1f3c;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .app-name {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 0.3rem;
        }

        .headline {
            font-size: 1.3rem;
            font-weight: 700;
            line-height: 1.3;
            color: var(--text);
        }

        .sub {
            font-size: 0.83rem;
            color: var(--muted);
            margin-top: 0.4rem;
            line-height: 1.55;
        }

        /* ── Séparateur or ──────────────────────────────────────── */
        .divider {
            width: 36px;
            height: 2px;
            background: var(--accent);
            border-radius: 2px;
            margin: 0.9rem auto 0;
        }

        /* ── Card principale ────────────────────────────────────── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            width: 100%;
            max-width: 420px;
            padding: 1.35rem 1.2rem;
        }

        .card-label {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 0.8rem;
        }

        /* ── Liste des messages ─────────────────────────────────── */
        .msg-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .msg-btn {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            background: var(--bg);
            border: 1.5px solid var(--border);
            border-radius: 9px;
            padding: 0.8rem 1rem;
            cursor: pointer;
            text-align: left;
            color: var(--text);
            font-size: 0.88rem;
            font-family: var(--font);
            line-height: 1.4;
            transition: border-color .15s, background .15s;
            width: 100%;
        }

        .msg-btn:hover,
        .msg-btn:focus-visible {
            border-color: var(--accent);
            background: rgba(255, 255, 255, 0.03);
            outline: none;
        }

        .msg-btn.selected {
            border-color: var(--accent);
            background: var(--accent-lt);
        }

        /* Indicateur rond à gauche à la place des emojis */
        .msg-btn .indicator {
            flex-shrink: 0;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 2px solid var(--border);
            transition: border-color .15s, background .15s;
        }

        .msg-btn.selected .indicator {
            border-color: var(--accent);
            background: var(--accent);
        }

        /* ── Bouton d'envoi ─────────────────────────────────────── */
        .send-wrap {
            margin-top: 1.1rem;
        }

        .btn-send {
            display: block;
            width: 100%;
            background: var(--accent);
            color: #0d1f3c;
            border: none;
            border-radius: 9px;
            padding: 0.95rem 1rem;
            font-size: 0.95rem;
            font-weight: 700;
            font-family: var(--font);
            cursor: pointer;
            transition: background .15s, transform .1s;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        .btn-send:hover {
            background: var(--accent-dk);
        }

        .btn-send:active {
            transform: scale(0.98);
        }

        .btn-send:disabled {
            background: var(--border);
            color: var(--muted);
            cursor: not-allowed;
            transform: none;
        }

        /* ── Feedback ───────────────────────────────────────────── */
        .feedback {
            display: none;
            margin-top: 0.9rem;
            border-radius: 9px;
            padding: 0.8rem 1rem;
            font-size: 0.85rem;
            line-height: 1.5;
            text-align: center;
        }

        .feedback.success {
            background: rgba(74, 222, 128, 0.1);
            border: 1px solid rgba(74, 222, 128, 0.3);
            color: var(--success);
        }

        .feedback.error {
            background: rgba(248, 113, 113, 0.1);
            border: 1px solid rgba(248, 113, 113, 0.3);
            color: var(--error);
        }

        /* ── Loader spinner ─────────────────────────────────────── */
        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2.5px solid rgba(13, 31, 60, 0.3);
            border-top-color: #0d1f3c;
            border-radius: 50%;
            animation: spin .7s linear infinite;
            vertical-align: middle;
            margin-right: 7px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* ── Note anonymat ──────────────────────────────────────── */
        .anon-note {
            margin-top: 1.4rem;
            text-align: center;
            font-size: 0.77rem;
            color: var(--muted);
            line-height: 1.6;
            max-width: 420px;
        }

        .anon-note strong {
            color: var(--text);
        }

        /* Icône cadenas SVG inline */
        .icon-lock {
            display: inline-block;
            width: 13px;
            height: 13px;
            vertical-align: -2px;
            margin-right: 3px;
            stroke: var(--accent);
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* ── Footer ─────────────────────────────────────────────── */
        .footer {
            margin-top: 2.5rem;
            font-size: 0.7rem;
            color: var(--muted);
            text-align: center;
            letter-spacing: 0.03em;
        }

        .footer span {
            color: var(--accent);
            font-weight: 600;
        }

        /* ── Responsive ─────────────────────────────────────────── */
        @media (max-width: 360px) {
            .headline {
                font-size: 1.1rem;
            }

            .btn-send {
                font-size: 0.88rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .spinner {
                animation: none;
                border-top-color: #0d1f3c;
            }
        }
    </style>
</head>

<body>

    {{-- ── Header ─────────────────────────────────────────────── --}}
    <div class="header">
        <div class="logo-wrap" aria-hidden="true">
            {{-- Icône QR code simple --}}
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <rect x="3" y="3" width="7" height="7" rx="1" />
                <rect x="14" y="3" width="7" height="7" rx="1" />
                <rect x="3" y="14" width="7" height="7" rx="1" />
                <rect x="5" y="5" width="3" height="3" fill="#0d1f3c" />
                <rect x="16" y="5" width="3" height="3" fill="#0d1f3c" />
                <rect x="5" y="16" width="3" height="3" fill="#0d1f3c" />
                <path d="M14 14h2v2h-2zm4 0h3v3h-3zm-4 4h3v3h-3zm4 2h2v2h-2z" />
            </svg>
        </div>
        <div class="app-name">QR Notify</div>
        <h1 class="headline">Ce véhicule vous bloque ?</h1>
        <p class="sub">Choisissez un message — le propriétaire<br>sera notifié instantanément.</p>
        <div class="divider"></div>
    </div>

    {{-- ── Card de signalement ──────────────────────────────────── --}}
    <div class="card" role="main">
        <p class="card-label">Quel est le problème ?</p>

        <div class="msg-list" role="group" aria-label="Choisir un message de signalement">
            @foreach ($messages as $key => $text)
                @php
                    // Supprime l'emoji en début de chaîne (unicode étendu)
$label = preg_replace('/^[\x{1F000}-\x{1FFFF}\x{2600}-\x{27FF}\x{FE00}-\x{FEFF}]+\s*/u', '', $text);
                @endphp
                <button type="button" class="msg-btn" data-key="{{ $key }}" aria-pressed="false">
                    <span class="indicator" aria-hidden="true"></span>
                    <span>{{ $label }}</span>
                </button>
            @endforeach
        </div>

        <div class="send-wrap">
            <button id="btn-send" class="btn-send" type="button" disabled aria-live="polite">
                Envoyer le signalement
            </button>
        </div>

        <div id="feedback" class="feedback" role="alert" aria-live="assertive"></div>
    </div>

    {{-- ── Note anonymat ──────────────────────────────────────────── --}}
    <p class="anon-note">
        <svg class="icon-lock" viewBox="0 0 24 24" aria-hidden="true">
            <rect x="5" y="11" width="14" height="10" rx="2" />
            <path d="M8 11V7a4 4 0 0 1 8 0v4" />
        </svg>
        <strong>Votre anonymat est garanti.</strong><br>
        Aucune donnée personnelle n'est transmise au propriétaire.
    </p>

    <footer class="footer">
        <span>QR Notify</span> &mdash; Signalement anonyme &amp; instantané
    </footer>

    {{-- ── Script (vanilla JS, ~1 Ko) ────────────────────────────── --}}
    <script>
        (function() {
            const TOKEN = @json($token);
            const SEND_URL = `/n/${TOKEN}/send`;
            const CSRF = document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] ?
                decodeURIComponent(document.cookie.match(/XSRF-TOKEN=([^;]+)/)[1]) :
                '';

            let selectedKey = null;

            const btnSend = document.getElementById('btn-send');
            const feedback = document.getElementById('feedback');
            const msgBtns = document.querySelectorAll('.msg-btn');

            // ── Sélection d'un message ──────────────────────────────
            msgBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    msgBtns.forEach(b => {
                        b.classList.remove('selected');
                        b.setAttribute('aria-pressed', 'false');
                    });
                    btn.classList.add('selected');
                    btn.setAttribute('aria-pressed', 'true');
                    selectedKey = btn.dataset.key;
                    btnSend.disabled = false;
                    hideFeedback();
                });
            });

            // ── Envoi ───────────────────────────────────────────────
            btnSend.addEventListener('click', async () => {
                if (!selectedKey) return;

                setLoading(true);
                hideFeedback();

                try {
                    const res = await fetch(SEND_URL, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-XSRF-TOKEN': CSRF,
                        },
                        body: JSON.stringify({
                            message_key: selectedKey
                        }),
                    });

                    const data = await res.json();

                    if (res.ok && data.success) {
                        showFeedback('success', 'Signalement envoyé. Le propriétaire a été notifié.');
                        msgBtns.forEach(b => {
                            b.disabled = true;
                            b.classList.remove('selected');
                        });
                        btnSend.disabled = true;
                        btnSend.textContent = 'Signalement envoyé';
                    } else {
                        showFeedback('error', data.message ?? 'Une erreur est survenue. Réessayez.');
                    }
                } catch (e) {
                    showFeedback('error', 'Connexion impossible. Vérifiez votre réseau.');
                } finally {
                    setLoading(false);
                }
            });

            // ── Helpers ─────────────────────────────────────────────
            function setLoading(on) {
                if (on) {
                    btnSend.disabled = true;
                    btnSend.innerHTML = '<span class="spinner"></span>Envoi en cours…';
                } else {
                    btnSend.innerHTML = 'Envoyer le signalement';
                    if (selectedKey) btnSend.disabled = false;
                }
            }

            function showFeedback(type, msg) {
                feedback.className = 'feedback ' + type;
                feedback.textContent = msg;
                feedback.style.display = 'block';
            }

            function hideFeedback() {
                feedback.style.display = 'none';
                feedback.className = 'feedback';
            }
        })();
    </script>

</body>

</html>
