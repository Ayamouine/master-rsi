<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Authentification') – Master RSI</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,300&display=swap" rel="stylesheet">

    <style>
        :root {
            --blue:      #1a3a6b;
            --gold:      #e8a020;
            --blue-dark: #0f2347;
            --blue-mid:  #2451a0;
            --white:     #ffffff;
            --light:     #f4f6fb;
            --muted:     #8a97b0;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--blue-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* ══════════════════════════
           HEADER
        ══════════════════════════ */
        header {
            background: linear-gradient(120deg, var(--blue-dark) 0%, #1a3a6b 55%, #243d7a 100%);
            border-bottom: 3px solid var(--gold);
            position: relative;
            overflow: hidden;
        }
        header::before {
            content: '';
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 700px 200px at 70% 50%, rgba(232,160,32,.07) 0%, transparent 70%),
                repeating-linear-gradient(
                    -45deg, transparent, transparent 28px,
                    rgba(255,255,255,.018) 28px, rgba(255,255,255,.018) 29px
                );
            pointer-events: none;
        }

        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1300px;
            margin: 0 auto;
            padding: 16px 40px;
            gap: 20px;
            position: relative;
            z-index: 1;
        }

        /* Logos */
        .logos { display: flex; align-items: center; gap: 16px; flex-shrink: 0; }
        .logo-card {
            background: rgba(255,255,255,.97);
            border-radius: 10px;
            padding: 7px 11px;
            box-shadow: 0 4px 20px rgba(0,0,0,.35);
            transition: transform .2s;
        }
        .logo-card:hover { transform: translateY(-2px); }
        .logo-card img { height: 58px; width: auto; object-fit: contain; display: block; }
        .logo-sep {
            width: 1px; height: 52px;
            background: linear-gradient(to bottom, transparent, rgba(232,160,32,.55), transparent);
        }

        /* Titre */
        .header-title { flex: 1; text-align: center; color: #fff; }
        .badge-pill {
            display: inline-block;
            background: var(--gold);
            color: var(--blue-dark);
            font-size: .62rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 3px 13px;
            border-radius: 20px;
            margin-bottom: 7px;
        }
        .header-title h1 {
            font-family: var(--font-sans);
            font-size: 1.3rem;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 3px;
        }
        .header-title p { font-size: .75rem; color: rgba(255,255,255,.6); font-weight: 300; }

        /* Contacts rapides */
        .header-chips { display: flex; flex-direction: column; gap: 5px; flex-shrink: 0; }
        .chip {
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.13);
            border-radius: 20px;
            padding: 4px 13px;
            font-size: .7rem;
            color: rgba(255,255,255,.72);
            display: flex; align-items: center; gap: 6px;
            text-decoration: none;
            transition: background .2s, color .2s;
        }
        .chip:hover { background: rgba(232,160,32,.2); color: var(--gold); }
        .chip i { color: var(--gold); font-size: .68rem; }

        /* ══════════════════════════
           CORPS — split
        ══════════════════════════ */
        .page-body { flex: 1; display: flex; min-height: 0; }

        /* ── Panneau gauche ── */
        .panel-left {
            flex: 1;
            background:
                linear-gradient(155deg, rgba(10,22,50,.93) 0%, rgba(26,58,107,.88) 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 50px;
            position: relative;
            overflow: hidden;
        }
        .panel-left::after {
            content: '';
            position: absolute; right: 0; top: 0; bottom: 0;
            width: 1px;
            background: linear-gradient(to bottom, transparent, var(--gold), transparent);
        }
        /* Cercle décoratif */
        .panel-left::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            border-radius: 50%;
            border: 1px solid rgba(232,160,32,.08);
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 0 0 0 80px rgba(232,160,32,.03), 0 0 0 160px rgba(232,160,32,.015);
        }

        .panel-content {
            text-align: center;
            color: #fff;
            max-width: 400px;
            position: relative;
            animation: fadeUp .7s ease both;
        }
        .fst-logo-hero {
            background: rgba(255,255,255,.96);
            border-radius: 16px;
            padding: 16px 22px;
            display: inline-block;
            margin-bottom: 26px;
            box-shadow: 0 10px 44px rgba(0,0,0,.4);
        }
        .fst-logo-hero img { height: 88px; width: auto; display: block; }
        .panel-content h2 {
            font-family: var(--font-sans);
            font-size: 1.65rem;
            font-weight: 700;
            margin-bottom: 10px;
            line-height: 1.3;
        }
        .panel-content p {
            font-size: .86rem;
            color: rgba(255,255,255,.62);
            line-height: 1.75;
            margin-bottom: 32px;
        }
        .panel-content strong { color: rgba(255,255,255,.9); }

        .stats-row { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }
        .stat-box {
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(232,160,32,.22);
            border-radius: 10px;
            padding: 12px 20px;
            text-align: center;
        }
        .stat-box .num {
            font-family: var(--font-sans);
            font-size: 1.45rem;
            color: var(--gold);
            display: block;
        }
        .stat-box .lbl {
            font-size: .65rem;
            color: rgba(255,255,255,.5);
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }

        /* ── Panneau droit (formulaire) ── */
        .panel-right {
            width: 450px;
            flex-shrink: 0;
            background: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 52px 46px;
        }

        @yield('panel-content')

        /* ══════════════════════════
           FOOTER
        ══════════════════════════ */
        footer {
            background: var(--blue-dark);
            border-top: 2px solid var(--gold);
            font-family: 'DM Sans', sans-serif;
        }

        .footer-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            max-width: 1300px;
            margin: 0 auto;
            padding: 36px 40px 28px;
        }

        .f-col { flex: 1; min-width: 190px; }
        .f-col h4 {
            font-family: var(--font-sans);
            font-size: .9rem;
            color: var(--gold);
            margin-bottom: 14px;
            padding-bottom: 7px;
            border-bottom: 1px solid rgba(232,160,32,.22);
            display: flex; align-items: center; gap: 7px;
        }

        /* Logo col */
        .f-logos { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
        .f-logos img {
            height: 48px;
            background: rgba(255,255,255,.92);
            border-radius: 7px;
            padding: 4px 6px;
        }
        .f-tagline { font-size: .79rem; color: rgba(255,255,255,.55); line-height: 1.6; }

        /* Liste infos */
        .info-list { list-style: none; }
        .info-list li {
            display: flex; align-items: flex-start; gap: 9px;
            font-size: .78rem;
            color: rgba(255,255,255,.62);
            margin-bottom: 9px;
            line-height: 1.55;
        }
        .info-list li i { color: var(--gold); font-size: .77rem; margin-top: 3px; flex-shrink: 0; }
        .info-list li a { color: rgba(255,255,255,.62); text-decoration: none; transition: color .2s; }
        .info-list li a:hover { color: var(--gold); }

        /* Réseaux sociaux */
        .socials { display: flex; flex-wrap: wrap; gap: 8px; }
        .soc-btn {
            display: flex; align-items: center; gap: 7px;
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 8px;
            padding: 7px 12px;
            font-size: .74rem;
            color: rgba(255,255,255,.68);
            text-decoration: none;
            transition: all .2s;
            white-space: nowrap;
        }
        .soc-btn:hover { background: rgba(232,160,32,.15); border-color: var(--gold); color: var(--gold); }
        .soc-btn.fb i  { color: #1877f2; }
        .soc-btn.li i  { color: #0a66c2; }
        .soc-btn.ig i  { color: #e1306c; }
        .soc-btn.yt i  { color: #ff0000; }
        .soc-btn.gm i  { color: #34a853; }
        .soc-btn.web i { color: var(--gold); }

        /* Carte */
        .f-map {
            border-radius: 9px;
            overflow: hidden;
            border: 1.5px solid rgba(232,160,32,.25);
        }
        .f-map iframe {
            display: block; width: 100%; height: 148px;
            filter: grayscale(15%) contrast(1.05);
        }
        .f-map-label {
            font-size: .7rem; color: rgba(255,255,255,.38);
            margin-top: 6px;
        }

        /* Barre basse */
        .footer-bar {
            background: rgba(0,0,0,.3);
            border-top: 1px solid rgba(255,255,255,.06);
            text-align: center;
            padding: 11px 24px;
            font-size: .72rem;
            color: rgba(255,255,255,.38);
        }
        .footer-bar a { color: var(--gold); text-decoration: none; }

        /* ══════════════════════════
           Animations
        ══════════════════════════ */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(22px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ══════════════════════════
           Responsive
        ══════════════════════════ */
        @media (max-width: 920px) {
            .panel-left { display: none; }
            .panel-right { width: 100%; }
            .header-chips { display: none; }
        }
        @media (max-width: 600px) {
            .header-inner { padding: 12px 16px; }
            .panel-right { padding: 36px 22px; }
            .footer-grid { padding: 24px 18px; gap: 26px; }
            .header-title h1 { font-size: 1rem; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ══════ HEADER ══════ --}}
<header>
    <div class="header-inner">
        <div class="logos">
            <div class="logo-card">
                <img src="{{ asset('images/fst1.png') }}" alt="Université Hassan Iᵉʳ">
            </div>
            <div class="logo-sep"></div>
            <div class="logo-card">
                <img src="{{ asset('images/fst.png') }}" alt="FST Settat">
            </div>
        </div>

        <div class="header-title">
            <div class="badge-pill">Module : Langage du Web &nbsp;·&nbsp; Pr. Sofia El Amoury</div>
            <h1>Faculté des Sciences et Techniques de Settat</h1>
            <p>Université Hassan I<sup>er</sup> &nbsp;—&nbsp; Master RSI &nbsp;·&nbsp; Année 2025-2026</p>
        </div>

        <div class="header-chips">
            <a class="chip" href="tel:+212523400736">
                <i class="fa fa-phone"></i> +212 5234-00736
            </a>
            <a class="chip" href="mailto:contact_fsts@uhp.ac.ma">
                <i class="fa fa-envelope"></i> contact_fsts@uhp.ac.ma
            </a>
            <a class="chip" href="https://www.fsts.ac.ma" target="_blank">
                <i class="fa fa-globe"></i> fsts.ac.ma
            </a>
        </div>
    </div>
</header>

{{-- ══════ CORPS ══════ --}}
<div class="page-body">

    {{-- Panneau gauche --}}
    <div class="panel-left">
        <div class="panel-content">
            <div class="fst-logo-hero">
                <img src="{{ asset('images/fst.png') }}" alt="FST Settat">
            </div>
            <h2>Bienvenue sur<br>votre espace RSI</h2>
            <p>
                Plateforme pédagogique du Master<br>
                <strong>Réseaux et Systèmes Informatiques</strong><br>
                Faculté des Sciences et Techniques de Settat
            </p>
            <div class="stats-row">
                <div class="stat-box">
                    <span class="num">6</span>
                    <span class="lbl">Projets</span>
                </div>
                <div class="stat-box">
                    <span class="num">RSI</span>
                    <span class="lbl">Master</span>
                </div>
                <div class="stat-box">
                    <span class="num">2025</span>
                    <span class="lbl">Année</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Panneau formulaire --}}
    <div class="panel-right">
        @yield('content')
    </div>

</div>

{{-- ══════ FOOTER ══════ --}}
<footer>
    <div class="footer-grid">

        {{-- Présentation --}}
        <div class="f-col" style="max-width:270px;">
            <div class="f-logos">
                <img src="{{ asset('images/fst.png') }}"  alt="FST">
                <img src="{{ asset('images/fst1.png') }}" alt="UH1">
            </div>
            <div class="f-tagline">
                Faculté des Sciences et Techniques de Settat,<br>
                composante de l'Université Hassan I<sup>er</sup>.<br>
                Formation d'excellence en sciences et technologies.
            </div>
        </div>

        {{-- Coordonnées --}}
        <div class="f-col">
            <h4><i class="fa fa-address-book"></i> Coordonnées</h4>
            <ul class="info-list">
                <li><i class="fa fa-map-marker-alt"></i>
                    Km 3, Route de Casablanca – B.P. 577,<br>26000 Settat, Maroc
                </li>
                <li><i class="fa fa-phone"></i>
                    <a href="tel:+212523400736">+212 5234-00736</a>
                </li>
                <li><i class="fa fa-fax"></i>
                    <a href="tel:+212523400737">+212 5234-00737</a>
                </li>
                <li><i class="fa fa-envelope"></i>
                    <a href="mailto:contact_fsts@uhp.ac.ma">contact_fsts@uhp.ac.ma</a>
                </li>
                <li><i class="fa fa-globe"></i>
                    <a href="https://www.fsts.ac.ma" target="_blank">www.fsts.ac.ma</a>
                </li>
                <li><i class="fa fa-university"></i>
                    <a href="https://www.uh1.ac.ma" target="_blank">www.uh1.ac.ma</a>
                </li>
            </ul>
        </div>

        {{-- Réseaux sociaux --}}
        <div class="f-col">
            <h4><i class="fa fa-share-alt"></i> Suivez-nous</h4>
            <div class="socials">
                <a class="soc-btn fb" href="https://www.facebook.com/fstsettatofficiel" target="_blank">
                    <i class="fab fa-facebook-f"></i> Facebook
                </a>
                <a class="soc-btn li" href="https://www.linkedin.com/school/fst-settat" target="_blank">
                    <i class="fab fa-linkedin-in"></i> LinkedIn
                </a>
                <a class="soc-btn ig" href="https://www.instagram.com/fstsettat" target="_blank">
                    <i class="fab fa-instagram"></i> Instagram
                </a>
                <a class="soc-btn web" href="https://www.fsts.ac.ma" target="_blank">
                    <i class="fa fa-globe"></i> Site officiel
                </a>
                <a class="soc-btn gm" href="https://maps.google.com/?q=Faculte+Sciences+Techniques+Settat" target="_blank">
                    <i class="fa fa-map-marker-alt"></i> Google Maps
                </a>
            </div>
        </div>

        {{-- Carte --}}
        <div class="f-col" style="min-width:230px; max-width:290px;">
            <h4><i class="fa fa-map"></i> Localisation</h4>
            <div class="f-map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3344.970328812372!2d-7.619079925249059!3d33.030912170969216!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xda61aabf2a6a8b9%3A0xe6a579c28d993de9!2zRlNUIDogRmFjdWx0w6kgZGVzIFNjaWVuY2VzIGV0IFRlY2huaXF1ZSDZg9mE2YrYqSDYp9mE2LnZhNmI2YUg2Ygg2KfZhNiq2YLZhtmK2KfYqiDYs9i32KfYqnNfU2V0dGF0!5e0!3m2!1sfr!2sus!4v1776528861801!5m2!1sfr!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <p class="f-map-label">Km 3, Route de Casablanca, 26000 Settat</p>
        </div>

    </div>

    <div class="footer-bar">
        &copy; {{ date('Y') }} –
        <a href="https://www.fsts.ac.ma" target="_blank">FST Settat</a> &nbsp;·&nbsp;
        <a href="https://www.uh1.ac.ma"  target="_blank">Université Hassan I<sup>er</sup></a>
        &nbsp;·&nbsp; Master RSI – Langage du Web &nbsp;·&nbsp; Pr. Sofia El Amoury
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
