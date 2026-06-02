@extends('layouts.app')
@section('title', 'Mes Projets')

@push('styles')
<style>
.proj-hero { background:linear-gradient(135deg,var(--accent) 0%,var(--accent-3) 50%,var(--accent-2) 100%); border-radius:16px; padding:32px 36px; color:#fff; margin-bottom:28px; position:relative; overflow:hidden; box-shadow:var(--shadow-soft); }
.proj-hero::before { content:''; position:absolute; inset:0; background:radial-gradient(circle at top right, rgba(255,255,255,.22), transparent 28%), repeating-linear-gradient(-45deg,transparent,transparent 28px,rgba(255,255,255,.08) 28px,rgba(255,255,255,.08) 29px); pointer-events:none; }
.proj-hero h2 { font-size:1.8rem; font-weight:800; margin-bottom:6px; }
.proj-hero p { font-size:.9rem; color:rgba(255,255,255,.84); max-width:520px; line-height:1.7; }

.projects-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:20px; }

.pcard {
    background:var(--bg-surface); border-radius:18px; border:1px solid var(--border);
    overflow:hidden; text-decoration:none; color:inherit;
    display:flex; flex-direction:column;
    transition:box-shadow .25s, transform .25s, border-color .25s;
    opacity:0; transform:translateY(20px);
    animation: fadeUp .5s ease forwards;
}
.pcard:hover { box-shadow:var(--shadow-soft); transform:translateY(-4px); text-decoration:none; color:inherit; border-color:var(--accent); }
@keyframes fadeUp { to { opacity:1; transform:translateY(0); } }

