@extends('layouts.app')
@section('title', 'About me')

@push('styles')
<style>
    .about-shell {
        display: grid;
        grid-template-columns: 360px 1fr;
        gap: 16px;
        align-items: start;
    }

    .glass-card {
        border: 1px solid var(--border);
        border-radius: 18px;
        background: linear-gradient(180deg, color-mix(in srgb, var(--bg-surface) 88%, #fff 12%), var(--bg-surface));
        box-shadow: var(--shadow-soft);
    }

    .about-panel {
        padding: 18px;
        position: sticky;
        top: 98px;
    }

    .panel-title {
        margin: 0 0 6px;
        font-size: 1.2rem;
        color: var(--text-main);
    }

    .panel-sub {
        margin: 0 0 16px;
        color: var(--text-muted);
        font-size: 0.9rem;
        line-height: 1.6;
    }

    .input-group {
        display: grid;
        gap: 12px;
    }

    .field label {
        display: block;
        margin-bottom: 6px;
        color: var(--text-main);
        font-size: 0.84rem;
        font-weight: 600;
    }

    .field input,
    .field textarea,
    .field select {
        width: 100%;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--bg-main);
        color: var(--text-main);
        padding: 11px 12px;
        font: inherit;
        outline: none;
    }

    .field textarea { min-height: 110px; resize: vertical; }

    .field input:focus,
    .field textarea:focus,
    .field select:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--accent) 18%, transparent 82%);
    }

    .action-row {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 14px;
    }

    .btn-action {
        border: 1px solid var(--border);
        background: var(--bg-soft);
        color: var(--text-main);
        border-radius: 12px;
        padding: 10px 14px;
        text-decoration: none;
        font-size: 0.86rem;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-primary {
        background: linear-gradient(135deg, #7c3aed, #22c55e 120%);
        border-color: transparent;
        color: #fff;
    }

    .btn-accent {
        background: linear-gradient(135deg, #ff7a59, #f59e0b);
        border-color: transparent;
        color: #1a1010;
    }

    .btn-action:hover { transform: translateY(-2px); }

    .btn-action:active { transform: translateY(0); }

    .profile-note {
        margin-top: 14px;
        padding: 12px 14px;
        border-radius: 14px;
        border: 1px dashed color-mix(in srgb, var(--accent) 45%, var(--border) 55%);
        background: color-mix(in srgb, var(--accent) 8%, var(--bg-main) 92%);
        color: var(--text-muted);
        font-size: 0.84rem;
        line-height: 1.6;
    }

    .toolbar-row {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 12px;
    }

    .edit-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        background: var(--bg-soft);
        border: 1px solid var(--border);
        color: var(--text-muted);
        font-size: 0.82rem;
    }

    .is-hidden { display: none !important; }

    .cv-print-only {
        display: none;
    }

    .cv-zone {
        display: grid;
        gap: 16px;
    }

    .hero-card {
        padding: 20px;
        border-radius: 20px;
        border: 1px solid var(--border);
        background: linear-gradient(135deg, color-mix(in srgb, #7c3aed 18%, var(--bg-surface) 82%), color-mix(in srgb, #22d3ee 14%, var(--bg-surface) 86%));
        box-shadow: var(--shadow-soft);
        overflow: hidden;
        position: relative;
    }

    .hero-card::after {
        content: '';
        position: absolute;
        right: -70px;
        top: -60px;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0.18), transparent 68%);
    }

    .hero-row {
        display: flex;
        align-items: center;
        gap: 14px;
        position: relative;
        z-index: 1;
    }

    .hero-avatar {
        width: 82px;
        height: 82px;
        border-radius: 22px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f97316, #ec4899);
        color: #fff;
        font-size: 1.5rem;
        font-weight: 800;
        box-shadow: 0 12px 22px rgba(0,0,0,0.18);
        flex-shrink: 0;
    }

    .hero-name {
        margin: 0;
        font-size: clamp(1.5rem, 2.2vw, 2.4rem);
        color: var(--text-main);
        line-height: 1.1;
    }

    .hero-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }

    .pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 999px;
        padding: 7px 11px;
        font-size: 0.76rem;
        font-weight: 700;
        color: #1b1012;
    }

    .p-cyan { background: #22d3ee; }
    .p-yellow { background: #fbbf24; }
    .p-purple { background: #c4b5fd; }
    .p-pink { background: #f472b6; }
    .p-green { background: #86efac; }

    .cv-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .cv-card {
        padding: 18px;
        border-radius: 18px;
        border: 1px solid var(--border);
        background: var(--bg-surface);
        box-shadow: var(--shadow-soft);
    }

    .card-title {
        margin: 0 0 14px;
        color: var(--text-main);
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 1.2px;
    }

    .contact-item {
        display: flex;
        gap: 10px;
        align-items: flex-start;
        margin-bottom: 10px;
        color: var(--text-muted);
        font-size: 0.88rem;
    }

    .contact-item i { color: var(--accent); width: 16px; margin-top: 3px; }

    .skills-list {
        display: grid;
        gap: 11px;
    }

    .skill-line {
        display: flex;
        justify-content: space-between;
        gap: 8px;
        font-size: 0.82rem;
        color: var(--text-muted);
        margin-bottom: 5px;
    }

    .bar {
        height: 8px;
        border-radius: 999px;
        background: var(--bg-main);
        overflow: hidden;
        border: 1px solid var(--border);
    }

    .bar > span {
        display: block;
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(90deg, var(--from), var(--to));
    }

    .timeline {
        display: grid;
        gap: 10px;
    }

    .timeline-item {
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 12px;
        background: color-mix(in srgb, var(--bg-soft) 70%, transparent 30%);
    }

    .timeline-head {
        display: flex;
        justify-content: space-between;
        gap: 8px;
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 4px;
    }

    .timeline-sub,
    .timeline-desc {
        color: var(--text-muted);
        font-size: 0.8rem;
        line-height: 1.6;
    }

    .chips {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .chip {
        padding: 7px 10px;
        border-radius: 999px;
        background: var(--bg-main);
        border: 1px solid var(--border);
        color: var(--text-main);
        font-size: 0.78rem;
    }

    /* Editable fields styling */
    .skill-row input,
    #interests-list .interest-chip,
    .experience-row input,
    .experience-row textarea {
        background: var(--bg-main);
        color: var(--text-main);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 8px 10px;
        font: inherit;
        outline: none;
    }

    .skill-row input { min-width: 80px; }
    .interest-chip { display:inline-flex; align-items:center; gap:8px; padding:6px 10px; border-radius:999px; }
    .experience-row { background: transparent; }
    .experience-row textarea { min-height:70px; resize:vertical; }

    /* Layout improvements for editable lists */
    #skills-list .skill-row { display:flex; gap:8px; flex-wrap:wrap; align-items:center; }
    #skills-list .skill-row .skill-name { flex:1 1 160px; min-width:120px; }
    #skills-list .skill-row .skill-percent { width:80px; }
    #skills-list .skill-row .skill-from, #skills-list .skill-row .skill-to { width:120px; }
    #interests-list { display:flex; gap:8px; flex-wrap:wrap; }
    #interests-list .interest-chip { background: var(--bg-soft); border-radius:999px; padding:6px 10px; }
    #experience-list .experience-row { background: color-mix(in srgb, var(--bg-soft) 60%, transparent 40%); }

    .profile-preview {
        display: grid;
        gap: 16px;
    }

    .preview-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .muted { color: var(--text-muted); }

    @media (max-width: 1050px) {
        .about-shell,
        .cv-grid { grid-template-columns: 1fr; }
        .about-panel { position: static; }
    }

    @media print {
        body * { visibility: hidden !important; }
        .cv-zone, .cv-zone * { visibility: visible !important; }
        .cv-zone { position: absolute; left: 0; top: 0; width: 100%; }
        .about-shell { display: block; }
        .about-panel { display: none !important; }
        .hero-card, .cv-card { box-shadow: none !important; }
        .cv-zone { gap: 10px; }
    }
</style>
@endpush

@section('content')
@php
    $isAdmin = session('is_admin', false) === true;
    $profile = $etudiant;
    $bioDefault = $profile->bio ?? 'Décrivez ici votre parcours, vos objectifs et votre valeur ajoutée. Le CV se met à jour automatiquement à partir de ces données.';
    $filiere = $profile->filiere ?? 'RSI';
    $city = $profile->city ?? 'Settat';
    $phone = $profile->phone ?? '+212 600 000 000';
    $email = $profile->email ?? ($profile->login . '@fsts.ac.ma');
    $linkedin = $profile->linkedin ?? 'fst-settat';
    $github = $profile->github ?? 'fst-settat';

    // Use profile-provided JSON fields when available (set by controller)
    $skills = $profile->skills ?? [];
    $interests = $profile->interests ?? [];
    $experience = $profile->experience ?? [];
@endphp

<div class="about-shell">
    <aside class="glass-card about-panel">
        <h1 class="panel-title">Modifier mon profil</h1>
        <p class="panel-sub">Passez en mode édition pour modifier les informations, puis enregistrez pour appliquer les changements.</p>

        <div class="toolbar-row">
            <button type="button" class="btn-action btn-accent" id="toggle-edit">Modifier</button>
            <span class="edit-badge" id="edit-state">Mode lecture</span>
        </div>

        <form action="{{ route('about.update') }}" method="POST" class="input-group" data-live-form id="about-form">
            @csrf
            @method('PUT')

            <div class="field editable-block is-hidden">
                <label for="nom">Nom complet</label>
                <input id="nom" name="nom" type="text" value="{{ old('nom', $profile->nom ?? '') }}" data-preview="name" required disabled>
            </div>

            <div class="field editable-block is-hidden">
                <label for="filiere">Filière</label>
                <select id="filiere" name="filiere" data-preview="filiere" disabled>
                    @foreach(['RSI','GI','IMI','SITD','IRISTI'] as $option)
                        <option value="{{ $option }}" @selected(old('filiere', $filiere) === $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field editable-block is-hidden">
                <label for="bio">Bio</label>
                <textarea id="bio" name="bio" data-preview="bio" disabled>{{ old('bio', $bioDefault) }}</textarea>
            </div>

            <div class="field editable-block is-hidden">
                <label for="phone">Téléphone</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone', $phone) }}" data-preview="phone" disabled>
            </div>

            <div class="field editable-block is-hidden">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $email) }}" data-preview="email" disabled>
            </div>

            <div class="field editable-block is-hidden">
                <label for="city">Ville</label>
                <input id="city" name="city" type="text" value="{{ old('city', $city) }}" data-preview="city" disabled>
            </div>

            <div class="field editable-block is-hidden">
                <label for="linkedin">LinkedIn</label>
                <input id="linkedin" name="linkedin" type="text" value="{{ old('linkedin', $linkedin) }}" data-preview="linkedin" disabled>
            </div>

            <div class="field editable-block is-hidden">
                <label for="github">GitHub</label>
                <input id="github" name="github" type="text" value="{{ old('github', $github) }}" data-preview="github" disabled>
            </div>

            <div class="field editable-block is-hidden">
                <label>Compétences</label>
                <div id="skills-list">
                    @foreach($skills as $i => $skill)
                        <div class="skill-row" data-index="{{ $i }}" style="display:flex;gap:8px;margin-bottom:8px;">
                            <input type="text" class="skill-name" placeholder="Nom" value="{{ $skill[0] ?? '' }}" disabled>
                            <input type="number" class="skill-percent" placeholder="%" value="{{ $skill[1] ?? 0 }}" min="0" max="100" style="width:80px;" disabled>
                            <input type="text" class="skill-from" placeholder="from color" value="{{ $skill[2] ?? '#22d3ee' }}" style="width:120px;" disabled>
                            <input type="text" class="skill-to" placeholder="to color" value="{{ $skill[3] ?? $skill[2] ?? '#22d3ee' }}" style="width:120px;" disabled>
                            <button type="button" class="btn-action" onclick="removeSkillRow(this)">Suppr</button>
                        </div>
                    @endforeach
                </div>
                <div style="margin-top:8px;"><button type="button" class="btn-action" id="add-skill">Ajouter une compétence</button></div>
            </div>

            <div class="field editable-block is-hidden">
                <label>Centres d'intérêt</label>
                <div id="interests-list" style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:8px;">
                    @foreach($interests as $interest)
                        <div class="interest-chip">{{ $interest }} <button type="button" class="btn-action" onclick="removeInterest(this)">x</button></div>
                    @endforeach
                </div>
                <div style="display:flex;gap:8px;margin-top:6px;"><input id="new-interest" placeholder="Ajouter un centre d'intérêt" disabled><button type="button" class="btn-action" id="add-interest" disabled>Ajouter</button></div>
            </div>

            <div class="field editable-block is-hidden">
                <label>Expériences</label>
                <div id="experience-list">
                    @foreach($experience as $i => $exp)
                        <div class="experience-row" data-index="{{ $i }}" style="border:1px solid var(--border);padding:8px;border-radius:8px;margin-bottom:8px;">
                            <div style="display:flex;gap:8px;margin-bottom:6px;"><input class="exp-title" placeholder="Titre" value="{{ $exp[0] ?? '' }}" disabled><input class="exp-sub" placeholder="Sous-titre" value="{{ $exp[1] ?? '' }}" disabled><input class="exp-period" placeholder="Période" value="{{ $exp[2] ?? '' }}" disabled></div>
                            <div style="margin-bottom:6px;"><textarea class="exp-desc" placeholder="Description" disabled>{{ $exp[3] ?? '' }}</textarea></div>
                            <div style="display:flex;gap:8px;margin-bottom:6px;"><input class="exp-chips" placeholder="chips (virgule)" value="{{ is_array($exp[4]) ? implode(',', $exp[4]) : '' }}" disabled><input class="exp-color" placeholder="color" value="{{ $exp[5] ?? '' }}" disabled><button type="button" class="btn-action" onclick="removeExperience(this)">Suppr</button></div>
                        </div>
                    @endforeach
                </div>
                <div style="margin-top:8px;"><button type="button" class="btn-action" id="add-experience">Ajouter une expérience</button></div>
            </div>

            <input type="hidden" name="profile_skills" id="hidden_profile_skills" value='{{ old('profile_skills', json_encode($skills, JSON_UNESCAPED_UNICODE)) }}'>
            <input type="hidden" name="profile_interests" id="hidden_profile_interests" value='{{ old('profile_interests', json_encode($interests, JSON_UNESCAPED_UNICODE)) }}'>
            <input type="hidden" name="profile_experience" id="hidden_profile_experience" value='{{ old('profile_experience', json_encode($experience, JSON_UNESCAPED_UNICODE)) }}'>

            <div class="action-row">
                <button type="submit" class="btn-action btn-primary">Enregistrer</button>
                <button type="button" class="btn-action btn-accent" id="print-cv">Télécharger / Imprimer</button>
            </div>

            <div class="profile-note">Astuce: votre CV se met à jour instantanément pendant la saisie, puis il est sauvegardé dans la base quand vous cliquez sur Enregistrer.</div>
        </form>
    </aside>

    <section class="cv-zone">
        <div class="hero-card glass-card">
            <div class="hero-row">
                <div class="hero-avatar" id="preview-avatar">{{ strtoupper(substr($profile->nom ?? 'E',0,1)) }}</div>
                <div>
                    <h2 class="hero-name" id="preview-name">{{ $profile->nom ?? 'Votre Nom' }}</h2>
                    <div class="muted" id="preview-bio">{{ $bioDefault }}</div>
                    <div class="hero-meta">
                        <span class="pill p-cyan" id="preview-city"><i class="fa fa-map-marker-alt"></i> {{ $city }}</span>
                        <span class="pill p-yellow" id="preview-filiere"><i class="fa fa-graduation-cap"></i> {{ $filiere }}</span>
                        <span class="pill p-purple"><i class="fab fa-github"></i> <span id="preview-github">{{ $github }}</span></span>
                        <span class="pill p-pink"><i class="fab fa-linkedin"></i> <span id="preview-linkedin">{{ $linkedin }}</span></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="cv-grid">
            <div class="cv-card">
                <h3 class="card-title">Contact</h3>
                <div class="contact-item"><i class="fa fa-envelope"></i><span id="preview-email">{{ $email }}</span></div>
                <div class="contact-item"><i class="fa fa-phone"></i><span id="preview-phone">{{ $phone }}</span></div>
                <div class="contact-item"><i class="fa fa-map-marker-alt"></i><span id="preview-city-text">{{ $city }}</span></div>
                <div class="contact-item"><i class="fab fa-linkedin"></i><span>linkedin.com/in/<span id="preview-linkedin-text">{{ $linkedin }}</span></span></div>
                <div class="contact-item"><i class="fab fa-github"></i><span>github.com/<span id="preview-github-text">{{ $github }}</span></span></div>
            </div>

            <div class="cv-card">
                <h3 class="card-title">Compétences</h3>
                <div class="skills-list">
                    @foreach($skills as $skill)
                        <div>
                            <div class="skill-line"><strong>{{ $skill[0] }}</strong><span>{{ $skill[1] }}%</span></div>
                            <div class="bar"><span style="width: {{ $skill[1] }}%; --from: {{ $skill[2] }}; --to: {{ $skill[3] }};"></span></div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="cv-card">
                <h3 class="card-title">Expérience</h3>
                <div class="timeline">
                    @foreach($experience as $item)
                        <div class="timeline-item">
                            <div class="timeline-head"><span>{{ $item[0] }}</span><span>{{ $item[2] }}</span></div>
                            <div class="timeline-sub">{{ $item[1] }}</div>
                            <div class="timeline-desc">{{ $item[3] }}</div>
                            <div class="chips" style="margin-top:8px;">
                                @foreach($item[4] as $chip)
                                    <span class="chip">{{ $chip }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="cv-card">
                <h3 class="card-title">Centres d'intérêt</h3>
                <div class="chips">
                    @foreach($interests as $interest)
                        <span class="chip">{{ $interest }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    const liveForm = document.querySelector('[data-live-form]');
    const printBtn = document.getElementById('print-cv');
    const toggleEditBtn = document.getElementById('toggle-edit');
    const editState = document.getElementById('edit-state');
    const editableBlocks = document.querySelectorAll('.editable-block');
    let editMode = false;

    function syncPreview() {
        const map = {
            name: ['preview-name', 'preview-avatar'],
            filiere: ['preview-filiere'],
            bio: ['preview-bio'],
            phone: ['preview-phone'],
            email: ['preview-email'],
            city: ['preview-city', 'preview-city-text'],
            linkedin: ['preview-linkedin', 'preview-linkedin-text'],
            github: ['preview-github', 'preview-github-text'],
        };

        document.querySelectorAll('[data-preview]').forEach((field) => {
            const targets = map[field.dataset.preview] || [];
            const value = field.value || '';
            targets.forEach((id) => {
                const node = document.getElementById(id);
                if (!node) return;
                node.textContent = value;
                if (id === 'preview-avatar') {
                    const first = (value.trim().charAt(0) || 'E').toUpperCase();
                    node.textContent = first;
                }
            });
        });
    }

    if (liveForm) {
        liveForm.addEventListener('input', () => {
            if (editMode) updateHiddenFieldsOnly();
        });
        syncPreview();
    }

    function setEditMode(enabled) {
        editMode = enabled;
        editableBlocks.forEach(block => {
            block.classList.toggle('is-hidden', !enabled);
            block.querySelectorAll('input, textarea, select, button').forEach(el => {
                if (el.type === 'hidden') {
                    el.disabled = false;
                    return;
                }
                if (el.id === 'add-skill' || el.id === 'add-interest' || el.id === 'add-experience' || el.type === 'button' || el.id === 'new-interest') {
                    el.disabled = !enabled;
                } else if (el.name || el.classList.contains('skill-name') || el.classList.contains('skill-percent') || el.classList.contains('skill-from') || el.classList.contains('skill-to') || el.classList.contains('exp-title') || el.classList.contains('exp-sub') || el.classList.contains('exp-period') || el.classList.contains('exp-desc') || el.classList.contains('exp-chips') || el.classList.contains('exp-color')) {
                    el.disabled = !enabled;
                }
            });
        });
        if (toggleEditBtn) toggleEditBtn.textContent = enabled ? 'Fermer' : 'Modifier';
        if (editState) editState.textContent = enabled ? 'Mode édition' : 'Mode lecture';
    }

    if (toggleEditBtn) {
        toggleEditBtn.addEventListener('click', () => setEditMode(!editMode));
    }

    setEditMode(false);

    // Handle preview rendering from saved values
    function renderSkills(skills) {
        const container = document.querySelector('.skills-list');
        if (!container) return;
        container.innerHTML = '';
        skills.forEach(skill => {
            const name = skill[0] || '';
            const percent = skill[1] || 0;
            const from = skill[2] || '#22d3ee';
            const to = skill[3] || from;
            const item = document.createElement('div');
            item.innerHTML = `<div><div class="skill-line"><strong>${escapeHtml(name)}</strong><span>${escapeHtml(percent)}%</span></div><div class="bar"><span style="width: ${escapeHtml(percent)}%; --from: ${escapeHtml(from)}; --to: ${escapeHtml(to)};"></span></div></div>`;
            container.appendChild(item);
        });
    }

    function renderInterests(interests) {
        const container = document.querySelector('.cv-card .chips');
        if (!container) return;
        // find the interest chips container inside the right card (Centres d'intérêt)
        const cards = document.querySelectorAll('.cv-card');
        let target = null;
        cards.forEach(card => {
            if (card.querySelector('.card-title') && card.querySelector('.card-title').textContent.trim() === "Centres d'intérêt") {
                target = card.querySelector('.chips');
            }
        });
        if (!target) target = container;
        target.innerHTML = '';
        interests.forEach(i => {
            const span = document.createElement('span');
            span.className = 'chip';
            span.textContent = i;
            target.appendChild(span);
        });
    }

    function renderExperience(items) {
        const container = document.querySelector('.timeline');
        if (!container) return;
        container.innerHTML = '';
        items.forEach(item => {
            const headLeft = item[0] || '';
            const title = item[1] || '';
            const period = item[2] || '';
            const desc = item[3] || '';
            const chips = item[4] || [];
            const wrapper = document.createElement('div');
            wrapper.className = 'timeline-item';
            let chipsHtml = '';
            chips.forEach(c => { chipsHtml += `<span class="chip">${escapeHtml(c)}</span>`; });
            wrapper.innerHTML = `<div class="timeline-head"><span>${escapeHtml(headLeft)}</span><span>${escapeHtml(period)}</span></div><div class="timeline-sub">${escapeHtml(title)}</div><div class="timeline-desc">${escapeHtml(desc)}</div><div class="chips" style="margin-top:8px;">${chipsHtml}</div>`;
            container.appendChild(wrapper);
        });
    }

    function escapeHtml(unsafe) {
        return String(unsafe).replace(/[&<>"']/g, function (m) { return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'})[m]; });
    }

    // Editable list handlers only affect form fields; preview updates on save/input in edit mode
    function collectSkillsFromUI() {
        const rows = document.querySelectorAll('#skills-list .skill-row');
        const out = [];
        rows.forEach(row => {
            const name = row.querySelector('.skill-name')?.value || '';
            const percent = parseInt(row.querySelector('.skill-percent')?.value || 0) || 0;
            const from = row.querySelector('.skill-from')?.value || '#22d3ee';
            const to = row.querySelector('.skill-to')?.value || from;
            if (name) out.push([name, percent, from, to]);
        });
        return out;
    }

    function collectInterestsFromUI() {
        const chips = [];
        document.querySelectorAll('#interests-list .interest-chip').forEach(c => {
            chips.push(c.firstChild.textContent.trim());
        });
        return chips;
    }

    function collectExperienceFromUI() {
        const out = [];
        document.querySelectorAll('#experience-list .experience-row').forEach(row => {
            const title = row.querySelector('.exp-title')?.value || '';
            const sub = row.querySelector('.exp-sub')?.value || '';
            const period = row.querySelector('.exp-period')?.value || '';
            const desc = row.querySelector('.exp-desc')?.value || '';
            const chips = (row.querySelector('.exp-chips')?.value || '').split(',').map(s=>s.trim()).filter(Boolean);
            const color = row.querySelector('.exp-color')?.value || '';
            if (title) out.push([title, sub, period, desc, chips, color]);
        });
        return out;
    }

    function updateHiddenFieldsOnly() {
        const skills = collectSkillsFromUI();
        const interests = collectInterestsFromUI();
        const experience = collectExperienceFromUI();
        document.getElementById('hidden_profile_skills').value = JSON.stringify(skills);
        document.getElementById('hidden_profile_interests').value = JSON.stringify(interests);
        document.getElementById('hidden_profile_experience').value = JSON.stringify(experience);
    }

    document.getElementById('add-skill').addEventListener('click', () => {
        const container = document.getElementById('skills-list');
        const div = document.createElement('div');
        div.className = 'skill-row';
        div.style.display = 'flex'; div.style.gap = '8px'; div.style.marginBottom = '8px';
        div.innerHTML = '<input type="text" class="skill-name" placeholder="Nom"><input type="number" class="skill-percent" placeholder="%" min="0" max="100" style="width:80px;"><input type="text" class="skill-from" placeholder="from color" style="width:120px;"><input type="text" class="skill-to" placeholder="to color" style="width:120px;"><button type="button" class="btn-action" onclick="removeSkillRow(this)">Suppr</button>';
        container.appendChild(div);
        div.querySelectorAll('input').forEach(i => i.addEventListener('input', updateHiddenFieldsOnly));
        updateHiddenFieldsOnly();
    });

    function removeSkillRow(btn) { btn.parentElement.remove(); updateHiddenFieldsOnly(); }

    document.getElementById('add-interest').addEventListener('click', () => {
        const val = document.getElementById('new-interest').value.trim();
        if (!val) return;
        const container = document.getElementById('interests-list');
        const div = document.createElement('div');
        div.className = 'interest-chip';
        div.style.display = 'inline-flex'; div.style.alignItems = 'center'; div.style.gap = '6px'; div.style.padding = '6px'; div.style.border = '1px solid var(--border)'; div.style.borderRadius = '999px';
        div.innerHTML = `${val} <button type="button" class="btn-action" onclick="removeInterest(this)">x</button>`;
        container.appendChild(div);
        document.getElementById('new-interest').value = '';
        updateHiddenFieldsOnly();
    });

    function removeInterest(btn) { btn.parentElement.remove(); updateHiddenFieldsOnly(); }

    document.getElementById('add-experience').addEventListener('click', () => {
        const container = document.getElementById('experience-list');
        const div = document.createElement('div');
        div.className = 'experience-row';
        div.style.border='1px solid var(--border)'; div.style.padding='8px'; div.style.borderRadius='8px'; div.style.marginBottom='8px';
        div.innerHTML = `<div style="display:flex;gap:8px;margin-bottom:6px;"><input class="exp-title" placeholder="Titre"><input class="exp-sub" placeholder="Sous-titre"><input class="exp-period" placeholder="Période"></div><div style="margin-bottom:6px;"><textarea class="exp-desc" placeholder="Description"></textarea></div><div style="display:flex;gap:8px;margin-bottom:6px;"><input class="exp-chips" placeholder="chips (virgule)"><input class="exp-color" placeholder="color"><button type="button" class="btn-action" onclick="removeExperience(this)">Suppr</button></div>`;
        container.appendChild(div);
        div.querySelectorAll('input,textarea').forEach(i => i.addEventListener('input', updateHiddenFieldsOnly));
        updateHiddenFieldsOnly();
    });

    function removeExperience(btn) { btn.parentElement.parentElement.remove(); updateHiddenFieldsOnly(); }

    // wire existing inputs to update hidden fields only, not the visible preview
    document.querySelectorAll('#skills-list input, #experience-list input, #experience-list textarea, #interests-list .interest-chip, #new-interest').forEach(i => {
        i.addEventListener('input', updateHiddenFieldsOnly);
    });



    // ensure hidden fields are updated before submit
liveForm.addEventListener('submit', function() {
    if (editMode) {
        updateHiddenFieldsOnly();
    }
});
    if (printBtn) {
        printBtn.addEventListener('click', () => window.print());
    }
</script>
@endpush
