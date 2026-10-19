{{-- ============================================================
     resources/views/terms.blade.php
     Conditions d'utilisation — QR Notify (FR / EN)
     Langue : ?lang=fr|en (envoyée par l'app), sinon celle du navigateur.
     Charte : #0d1f3c | #c9962a | #f0e8d5
     ⚠ Texte à faire valider par un juriste avant publication définitive.
     ============================================================ --}}
@php($en = $lang === 'en')
<!DOCTYPE html>
<html lang="{{ $lang }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Notify — {{ $en ? 'Terms of use' : "Conditions d'utilisation" }}</title>
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

        .lang-switch { display: flex; justify-content: flex-end; gap: 0.4rem; margin-bottom: 1rem; }
        .lang-switch a {
            color: var(--muted); text-decoration: none; font-size: 0.8rem; font-weight: 700;
            letter-spacing: 0.06em; padding: 0.3rem 0.7rem; border-radius: 999px; border: 1px solid var(--border);
        }
        .lang-switch a.active { background: var(--accent); color: var(--bg); border-color: var(--accent); }

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

        .intro, .toc, .contact-card {
            background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius);
        }
        .intro { padding: 1.4rem 1.5rem; margin-bottom: 2.5rem; font-size: 0.95rem; color: var(--muted); }
        .intro strong { color: var(--text); }

        .toc { padding: 1.2rem 1.4rem; margin-bottom: 2.5rem; }
        .toc-title { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--accent); margin-bottom: 0.7rem; }
        .toc ol { margin: 0 0 0 1.2rem; font-size: 0.88rem; color: var(--muted); }
        .toc a { color: var(--muted); text-decoration: none; }
        .toc a:hover { color: var(--accent); }

        section { margin-bottom: 2.2rem; }
        h2 { font-size: 1.15rem; font-weight: 700; color: var(--accent); margin-bottom: 0.9rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border); }
        p { font-size: 0.94rem; color: var(--muted); margin-bottom: 0.8rem; }
        p strong, li strong { color: var(--text); }
        ul { margin: 0.6rem 0 1rem 1.3rem; font-size: 0.94rem; color: var(--muted); }
        li { margin-bottom: 0.5rem; }
        a.inline { color: var(--accent); }

        .highlight {
            background: rgba(201, 150, 42, 0.08); border: 1px solid rgba(201, 150, 42, 0.3);
            border-radius: var(--radius); padding: 1.1rem 1.3rem; margin: 1rem 0; font-size: 0.9rem;
        }
        .highlight p { color: var(--text); margin-bottom: 0; }

        .contact-card { padding: 1.5rem; text-align: center; margin-top: 2.5rem; }
        .contact-card h2 { border: none; margin-bottom: 0.6rem; }
        .contact-card a { color: var(--accent); text-decoration: none; font-weight: 600; }
        .contact-card a:hover { text-decoration: underline; }

        .footer { text-align: center; margin-top: 3rem; padding-top: 1.5rem; border-top: 1px solid var(--border); font-size: 0.78rem; color: var(--muted); opacity: 0.7; }

        @media (max-width: 480px) { h1 { font-size: 1.4rem; } }
    </style>
</head>

