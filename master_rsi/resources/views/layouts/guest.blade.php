<!DOCTYPE html>
<html lang="fr" data-theme="dark">
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
    @vite(['resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')

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
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: radial-gradient(circle at top right, var(--glow), transparent 32%), var(--bg-main);
            color: var(--text-main);
            font-family: var(--font-sans);
        }

        .nav-logo {
            height: 38px;
            background: var(--bg-main);
            border-radius: 8px;
            padding: 3px 6px;
            display: flex;
            align-items: center;
        }

        .nav-logo img {
            height: 100%;
            width: auto;
            object-fit: contain;
        }

        .theme-toggle {
            background: rgba(6, 182, 212, 0.1);
            border: 1px solid var(--border);
            color: var(--accent);
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .theme-toggle:hover {
            border-color: var(--accent);
            background: rgba(6, 182, 212, 0.2);
        }

        .hover-lift {
            transition: transform 0.2s ease;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
        }

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

        .footer-col li a:hover {
            color: var(--accent);
        }

        .footer-logos {
            display: flex;
            gap: 12px;
            margin-bottom: 12px;
        }

        .footer-logo {
            height: 40px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 6px;
            padding: 4px 8px;
        }

        .footer-logo img {
            height: 100%;
            width: auto;
        }

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

        .social-btn:hover {
            filter: brightness(1.05);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div style="min-height:100vh;display:flex;flex-direction:column;font-family:var(--font-sans);">
        <header style="background:var(--bg-surface);border-bottom:1px solid var(--border);padding:12px 16px;">
            <div class="container" style="display:flex;align-items:center;justify-content:space-between;">
                <div style="display:flex;align-items:center;gap:8px;">
                    <div class="nav-logo"><img src="{{ asset('images/fst1.png') }}" alt="UH1"></div>
                    <div class="nav-logo"><img src="{{ asset('images/fst.png') }}" alt="FST"></div>
                    <a href="{{ route('login') }}" style="display:flex;align-items:center;gap:8px;text-decoration:none;margin-left:16px;">
                        <span class="brand-pill">UH1</span>
                        <span style="color:var(--text-main);font-weight:600;">Master RSI</span>
                    </a>
                </div>
                <button type="button" class="theme-toggle hover-lift" id="theme-toggle" onclick="toggleTheme()">Mode clair</button>
            </div>
        </header>

        <main style="flex:1;padding:20px 16px;display:flex;align-items:center;justify-content:center;">
            @yield('content')
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
    </div>

    <script>
        (function () {
            const html = document.documentElement;
            const toggleBtn = document.getElementById('theme-toggle');
            const storedTheme = localStorage.getItem('master_rsi_theme') || 'dark';

            html.setAttribute('data-theme', storedTheme);
            if (toggleBtn) {
                toggleBtn.textContent = storedTheme === 'dark' ? 'Mode clair' : 'Mode sombre';
            }

            window.toggleTheme = function () {
                const currentTheme = html.getAttribute('data-theme') || 'dark';
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                html.setAttribute('data-theme', newTheme);
                localStorage.setItem('master_rsi_theme', newTheme);
                if (toggleBtn) {
                    toggleBtn.textContent = newTheme === 'dark' ? 'Mode clair' : 'Mode sombre';
                }
            };
        })();
    </script>
    @stack('scripts')
</body>
</html>
