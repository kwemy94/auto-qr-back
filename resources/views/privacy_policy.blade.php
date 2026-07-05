{{-- ============================================================
     resources/views/privacy-policy.blade.php
     Politique de confidentialité — QR Notify
     URL publique requise par Google Play Console
     Charte : #0d1f3c | #c9962a | #f0e8d5
     ============================================================ --}}
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Notify — Politique de confidentialité</title>
    <meta name="robots" content="index, follow">
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
            --text: #f0e8d5;
            --muted: #9fb0cc;
            --radius: 14px;
            --font: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        html {
            font-size: 16px;
            scroll-behavior: smooth;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--font);
            line-height: 1.7;
        }

        .container {
            max-width: 760px;
            margin: 0 auto;
            padding: 3rem 1.5rem 5rem;
        }

        /* ── Header ─────────────────────────────────────────────── */
        .header {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 2.5rem;
            text-align: center;
        }

        .logo-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(201, 150, 42, 0.1);
            border: 1.5px solid rgba(201, 150, 42, 0.3);
            width: 64px;
            height: 64px;
            border-radius: 18px;
            margin-bottom: 0.5rem;
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

        .brand {
            font-size: 1.3rem;
            font-weight: 700;
        }

        h1 {
            font-size: 1.7rem;
            font-weight: 700;
            margin-top: 0.75rem;
            text-align: center;
        }

        .updated {
            font-size: 0.82rem;
            color: var(--muted);
            margin-top: 0.4rem;
        }

        /* ── Intro card ─────────────────────────────────────────── */
        .intro {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.4rem 1.5rem;
            margin-bottom: 2.5rem;
            font-size: 0.95rem;
            color: var(--muted);
        }

        .intro strong {
            color: var(--text);
        }

        /* ── Sections ───────────────────────────────────────────── */
        section {
            margin-bottom: 2.2rem;
        }

        h2 {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 0.9rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--border);
        }

        h3 {
            font-size: 0.98rem;
            font-weight: 600;
            color: var(--text);
            margin: 1.1rem 0 0.5rem;
        }

        p {
            font-size: 0.94rem;
            color: var(--muted);
            margin-bottom: 0.8rem;
        }

        p strong,
        li strong {
            color: var(--text);
        }

        ul,
        ol {
            margin: 0.6rem 0 1rem 1.3rem;
            font-size: 0.94rem;
            color: var(--muted);
        }

        li {
            margin-bottom: 0.5rem;
        }

        /* ── Data table ─────────────────────────────────────────── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0 1.4rem;
            font-size: 0.88rem;
        }

        .data-table th {
            background: var(--surface);
            color: var(--accent);
            text-align: left;
            padding: 0.7rem 0.9rem;
            border: 1px solid var(--border);
            font-weight: 600;
        }

        .data-table td {
            padding: 0.7rem 0.9rem;
            border: 1px solid var(--border);
            color: var(--muted);
            vertical-align: top;
        }

        .data-table tr:nth-child(even) td {
            background: rgba(255, 255, 255, 0.02);
        }

        /* ── Highlight box ──────────────────────────────────────── */
        .highlight {
            background: rgba(29, 158, 117, 0.08);
            border: 1px solid rgba(29, 158, 117, 0.3);
            border-radius: var(--radius);
            padding: 1.1rem 1.3rem;
            margin: 1rem 0;
            font-size: 0.9rem;
        }

        .highlight-title {
            color: #4fd1a5;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 0.4rem;
        }

        .highlight p {
            color: var(--text);
            margin-bottom: 0;
        }

        /* ── Contact card ───────────────────────────────────────── */
        .contact-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.5rem;
            text-align: center;
            margin-top: 2.5rem;
        }

        .contact-card h2 {
            border: none;
            margin-bottom: 0.6rem;
        }

        .contact-card a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
        }

        .contact-card a:hover {
            text-decoration: underline;
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

        .toc {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.2rem 1.4rem;
            margin-bottom: 2.5rem;
        }

        .toc-title {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--accent);
            margin-bottom: 0.7rem;
        }

        .toc ol {
            margin: 0 0 0 1.2rem;
            font-size: 0.88rem;
        }

        .toc a {
            color: var(--muted);
            text-decoration: none;
        }

        .toc a:hover {
            color: var(--accent);
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 1.4rem;
            }

            .data-table {
                font-size: 0.78rem;
            }

            .data-table th,
            .data-table td {
                padding: 0.5rem 0.6rem;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        {{-- ── Header ── --}}
        <div class="header">
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
            <div class="brand">QR Notify</div>
            <h1>Politique de confidentialité</h1>
            <div class="updated">Dernière mise à jour : 20 juin 2026</div>
        </div>

        {{-- ── Intro ── --}}
        <div class="intro">
            QR Notify (<strong>« nous », « notre », « l'application »</strong>) respecte votre vie privée.
            Cette politique explique quelles données nous collectons, pourquoi, et comment elles sont protégées.
            En utilisant QR Notify, vous acceptez les pratiques décrites ici.
        </div>

        {{-- ── Table des matières ── --}}
        <div class="toc">
            <div class="toc-title">Sommaire</div>
            <ol>
                <li><a href="#donnees-collectees">Données que nous collectons</a></li>
                <li><a href="#camera">Utilisation de la caméra</a></li>
                <li><a href="#notifications">Notifications push</a></li>
                <li><a href="#anonymat">Anonymat du signalement</a></li>
                <li><a href="#utilisation">Comment nous utilisons vos données</a></li>
                <li><a href="#partage">Partage des données</a></li>
                <li><a href="#conservation">Conservation et suppression</a></li>
                <li><a href="#securite">Sécurité</a></li>
                <li><a href="#droits">Vos droits</a></li>
                <li><a href="#mineurs">Utilisation par des mineurs</a></li>
                <li><a href="#modifications">Modifications de cette politique</a></li>
                <li><a href="#contact">Nous contacter</a></li>
            </ol>
        </div>

        {{-- ── 1. Données collectées ── --}}
        <section id="donnees-collectees">
            <h2>1. Données que nous collectons</h2>
            <p>QR Notify collecte uniquement les données strictement nécessaires au fonctionnement du service.</p>

            <table class="data-table">
                <tr>
                    <th>Donnée</th>
                    <th>Quand</th>
                    <th>Pourquoi</th>
                </tr>
                <tr>
                    <td>Nom et adresse e-mail</td>
                    <td>À l'inscription</td>
                    <td>Créer et sécuriser votre compte</td>
                </tr>
                <tr>
                    <td>Mot de passe (chiffré)</td>
                    <td>À l'inscription</td>
                    <td>Authentification sécurisée</td>
                </tr>
                <tr>
                    <td>Jeton de notification push (FCM)</td>
                    <td>À l'inscription et lors de chaque renouvellement</td>
                    <td>Vous envoyer des notifications</td>
                </tr>
                <tr>
                    <td>Code QR unique</td>
                    <td>Généré à l'inscription</td>
                    <td>Identifier votre véhicule sans révéler votre identité</td>
                </tr>
                <tr>
                    <td>Historique des signalements reçus</td>
                    <td>À chaque signalement</td>
                    <td>Vous permettre de consulter vos notifications passées</td>
                </tr>
                <tr>
                    <td>Adresse IP du signalant (hachée)</td>
                    <td>À chaque signalement</td>
                    <td>Empêcher les abus, jamais stockée en clair</td>
                </tr>
            </table>

            <div class="highlight">
                <div class="highlight-title">Ce que nous ne collectons jamais</div>
                <p>Nous ne collectons pas votre position GPS, vos contacts, vos photos personnelles, ni aucune donnée du
                    signalant (nom, numéro, e-mail) lorsqu'il effectue un signalement.</p>
            </div>
        </section>

        {{-- ── 2. Caméra ── --}}
        <section id="camera">
            <h2>2. Utilisation de la caméra</h2>
            <p>QR Notify demande l'accès à votre caméra uniquement pour scanner un QR code. Aucune photo ni vidéo n'est
                enregistrée, transmise ou stockée. Le flux caméra est utilisé localement sur votre appareil, en temps
                réel, pour la seule lecture du code.</p>
            <p>Vous pouvez également importer une image existante depuis votre galerie pour scanner un QR code ; dans ce
                cas, seule l'image sélectionnée est traitée temporairement pour la lecture, puis n'est pas conservée par
                nos serveurs.</p>
        </section>

        {{-- ── 3. Notifications push ── --}}
        <section id="notifications">
            <h2>3. Notifications push</h2>
            <p>QR Notify utilise Firebase Cloud Messaging (un service fourni par Google) pour vous envoyer des
                notifications instantanées lorsque votre véhicule est signalé. Pour cela, un identifiant technique
                (jeton FCM) propre à votre appareil est stocké sur nos serveurs.</p>
            <p>Ce jeton ne contient aucune information personnelle lisible et ne peut pas, à lui seul, vous identifier
                sans accès à notre base de données.</p>
            <h3>Désactivation</h3>
            <p>Vous pouvez désactiver les notifications à tout moment depuis les paramètres de votre téléphone.
                Désactiver les notifications limitera cependant le fonctionnement principal de l'application.</p>
        </section>

        {{-- ── 4. Anonymat ── --}}
        <section id="anonymat">
            <h2>4. Anonymat du signalement</h2>
            <p>QR Notify est conçu autour d'un principe d'anonymat strict entre les deux parties :</p>
            <ul>
                <li><strong>Le signalant</strong> (la personne qui scanne le QR code) n'a besoin d'aucun compte,
                    d'aucune installation d'application, et ne transmet aucune donnée identifiante.</li>
                <li><strong>Le propriétaire</strong> du véhicule ne peut jamais savoir qui a effectué le signalement :
                    ni nom, ni numéro de téléphone, ni adresse e-mail.</li>
            </ul>
            <p>Les messages envoyés sont limités à une liste prédéfinie de formulations neutres (par exemple « Votre
                véhicule bloque le passage »), afin d'empêcher tout contenu injurieux, identifiant ou inapproprié.</p>
            <p>Pour prévenir les abus (signalements répétés malveillants), nous appliquons une limite technique du
                nombre de signalements autorisés par intervalle de temps, basée sur une empreinte non réversible de
                l'adresse IP — jamais sur une identité.</p>
        </section>

        {{-- ── 5. Utilisation des données ── --}}
        <section id="utilisation">
            <h2>5. Comment nous utilisons vos données</h2>
            <ul>
                <li>Créer et gérer votre compte utilisateur</li>
                <li>Générer votre QR code personnel unique</li>
                <li>Vous transmettre les notifications de signalement en temps réel</li>
                <li>Afficher votre historique de notifications reçues</li>
                <li>Assurer la sécurité du service et prévenir les usages abusifs</li>
                <li>Répondre à vos demandes via le formulaire de contact</li>
            </ul>
            <p>Nous n'utilisons jamais vos données à des fins publicitaires et ne vendons aucune donnée personnelle à
                des tiers.</p>
        </section>

        {{-- ── 6. Partage des données ── --}}
        <section id="partage">
            <h2>6. Partage des données</h2>
            <p>Vos données ne sont partagées qu'avec les prestataires techniques strictement nécessaires au
                fonctionnement du service :</p>
            <table class="data-table">
                <tr>
                    <th>Prestataire</th>
                    <th>Rôle</th>
                </tr>
                <tr>
                    <td>Google Firebase (Cloud Messaging)</td>
                    <td>Livraison des notifications push</td>
                </tr>
                <tr>
                    <td>Hébergeur du serveur applicatif</td>
                    <td>Stockage de la base de données et exécution du backend</td>
                </tr>
            </table>
            <p>Nous ne partageons aucune donnée à des fins commerciales, publicitaires, ou avec des courtiers de
                données. Vos données peuvent être divulguées si la loi camerounaise ou une décision de justice l'exige.
            </p>
        </section>

        {{-- ── 7. Conservation ── --}}
        <section id="conservation">
            <h2>7. Conservation et suppression des données</h2>
            <p>Vos données sont conservées tant que votre compte est actif. Vous pouvez demander la suppression
                définitive de votre compte et de toutes les données associées à tout moment en nous contactant à
                l'adresse indiquée ci-dessous.</p>
            <p>La suppression d'un compte entraîne l'effacement irréversible : du profil utilisateur, du QR code
                associé, du jeton de notification, et de l'historique des signalements reçus.</p>
        </section>

        {{-- ── 8. Sécurité ── --}}
        <section id="securite">
            <h2>8. Sécurité</h2>
            <p>Nous mettons en œuvre des mesures techniques raisonnables pour protéger vos données :</p>
            <ul>
                <li>Mots de passe chiffrés, jamais stockés en clair</li>
                <li>Connexions chiffrées (HTTPS) entre l'application et nos serveurs</li>
                <li>Adresses IP hachées de façon non réversible (SHA-256)</li>
                <li>Authentification par jeton sécurisé pour l'accès à l'API</li>
            </ul>
            <p>Aucun système n'étant totalement infaillible, nous ne pouvons garantir une sécurité absolue, mais nous
                nous engageons à corriger rapidement toute vulnérabilité identifiée.</p>
        </section>

        {{-- ── 9. Droits ── --}}
        <section id="droits">
            <h2>9. Vos droits</h2>
            <p>Vous disposez des droits suivants concernant vos données personnelles :</p>
            <ul>
                <li><strong>Accès</strong> — obtenir une copie des données que nous détenons sur vous</li>
                <li><strong>Rectification</strong> — corriger des informations inexactes</li>
                <li><strong>Suppression</strong> — demander l'effacement de votre compte et de vos données</li>
                <li><strong>Opposition</strong> — vous opposer à certains traitements de vos données</li>
            </ul>
            <p>Pour exercer l'un de ces droits, contactez-nous à l'adresse indiquée en bas de page.</p>
        </section>

        {{-- ── 10. Mineurs ── --}}
        <section id="mineurs">
            <h2>10. Utilisation par des mineurs</h2>
            <p>QR Notify n'est pas spécifiquement destiné aux enfants de moins de 13 ans. Nous ne collectons pas
                sciemment de données auprès de mineurs de cette tranche d'âge. Si vous pensez qu'un enfant nous a fourni
                des données personnelles sans consentement parental, contactez-nous pour leur suppression.</p>
        </section>

        {{-- ── 11. Modifications ── --}}
        <section id="modifications">
            <h2>11. Modifications de cette politique</h2>
            <p>Cette politique de confidentialité peut être mise à jour périodiquement pour refléter des changements
                dans nos pratiques ou pour des raisons légales. La date de dernière mise à jour est indiquée en haut de
                cette page. Nous vous encourageons à consulter cette page régulièrement.</p>
        </section>

        {{-- ── Contact ── --}}
        <div class="contact-card" id="contact">
            <h2>Nous contacter</h2>
            <p>Pour toute question concernant cette politique de confidentialité ou vos données personnelles :</p>
            <p>
                <a href="mailto:contact@qrnotify.streetsmart.tech">contact@qrnotify.streetsmart.tech</a>
            </p>
            <p style="margin-top: 0.8rem; font-size: 0.85rem;">
                Vous pouvez également utiliser le formulaire de contact directement dans l'application.
            </p>
        </div>

        <footer class="footer">
            QR Notify &mdash; Application de signalement anonyme de véhicules<br>
            &copy; StreetSmart &mdash; {{ date('Y') }}
        </footer>

    </div>

</body>

</html>
