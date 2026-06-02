@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    .dash-wrap {
        display: grid;
        gap: 16px;
    }

    .dash-topbar {
        border: 1px solid var(--border);
        border-radius: 14px;
        background: var(--bg-surface);
        padding: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        box-shadow: var(--shadow-soft);
    }

    .dash-topbar h1 {
        margin: 0;
        color: var(--text-main);
        font-size: 1.32rem;
    }

    .dash-topbar p {
        margin: 4px 0 0;
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    .dash-actions {
        display: flex;
        gap: 8px;
    }

    .dash-actions a {
        text-decoration: none;
        border: 1px solid var(--border);
        border-radius: 10px;
        color: var(--text-main);
        background: var(--bg-soft);
        padding: 8px 12px;
        font-size: 0.86rem;
    }

    .dash-actions a:hover { border-color: var(--accent); }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }

    .kpi {
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--bg-surface);
        padding: 14px;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    }

    .kpi:hover {
        transform: translateY(-3px);
        border-color: color-mix(in srgb, var(--accent) 45%, var(--border) 55%);
    }

    .kpi-label {
        color: var(--text-muted);
        font-size: 0.78rem;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .kpi-value {
        color: var(--text-main);
        font-size: 1.35rem;
        font-weight: 700;
    }

    .dash-body {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 14px;
    }

    .project-list {
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--bg-surface);
        padding: 12px;
    }

    .project-list h3 {
        margin: 2px 0 10px;
        font-size: 0.95rem;
        color: var(--text-main);
    }

    .plist-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text-main);
        text-decoration: none;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 8px 10px;
        margin-bottom: 8px;
        background: var(--bg-soft);
        font-size: 0.82rem;
    }

    .plist-item:hover { border-color: var(--accent); }

    .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .tag {
        margin-left: auto;
        font-size: 0.68rem;
        border-radius: 999px;
        padding: 2px 8px;
        color: #1b0d10;
        font-weight: 700;
    }

    .cards-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    .project-card {
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--bg-surface);
        padding: 14px;
        display: flex;
        flex-direction: column;
        min-height: 170px;
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.1);
    }

    .project-card:hover {
        transform: translateY(-4px);
        border-color: color-mix(in srgb, var(--accent) 40%, var(--border) 60%);
    }

    .project-num {
        font-size: 0.74rem;
        color: var(--text-muted);
        margin-bottom: 8px;
    }

    .project-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        margin-bottom: 10px;
        font-weight: 700;
    }

    .project-title {
        color: var(--text-main);
        font-size: 0.92rem;
        margin: 0 0 8px;
    }

    .project-desc {
        color: var(--text-muted);
        font-size: 0.8rem;
        line-height: 1.55;
        margin: 0 0 12px;
        flex: 1;
    }

    .project-link {
        text-decoration: none;
        color: var(--accent);
        font-size: 0.8rem;
        font-weight: 600;
    }

    .project-link:hover {
        filter: brightness(1.08);
    }

    .accent-js { background: #fbbf24; }
    .accent-php { background: #a78bfa; }
    .accent-mysql { background: #34d399; }
    .accent-quiz { background: #f472b6; }
    .accent-chart { background: #fb923c; }
    .accent-maps { background: #22d3ee; }

    @media (max-width: 1100px) {
        .cards-grid { grid-template-columns: repeat(2, 1fr); }
        .kpi-grid { grid-template-columns: repeat(2, 1fr); }
        .dash-body { grid-template-columns: 1fr; }
    }

    @media (max-width: 640px) {
        .cards-grid { grid-template-columns: 1fr; }
        .kpi-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
@php
    $isAdmin = session('is_admin', false) === true;
    $note1 = (float)($etudiant->note1 ?? 0);
    $note2 = (float)($etudiant->note2 ?? 0);
    $moyenne = (float)($etudiant->moyenne ?? 0);

    $projects = [
        [
            'num' => '01',
            'title' => 'Matrices JS',
            'desc' => 'Calcul de somme et produit matriciel cote client en JavaScript.',
            'tag' => 'JS',
            'color' => 'accent-js',
            'route' => route('projets.matrices'),
        ],
        [
            'num' => '02',
            'title' => 'Formulaires PHP',
            'desc' => 'Saisie et stockage de donnees etudiants dans fichier texte.',
            'tag' => 'PHP',
            'color' => 'accent-php',
            'route' => route('projets.fichiers'),
        ],
        [
            'num' => '03',
            'title' => 'Images DB MySQL',
            'desc' => 'Upload et affichage d images stockees en base de donnees.',
            'tag' => 'MySQL',
            'color' => 'accent-mysql',
            'route' => route('projets.images'),
        ],
        [
            'num' => '04',
            'title' => 'Quiz JS & PHP',
            'desc' => 'Quiz interactif avec calcul de score et sauvegarde.',
            'tag' => 'Quiz',
            'color' => 'accent-quiz',
            'route' => route('projets.quiz'),
        ],
    ];

    if ($isAdmin) {
        $projects[] = [
            'num' => '05',
            'title' => 'Statistiques Chart.js',
            'desc' => 'Visualisation des moyennes via graphiques dynamiques.',
            'tag' => 'Chart',
            'color' => 'accent-chart',
            'route' => route('projets.stats'),
        ];

        $projects[] = [
            'num' => '06',
            'title' => 'Geolocalisation Maps',
            'desc' => 'Affichage des positions GPS des etudiants sur carte.',
            'tag' => 'Maps',
            'color' => 'accent-maps',
            'route' => route('projets.geoloc'),
        ];
    }
@endphp

<div class="dash-wrap">
    <section class="dash-topbar">
        <div>
            <h1>Bonjour {{ $etudiant->nom ?? 'Etudiant' }}</h1>
            <p>Bienvenue dans votre espace de suivi des projets web.</p>
        </div>
        <div class="dash-actions">
            <a href="{{ route('about') }}">About me</a>
            <a href="{{ route('projets.index') }}">Mes projets</a>
        </div>
    </section>

    <section class="kpi-grid">
        <article class="kpi">
            <div class="kpi-label">Projets</div>
            <div class="kpi-value">{{ $isAdmin ? '6' : '4' }}</div>
        </article>
        <article class="kpi">
            <div class="kpi-label">Note 1</div>
            <div class="kpi-value">{{ number_format($note1, 2) }}</div>
        </article>
        <article class="kpi">
            <div class="kpi-label">Note 2</div>
            <div class="kpi-value">{{ number_format($note2, 2) }}</div>
        </article>
        <article class="kpi">
            <div class="kpi-label">Moyenne</div>
            <div class="kpi-value">{{ number_format($moyenne, 2) }}</div>
        </article>
    </section>

    <section class="dash-body">
        <aside class="project-list">
            <h3>Liste des projets</h3>
            @foreach($projects as $project)
                <a href="{{ $project['route'] }}" class="plist-item">
                    <span class="dot {{ $project['color'] }}"></span>
                    <span>{{ $project['num'] }}. {{ $project['title'] }}</span>
                    <span class="tag {{ $project['color'] }}">{{ $project['tag'] }}</span>
                </a>
            @endforeach
        </aside>

        <div class="cards-grid">
            @foreach($projects as $project)
                <article class="project-card">
                    <div class="project-num">Projet {{ $project['num'] }}</div>
                    <div class="project-icon {{ $project['color'] }}">{{ $project['num'] }}</div>
                    <h3 class="project-title">{{ $project['title'] }}</h3>
                    <p class="project-desc">{{ $project['desc'] }}</p>
                    <a href="{{ $project['route'] }}" class="project-link">Ouvrir →</a>
                </article>
            @endforeach
        </div>
    </section>
</div>
@endsection

