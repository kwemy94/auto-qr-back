{{-- ============================================================
     resources/views/privacy_policy.blade.php
     Politique de confidentialité — QR Notify (FR / EN)
     Langue : ?lang=fr|en (envoyée par l'app), sinon choix mémorisé, sinon anglais.
     Rédaction volontairement générique : aucun outil ni fournisseur
     technique n'est nommé, seul leur rôle est décrit.
     Charte : #0d1f3c | #c9962a | #f0e8d5
     ============================================================ --}}
@php($en = $lang === 'en')
<!DOCTYPE html>
<html lang="{{ $lang }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Notify — {{ $en ? 'Privacy policy' : 'Politique de confidentialité' }}</title>
    <meta name="robots" content="index, follow">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

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

        html { font-size: 16px; scroll-behavior: smooth; }
        body { background: var(--bg); color: var(--text); font-family: var(--font); line-height: 1.7; }
        .container { max-width: 760px; margin: 0 auto; padding: 3rem 1.5rem 5rem; }

        .header { display: flex; flex-direction: column; align-items: center; gap: 0.5rem; margin-bottom: 2.5rem; text-align: center; }
        .logo-wrap {
            display: inline-flex; align-items: center; justify-content: center;
            background: rgba(201, 150, 42, 0.1); border: 1.5px solid rgba(201, 150, 42, 0.3);
            width: 64px; height: 64px; border-radius: 18px; margin-bottom: 0.5rem;
        }
        .logo-wrap svg { width: 32px; height: 32px; stroke: var(--accent); fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .brand { font-size: 1.3rem; font-weight: 700; }
        h1 { font-size: 1.7rem; font-weight: 700; margin-top: 0.75rem; }
        .updated { font-size: 0.82rem; color: var(--muted); margin-top: 0.4rem; }

        .intro, .toc, .contact-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); }
        .intro { padding: 1.4rem 1.5rem; margin-bottom: 2.5rem; font-size: 0.95rem; color: var(--muted); }
        .intro strong { color: var(--text); }

        .toc { padding: 1.2rem 1.4rem; margin-bottom: 2.5rem; }
        .toc-title { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--accent); margin-bottom: 0.7rem; }
        .toc ol { margin: 0 0 0 1.2rem; font-size: 0.88rem; color: var(--muted); }
        .toc a { color: var(--muted); text-decoration: none; }
        .toc a:hover { color: var(--accent); }

        section { margin-bottom: 2.2rem; }
        h2 { font-size: 1.15rem; font-weight: 700; color: var(--accent); margin-bottom: 0.9rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border); }
        h3 { font-size: 0.98rem; font-weight: 600; color: var(--text); margin: 1.1rem 0 0.5rem; }
        p { font-size: 0.94rem; color: var(--muted); margin-bottom: 0.8rem; }
        p strong, li strong { color: var(--text); }
        ul { margin: 0.6rem 0 1rem 1.3rem; font-size: 0.94rem; color: var(--muted); }
        li { margin-bottom: 0.5rem; }
        a.inline { color: var(--accent); }

        .table-wrap { overflow-x: auto; margin: 1rem 0 1.4rem; }
        .data-table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
        .data-table th { background: var(--surface); color: var(--accent); text-align: left; padding: 0.7rem 0.9rem; border: 1px solid var(--border); font-weight: 600; }
        .data-table td { padding: 0.7rem 0.9rem; border: 1px solid var(--border); color: var(--muted); vertical-align: top; }
        .data-table tr:nth-child(even) td { background: rgba(255, 255, 255, 0.02); }

        .highlight {
            background: rgba(29, 158, 117, 0.08); border: 1px solid rgba(29, 158, 117, 0.3);
            border-radius: var(--radius); padding: 1.1rem 1.3rem; margin: 1rem 0; font-size: 0.9rem;
        }
        .highlight-title { color: #4fd1a5; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 0.4rem; }
        .highlight p { color: var(--text); margin-bottom: 0; }

        .contact-card { padding: 1.5rem; text-align: center; margin-top: 2.5rem; }
        .contact-card h2 { border: none; margin-bottom: 0.6rem; }
        .contact-card a { color: var(--accent); text-decoration: none; font-weight: 600; }
        .contact-card a:hover { text-decoration: underline; }

        .footer { text-align: center; margin-top: 3rem; padding-top: 1.5rem; border-top: 1px solid var(--border); font-size: 0.78rem; color: var(--muted); opacity: 0.7; }

        @media (max-width: 480px) {
            h1 { font-size: 1.4rem; }
            .data-table { font-size: 0.78rem; }
            .data-table th, .data-table td { padding: 0.5rem 0.6rem; }
        }
    </style>
</head>

<body>
    <div class="container">

        @include('partials.lang-switch')

        <div class="header">
            <div class="logo-wrap" aria-hidden="true">
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
            <h1>{{ $en ? 'Privacy policy' : 'Politique de confidentialité' }}</h1>
            <div class="updated">{{ $en ? 'Last updated: October 19, 2026' : 'Dernière mise à jour : 19 octobre 2026' }}</div>
        </div>

        <nav class="toc" aria-label="{{ $en ? 'Contents' : 'Sommaire' }}">
            <div class="toc-title">{{ $en ? 'Contents' : 'Sommaire' }}</div>
            <ol>
                <li><a href="#donnees">{{ $en ? 'Data we collect' : 'Données que nous collectons' }}</a></li>
                <li><a href="#camera">{{ $en ? 'Camera and photos' : 'Caméra et photos' }}</a></li>
                <li><a href="#notifications">{{ $en ? 'Notifications' : 'Notifications' }}</a></li>
                <li><a href="#anonymat">{{ $en ? 'Anonymous reporting' : 'Anonymat du signalement' }}</a></li>
                <li><a href="#paiement">{{ $en ? 'Subscription and payment' : 'Abonnement et paiement' }}</a></li>
                <li><a href="#utilisation">{{ $en ? 'How we use your data' : 'Comment nous utilisons vos données' }}</a></li>
                <li><a href="#partage">{{ $en ? 'Who receives your data' : 'Qui reçoit vos données' }}</a></li>
                <li><a href="#conservation">{{ $en ? 'Retention and deletion' : 'Conservation et suppression' }}</a></li>
                <li><a href="#securite">{{ $en ? 'Security' : 'Sécurité' }}</a></li>
                <li><a href="#droits">{{ $en ? 'Your rights' : 'Vos droits' }}</a></li>
                <li><a href="#mineurs">{{ $en ? 'Minors' : 'Mineurs' }}</a></li>
                <li><a href="#modifications">{{ $en ? 'Changes to this policy' : 'Modifications de cette politique' }}</a></li>
                <li><a href="#contact">{{ $en ? 'Contact' : 'Contact' }}</a></li>
            </ol>
        </nav>

        @if ($en)
        {{-- ══════════════════════════ ENGLISH ══════════════════════════ --}}
        <div class="intro">
            QR Notify (<strong>"we"</strong>, <strong>"the app"</strong>), published by <strong>StreetSmart</strong>,
            respects your privacy. This policy explains what data we collect, why, and how it is protected. By using
            QR Notify, you accept the practices described here. See also our
            <a class="inline" href="{{ route('terms', ['lang' => 'en']) }}">terms of use</a>.
        </div>

        <section id="donnees">
            <h2>1. Data we collect</h2>
            <p>We only collect the data strictly necessary for the service to work.</p>
            <div class="table-wrap">
                <table class="data-table">
                    <tr><th>Data</th><th>When</th><th>Why</th></tr>
                    <tr><td>Name and email address</td><td>At sign-up (editable in the app)</td><td>Create and secure your account</td></tr>
                    <tr><td>Password (encrypted)</td><td>At sign-up</td><td>Secure login</td></tr>
                    <tr><td>Notification identifier of your device</td><td>At login and when it is renewed</td><td>Send you report notifications</td></tr>
                    <tr><td>Unique QR code</td><td>Generated at sign-up</td><td>Identify your vehicle without revealing your identity</td></tr>
                    <tr><td>History of reports received, and your replies (e.g. "I'm coming")</td><td>With each report</td><td>Let you view past reports and inform the person who reported</td></tr>
                    <tr><td>Mobile Money number and payment history</td><td>When you subscribe</td><td>Process the payment and activate your subscription</td></tr>
                    <tr><td>Messages sent through the contact form</td><td>When you contact us</td><td>Answer your request</td></tr>
                    <tr><td>Irreversible fingerprint of the reporter's network address</td><td>With each report</td><td>Prevent abuse; the address itself is never stored</td></tr>
                </table>
            </div>
            <div class="highlight">
                <div class="highlight-title">What we never collect</div>
                <p>We do not collect your location, your contacts or your personal photos, nor any data about the
                    person who reports (name, number, email).</p>
            </div>
        </section>

        <section id="camera">
            <h2>2. Camera and photos</h2>
            <p>QR Notify asks for camera access only to scan a QR code. The camera image is read on your device in
                real time; no photo or video is recorded or sent.</p>
            <p>You can also pick an existing image from your gallery to read a QR code. Only the selected image is
                sent, temporarily, to an image-reading service to decode the code; it is not kept afterwards.</p>
        </section>

        <section id="notifications">
            <h2>3. Notifications</h2>
            <p>To alert you when your vehicle is reported, we store a technical identifier specific to your device and
                use a notification delivery provider to send you the alert. This identifier contains no readable
                personal information.</p>
            <h3>Turning notifications off</h3>
            <p>You can turn notifications off at any time in your phone's settings. Doing so limits the app's main
                feature, as you will no longer be alerted.</p>
        </section>

        <section id="anonymat">
            <h2>4. Anonymous reporting</h2>
            <ul>
                <li><strong>The person who reports</strong> (who scans the QR code) needs no account and no app, and
                    sends no identifying data.</li>
                <li><strong>The vehicle owner</strong> never knows who reported: no name, phone number or email.</li>
                <li><strong>Phone numbers are never exchanged</strong> between the two people; reports and replies go
                    through our service.</li>
            </ul>
            <p>Messages are limited to a predefined list of neutral wordings (for example "Your vehicle is blocking
                the way"), which prevents abusive or identifying content. To prevent repeated malicious reports, the
                number of reports allowed per period is limited, based on an irreversible fingerprint of the network
                address, never on an identity.</p>
        </section>

        <section id="paiement">
            <h2>5. Subscription and payment</h2>
            <p>Payments are made by Mobile Money through a payment provider. We only receive the information needed
                to confirm the payment (number used, amount, status, reference). We never have access to your Mobile
                Money PIN.</p>
        </section>

        <section id="utilisation">
            <h2>6. How we use your data</h2>
            <ul>
                <li>Create and manage your account</li>
                <li>Generate your unique QR code</li>
                <li>Deliver report notifications and pass on your replies</li>
                <li>Show your report history</li>
                <li>Manage your trial and subscription</li>
                <li>Keep the service secure and prevent abuse</li>
                <li>Answer your requests sent through the contact form</li>
            </ul>
            <p>We never use your data for advertising and never sell personal data.</p>
        </section>

        <section id="partage">
            <h2>7. Who receives your data</h2>
            <p>Your data is only shared with the technical providers strictly necessary to run the service, each
                limited to its role:</p>
            <div class="table-wrap">
                <table class="data-table">
                    <tr><th>Type of provider</th><th>Role</th></tr>
                    <tr><td>Hosting provider</td><td>Stores the database and runs the service</td></tr>
                    <tr><td>Notification delivery provider</td><td>Delivers alerts to your phone</td></tr>
                    <tr><td>Mobile Money payment provider</td><td>Processes subscription payments</td></tr>
                    <tr><td>Image-reading service</td><td>Decodes a QR code from an image you choose, and generates the image of your QR code for printing</td></tr>
                </table>
            </div>
            <p>Some of these providers may process data outside Cameroon; they are chosen for their data-protection
                commitments. We share no data for commercial or advertising purposes. Data may be disclosed if
                Cameroonian law or a court decision requires it.</p>
        </section>

        <section id="conservation">
            <h2>8. Retention and deletion</h2>
            <p>Your data is kept as long as your account is active. You can delete your account at any time from the
                app (<strong>Settings › Delete my account</strong>) or by contacting us.</p>
            <p>Deletion permanently erases your profile, your QR code, your notification identifier and your report
                history. Payment records may be kept for the period required by accounting obligations. Contact
                messages are kept without being linked to your account.</p>
        </section>

        <section id="securite">
            <h2>9. Security</h2>
            <ul>
                <li>Passwords encrypted, never stored in plain text</li>
                <li>Encrypted connections between the app and our service</li>
                <li>Network addresses turned into irreversible fingerprints</li>
                <li>Access to your data restricted to your authenticated account</li>
            </ul>
            <p>No system is completely infallible: we cannot guarantee absolute security, but we commit to quickly
                fixing any vulnerability identified.</p>
        </section>

        <section id="droits">
            <h2>10. Your rights</h2>
            <ul>
                <li><strong>Access</strong>: get a copy of the data we hold about you</li>
                <li><strong>Rectification</strong>: correct inaccurate information (name and email are editable in the app)</li>
                <li><strong>Deletion</strong>: erase your account and data</li>
                <li><strong>Objection</strong>: object to some processing of your data</li>
            </ul>
            <p>To exercise these rights, contact us at the address at the bottom of this page.</p>
        </section>

        <section id="mineurs">
            <h2>11. Minors</h2>
            <p>Creating an account requires being at least 18 years old, or having the consent of a parent or legal
                guardian. If you believe a child has given us personal data without consent, contact us so we can
                delete it.</p>
        </section>

        <section id="modifications">
            <h2>12. Changes to this policy</h2>
            <p>This policy may be updated to reflect changes to the service or to the law. The date at the top of the
                page shows the latest version; significant changes will be announced in the app.</p>
        </section>

        @else
        {{-- ══════════════════════════ FRANÇAIS ══════════════════════════ --}}
        <div class="intro">
            QR Notify (<strong>« nous »</strong>, <strong>« l'application »</strong>), édité par
            <strong>StreetSmart</strong>, respecte votre vie privée. Cette politique explique quelles données nous
            collectons, pourquoi, et comment elles sont protégées. En utilisant QR Notify, vous acceptez les pratiques
            décrites ici. Voir aussi nos
            <a class="inline" href="{{ route('terms', ['lang' => 'fr']) }}">conditions d'utilisation</a>.
        </div>

        <section id="donnees">
            <h2>1. Données que nous collectons</h2>
            <p>Nous collectons uniquement les données strictement nécessaires au fonctionnement du service.</p>
            <div class="table-wrap">
                <table class="data-table">
                    <tr><th>Donnée</th><th>Quand</th><th>Pourquoi</th></tr>
                    <tr><td>Nom et adresse e-mail</td><td>À l'inscription (modifiables dans l'app)</td><td>Créer et sécuriser votre compte</td></tr>
                    <tr><td>Mot de passe (chiffré)</td><td>À l'inscription</td><td>Connexion sécurisée</td></tr>
                    <tr><td>Identifiant de notification de votre appareil</td><td>À la connexion et lors de son renouvellement</td><td>Vous envoyer les notifications de signalement</td></tr>
                    <tr><td>Code QR unique</td><td>Généré à l'inscription</td><td>Identifier votre véhicule sans révéler votre identité</td></tr>
                    <tr><td>Historique des signalements reçus et de vos réponses (ex. « J'arrive »)</td><td>À chaque signalement</td><td>Consulter vos signalements passés et informer la personne qui a signalé</td></tr>
                    <tr><td>Numéro Mobile Money et historique des paiements</td><td>Lors d'un abonnement</td><td>Traiter le paiement et activer votre abonnement</td></tr>
                    <tr><td>Messages envoyés via le formulaire de contact</td><td>Lorsque vous nous écrivez</td><td>Répondre à votre demande</td></tr>
                    <tr><td>Empreinte non réversible de l'adresse réseau du signalant</td><td>À chaque signalement</td><td>Prévenir les abus ; l'adresse elle-même n'est jamais conservée</td></tr>
                </table>
            </div>
            <div class="highlight">
                <div class="highlight-title">Ce que nous ne collectons jamais</div>
                <p>Nous ne collectons ni votre position, ni vos contacts, ni vos photos personnelles, ni aucune donnée de
                    la personne qui signale (nom, numéro, e-mail).</p>
            </div>
        </section>

        <section id="camera">
            <h2>2. Caméra et photos</h2>
            <p>QR Notify demande l'accès à la caméra uniquement pour scanner un QR code. L'image de la caméra est lue
                sur votre appareil, en temps réel ; aucune photo ni vidéo n'est enregistrée ou transmise.</p>
            <p>Vous pouvez aussi choisir une image existante dans votre galerie pour lire un QR code. Seule l'image
                sélectionnée est alors transmise, temporairement, à un service de lecture d'image pour décoder le code ;
                elle n'est pas conservée ensuite.</p>
        </section>

        <section id="notifications">
            <h2>3. Notifications</h2>
            <p>Pour vous alerter lorsque votre véhicule est signalé, nous conservons un identifiant technique propre à
                votre appareil et faisons appel à un prestataire d'envoi de notifications. Cet identifiant ne contient
                aucune information personnelle lisible.</p>
            <h3>Désactivation</h3>
            <p>Vous pouvez désactiver les notifications à tout moment dans les réglages de votre téléphone. Cela limite
                la fonction principale de l'application, puisque vous ne serez plus alerté.</p>
        </section>

        <section id="anonymat">
            <h2>4. Anonymat du signalement</h2>
            <ul>
                <li><strong>La personne qui signale</strong> (qui scanne le QR code) n'a besoin d'aucun compte ni
                    d'aucune application, et ne transmet aucune donnée identifiante.</li>
                <li><strong>Le propriétaire</strong> du véhicule ne sait jamais qui a signalé : ni nom, ni numéro, ni
                    e-mail.</li>
                <li><strong>Aucun numéro de téléphone n'est échangé</strong> entre les deux personnes : signalements et
                    réponses passent par notre service.</li>
            </ul>
            <p>Les messages sont limités à une liste prédéfinie de formulations neutres (par exemple « Votre véhicule
                bloque le passage »), ce qui empêche tout contenu injurieux ou identifiant. Pour prévenir les
                signalements malveillants répétés, leur nombre est limité par période, sur la base d'une empreinte non
                réversible de l'adresse réseau, jamais d'une identité.</p>
        </section>

        <section id="paiement">
            <h2>5. Abonnement et paiement</h2>
            <p>Les paiements se font par Mobile Money, via un prestataire de paiement. Nous ne recevons que les
                informations nécessaires pour confirmer le paiement (numéro utilisé, montant, statut, référence). Nous
                n'avons jamais accès à votre code secret Mobile Money.</p>
        </section>

        <section id="utilisation">
            <h2>6. Comment nous utilisons vos données</h2>
            <ul>
                <li>Créer et gérer votre compte</li>
                <li>Générer votre QR code unique</li>
                <li>Vous transmettre les signalements et relayer vos réponses</li>
                <li>Afficher l'historique de vos signalements</li>
                <li>Gérer votre essai et votre abonnement</li>
                <li>Assurer la sécurité du service et prévenir les abus</li>
                <li>Répondre à vos demandes envoyées via le formulaire de contact</li>
            </ul>
            <p>Nous n'utilisons jamais vos données à des fins publicitaires et ne vendons aucune donnée personnelle.</p>
        </section>

        <section id="partage">
            <h2>7. Qui reçoit vos données</h2>
            <p>Vos données ne sont partagées qu'avec les prestataires techniques strictement nécessaires au
                fonctionnement du service, chacun limité à son rôle :</p>
            <div class="table-wrap">
                <table class="data-table">
                    <tr><th>Type de prestataire</th><th>Rôle</th></tr>
                    <tr><td>Hébergeur</td><td>Stocke la base de données et fait fonctionner le service</td></tr>
                    <tr><td>Prestataire d'envoi de notifications</td><td>Achemine les alertes jusqu'à votre téléphone</td></tr>
                    <tr><td>Prestataire de paiement Mobile Money</td><td>Traite les paiements d'abonnement</td></tr>
                    <tr><td>Service de lecture d'image</td><td>Décode un QR code à partir d'une image que vous choisissez, et génère l'image de votre QR code pour l'impression</td></tr>
                </table>
            </div>
            <p>Certains de ces prestataires peuvent traiter des données hors du Cameroun ; ils sont choisis pour leurs
                engagements en matière de protection des données. Nous ne partageons aucune donnée à des fins
                commerciales ou publicitaires. Des données peuvent être communiquées si la loi camerounaise ou une
                décision de justice l'exige.</p>
        </section>

        <section id="conservation">
            <h2>8. Conservation et suppression</h2>
            <p>Vos données sont conservées tant que votre compte est actif. Vous pouvez supprimer votre compte à tout
                moment depuis l'application (<strong>Paramètres › Supprimer mon compte</strong>) ou en nous contactant.</p>
            <p>La suppression efface définitivement votre profil, votre QR code, votre identifiant de notification et
                l'historique de vos signalements. Les justificatifs de paiement peuvent être conservés pendant la durée
                imposée par les obligations comptables. Les messages de contact sont conservés sans lien avec votre
                compte.</p>
        </section>

        <section id="securite">
            <h2>9. Sécurité</h2>
            <ul>
                <li>Mots de passe chiffrés, jamais stockés en clair</li>
                <li>Connexions chiffrées entre l'application et notre service</li>
                <li>Adresses réseau transformées en empreintes non réversibles</li>
                <li>Accès à vos données réservé à votre compte authentifié</li>
            </ul>
            <p>Aucun système n'étant totalement infaillible, nous ne pouvons garantir une sécurité absolue, mais nous
                nous engageons à corriger rapidement toute vulnérabilité identifiée.</p>
        </section>

        <section id="droits">
            <h2>10. Vos droits</h2>
            <ul>
                <li><strong>Accès</strong> : obtenir une copie des données que nous détenons sur vous</li>
                <li><strong>Rectification</strong> : corriger des informations inexactes (nom et e-mail modifiables dans l'app)</li>
                <li><strong>Suppression</strong> : effacer votre compte et vos données</li>
                <li><strong>Opposition</strong> : vous opposer à certains traitements de vos données</li>
            </ul>
            <p>Pour exercer ces droits, contactez-nous à l'adresse indiquée en bas de page.</p>
        </section>

        <section id="mineurs">
            <h2>11. Mineurs</h2>
            <p>La création d'un compte nécessite d'avoir au moins 18 ans, ou l'accord d'un parent ou tuteur légal. Si
                vous pensez qu'un enfant nous a fourni des données personnelles sans accord, contactez-nous pour leur
                suppression.</p>
        </section>

        <section id="modifications">
            <h2>12. Modifications de cette politique</h2>
            <p>Cette politique peut être mise à jour pour refléter l'évolution du service ou de la loi. La date en haut
                de page indique la dernière version ; les changements importants seront annoncés dans l'application.</p>
        </section>
        @endif

        <div class="contact-card" id="contact">
            <h2>{{ $en ? 'Contact us' : 'Nous contacter' }}</h2>
            <p>{{ $en ? 'For any question about this policy or your personal data:' : 'Pour toute question sur cette politique ou vos données personnelles :' }}</p>
            <p><a href="mailto:contact@qrnotify.streetsmart.tech">contact@qrnotify.streetsmart.tech</a></p>
            <p style="margin-top: 0.8rem; font-size: 0.85rem;">
                {{ $en ? 'You can also use the contact form in the app.' : "Vous pouvez également utiliser le formulaire de contact dans l'application." }}
            </p>
        </div>

        <footer class="footer">
            QR Notify &mdash; {{ $en ? 'Anonymous vehicle reporting' : 'Signalement anonyme de véhicules' }}<br>
            &copy; StreetSmart &mdash; {{ date('Y') }}
        </footer>
    </div>
</body>

</html>
