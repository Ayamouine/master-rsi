@extends('layouts.guest')

@section('title', 'Connexion')

@push('styles')
<style>
    .main-content {
        width: 100%;
    }

    .auth-split {
        display: grid;
        grid-template-columns: 1fr 1fr;
        border: 1px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
        background: linear-gradient(140deg, color-mix(in srgb, var(--bg-surface) 90%, var(--bg-main) 10%), var(--bg-surface));
        min-height: 620px;
        box-shadow: var(--shadow-soft);
    }

    .auth-left {
        padding: 36px;
        border-right: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-card {
        width: min(420px, 100%);
        background: color-mix(in srgb, var(--bg-surface) 88%, var(--bg-main) 12%);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.14);
    }

    .login-title {
        margin: 0 0 8px;
        font-size: 1.35rem;
        color: var(--text-main);
    }

    .login-sub {
        margin: 0 0 20px;
        color: var(--text-muted);
        font-size: 0.92rem;
    }

    .field { margin-bottom: 14px; }

    .field label {
        display: block;
        margin-bottom: 6px;
        color: var(--text-main);
        font-size: 0.88rem;
    }

    .input-wrap {
        border: 1px solid var(--border);
        border-radius: 10px;
        background: color-mix(in srgb, var(--bg-main) 85%, var(--bg-surface) 15%);
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 0 10px;
    }

    .input-wrap input {
        width: 100%;
        border: 0;
        outline: 0;
        background: transparent;
        color: var(--text-main);
        height: 42px;
        font-size: 0.92rem;
    }

    .input-wrap input::placeholder { color: var(--text-muted); }

    .toggle-pass {
        border: 0;
        background: transparent;
        color: var(--accent);
        cursor: pointer;
        font-size: 0.82rem;
    }

    .login-error {
        margin-bottom: 14px;
        border: 1px solid color-mix(in srgb, var(--primary-btn) 75%, #000 25%);
        background: color-mix(in srgb, var(--primary-btn) 24%, var(--bg-main) 76%);
        color: var(--text-main);
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 0.88rem;
    }

    .btn-login {
        width: 100%;
        border: 1px solid color-mix(in srgb, var(--primary-btn) 78%, #000 22%);
        border-radius: 10px;
        background: var(--primary-btn);
        color: #fff2f5;
        height: 44px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-login:hover { filter: brightness(1.08); }

    .auth-right {
        padding: 36px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: radial-gradient(circle at 70% 20%, rgba(251,113,133,0.14), transparent 38%), color-mix(in srgb, var(--bg-main) 85%, var(--bg-surface) 15%);
        position: relative;
        overflow: hidden;
    }

    .auth-right::before {
        content: '';
        position: absolute;
        right: -60px;
        top: -40px;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(251,113,133,0.2), transparent 70%);
        pointer-events: none;
    }

    .hero-title {
        margin: 0 0 14px;
        font-size: clamp(1.7rem, 3vw, 2.5rem);
        line-height: 1.15;
        color: var(--text-main);
        max-width: 520px;
    }

    .hero-text {
        margin: 0 0 22px;
        color: var(--text-muted);
        max-width: 520px;
        font-size: 0.95rem;
        line-height: 1.7;
    }

    .tech-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        max-width: 520px;
    }

    .tech-badge {
        padding: 7px 11px;
        border-radius: 999px;
        border: 1px solid var(--border);
        font-size: 0.78rem;
        font-weight: 600;
        color: #1c0c10;
    }

    .b-js { background: #fbbf24; }
    .b-php { background: #a78bfa; }
    .b-mysql { background: #34d399; }
    .b-maps { background: #22d3ee; }
    .b-chart { background: #fb923c; }
    .b-quiz { background: #f472b6; }

    @media (max-width: 980px) {
        .auth-split { grid-template-columns: 1fr; }
        .auth-left { border-right: 0; border-bottom: 1px solid var(--border); }
    }
</style>
@endpush

@section('content')
<div class="auth-split">
    <section class="auth-left">
        <div class="login-card">
            <h1 class="login-title">Connexion</h1>
            <p class="login-sub">Accedez a votre espace Master RSI.</p>

            @if ($errors->any())
                <div class="login-error">{{ $errors->first() }}</div>
            @endif

            @if (session('error'))
                <div class="login-error">{{ session('error') }}</div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <div class="field">
                    <label for="login">Identifiant</label>
                    <div class="input-wrap">
                        <input
                            type="text"
                            id="login"
                            name="login"
                            placeholder="Votre identifiant"
                            value="{{ old('login') }}"
                            required
                            autofocus
                        >
                    </div>
                </div>

                <div class="field">
                    <label for="password">Mot de passe</label>
                    <div class="input-wrap">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Votre mot de passe"
                            required
                        >
                        <button type="button" class="toggle-pass" id="togglePass" onclick="togglePassword()">Afficher</button>
                    </div>
                </div>

                <button type="submit" class="btn-login">Se connecter</button>
            </form>
        </div>
    </section>

    <section class="auth-right">
        <h2 class="hero-title">Bienvenue sur votre espace RSI</h2>
        <p class="hero-text">
            Un environnement unique pour explorer les projets du module Langage du Web avec une experience claire, moderne et orientee pratique.
        </p>

        <div class="tech-badges">
            <span class="tech-badge b-js">JavaScript</span>
            <span class="tech-badge b-php">PHP</span>
            <span class="tech-badge b-mysql">MySQL</span>
            <span class="tech-badge b-maps">Maps</span>
            <span class="tech-badge b-chart">Chart.js</span>
            <span class="tech-badge b-quiz">Quiz</span>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const toggleBtn = document.getElementById('togglePass');
        const passInput = document.getElementById('password');

        window.togglePassword = function () {
            if (!toggleBtn || !passInput) return;
            const isHidden = passInput.type === 'password';
            passInput.type = isHidden ? 'text' : 'password';
            toggleBtn.textContent = isHidden ? 'Masquer' : 'Afficher';
        };
    })();
</script>
@endpush

