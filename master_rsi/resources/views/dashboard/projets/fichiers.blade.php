@extends('layouts.app')
@section('title', 'Projet 2 – Fichiers PHP')

@push('styles')
<style>
.proj-badge { display:inline-flex;align-items:center;gap:7px;background:color-mix(in srgb, var(--accent) 14%, var(--bg-surface) 86%);border:1px solid var(--border);color:var(--accent);font-size:.65rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;padding:4px 13px;border-radius:20px;margin-bottom:14px; }
.fichiers-wrap { display:grid; grid-template-columns:420px 1fr; gap:24px; align-items:start; }
.form-card { background:var(--bg-surface); border-radius:18px; border:1px solid var(--border); overflow:hidden; box-shadow:var(--shadow-soft); }
.form-card-header { background:linear-gradient(135deg,var(--accent),var(--accent-3)); padding:20px 24px; }
.form-card-header h3 { font-size:1rem; font-weight:800; color:#fff; margin:0 0 4px; }
.form-card-header p { font-size:.72rem; color:rgba(255,255,255,.72); margin:0; }
.form-section { padding:20px 24px; border-bottom:1px solid var(--border); }
.form-section-label { font-size:.65rem; font-weight:700; letter-spacing:2px; text-transform:uppercase; color:var(--text-muted); margin-bottom:14px; display:flex; align-items:center; gap:6px; }
.form-section-label i { color:var(--accent-2); }
.form-row { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
.f-group { margin-bottom:14px; }
.f-label { font-size:.73rem; font-weight:600; color:var(--text-muted); margin-bottom:5px; display:block; }
.f-input { width:100%; padding:10px 14px; border:1.5px solid var(--border); border-radius:10px; font-size:.83rem; color:var(--text-main); background:color-mix(in srgb, var(--bg-main) 72%, var(--bg-surface) 28%); transition:border-color .2s,box-shadow .2s; font-family:'DM Sans',sans-serif; box-sizing:border-box; }
.f-input:focus { outline:none; border-color:var(--accent); box-shadow:0 0 0 3px color-mix(in srgb, var(--accent) 18%, transparent 82%); background:var(--bg-surface); }
.f-input.note { border-left:3px solid var(--accent); }
.btn-submit { margin:20px 24px; width:calc(100% - 48px); padding:12px; border:none; border-radius:12px; background:linear-gradient(135deg,var(--accent),var(--accent-3)); color:#fff; font-size:.85rem; font-weight:700; font-family:'DM Sans',sans-serif; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:filter .2s,transform .15s; }
.btn-submit.edit-mode { background:linear-gradient(135deg,var(--accent-2),#d97706); }
.btn-submit:hover { filter:brightness(1.1); transform:translateY(-1px); }
.btn-cancel { margin:0 24px 20px; width:calc(100% - 48px); padding:10px; border:1.5px solid var(--border); border-radius:12px; background:var(--bg-main); color:var(--text-muted); font-size:.82rem; font-weight:600; font-family:'DM Sans',sans-serif; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; text-decoration:none; transition:border-color .2s, color .2s, background .2s; }
.btn-cancel:hover { border-color:var(--accent-2); color:var(--accent-2); background:color-mix(in srgb, var(--accent-2) 8%, var(--bg-main) 92%); }
.alert-success { margin:0 24px 16px; padding:10px 14px; border-radius:10px; background:color-mix(in srgb, #10b981 14%, var(--bg-main) 86%); border:1px solid #10b981; color:color-mix(in srgb, #047857 82%, var(--text-main) 18%); font-size:.78rem; font-weight:600; display:flex; align-items:center; gap:8px; }
.alert-error { margin:0 24px 16px; padding:10px 14px; border-radius:10px; background:color-mix(in srgb, #ef4444 14%, var(--bg-main) 86%); border:1px solid #ef4444; color:color-mix(in srgb, #7f1d1d 82%, var(--text-main) 18%); font-size:.78rem; font-weight:600; display:flex; align-items:center; gap:8px; }
.edit-banner { margin:16px 24px 0; padding:10px 14px; border-radius:10px; background:color-mix(in srgb, var(--accent-2) 12%, var(--bg-main) 88%); border:1px solid var(--accent-2); color:color-mix(in srgb, var(--accent-2) 70%, var(--text-main) 30%); font-size:.78rem; font-weight:600; display:flex; align-items:center; gap:8px; }
.sem-btn { flex:1; display:flex; align-items:center; gap:8px; padding:12px 14px; border:2px solid var(--border); border-radius:10px; cursor:pointer; transition:all .2s; background:color-mix(in srgb, var(--bg-main) 85%, var(--bg-surface) 15%); }
.sem-btn:hover { border-color:var(--accent); }
.sem-btn.selected-s1 { border-color:var(--accent); background:color-mix(in srgb, var(--accent) 10%, var(--bg-main) 90%); }
.sem-btn.selected-s2 { border-color:#10b981; background:color-mix(in srgb, #10b981 10%, var(--bg-main) 90%); }
/* Table */
.table-card { background:var(--bg-surface); border-radius:18px; border:1px solid var(--border); overflow:hidden; box-shadow:var(--shadow-soft); }
.table-card-header { background:linear-gradient(135deg,#10b981,#14b8a6); padding:16px 22px; display:flex; align-items:center; gap:10px; }
.table-card-header h3 { font-size:.95rem; font-weight:800; color:#fff; margin:0; flex:1; }
.count-badge { background:rgba(255,255,255,.16); color:#fff; font-size:.7rem; font-weight:700; padding:3px 10px; border-radius:12px; }
.table-wrap { overflow-x:auto; }
table.rsi-table { width:100%; border-collapse:collapse; }
.rsi-table thead th { background:color-mix(in srgb, var(--bg-main) 82%, var(--bg-surface) 18%); padding:10px 14px; font-size:.7rem; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:var(--text-muted); border-bottom:2px solid var(--border); white-space:nowrap; }
.rsi-table tbody tr { border-bottom:1px solid var(--border); transition:background .15s; }
.rsi-table tbody tr:hover { background:color-mix(in srgb, var(--accent) 6%, var(--bg-main) 94%); }
.rsi-table tbody tr.active-edit { background:color-mix(in srgb, var(--accent-2) 10%, var(--bg-main) 90%); }
.rsi-table tbody td { padding:11px 14px; font-size:.8rem; color:var(--text-main); }
.cne-cell { font-family:'Courier New',monospace; font-size:.75rem; color:var(--text-muted); }
.note-cell { text-align:center; font-weight:700; }
.note-cell.good { color:#10b981; }
.note-cell.bad { color:#ef4444; }
.btn-edit { display:inline-flex; align-items:center; gap:5px; padding:6px 12px; border-radius:8px; background:linear-gradient(135deg, color-mix(in srgb, var(--accent-2) 92%, #fff 8%), color-mix(in srgb, var(--accent-2) 76%, #000 24%)); border:1px solid color-mix(in srgb, var(--accent-2) 80%, var(--border) 20%); color:#fff; font-size:.72rem; font-weight:800; text-decoration:none; transition:transform .15s, filter .15s, box-shadow .15s; box-shadow:0 6px 14px rgba(0,0,0,.08); }
.btn-edit:hover { filter:brightness(1.05); transform:translateY(-1px); }
.btn-edit:visited { color:#fff; }

html[data-theme='light'] .btn-edit,
html[data-theme='light'] .btn-submit.edit-mode,
html[data-theme='light'] .btn-cancel,
html[data-theme='light'] .sem-btn,
html[data-theme='light'] .f-input,
html[data-theme='light'] .table-card,
html[data-theme='light'] .form-card {
    box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
}

html[data-theme='light'] .rsi-table thead th,
html[data-theme='light'] .rsi-table tbody td,
html[data-theme='light'] .f-label,
html[data-theme='light'] .form-section-label,
html[data-theme='light'] .cne-cell,
html[data-theme='light'] .btn-cancel,
html[data-theme='light'] .alert-success,
html[data-theme='light'] .alert-error,
html[data-theme='light'] .edit-banner {
    color: var(--text-main);
}

.admin-only-actions { display: none; }

@media (max-width: 900px){ .fichiers-wrap{grid-template-columns:1fr;} }
.empty-state { text-align:center; padding:40px 20px; color:var(--text-muted); font-size:.82rem; }
.empty-state i { font-size:2rem; color:var(--border); margin-bottom:12px; display:block; }
@media(max-width:900px){ .fichiers-wrap{grid-template-columns:1fr;} }
</style>
@endpush

@section('content')
<div class="proj-badge" data-anim><i class="fa fa-file-alt"></i> Projet 02 — PHP Fichiers</div>
<div class="page-heading" data-anim><i class="fa fa-file-alt"></i> Gestion de Formulaire avec les Fichiers</div>

<div class="fichiers-wrap" data-anim>

    {{-- ═══ FORMULAIRE ═══ --}}
    <div class="form-card">

        <div class="form-card-header"
             style="{{ isset($student) ? 'background:linear-gradient(135deg,#92400e,#d97706)' : '' }}">
            <h3>
                <i class="fa {{ isset($student) ? 'fa-edit' : 'fa-plus-circle' }}" style="color:#e8a020;"></i>
                {{ isset($student) ? 'Modifier l\'étudiant' : 'Ajouter un Étudiant' }}
            </h3>
            <p>
                @if(isset($student))
                    Modification de <strong style="color:#fde68a;">{{ $student['nom'] }} {{ $student['prenom'] }}</strong>
                @else
                    Le login = <strong>Nom</strong> et le mot de passe = <strong>CNE</strong>.
                @endif
            </p>
        </div>

        @if($isAdmin)

            @if(isset($student))
            <div class="edit-banner">
                <i class="fa fa-exclamation-triangle"></i>
                Mode modification — CNE : <strong>{{ $student['cne'] }}</strong>
                <span style="margin-left:12px;color:var(--text-main);font-weight:600;">Identifiant : <strong style="color:#fde68a;">{{ $student['nom'] }}</strong> • Mot de passe : <strong style="color:#fde68a;">{{ $student['cne'] }}</strong></span>
            </div>
            @endif

            @if(session('success'))
            <div class="alert-success"><i class="fa fa-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="alert-error"><i class="fa fa-times-circle"></i> {{ session('error') }}</div>
            @endif

            @if(isset($student))
                {{-- ── Formulaire MODIFICATION ── --}}
                <form action="{{ route('projets.fichiers.update', $student['cne']) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="nom" value="{{ $student['nom'] }}">
                    <input type="hidden" name="prenom" value="{{ $student['prenom'] }}">

                    {{-- Choix semestre modification --}}
                    <div class="form-section">
                        <div class="form-section-label"><i class="fa fa-calendar"></i> Semestre à modifier</div>
                        <div style="display:flex;gap:10px;">
                            <label class="sem-btn" id="edit-sem1-label">
                                <input type="radio" name="semestre" value="1" id="edit_sem1"
                                       style="accent-color:#0f2347;" checked>
                                <span style="font-size:.82rem;font-weight:600;color:#0f2347;">
                                    <i class="fa fa-calendar"></i> Semestre 1
                                </span>
                            </label>
                            <label class="sem-btn" id="edit-sem2-label">
                                <input type="radio" name="semestre" value="2" id="edit_sem2"
                                       style="accent-color:#10b981;">
                                <span style="font-size:.82rem;font-weight:600;color:#10b981;">
                                    <i class="fa fa-calendar"></i> Semestre 2
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-section-label">
                            <i class="fa fa-star"></i>
                            Notes des modules — <span id="edit-sem-label">Semestre 1</span>
                        </div>
                        @foreach(['module1'=>'Module 1','module2'=>'Module 2','module3'=>'Module 3'] as $name=>$label)
                        <div class="f-group">
                            <label class="f-label">{{ $label }} <span style="color:#8a97b0;">(0 – 20)</span></label>
                            <input type="number" name="{{ $name }}" class="f-input note"
                                   placeholder="Note /20" min="0" max="20" step="0.5" required
                                   value="{{ $student[$name] ?? old($name) }}">
                        </div>
                        @endforeach
                    </div>

                    <button type="submit" class="btn-submit edit-mode">
                        <i class="fa fa-save"></i> Enregistrer les modifications
                    </button>
                </form>
                <a href="{{ route('projets.fichiers') }}" class="btn-cancel">
                    <i class="fa fa-times"></i> Annuler la modification
                </a>

            @else
                {{-- ══════════════════════════════════════
                     FORMULAIRE AJOUT EN 2 ÉTAPES
                ══════════════════════════════════════ --}}

                {{-- Indicateur d'étapes --}}
                <div style="display:flex;align-items:center;padding:18px 24px 0;">
                    <div id="step-ind-1"
                         style="flex:1;text-align:center;padding:8px 4px;border-radius:10px 0 0 10px;
                                background:#0f2347;color:#fff;font-size:.72rem;font-weight:700;
                                letter-spacing:.5px;transition:background .3s;">
                        <i class="fa fa-user"></i> Étape 1 — Informations
                    </div>
                    <div id="step-ind-2"
                         style="flex:1;text-align:center;padding:8px 4px;border-radius:0 10px 10px 0;
                                background:#e0e6f0;color:#8a97b0;font-size:.72rem;font-weight:700;
                                letter-spacing:.5px;transition:background .3s;">
                        <i class="fa fa-star"></i> Étape 2 — Notes
                    </div>
                </div>

                {{-- ── ÉTAPE 1 ── --}}
                <div id="step1">

                    {{-- Identité --}}
                    <div class="form-section" style="border-top:none;margin-top:16px;">
                        <div class="form-section-label"><i class="fa fa-id-card"></i> Identité</div>
                        <div class="f-group">
                            <label class="f-label">CNE</label>
                            <input type="text" id="s1_cne" class="f-input"
                                   placeholder="Ex: R149018652" maxlength="20">
                        </div>
                        <div class="form-row">
                            <div class="f-group">
                                <label class="f-label">Nom</label>
                                <input type="text" id="s1_nom" class="f-input" placeholder="Nom">
                            </div>
                            <div class="f-group">
                                <label class="f-label">Prénom</label>
                                <input type="text" id="s1_prenom" class="f-input" placeholder="Prénom">
                            </div>
                        </div>
                    </div>

                    {{-- Filière & Semestre --}}
                    <div class="form-section">
                        <div class="form-section-label"><i class="fa fa-university"></i> Filière & Semestre</div>

                        <div class="f-group">
                            <label class="f-label">Filière</label>
                            <select id="s1_filiere" class="f-input" style="cursor:pointer;">
                                <option value="">-- Choisir une filière --</option>
                                @foreach(['RSI','GI','IMI','SITD','IRISTI'] as $f)
                                <option value="{{ $f }}">{{ $f }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="f-group">
                            <label class="f-label">Semestre concerné</label>
                            <div style="display:flex;gap:10px;">
                                <label class="sem-btn" id="sem1-label" onclick="selectSem(1)">
                                    <input type="radio" name="_sem_choice" value="1" id="sem1_radio"
                                           style="accent-color:#0f2347;">
                                    <div>
                                        <div style="font-size:.82rem;font-weight:700;color:#0f2347;">
                                            <i class="fa fa-calendar"></i> Semestre 1
                                        </div>
                                        <div style="font-size:.68rem;color:#8a97b0;margin-top:2px;">
                                            → stocké dans Note S1
                                        </div>
                                    </div>
                                </label>
                                <label class="sem-btn" id="sem2-label" onclick="selectSem(2)">
                                    <input type="radio" name="_sem_choice" value="2" id="sem2_radio"
                                           style="accent-color:#10b981;">
                                    <div>
                                        <div style="font-size:.82rem;font-weight:700;color:#10b981;">
                                            <i class="fa fa-calendar"></i> Semestre 2
                                        </div>
                                        <div style="font-size:.68rem;color:#8a97b0;margin-top:2px;">
                                            → stocké dans Note S2
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Adresse --}}
                    <div class="form-section">
                        <div class="form-section-label">
                            <i class="fa fa-map-marker-alt"></i> Adresse & Géolocalisation
                            <span style="font-weight:400;color:#b0bac8;font-size:.6rem;">(optionnel)</span>
                        </div>
                        <div class="f-group">
                            <label class="f-label">Adresse</label>
                            <div style="display:flex;gap:8px;">
                                <input type="text" id="s1_address" class="f-input"
                                       placeholder="Ex: Settat, Maroc" style="flex:1;">
                                <button type="button" id="btn-geocode" onclick="geocodeAddress()"
                                        style="padding:10px 14px;border:none;border-radius:10px;
                                               background:#1a3a6b;color:#fff;font-size:.78rem;
                                               font-weight:700;cursor:pointer;white-space:nowrap;">
                                    <i class="fa fa-search-location"></i> Localiser
                                </button>
                            </div>
                        </div>
                        <div id="geo-result" style="display:none;margin-top:10px;">
                            <div style="background:#ecfdf5;border:1px solid #bbf7d0;border-radius:10px;
                                        padding:10px 14px;font-size:.76rem;color:#059669;">
                                <i class="fa fa-check-circle"></i> <span id="geo-display"></span>
                            </div>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:10px;">
                                <div class="f-group" style="margin:0;">
                                    <label class="f-label">Latitude</label>
                                    <input type="text" id="s1_lat" class="f-input" readonly
                                           style="background:#f0f4fb;color:#8a97b0;">
                                </div>
                                <div class="f-group" style="margin:0;">
                                    <label class="f-label">Longitude</label>
                                    <input type="text" id="s1_lon" class="f-input" readonly
                                           style="background:#f0f4fb;color:#8a97b0;">
                                </div>
                            </div>
                        </div>
                        <div id="geo-error"
                             style="display:none;margin-top:8px;padding:8px 12px;border-radius:8px;
                                    background:#fef2f2;border:1px solid #fecaca;color:#dc2626;font-size:.74rem;">
                            <i class="fa fa-times-circle"></i> <span id="geo-error-msg"></span>
                        </div>
                    </div>

                    {{-- Prévisualisation --}}
                    <div id="step1-preview" style="display:none;margin:16px 24px;">
                        <div style="background:#f4f6fb;border-radius:12px;padding:14px 16px;border:1px solid #e0e6f0;">
                            <p style="font-size:.65rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:#8a97b0;margin:0 0 10px;">
                                Récapitulatif
                            </p>
                            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;">
                                <div>
                                    <p style="font-size:.68rem;color:#8a97b0;margin:0;">CNE</p>
                                    <p id="prev-cne" style="font-family:'Courier New',monospace;font-size:.8rem;font-weight:700;color:#2c3e50;margin:2px 0 0;"></p>
                                </div>
                                <div>
                                    <p style="font-size:.68rem;color:#8a97b0;margin:0;">Nom</p>
                                    <p id="prev-nom" style="font-size:.8rem;font-weight:700;color:#2c3e50;margin:2px 0 0;"></p>
                                </div>
                                <div>
                                    <p style="font-size:.68rem;color:#8a97b0;margin:0;">Prénom</p>
                                    <p id="prev-prenom" style="font-size:.8rem;color:#2c3e50;margin:2px 0 0;"></p>
                                </div>
                            </div>
                            <div id="prev-extra" style="display:none;margin-top:10px;padding-top:10px;border-top:1px solid #e0e6f0;display:flex;gap:16px;">
                                <div>
                                    <p style="font-size:.68rem;color:#8a97b0;margin:0;">Filière</p>
                                    <p id="prev-filiere" style="font-size:.78rem;font-weight:700;color:#0f2347;margin:2px 0 0;"></p>
                                </div>
                                <div>
                                    <p style="font-size:.68rem;color:#8a97b0;margin:0;">Semestre</p>
                                    <p id="prev-semestre" style="font-size:.78rem;font-weight:700;color:#10b981;margin:2px 0 0;"></p>
                                </div>
                            </div>
                            <div id="prev-geo-wrap" style="display:none;margin-top:10px;padding-top:10px;border-top:1px solid #e0e6f0;">
                                <p style="font-size:.68rem;color:#8a97b0;margin:0 0 4px;">
                                    <i class="fa fa-map-marker-alt" style="color:#10b981;"></i> Coordonnées
                                </p>
                                <p id="prev-geo" style="font-size:.75rem;color:#059669;margin:0;font-weight:600;"></p>
                            </div>
                        </div>
                    </div>

                    <div style="padding:0 24px 20px;">
                        <button type="button" onclick="goStep2()"
                                style="width:100%;padding:12px;border:none;border-radius:12px;
                                       background:linear-gradient(135deg,#0f2347,#1a3a6b);color:#fff;
                                       font-size:.85rem;font-weight:700;cursor:pointer;
                                       display:flex;align-items:center;justify-content:center;gap:8px;">
                            <i class="fa fa-arrow-right"></i> Continuer vers les notes
                        </button>
                    </div>
                </div>

                {{-- ── ÉTAPE 2 ── --}}
                <div id="step2" style="display:none;">

                    {{-- Rappel étudiant --}}
                    <div style="margin:16px 24px 0;padding:14px 16px;border-radius:12px;
                                background:#f4f6fb;border:1px solid #e0e6f0;">
                        <p style="font-size:.65rem;font-weight:700;letter-spacing:1.5px;
                                  text-transform:uppercase;color:#8a97b0;margin:0 0 10px;">
                            <i class="fa fa-user"></i> Récapitulatif
                        </p>
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px;">
                            <div>
                                <p style="font-size:.68rem;color:#8a97b0;margin:0;">CNE</p>
                                <p id="s2-cne" style="font-family:'Courier New',monospace;font-size:.78rem;font-weight:700;color:#2c3e50;margin:2px 0 0;"></p>
                            </div>
                            <div>
                                <p style="font-size:.68rem;color:#8a97b0;margin:0;">Nom</p>
                                <p id="s2-nom" style="font-size:.78rem;font-weight:700;color:#2c3e50;margin:2px 0 0;"></p>
                            </div>
                            <div>
                                <p style="font-size:.68rem;color:#8a97b0;margin:0;">Prénom</p>
                                <p id="s2-prenom" style="font-size:.78rem;color:#2c3e50;margin:2px 0 0;"></p>
                            </div>
                        </div>
                        <div style="margin-top:10px;padding-top:10px;border-top:1px solid #e0e6f0;display:flex;gap:20px;">
                            <div>
                                <p style="font-size:.68rem;color:#8a97b0;margin:0;">Filière</p>
                                <p id="s2-filiere" style="font-size:.78rem;font-weight:700;color:#0f2347;margin:2px 0 0;"></p>
                            </div>
                            <div>
                                <p style="font-size:.68rem;color:#8a97b0;margin:0;">Semestre</p>
                                <p id="s2-semestre" style="font-size:.78rem;font-weight:700;color:#10b981;margin:2px 0 0;"></p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('projets.fichiers.save') }}" method="POST" id="form-final">
                        @csrf
                        <input type="hidden" name="cne"       id="f_cne">
                        <input type="hidden" name="nom"       id="f_nom">
                        <input type="hidden" name="prenom"    id="f_prenom">
                        <input type="hidden" name="filiere"   id="f_filiere">
                        <input type="hidden" name="semestre"  id="f_semestre">
                        <input type="hidden" name="latitude"  id="f_lat">
                        <input type="hidden" name="longitude" id="f_lon">

                        <div class="form-section">
                            <div class="form-section-label">
                                <i class="fa fa-star"></i>
                                Notes des modules — <span id="s2-sem-label">Semestre ?</span>
                            </div>
                            @foreach(['module1'=>'Module 1','module2'=>'Module 2','module3'=>'Module 3'] as $name=>$label)
                            <div class="f-group">
                                <label class="f-label">{{ $label }} <span style="color:#8a97b0;">(0 – 20)</span></label>
                                <input type="number" name="{{ $name }}" class="f-input note"
                                       placeholder="Note /20" min="0" max="20" step="0.5" required>
                            </div>
                            @endforeach
                        </div>

                        <div style="padding:0 24px;">
                            <button type="submit" class="btn-submit" style="margin:12px 0;width:100%;">
                                <i class="fa fa-plus"></i> Enregistrer dans le fichier
                            </button>
                        </div>
                    </form>

                    <button type="button" onclick="goStep1()"
                            style="margin:0 24px 20px;width:calc(100% - 48px);padding:10px;
                                   border:1.5px solid #e0e6f0;border-radius:12px;background:#fff;
                                   color:#8a97b0;font-size:.82rem;font-weight:600;
                                   font-family:'DM Sans',sans-serif;cursor:pointer;
                                   display:flex;align-items:center;justify-content:center;gap:8px;">
                        <i class="fa fa-arrow-left"></i> Retour aux informations
                    </button>
                </div>

            @endif

        @else
            {{-- Étudiant : lecture seule --}}
            <div style="padding:30px 24px;text-align:center;color:#8a97b0;">
                <i class="fa fa-lock" style="font-size:2.5rem;color:#e0e6f0;display:block;margin-bottom:14px;"></i>
                <p style="font-size:.85rem;font-weight:600;color:#5a6880;">Accès en lecture seule</p>
                <p style="font-size:.76rem;margin-top:8px;">
                    L'ajout et la modification d'étudiants<br>sont réservés à l'<strong>administrateur</strong>.
                </p>
                <a href="{{ route('login') }}"
                   style="display:inline-flex;align-items:center;gap:6px;margin-top:16px;
                          padding:8px 18px;border-radius:10px;background:#1a3a6b;color:#fff;
                          text-decoration:none;font-size:.78rem;font-weight:700;">
                    <i class="fa fa-shield-alt"></i> Connexion Admin
                </a>
            </div>
        @endif

    </div>{{-- fin .form-card --}}

    {{-- ═══ TABLEAU ═══ --}}
    <div class="table-card">
        <div class="table-card-header">
            <i class="fa fa-list" style="color:rgba(255,255,255,.8);"></i>
            <h3>Liste des étudiants enregistrés</h3>
            <span class="count-badge">{{ count($students) }} entrée(s)</span>
        </div>
        <div class="table-wrap">
            @if(count($students) > 0)
            <table class="rsi-table">
                <thead>
                    <tr>
                        <th>#</th><th>CNE</th><th>Nom</th><th>Prénom</th>
                        <th>Mod. 1</th><th>Mod. 2</th><th>Mod. 3</th><th>Moy.</th>@if($isAdmin)<th>Modifier</th>@endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $i => $s)
                    @php $moy = round(($s['module1']+$s['module2']+$s['module3'])/3, 2); @endphp
                    <tr class="{{ (isset($student) && $student['cne'] === $s['cne']) ? 'active-edit' : '' }}">
                        <td style="color:#8a97b0;font-size:.72rem;">{{ $i+1 }}</td>
                        <td class="cne-cell">{{ $s['cne'] }}</td>
                        <td style="font-weight:600;">
                            {{ $s['nom'] }}
                            @if($isAdmin)
                            <div style="font-size:.72rem;color:var(--text-muted);margin-top:6px;">
                                Identifiant : <strong style="color:var(--accent);">{{ $s['nom'] }}</strong>
                                &nbsp;•&nbsp;
                                Mot de passe : <strong style="color:var(--accent);">{{ $s['cne'] }}</strong>
                            </div>
                            @endif
                        </td>
                        <td>{{ $s['prenom'] }}</td>
                        <td class="note-cell {{ $s['module1'] >= 10 ? 'good' : 'bad' }}">{{ $s['module1'] }}</td>
                        <td class="note-cell {{ $s['module2'] >= 10 ? 'good' : 'bad' }}">{{ $s['module2'] }}</td>
                        <td class="note-cell {{ $s['module3'] >= 10 ? 'good' : 'bad' }}">{{ $s['module3'] }}</td>
                        <td class="note-cell {{ $moy >= 10 ? 'good' : 'bad' }}" style="font-size:.85rem;">{{ $moy }}</td>
                        @if($isAdmin)
                        <td>
                            <a href="{{ route('projets.fichiers.edit', $s['cne']) }}" class="btn-edit">
                                <i class="fa fa-edit"></i> Modifier
                            </a>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state">
                <i class="fa fa-file-alt"></i>
                <p>Aucun étudiant enregistré pour l'instant.</p>
                <p style="font-size:.72rem;margin-top:4px;">Utilisez le formulaire pour ajouter une entrée.</p>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
// ── Sélection semestre (visuel) ───────────────────────────────────────────────
function selectSem(n) {
    document.getElementById('sem1-label').classList.toggle('selected-s1', n === 1);
    document.getElementById('sem2-label').classList.toggle('selected-s2', n === 2);
    document.getElementById('sem1-label').classList.toggle('selected-s2', false);
    document.getElementById('sem2-label').classList.toggle('selected-s1', false);
    updatePreview();
}

// ── Géocodage ─────────────────────────────────────────────────────────────────
function geocodeAddress() {
    const address = document.getElementById('s1_address').value.trim();
    if (!address) { showGeoError('Veuillez saisir une adresse.'); return; }

    const btn = document.getElementById('btn-geocode');
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Recherche...';
    btn.disabled = true;
    document.getElementById('geo-result').style.display = 'none';
    document.getElementById('geo-error').style.display  = 'none';

    fetch("{{ route('projets.fichiers.geocode') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ address })
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = '<i class="fa fa-search-location"></i> Localiser';
        btn.disabled  = false;
        if (data.success) {
            document.getElementById('s1_lat').value = data.latitude;
            document.getElementById('s1_lon').value = data.longitude;
            document.getElementById('geo-display').textContent = data.display;
            document.getElementById('geo-result').style.display = 'block';
            updatePreview();
        } else {
            showGeoError(data.message ?? 'Adresse introuvable.');
        }
    })
    .catch(() => {
        btn.innerHTML = '<i class="fa fa-search-location"></i> Localiser';
        btn.disabled  = false;
        showGeoError('Erreur réseau. Réessayez.');
    });
}

function showGeoError(msg) {
    document.getElementById('geo-error-msg').textContent = msg;
    document.getElementById('geo-error').style.display   = 'block';
    document.getElementById('geo-result').style.display  = 'none';
}

// ── Navigation étapes ─────────────────────────────────────────────────────────
function goStep2() {
    const cne      = document.getElementById('s1_cne').value.trim();
    const nom      = document.getElementById('s1_nom').value.trim();
    const prenom   = document.getElementById('s1_prenom').value.trim();
    const filiere  = document.getElementById('s1_filiere').value;
    const semRadio = document.querySelector('input[name="_sem_choice"]:checked');

    if (!cne || !nom || !prenom) {
        alert('Veuillez remplir le CNE, le nom et le prénom.'); return;
    }
    if (!filiere) {
        alert('Veuillez choisir une filière.'); return;
    }
    if (!semRadio) {
        alert('Veuillez choisir le semestre (1 ou 2).'); return;
    }

    const semestre = semRadio.value;

    document.getElementById('f_cne').value      = cne;
    document.getElementById('f_nom').value      = nom;
    document.getElementById('f_prenom').value   = prenom;
    document.getElementById('f_filiere').value  = filiere;
    document.getElementById('f_semestre').value = semestre;
    document.getElementById('f_lat').value      = document.getElementById('s1_lat').value;
    document.getElementById('f_lon').value      = document.getElementById('s1_lon').value;

    document.getElementById('s2-cne').textContent      = cne;
    document.getElementById('s2-nom').textContent      = nom;
    document.getElementById('s2-prenom').textContent   = prenom;
    document.getElementById('s2-filiere').textContent  = filiere;
    document.getElementById('s2-semestre').textContent = `Semestre ${semestre}`;
    document.getElementById('s2-sem-label').textContent = `Semestre ${semestre}`;

    document.getElementById('step1').style.display = 'none';
    document.getElementById('step2').style.display = 'block';
    document.getElementById('step-ind-1').style.cssText += ';background:#e0e6f0;color:#8a97b0;';
    document.getElementById('step-ind-2').style.cssText += ';background:#10b981;color:#fff;';
}

function goStep1() {
    document.getElementById('step2').style.display = 'none';
    document.getElementById('step1').style.display = 'block';
    document.getElementById('step-ind-1').style.cssText += ';background:#0f2347;color:#fff;';
    document.getElementById('step-ind-2').style.cssText += ';background:#e0e6f0;color:#8a97b0;';
}

// ── Prévisualisation temps réel ───────────────────────────────────────────────
function updatePreview() {
    const cne      = document.getElementById('s1_cne')?.value.trim()     || '';
    const nom      = document.getElementById('s1_nom')?.value.trim()     || '';
    const prenom   = document.getElementById('s1_prenom')?.value.trim()  || '';
    const filiere  = document.getElementById('s1_filiere')?.value        || '';
    const semRadio = document.querySelector('input[name="_sem_choice"]:checked');
    const lat      = document.getElementById('s1_lat')?.value            || '';
    const lon      = document.getElementById('s1_lon')?.value            || '';

    if (cne || nom || prenom) {
        document.getElementById('prev-cne').textContent    = cne    || '—';
        document.getElementById('prev-nom').textContent    = nom    || '—';
        document.getElementById('prev-prenom').textContent = prenom || '—';
        document.getElementById('step1-preview').style.display = 'block';
    }

    if (filiere || semRadio) {
        document.getElementById('prev-filiere').textContent  = filiere || '—';
        document.getElementById('prev-semestre').textContent = semRadio ? `Semestre ${semRadio.value}` : '—';
        document.getElementById('prev-extra').style.display  = 'flex';
    }

    if (lat && lon) {
        document.getElementById('prev-geo').textContent =
            `Lat: ${parseFloat(lat).toFixed(5)}  •  Lon: ${parseFloat(lon).toFixed(5)}`;
        document.getElementById('prev-geo-wrap').style.display = 'block';
    }
}

['s1_cne','s1_nom','s1_prenom','s1_filiere'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', updatePreview);
    if (el) el.addEventListener('change', updatePreview);
});

document.getElementById('s1_address')?.addEventListener('keydown', e => {
    if (e.key === 'Enter') { e.preventDefault(); geocodeAddress(); }
});

// Modifier : sync label semestre
document.querySelectorAll('input[name="semestre"]').forEach(r => {
    r.addEventListener('change', function() {
        const lbl = document.getElementById('edit-sem-label');
        if (lbl) lbl.textContent = `Semestre ${this.value}`;
        document.getElementById('edit-sem1-label')?.classList.toggle('selected-s1', this.value === '1');
        document.getElementById('edit-sem2-label')?.classList.toggle('selected-s2', this.value === '2');
    });
});
</script>
@endpush