<body>
    <div class="container">

        <nav class="lang-switch" aria-label="{{ $en ? 'Language' : 'Langue' }}">
            <a href="?lang=fr" class="{{ $en ? '' : 'active' }}" @if(!$en) aria-current="page" @endif>FR</a>
            <a href="?lang=en" class="{{ $en ? 'active' : '' }}" @if($en) aria-current="page" @endif>EN</a>
        </nav>

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
            <h1>{{ $en ? 'Terms of use' : "Conditions d'utilisation" }}</h1>
            <div class="updated">{{ $en ? 'Last updated: October 19, 2026' : 'Dernière mise à jour : 19 octobre 2026' }}</div>
        </div>

        @if ($en)
        <div class="intro">
            These terms govern your use of <strong>QR Notify</strong> (the mobile app, the reporting web page reached by
            scanning a QR code, and related services), published by <strong>StreetSmart</strong>
            (<strong>"we"</strong>, <strong>"us"</strong>). By creating an account or sending a report, you accept these
            terms. If you do not accept them, please do not use the service.
        </div>
        @else
        <div class="intro">
            Les présentes conditions encadrent l'utilisation de <strong>QR Notify</strong> (l'application mobile, la page
            web de signalement ouverte en scannant un QR code, et les services associés), édité par
            <strong>StreetSmart</strong> (<strong>« nous »</strong>). En créant un compte ou en envoyant un signalement,
            vous acceptez ces conditions. Si vous ne les acceptez pas, n'utilisez pas le service.
        </div>
        @endif

        <nav class="toc" aria-label="{{ $en ? 'Contents' : 'Sommaire' }}">
            <div class="toc-title">{{ $en ? 'Contents' : 'Sommaire' }}</div>
            <ol>
                <li><a href="#service">{{ $en ? 'The service' : 'Le service' }}</a></li>
                <li><a href="#compte">{{ $en ? 'Your account' : 'Votre compte' }}</a></li>
                <li><a href="#usage">{{ $en ? 'Acceptable use' : 'Règles d\'utilisation' }}</a></li>
                <li><a href="#limites">{{ $en ? 'What the service does not guarantee' : 'Ce que le service ne garantit pas' }}</a></li>
                <li><a href="#abonnement">{{ $en ? 'Trial, subscription and payment' : 'Essai, abonnement et paiement' }}</a></li>
                <li><a href="#donnees">{{ $en ? 'Personal data' : 'Données personnelles' }}</a></li>
                <li><a href="#propriete">{{ $en ? 'Intellectual property' : 'Propriété intellectuelle' }}</a></li>
                <li><a href="#suspension">{{ $en ? 'Suspension and account deletion' : 'Suspension et suppression du compte' }}</a></li>
                <li><a href="#responsabilite">{{ $en ? 'Liability' : 'Responsabilité' }}</a></li>
                <li><a href="#modifications">{{ $en ? 'Changes to these terms' : 'Modification des conditions' }}</a></li>
                <li><a href="#droit">{{ $en ? 'Governing law' : 'Droit applicable' }}</a></li>
                <li><a href="#contact">{{ $en ? 'Contact' : 'Contact' }}</a></li>
            </ol>
        </nav>

        @if ($en)
        {{-- ══════════════════════════ ENGLISH ══════════════════════════ --}}
        <section id="service">
            <h2>1. The service</h2>
            <p>QR Notify lets a vehicle owner place a QR code on their vehicle. Anyone who scans it can send the owner a
                predefined report (vehicle blocking the way, lights on, window open, flat tire…) without either person
                sharing their phone number. The owner receives a notification and can reply ("I'm coming", "In 10
                minutes"…); the person who reported sees the reply.</p>
            <p>Reporting does not require installing the app or creating an account. Receiving reports requires an owner
                account in the app.</p>
        </section>

        <section id="compte">
            <h2>2. Your account</h2>
            <ul>
                <li>You must be at least 18 years old, or have the consent of a parent or legal guardian, to create an account.</li>
                <li>You agree to provide accurate information and keep it up to date.</li>
                <li>You are responsible for keeping your password confidential and for activity on your account.</li>
                <li>Each QR code is personal: place it only on a vehicle that you own or are authorized to use.</li>
            </ul>
        </section>

        <section id="usage">
            <h2>3. Acceptable use</h2>
            <p>When using QR Notify, you agree <strong>not</strong> to:</p>
            <ul>
                <li>send false, abusive or repeated reports, or use the service to harass anyone;</li>
                <li>place a QR code on someone else's vehicle without their consent, or copy or tamper with another person's QR code;</li>
                <li>attempt to identify the person behind a report or an account by any means;</li>
                <li>disrupt the service, bypass its limits, or access it by automated means not provided by us.</li>
            </ul>
            <p>To prevent abuse, the number of reports per device and per vehicle is limited, and we may block reports
                from a source that breaks these rules.</p>
        </section>

        <section id="limites">
            <h2>4. What the service does not guarantee</h2>
            <p>Delivery of a report depends on factors outside our control: mobile network or internet access of both
                people, the owner's phone being on, notification settings, and third-party services. We therefore <strong>cannot guarantee</strong> that every report will be delivered or read,
                nor how quickly.</p>
            <div class="highlight">
                <p><strong>QR Notify is not an emergency service.</strong> In case of danger, accident or emergency,
                    contact the police, fire brigade or medical emergency services directly.</p>
            </div>
        </section>

        <section id="abonnement">
            <h2>5. Trial, subscription and payment</h2>
            <ul>
                <li><strong>Free trial:</strong> each new account gets 30 days free, with no commitment and no payment method required.</li>
                <li><strong>After the trial:</strong> an active subscription is required to keep receiving report notifications. Reports about your vehicle are still recorded and visible in the app.</li>
                <li><strong>Plans and prices:</strong> the available plans, their duration and price in CFA francs (XAF) are shown in the app before any payment.</li>
                <li><strong>Payment:</strong> by Mobile Money (MTN MoMo, Orange Money) through a payment provider. You confirm each payment on your phone.</li>
                <li><strong>No automatic renewal:</strong> a subscription ends at the end of its period. Renewing early adds the new period after the current one, with no days lost.</li>
                <li><strong>Refunds:</strong> a started period is not refundable, except in case of a billing error or duplicate charge, which you can report to us.</li>
            </ul>
        </section>

        <section id="donnees">
            <h2>6. Personal data</h2>
            <p>The way we collect and protect your data is described in our
                <a class="inline" href="{{ route('privacy-policy') }}">privacy policy</a>. In short: your phone number is
                never shared with the other person; reports go through our service.</p>
        </section>

        <section id="propriete">
            <h2>7. Intellectual property</h2>
            <p>The QR Notify name, logo, app, web pages and their content belong to StreetSmart. You receive a personal,
                non-exclusive and non-transferable right to use the service in accordance with these terms.</p>
        </section>

        <section id="suspension">
            <h2>8. Suspension and account deletion</h2>
            <p>You can delete your account at any time from the app (<strong>Settings › Delete my account</strong>).
                This permanently erases your account and your QR code; stickers already printed stop working.</p>
            <p>We may suspend or delete an account that seriously or repeatedly breaks these terms, after warning you
                where possible.</p>
        </section>

        <section id="responsabilite">
            <h2>9. Liability</h2>
            <p>The service is provided "as is". To the extent permitted by law, StreetSmart is not liable for indirect
                damage, nor for damage resulting from a report not delivered or delivered late, from misuse of the
                service by a user, or from interruptions of third-party services. Nothing in these terms limits rights
                that the law does not allow to be limited.</p>
        </section>

        <section id="modifications">
            <h2>10. Changes to these terms</h2>
            <p>We may update these terms. The date at the top of the page shows the latest version. If a change is
                significant, we will inform you in the app before it takes effect.</p>
        </section>

        <section id="droit">
            <h2>11. Governing law</h2>
            <p>These terms are governed by the laws of the Republic of Cameroon. In case of dispute, we will first seek
                an amicable solution; failing that, the competent courts of Cameroon will have jurisdiction.</p>
        </section>

        @else
        {{-- ══════════════════════════ FRANÇAIS ══════════════════════════ --}}
        <section id="service">
            <h2>1. Le service</h2>
            <p>QR Notify permet au propriétaire d'un véhicule d'y apposer un QR code. Toute personne qui le scanne peut
                lui envoyer un signalement prédéfini (véhicule qui bloque le passage, phares allumés, vitre ouverte, pneu
                dégonflé…) sans qu'aucun des deux n'ait à communiquer son numéro de téléphone. Le propriétaire reçoit une
                notification et peut répondre (« J'arrive », « Dans 10 min »…) ; la personne qui a signalé voit la
                réponse.</p>
            <p>Signaler ne nécessite ni l'installation de l'application ni la création d'un compte. Recevoir des
                signalements nécessite un compte propriétaire dans l'application.</p>
        </section>

        <section id="compte">
            <h2>2. Votre compte</h2>
            <ul>
                <li>Vous devez avoir au moins 18 ans, ou l'accord d'un parent ou tuteur légal, pour créer un compte.</li>
                <li>Vous vous engagez à fournir des informations exactes et à les tenir à jour.</li>
                <li>Vous êtes responsable de la confidentialité de votre mot de passe et de l'activité de votre compte.</li>
                <li>Chaque QR code est personnel : apposez-le uniquement sur un véhicule dont vous êtes propriétaire ou que vous êtes autorisé à utiliser.</li>
            </ul>
        </section>

        <section id="usage">
            <h2>3. Règles d'utilisation</h2>
            <p>En utilisant QR Notify, vous vous engagez à <strong>ne pas</strong> :</p>
            <ul>
                <li>envoyer de faux signalements, des signalements abusifs ou répétés, ni utiliser le service pour harceler qui que ce soit ;</li>
                <li>apposer un QR code sur le véhicule d'autrui sans son accord, ni copier ou détourner le QR code d'une autre personne ;</li>
                <li>chercher à identifier, par quelque moyen que ce soit, la personne à l'origine d'un signalement ou d'un compte ;</li>
                <li>perturber le service, contourner ses limites ou y accéder par des moyens automatisés que nous ne proposons pas.</li>
            </ul>
            <p>Pour éviter les abus, le nombre de signalements par appareil et par véhicule est limité, et nous pouvons
                bloquer les signalements d'une source qui enfreint ces règles.</p>
        </section>

        <section id="limites">
            <h2>4. Ce que le service ne garantit pas</h2>
            <p>L'acheminement d'un signalement dépend d'éléments qui ne dépendent pas de nous : réseau mobile ou accès à
                Internet des deux personnes, téléphone du propriétaire allumé, réglages de notifications, services tiers. 
                Nous ne pouvons donc <strong>pas garantir</strong> que chaque signalement sera
                reçu ou lu, ni dans quel délai.</p>
            <div class="highlight">
                <p><strong>QR Notify n'est pas un service d'urgence.</strong> En cas de danger, d'accident ou d'urgence,
                    contactez directement la police, les pompiers ou les secours médicaux.</p>
            </div>
        </section>

        <section id="abonnement">
            <h2>5. Essai, abonnement et paiement</h2>
            <ul>
                <li><strong>Essai gratuit :</strong> chaque nouveau compte bénéficie de 30 jours gratuits, sans engagement et sans moyen de paiement demandé.</li>
                <li><strong>Après l'essai :</strong> un abonnement actif est nécessaire pour continuer à recevoir les notifications de signalement. Les signalements sur votre véhicule restent enregistrés et consultables dans l'application.</li>
                <li><strong>Offres et prix :</strong> les offres disponibles, leur durée et leur prix en francs CFA (XAF) sont affichés dans l'application avant tout paiement.</li>
                <li><strong>Paiement :</strong> par Mobile Money (MTN MoMo, Orange Money), via un prestataire de paiement. Vous confirmez chaque paiement sur votre téléphone.</li>
                <li><strong>Pas de renouvellement automatique :</strong> un abonnement prend fin au terme de sa période. Un renouvellement anticipé ajoute la nouvelle période après l'actuelle, sans perte de jours.</li>
                <li><strong>Remboursement :</strong> une période commencée n'est pas remboursable, sauf erreur de facturation ou double débit, que vous pouvez nous signaler.</li>
            </ul>
        </section>

        <section id="donnees">
            <h2>6. Données personnelles</h2>
            <p>La manière dont nous collectons et protégeons vos données est décrite dans notre
                <a class="inline" href="{{ route('privacy-policy') }}">politique de confidentialité</a>. En résumé : votre
                numéro de téléphone n'est jamais communiqué à l'autre personne ; les signalements passent par notre
                service.</p>
        </section>

        <section id="propriete">
            <h2>7. Propriété intellectuelle</h2>
            <p>Le nom QR Notify, son logo, l'application, les pages web et leur contenu appartiennent à StreetSmart. Vous
                bénéficiez d'un droit d'utilisation personnel, non exclusif et non cessible du service, conformément aux
                présentes conditions.</p>
        </section>

        <section id="suspension">
            <h2>8. Suspension et suppression du compte</h2>
            <p>Vous pouvez supprimer votre compte à tout moment depuis l'application (<strong>Paramètres › Supprimer
                    mon compte</strong>). Cela efface définitivement votre compte et votre QR code ; les autocollants déjà
                imprimés cessent de fonctionner.</p>
            <p>Nous pouvons suspendre ou supprimer un compte qui enfreint gravement ou de manière répétée ces
                conditions, après vous avoir prévenu lorsque c'est possible.</p>
        </section>

        <section id="responsabilite">
            <h2>9. Responsabilité</h2>
            <p>Le service est fourni « en l'état ». Dans les limites permises par la loi, StreetSmart n'est pas
                responsable des dommages indirects, ni des dommages résultant d'un signalement non reçu ou reçu en
                retard, d'une utilisation abusive du service par un utilisateur, ou d'une interruption de services tiers.
                Rien dans ces conditions ne limite les droits que la loi interdit de limiter.</p>
        </section>

        <section id="modifications">
            <h2>10. Modification des conditions</h2>
            <p>Nous pouvons mettre à jour ces conditions. La date en haut de page indique la dernière version. En cas de
                changement important, nous vous en informerons dans l'application avant son entrée en vigueur.</p>
        </section>

        <section id="droit">
            <h2>11. Droit applicable</h2>
            <p>Ces conditions sont régies par le droit de la République du Cameroun. En cas de litige, nous
                rechercherons d'abord une solution amiable ; à défaut, les tribunaux compétents du Cameroun seront saisis.</p>
        </section>
        @endif

        <div class="contact-card" id="contact">
            <h2>{{ $en ? 'Contact us' : 'Nous contacter' }}</h2>
            <p>{{ $en ? 'For any question about these terms:' : 'Pour toute question concernant ces conditions :' }}</p>
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
