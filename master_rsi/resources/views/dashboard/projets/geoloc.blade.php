@extends('layouts.app')
@section('title', 'Projet 6 – Géolocalisation')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
.proj-badge { display:inline-flex;align-items:center;gap:7px;background:color-mix(in srgb, var(--accent) 14%, var(--bg-surface) 86%);border:1px solid var(--border);color:var(--accent);font-size:.65rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;padding:4px 13px;border-radius:20px;margin-bottom:14px; }
.geo-layout { display:grid; grid-template-columns:1fr 300px; gap:20px; align-items:start; }
.map-card { background:var(--bg-surface); border-radius:18px; border:1px solid var(--border); overflow:hidden; box-shadow:var(--shadow-soft); }
.map-card-header { background:linear-gradient(135deg,var(--accent),var(--accent-3)); padding:16px 22px; display:flex; align-items:center; gap:10px; }
.map-card-header h3 { font-size:.95rem; font-weight:800; color:#fff; margin:0; flex:1; }
.map-count { background:rgba(255,255,255,.16); color:#fff; font-size:.7rem; font-weight:700; padding:3px 10px; border-radius:12px; }
#map { height:460px; width:100%; display:block; z-index:0; }
.map-placeholder { height:460px; background:linear-gradient(135deg,color-mix(in srgb, var(--accent) 10%, var(--bg-main) 90%),color-mix(in srgb, var(--accent-3) 10%, var(--bg-main) 90%)); display:flex; align-items:center; justify-content:center; flex-direction:column; gap:12px; color:var(--accent); }
.map-placeholder i { font-size:3rem; opacity:.4; }
.map-placeholder p { font-size:.82rem; color:var(--text-muted); }
.geo-side { display:flex; flex-direction:column; gap:16px; }
.geo-info-card { background:var(--bg-surface); border-radius:16px; border:1px solid var(--border); overflow:hidden; box-shadow:var(--shadow-soft); }
.geo-info-header { padding:14px 18px; background:linear-gradient(135deg,color-mix(in srgb, var(--accent) 12%, var(--bg-main) 88%),color-mix(in srgb, var(--accent-3) 12%, var(--bg-main) 88%)); border-bottom:1px solid var(--border); display:flex; align-items:center; gap:8px; }
.geo-info-header h4 { font-size:.85rem; font-weight:800; color:var(--text-main); margin:0; }
.geo-info-header i { color:var(--accent); }
.student-list { list-style:none; margin:0; padding:0; }
.student-item { display:flex; align-items:center; gap:12px; padding:12px 16px; border-bottom:1px solid var(--border); cursor:pointer; transition:background .15s; }
.student-item:last-child { border-bottom:none; }
.student-item:hover { background:color-mix(in srgb, var(--accent) 6%, var(--bg-main) 94%); }
.student-dot { width:10px; height:10px; border-radius:50%; background:var(--accent); flex-shrink:0; box-shadow:0 0 0 3px rgba(6,182,212,.2); }
.student-name { font-size:.8rem; font-weight:600; color:var(--text-main); flex:1; }
.student-coords { font-size:.65rem; color:var(--text-muted); font-family:'Courier New',monospace; }
.geo-my-card { background:linear-gradient(135deg,var(--accent),var(--accent-3)); border-radius:16px; padding:20px; color:#fff; }
.geo-my-card h4 { font-size:.9rem; margin:0 0 10px; color:#fff; display:flex; align-items:center; gap:7px; font-weight:800; }
.geo-my-card h4 i { color:#fff2c2; }
.geo-my-card p { font-size:.75rem; color:rgba(255,255,255,.8); line-height:1.6; margin-bottom:14px; }
.btn-locate { width:100%; padding:10px; border:none; border-radius:10px; cursor:pointer; background:linear-gradient(135deg,#06b6d4,#0e7490); color:#fff; font-size:.78rem; font-weight:700; font-family:'DM Sans',sans-serif; display:flex; align-items:center; justify-content:center; gap:7px; transition:filter .2s,transform .15s; }
.btn-locate:hover { filter:brightness(1.1); transform:translateY(-1px); }
.geo-result { margin-top:10px; font-size:.72rem; color:rgba(255,255,255,.7); min-height:20px; }
@media(max-width:860px){ .geo-layout{grid-template-columns:1fr;} }
</style>
@endpush

@section('content')
<div class="proj-badge" data-anim><i class="fa fa-map-marker-alt"></i> Projet 06 — Géolocalisation</div>
<div class="page-heading" data-anim><i class="fa fa-map-marker-alt"></i> Géolocalisation des Étudiants</div>

<div class="geo-layout" data-anim>

    {{-- ═══ CARTE ═══ --}}
    <div class="map-card">
        <div class="map-card-header">
            <i class="fa fa-map" style="color:rgba(255,255,255,.8);"></i>
            <h3>Carte OpenStreetMap – Positions des étudiants</h3>
            <span class="map-count">
                <i class="fa fa-map-marker-alt"></i> {{ count($etudiants) }} position(s)
            </span>
        </div>
        @if(count($etudiants) > 0)
            <div id="map"></div>
        @else
            <div class="map-placeholder">
                <i class="fa fa-map-marked-alt"></i>
                <p>Aucune position GPS enregistrée en base de données.</p>
                <p style="font-size:.72rem;">Ajoutez une adresse lors de l'inscription d'un étudiant.</p>
            </div>
        @endif
    </div>

    {{-- ═══ SIDEBAR ═══ --}}
    <div class="geo-side">

        {{-- Liste étudiants --}}
        <div class="geo-info-card">
            <div class="geo-info-header">
                <i class="fa fa-users"></i>
                <h4>Étudiants géolocalisés</h4>
            </div>
            @if(count($etudiants) > 0)
            <ul class="student-list">
                @foreach($etudiants as $e)
                <li class="student-item"
                    onclick="focusStudent({{ $e->latitude }}, {{ $e->longitude }}, '{{ addslashes($e->nom) }}')">
                    <div class="student-dot"></div>
                    <div style="flex:1;">
                        <div class="student-name">{{ $e->nom }}</div>
                        <div class="student-coords">
                            {{ number_format($e->latitude,4) }}, {{ number_format($e->longitude,4) }}
                        </div>
                    </div>
                    <i class="fa fa-crosshairs" style="color:#c4cdd8;font-size:.75rem;"></i>
                </li>
                @endforeach
            </ul>
            @else
            <div style="padding:24px 16px;text-align:center;color:#8a97b0;font-size:.78rem;">
                <i class="fa fa-map-marker-alt" style="font-size:1.5rem;color:#e0e6f0;margin-bottom:8px;display:block;"></i>
                Aucun étudiant géolocalisé
            </div>
            @endif
        </div>

        {{-- Ma position --}}
        <div class="geo-my-card">
            <h4><i class="fa fa-crosshairs"></i> Ma position</h4>
            <p>Cliquez pour détecter automatiquement votre position GPS et l'afficher sur la carte.</p>
            <button class="btn-locate" onclick="getMyLocation()">
                <i class="fa fa-map-marker-alt"></i> Détecter ma position
            </button>
            <div class="geo-result" id="geoResult"></div>
        </div>

        {{-- À propos --}}
        <div class="geo-info-card">
            <div class="geo-info-header">
                <i class="fa fa-info-circle"></i>
                <h4>À propos de ce projet</h4>
            </div>
            <div style="padding:14px 16px;font-size:.76rem;color:#5a6880;line-height:1.65;">
                <p>Ce projet utilise <strong>Leaflet.js</strong> + OpenStreetMap pour afficher les positions géographiques des étudiants enregistrées dans la base de données (champs
                <code style="background:#f4f6fb;padding:1px 5px;border-radius:4px;">latitude</code> et
                <code style="background:#f4f6fb;padding:1px 5px;border-radius:4px;">longitude</code>).</p>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

@if(count($etudiants) > 0)
<script>
@php
    $geoData = $etudiants->map(function($e) {
        return [
            'nom' => $e->nom,
            'lat' => (float) $e->latitude,
            'lng' => (float) $e->longitude,
        ];
    })->values()->toArray();
@endphp
const etudiants = @json($geoData);

// ── Initialiser la carte ──────────────────────────────────────────────────────
const mapEl = document.getElementById('map');
if (!mapEl) {
    console.warn('Map container not found');
} else {
    const map = L.map(mapEl).setView(
        [etudiants[0].lat, etudiants[0].lng],
        7
    );

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(map);

// ── Icône personnalisée (rouge comme Google Maps) ─────────────────────────────
const redIcon = L.divIcon({
    className: '',
    html: `<div style="
        width:24px;height:24px;border-radius:50% 50% 50% 0;
        background:#e8342a;border:2px solid #fff;
        transform:rotate(-45deg);
        box-shadow:0 2px 6px rgba(0,0,0,.35);
    "></div>`,
    iconSize: [24, 24],
    iconAnchor: [12, 24],
    popupAnchor: [0, -28],
});

// ── Ajouter les marqueurs ─────────────────────────────────────────────────────
    const leafletMarkers = {};

    etudiants.forEach(e => {
        const marker = L.marker([e.lat, e.lng], { icon: redIcon })
            .addTo(map)
            .bindPopup(`
                <div style="font-family:'DM Sans',sans-serif;min-width:140px;">
                    <strong style="color:#0e7490;font-size:.85rem;">${e.nom}</strong><br>
                    <span style="font-size:.72rem;color:#8a97b0;font-family:'Courier New',monospace;">
                        ${e.lat.toFixed(4)}, ${e.lng.toFixed(4)}
                    </span>
                </div>
            `);
        leafletMarkers[e.nom] = marker;
    });

// ── Focus sur un étudiant depuis la sidebar ───────────────────────────────────
    function focusStudent(lat, lng, nom) {
        map.flyTo([lat, lng], 12, { animate: true, duration: 1 });
        if (leafletMarkers[nom]) {
            leafletMarkers[nom].openPopup();
        }
    }

// ── Détecter ma position ──────────────────────────────────────────────────────
    let myMarker = null;
    const myIcon = L.divIcon({
        className: '',
        html: `<div style="
            width:20px;height:20px;border-radius:50%;
            background:#e8a020;border:3px solid #0f2347;
            box-shadow:0 2px 8px rgba(0,0,0,.4);
        "></div>`,
        iconSize: [20, 20],
        iconAnchor: [10, 10],
        popupAnchor: [0, -14],
    });

    function getMyLocation() {
        const res = document.getElementById('geoResult');
        res.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Détection en cours...';

    if (!navigator.geolocation) {
        res.textContent = '❌ Géolocalisation non supportée.';
        return;
    }

        navigator.geolocation.getCurrentPosition(
            pos => {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;

                res.innerHTML = `<i class="fa fa-check-circle" style="color:#4ade80;"></i>
                    Lat: ${lat.toFixed(4)} – Lng: ${lng.toFixed(4)}`;

                if (myMarker) map.removeLayer(myMarker);

                myMarker = L.marker([lat, lng], { icon: myIcon })
                    .addTo(map)
                    .bindPopup('<strong style="color:#e8a020;">Ma position</strong>')
                    .openPopup();

                map.flyTo([lat, lng], 14, { animate: true, duration: 1.2 });
            },
            () => {
                res.textContent = '❌ Impossible de détecter la position.';
            }
        );
    }
}
</script>

@else
<script>
// Pas d'étudiants → bouton Ma position sans carte
function focusStudent() {}

let myMap = null;
function getMyLocation() {
    const res = document.getElementById('geoResult');
    res.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Détection en cours...';
    if (!navigator.geolocation) { res.textContent = '❌ Non supporté.'; return; }
    navigator.geolocation.getCurrentPosition(
        pos => {
            res.innerHTML = `<i class="fa fa-check-circle" style="color:#4ade80;"></i>
                Lat: ${pos.coords.latitude.toFixed(4)} – Lng: ${pos.coords.longitude.toFixed(4)}`;
        },
        () => { res.textContent = '❌ Impossible de détecter la position.'; }
    );
}
</script>
@endif
@endpush
