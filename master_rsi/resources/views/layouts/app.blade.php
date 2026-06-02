<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Master RSI')</title>

    <script>
        (function () {
            const savedTheme = localStorage.getItem('master_rsi_theme');
            if (savedTheme === 'light') {
                document.documentElement.setAttribute('data-theme', 'light');
            }
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --font-sans: 'DM Sans', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
            --bg-main: #0f1419;
            --bg-surface: #1a1f2b;
            --bg-soft: #232d3f;
            --border: #384450;
            --accent: #06b6d4;
            --accent-2: #f97316;
            --accent-3: #8b5cf6;
            --primary-btn: #0891b2;
            --text-main: #e5f2ff;
            --text-muted: #8fa3b5;
            --nav-bg: rgba(15, 20, 25, 0.92);
            --glow: rgba(6, 182, 212, 0.12);
            --shadow-soft: 0 8px 24px rgba(0, 0, 0, 0.18);
            --flash-success-bg: #09341d;
            --flash-success-border: #10b981;
            --flash-success-text: #6ee7b7;
            --flash-error-bg: #2f0f1f;
            --flash-error-border: #f87171;
            --flash-error-text: #feca5a;
            --avatar-bg-1: #1e40af;
            --avatar-bg-2: #0891b2;
        }

        html[data-theme='light'] {
            --bg-main: #eef4fb;
            --bg-surface: #e3ebf5;
            --bg-soft: #d4dfeb;
            --border: #b7c6d6;
            --accent: #06b6d4;
            --accent-2: #f97316;
            --accent-3: #8b5cf6;
            --primary-btn: #0891b2;
            --text-main: #0f172a;
            --text-muted: #4f6478;
            --nav-bg: rgba(238, 244, 251, 0.94);
            --glow: rgba(14, 165, 233, 0.08);
            --shadow-soft: 0 10px 24px rgba(15, 23, 42, 0.08);
            --flash-success-bg: #ecfdf5;
            --flash-success-border: #10b981;
            --flash-success-text: #047857;
            --flash-error-bg: #fef2f2;
            --flash-error-border: #f87171;
            --flash-error-text: #7f1d1d;
            --avatar-bg-1: #93c5fd;
            --avatar-bg-2: #67e8f9;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: radial-gradient(circle at top right, var(--glow), transparent 32%), var(--bg-main);
            color: var(--text-main);
            font-family: var(--font-sans);
        }

        /* Global controls */
        button, .btn {
            font-family: var(--font-sans);
            border-radius: 10px;
            padding: 8px 12px;
            border: 1px solid color-mix(in srgb, var(--primary-btn) 70%, var(--border) 30%);
            background: linear-gradient(180deg, var(--primary-btn), color-mix(in srgb, var(--primary-btn) 80%, #000 20%));
            color: #fff;
            cursor: pointer;
        }

        input, textarea, select {
            font-family: var(--font-sans);
            border-radius: 8px;
            border: 1px solid var(--border);
            padding: 8px 10px;
            background: var(--bg-surface);
            color: var(--text-main);
        }

        /* Project page helpers */
        .proj-badge { display:inline-flex; align-items:center; gap:8px; background:color-mix(in srgb, var(--accent) 12%, var(--bg-surface) 88%); border:1px solid var(--border); color:var(--accent); font-size:.72rem; font-weight:700; letter-spacing:1px; text-transform:uppercase; padding:5px 12px; border-radius:999px; }
        .page-heading { margin-top:12px; margin-bottom:18px; font-size:1.05rem; font-weight:800; color:var(--text-main); display:block; }

        .container {
            width: min(1180px, calc(100% - 32px));
            margin-inline: auto;
        }

        .top-nav {
            position: sticky;
            top: 0;
            z-index: 40;
            backdrop-filter: blur(6px);
            background: var(--nav-bg);
            border-bottom: 1px solid var(--border);
            box-shadow: var(--shadow-soft);
        }

        .top-nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 0;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--text-main);
            text-decoration: none;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .brand-pill {
            background: linear-gradient(130deg, #0891b2, #06b6d4);
            border: 1px solid #00d4ff;
            color: #e0f7ff;
            border-radius: 999px;
            padding: 6px 12px;
            font-size: 0.78rem;
            line-height: 1;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-links a {
            color: var(--text-muted);
            text-decoration: none;
            border: 1px solid transparent;
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 0.9rem;
            transition: 160ms ease;
        }

        .nav-links a:hover,
        .nav-links a.is-active {
            color: var(--text-main);
            border-color: var(--border);
            background: color-mix(in srgb, var(--accent) 12%, transparent 88%);
        }

        .right-tools {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .theme-toggle {
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--bg-soft);
            color: var(--text-main);
            font-size: 0.8rem;
            padding: 8px 11px;
            cursor: pointer;
        }

        .theme-toggle:hover { border-color: var(--accent); }

        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 1px solid var(--border);
            background: linear-gradient(145deg, var(--avatar-bg-1), var(--avatar-bg-2));
            color: var(--text-main);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
        }

        .main-content {
            flex: 1;
            width: 100%;
            padding: 8px 0 28px;
        }

        .main-content .container {
            animation: pageFade 0.45s ease both;
        }

        .flash {
            margin-bottom: 16px;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 0.92rem;
            background: var(--bg-soft);
        }

        .flash.success {
            border-color: var(--flash-success-border);
            background: var(--flash-success-bg);
            color: var(--flash-success-text);
        }

        .flash.error {
            border-color: var(--flash-error-border);
            background: var(--flash-error-bg);
            color: var(--flash-error-text);
        }

        .btn-logout {
            margin-left: 4px;
            border: 1px solid color-mix(in srgb, var(--primary-btn) 78%, #000 22%);
            border-radius: 10px;
            background: var(--primary-btn);
            color: #fff2f5;
            font-size: 0.84rem;
            padding: 8px 11px;
            cursor: pointer;
        }

        .btn-logout:hover { filter: brightness(1.08); }

        a,
        button {
            transition: transform 180ms ease, filter 180ms ease, background-color 180ms ease, border-color 180ms ease, color 180ms ease;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
        }

        [data-anim] {
            opacity: 0;
            transform: translateY(14px);
            transition: opacity 520ms ease, transform 520ms ease;
            will-change: opacity, transform;
        }

        [data-anim].in {
            opacity: 1;
            transform: translateY(0);
        }

        @keyframes pageFade {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        footer {
            border-top: 1px solid var(--border);
            background: var(--bg-surface);
            color: var(--text-muted);
            font-size: 0.88rem;
            text-align: center;
            padding: 15px 12px;
        }

        /* Override any localized serif fonts and enforce global sans for consistency */
        h1,h2,h3,h4,h5,h6 { font-family: var(--font-sans); }

        /* Logo sizing */
        .nav-logo { height: 42px; display: inline-flex; align-items: center; }
        .nav-logo img { height: 100%; width: auto; display:block; }

        /* Footer logos */
        .footer-logo img { height: 40px; width: auto; display:block; }

        /* Constrain large SVGs / images so they don't overflow layout */
        img, svg { max-width: 100%; height: auto; }

        /* Optional hero SVG limit when present */
        .hero-svg, .welcome-hero-svg { max-width: 420px; width: 100%; height: auto; }

        /* Unified UI tokens */
        .page-heading { font-size:1.05rem; font-weight:700; color:var(--text-main); display:flex; align-items:center; gap:8px; margin:10px 0 18px; }

        .chip, .badge { display:inline-flex; align-items:center; gap:8px; padding:6px 12px; border-radius:999px; background:color-mix(in srgb, var(--accent) 14%, transparent 86%); border:1px solid var(--border); color:var(--text-main); font-weight:700; font-size:0.78rem; }

        .btn { display:inline-flex; align-items:center; gap:8px; padding:8px 14px; border-radius:10px; border:1px solid transparent; background:var(--accent); color:#fff; font-weight:700; cursor:pointer; transition:transform .15s ease, filter .15s ease; }
        .btn.secondary { background:var(--bg-main); color:var(--text-main); border-color:var(--border); }
        .btn:hover { transform:translateY(-2px); filter:brightness(1.03); }

        /* Additional constraints for unusually large fixed-width elements */
        .w-\[448px\], .w-\[438px\] { max-width:420px !important; width:100% !important; }

        .footer-full {
            background: var(--bg-surface);
            border-top: 1px solid var(--border);
            padding: 20px 0;
        }

        .footer-cols {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 20px;
            max-width: 1180px;
            margin: 0 auto;
            padding: 20px 16px;
        }

        .footer-col h4 {
            margin: 0 0 12px;
            font-size: 0.85rem;
            color: var(--text-main);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .footer-col ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 6px;
        }

        .footer-col li a {
            color: var(--text-muted);
            font-size: 0.82rem;
            text-decoration: none;
        }

        .footer-col li a:hover { color: var(--accent); }

        .footer-logos { display: flex; gap: 12px; margin-bottom: 12px; }
        .footer-logo { height: 40px; background: rgba(255,255,255,0.08); border-radius: 6px; padding: 4px 8px; }
        .footer-logo img { height: 100%; width: auto; }

        .social-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 46px;
            height: 40px;
            padding: 0 12px;
            border-radius: 12px;
            border: 1px solid var(--border);
            color: #fff;
            text-decoration: none;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.7px;
            line-height: 1;
            transition: transform 0.2s ease, filter 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.12);
        }

        .social-btn span { display: inline-block; }

        .social-fb { background: linear-gradient(135deg, #1877f2, #0f5bd6); }
        .social-ig { background: linear-gradient(135deg, #f58529, #e1306c 55%, #833ab4); }
        .social-in { background: linear-gradient(135deg, #0a66c2, #0ea5e9); }
        .social-web { background: linear-gradient(135deg, #0f766e, #14b8a6); }

        html[data-theme='light'] .social-btn {
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08);
        }

        .social-btn:hover { filter: brightness(1.05); transform: translateY(-2px); }

        @media (max-width: 900px) {
            .top-nav-inner { flex-wrap: wrap; }
            .nav-links { order: 3; width: 100%; }
            .nav-links a { flex: 1; text-align: center; }
        }
    </style>

    @stack('styles')
</head>
<body>
    @php
        $displayName = session('etudiant_nom') ?? session('etudiant_log') ?? 'RSI';
        $chunks = preg_split('/\s+/', trim($displayName));
        $initials = strtoupper(substr($chunks[0] ?? 'R', 0, 1) . substr($chunks[1] ?? 'S', 0, 1));
    @endphp

    <header class="top-nav">
        <div class="container top-nav-inner">
            <div style="display:flex;align-items:center;gap:8px;">
                <div class="nav-logo"><img src="{{ asset('images/fst1.png') }}" alt="UH1"></div>
                <div class="nav-logo"><img src="{{ asset('images/fst.png') }}" alt="FST"></div>
            </div>

            <a href="{{ session('etudiant_id') ? route('dashboard') : route('login') }}" class="brand">
                <span class="brand-pill">UH1</span>
                <span>Master RSI</span>
            </a>

            <nav class="nav-links">
                <a href="{{ session('etudiant_id') ? route('dashboard') : route('login') }}" class="{{ request()->routeIs('dashboard') ? 'is-active' : '' }}">Accueil</a>
                @if(session('etudiant_id'))
                    <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'is-active' : '' }}">About me</a>
                    <a href="{{ route('projets.index') }}" class="{{ request()->routeIs('projets.*') ? 'is-active' : '' }}">Mes projets</a>
                @endif
            </nav>

            <div class="right-tools">
                <button type="button" class="theme-toggle hover-lift" id="theme-toggle">Mode clair</button>
                @if(session('etudiant_id'))
                    <span class="avatar">{{ $initials }}</span>
                    <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                        @csrf
                        <button class="btn-logout hover-lift" type="submit">Deconnexion</button>
                    </form>
                @endif
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            @if(session('success'))
                <div class="flash success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="flash error">{{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="footer-full">
        <div class="footer-cols">
            <div class="footer-col">
                <h4>À propos</h4>
                <div class="footer-logos">
                    <div class="footer-logo"><img src="{{ asset('images/fst1.png') }}" alt="UH1"></div>
                    <div class="footer-logo"><img src="{{ asset('images/fst.png') }}" alt="FST"></div>
                </div>
                <p style="font-size:0.85rem;line-height:1.6;margin:0;">Faculté des Sciences et Techniques de Settat · Université Hassan 1er</p>
            </div>

            <div class="footer-col">
                <h4>Contact</h4>
                <ul>
                    <li><i class="fa fa-phone" style="margin-right:6px;"></i> +212 5234-00736</li>
                    <li><i class="fa fa-envelope" style="margin-right:6px;"></i> <a href="mailto:contact@fsts.ac.ma">contact@fsts.ac.ma</a></li>
                    <li><i class="fa fa-map-marker" style="margin-right:6px;"></i> Settat, Maroc</li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Liens rapides</h4>
                <ul>
                    <li><a href="https://www.fsts.ac.ma" target="_blank">Site officiel FST</a></li>
                    <li><a href="https://www.uh1.ac.ma" target="_blank">Université Hassan 1er</a></li>
                    <li><a href="https://www.fsts.ac.ma" target="_blank">Master RSI</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Nous suivre</h4>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        <a class="social-btn social-fb" href="https://www.facebook.com/fstsettatofficiel" target="_blank" title="Facebook"><span>FB</span></a>
                        <a class="social-btn social-ig" href="https://www.instagram.com/fstsettat" target="_blank" title="Instagram"><span>IG</span></a>
                        <a class="social-btn social-in" href="https://www.linkedin.com/school/fst-settat" target="_blank" title="LinkedIn"><span>IN</span></a>
                        <a class="social-btn social-web" href="https://www.fsts.ac.ma" target="_blank" title="Site web"><span>WEB</span></a>
                </div>
            </div>
        </div>

        <div style="border-top:1px solid var(--border);text-align:center;padding:12px;color:var(--text-muted);font-size:0.8rem;">
            © {{ date('Y') }} FST Settat · Master RSI · Langage du Web
        </div>
    </footer>

    @stack('scripts')
    <script>
        (function () {
            const key = 'master_rsi_theme';
            const root = document.documentElement;
            const btn = document.getElementById('theme-toggle');

            const savedTheme = localStorage.getItem(key) || 'dark';
            root.setAttribute('data-theme', savedTheme);

            function isLight() {
                return root.getAttribute('data-theme') === 'light';
            }

            function refreshLabel() {
                if (!btn) return;
                btn.textContent = isLight() ? 'Mode sombre' : 'Mode clair';
            }

            refreshLabel();

            if (btn) {
                btn.addEventListener('click', function () {
                    if (isLight()) {
                        root.removeAttribute('data-theme');
                        localStorage.setItem(key, 'dark');
                    } else {
                        root.setAttribute('data-theme', 'light');
                        localStorage.setItem(key, 'light');
                    }
                    refreshLabel();
                });
            }

            const animatedElements = document.querySelectorAll(
                '[data-anim], .flash, .auth-left, .auth-right, .login-card, .dash-topbar, .kpi, .project-list, .project-card, .card, .card-rsi, table, .panel, .section, article, section'
            );

            animatedElements.forEach((el, index) => {
                el.setAttribute('data-anim', '');
                el.style.transitionDelay = `${Math.min(index * 35, 280)}ms`;
            });

            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('in');
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.08 });

                animatedElements.forEach((el) => observer.observe(el));
            } else {
                animatedElements.forEach((el) => el.classList.add('in'));
            }
        })();
    </script>
</body>
</html>