.pcard-header {
    padding:28px 24px 22px;
    position:relative;
    background:var(--pc-bg, #eff6ff);
}
.pcard-num {
    position:absolute; top:16px; right:18px;
    font-size:3rem; font-weight:900; color:var(--pc-color);
    opacity:.08; line-height:1; font-family:var(--font-sans);
}
.pcard-icon-wrap {
    width:52px; height:52px; border-radius:14px;
    background:var(--pc-color); display:flex; align-items:center;
    justify-content:center; color:#fff; font-size:1.2rem;
    box-shadow:0 6px 18px rgba(0,0,0,.15); margin-bottom:14px;
}
.pcard-tag {
    display:inline-block; background:var(--pc-color);
    color:#fff; font-size:.6rem; font-weight:700;
    letter-spacing:1.5px; text-transform:uppercase;
    padding:3px 10px; border-radius:20px; margin-bottom:10px;
}
.pcard-title { font-family:var(--font-sans); font-size:1.05rem; font-weight:700; color:#1e2a40; line-height:1.3; }

.pcard-body { padding:18px 24px 22px; flex:1; display:flex; flex-direction:column; }
.pcard-desc { font-size:.8rem; color:var(--text-muted); line-height:1.65; margin-bottom:14px; flex:1; }
.pcard-techs { display:flex; flex-wrap:wrap; gap:5px; margin-bottom:16px; }
.pcard-tech { background:color-mix(in srgb, var(--bg-main) 70%, var(--accent) 10%); border:1px solid var(--border); color:var(--text-muted); font-size:.65rem; font-weight:600; padding:3px 9px; border-radius:10px; }
.pcard-btn {
    display:flex; align-items:center; justify-content:space-between;
    background:var(--pc-color); color:#fff; border-radius:10px;
    padding:10px 16px; font-size:.78rem; font-weight:600;
    transition:filter .2s, transform .15s;
}
.pcard-btn:hover { filter:brightness(1.1); }
.pcard-btn i { transition:transform .2s; }
.pcard:hover .pcard-btn i { transform:translateX(4px); }
</style>
@endpush

@section('content')
@php $isAdmin = session('is_admin', false) === true; @endphp
<div class="proj-hero">
    <div style="display:inline-flex;align-items:center;gap:7px;background:rgba(232,160,32,.15);border:1px solid rgba(232,160,32,.3);color:#f5c25a;font-size:.65rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;padding:4px 14px;border-radius:20px;margin-bottom:14px;">
        <i class="fa fa-folder-open"></i> Module – Langage du Web
    </div>
    <h2>Mes Projets</h2>
    <p>Explorez les projets du module. Certains outils avancés restent réservés à l'administrateur.</p>
</div>

<div class="projects-grid">
    @php
    $projects = [
        ['route'=>'projets.matrices','num'=>'01','color'=>'#3b82f6','bg'=>'#eff6ff','icon'=>'fa-table','tag'=>'JavaScript','title'=>'Manipulation de Matrices','desc'=>'Génération de deux matrices aléatoires avec saisie des dimensions. Calcul de la somme et du produit matriciel côté client en JavaScript pur.','techs'=>['JavaScript','Algorithmes','DOM']],
        ['route'=>'projets.fichiers','num'=>'02','color'=>'#10b981','bg'=>'#ecfdf5','icon'=>'fa-file-alt','tag'=>'PHP – Fichiers','title'=>'Formulaires & Fichiers PHP','desc'=>'Saisie des informations d\'un étudiant (CNE, nom, prénom, notes) et persistance dans un fichier texte. Consultation de la liste avec tableau.','techs'=>['PHP','file_put_contents','Laravel']],
        ['route'=>'projets.images','num'=>'03','color'=>'#8b5cf6','bg'=>'#f5f3ff','icon'=>'fa-images','tag'=>'MySQL – BDD','title'=>'Images en Base de Données','desc'=>'Upload d\'images stockées au format binaire (longblob) dans MySQL. Affichage dynamique de toutes les images insérées.','techs'=>['PHP','MySQL','BLOB','Laravel']],
        ['route'=>'projets.quiz','num'=>'04','color'=>'#f59e0b','bg'=>'#fffbeb','icon'=>'fa-question-circle','tag'=>'Quiz Interactif','title'=>'Quiz JavaScript & PHP','desc'=>'Deux quiz QCM sur JavaScript et PHP. Calcul automatique de la note, affichage en alerte et envoi vers la base de données.','techs'=>['JavaScript','PHP','MySQL','AJAX']],
        ['route'=>'projets.stats','num'=>'05','color'=>'#ef4444','bg'=>'#fef2f2','icon'=>'fa-chart-bar','tag'=>'ChartJS','title'=>'Statistiques avec Chart.js','desc'=>'Visualisation graphique des moyennes de tous les étudiants du Master RSI avec des graphiques en barres dynamiques et colorés.','techs'=>['Chart.js','PHP','MySQL','Canvas'],'adminOnly'=>true],
        ['route'=>'projets.geoloc','num'=>'06','color'=>'#06b6d4','bg'=>'#ecfeff','icon'=>'fa-map-marker-alt','tag'=>'Google Maps','title'=>'Géolocalisation','desc'=>'Affichage des positions GPS des étudiants sur une carte Google Maps interactive. Les coordonnées sont stockées dans la base de données.','techs'=>['Google Maps API','PHP','MySQL','GPS'],'adminOnly'=>true],
    ];
    @endphp

    @foreach($projects as $p)
        @continue(! $isAdmin && !empty($p['adminOnly']))
        <a href="{{ route($p['route']) }}" class="pcard" data-anim style="--pc-color:{{ $p['color'] }}; --pc-bg:{{ $p['bg'] }}; animation-delay:{{ $loop->index * .08 }}s;">
        <div class="pcard-header" style="background:{{ $p['bg'] }};">
            <div class="pcard-num">{{ $p['num'] }}</div>
            <div class="pcard-icon-wrap" style="background:{{ $p['color'] }};"><i class="fa {{ $p['icon'] }}"></i></div>
            <div class="pcard-tag" style="background:{{ $p['color'] }};">{{ $p['tag'] }}</div>
            <div class="pcard-title">{{ $p['title'] }}</div>
        </div>
        <div class="pcard-body">
            <div class="pcard-desc">{{ $p['desc'] }}</div>
            <div class="pcard-techs">
                @foreach($p['techs'] as $t)<span class="pcard-tech">{{ $t }}</span>@endforeach
            </div>
            <div class="pcard-btn" style="background:{{ $p['color'] }};">
                <span>Accéder au projet</span>
                <i class="fa fa-arrow-right"></i>
            </div>
        </div>
    </a>
    @endforeach
</div>
@endsection
