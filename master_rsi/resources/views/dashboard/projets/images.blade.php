@extends('layouts.app')
@section('title', 'Projet 3 – Images BDD')

@push('styles')
<style>
.proj-badge {
    display:inline-flex; align-items:center; gap:7px;
    background:color-mix(in srgb, var(--accent) 14%, var(--bg-surface) 86%); border:1px solid var(--border); color:var(--accent);
    font-size:.65rem; font-weight:700; letter-spacing:1.5px;
    text-transform:uppercase; padding:4px 13px; border-radius:20px; margin-bottom:14px;
}

/* Upload zone */
.upload-section {
    background:var(--bg-surface); border-radius:18px; border:1px solid var(--border);
    overflow:hidden; margin-bottom:24px; box-shadow:var(--shadow-soft);
}
.upload-header {
    background:linear-gradient(135deg,var(--accent-3),var(--accent));
    padding:18px 24px; display:flex; align-items:center; gap:10px;
}
.upload-header h3 {
    font-size:1rem;
    font-weight:800; color:#fff; margin:0;
}
.upload-body { padding:24px; display:flex; align-items:center; gap:20px; }

.drop-zone {
    flex:1; border:2px dashed var(--border); border-radius:14px; padding:32px 20px;
    text-align:center; cursor:pointer; transition:all .25s; background:color-mix(in srgb, var(--bg-main) 84%, var(--bg-surface) 16%);
    position:relative;
}
.drop-zone.dragover { border-color:var(--accent-3); background:color-mix(in srgb, var(--accent-3) 12%, var(--bg-main) 88%); }
.drop-zone input[type=file] {
    position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%;
}
.drop-zone i { font-size:2rem; color:var(--accent-3); margin-bottom:10px; display:block; transition:color .2s; }
.drop-zone:hover i { color:var(--accent); }
.drop-zone p { font-size:.8rem; color:var(--text-muted); margin:0; line-height:1.5; }
.drop-zone strong { color:var(--accent); }

.upload-preview { width:120px; text-align:center; }
.preview-img {
    width:110px; height:110px; object-fit:cover;
    border-radius:12px; border:2px solid var(--border); display:none;
}
.preview-placeholder {
    width:110px; height:110px; border-radius:12px; background:color-mix(in srgb, var(--bg-main) 84%, var(--bg-surface) 16%);
    border:2px dashed var(--border); display:flex; align-items:center;
    justify-content:center; color:var(--accent-3); font-size:2rem;
}
.btn-upload {
    padding:11px 22px; border:none; border-radius:11px; cursor:pointer;
    background:linear-gradient(135deg,var(--accent-3),var(--accent)); color:#fff;
    font-size:.82rem; font-weight:700; font-family:'DM Sans',sans-serif;
    display:flex; align-items:center; gap:7px; white-space:nowrap;
    transition:filter .2s, transform .15s; align-self:center;
}
.btn-upload:hover { filter:brightness(1.1); transform:translateY(-1px); }
.file-info { font-size:.7rem; color:var(--text-muted); margin-top:6px; }

/* Gallery */
.gallery-header {
    display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;
}
.gallery-count {
    font-size:.75rem; color:var(--text-muted); background:color-mix(in srgb, var(--bg-main) 82%, var(--bg-surface) 18%);
    border:1px solid var(--border); padding:4px 12px; border-radius:12px;
}
.gallery-grid {
    display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:16px;
}
.img-card {
    background:var(--bg-surface); border-radius:14px; border:1px solid var(--border); overflow:hidden;
    box-shadow:var(--shadow-soft); transition:box-shadow .2s, transform .2s;
}
.img-card:hover { box-shadow:0 8px 24px rgba(139,92,246,.18); transform:translateY(-3px); }
.img-thumb { width:100%; height:130px; object-fit:cover; display:block; cursor:pointer; }
.img-thumb-placeholder {
    width:100%; height:130px; background:color-mix(in srgb, var(--accent-3) 10%, var(--bg-main) 90%);
    display:flex; align-items:center; justify-content:center; color:var(--accent-3); font-size:2rem;
}
.img-info { padding:10px 12px; }
.img-name {
    font-size:.72rem; font-weight:600; color:var(--text-main);
    white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-bottom:3px;
}
.img-meta { display:flex; justify-content:space-between; font-size:.65rem; color:var(--text-muted); }
.empty-gallery { text-align:center; padding:48px 20px; color:var(--text-muted); grid-column:1/-1; }
.empty-gallery i { font-size:2.5rem; color:var(--border); margin-bottom:14px; display:block; }

/* Lightbox */
.lightbox {
    display:none; position:fixed; inset:0; background:rgba(0,0,0,.85);
    z-index:9999; align-items:center; justify-content:center;
}
.lightbox.open { display:flex; }
.lightbox img {
    max-width:90vw; max-height:85vh; border-radius:12px;
    box-shadow:0 24px 80px rgba(0,0,0,.5);
}
.lightbox-close {
    position:absolute; top:20px; right:24px; color:#fff; font-size:1.5rem; cursor:pointer;
    background:rgba(255,255,255,.1); width:40px; height:40px; border-radius:50%;
    display:flex; align-items:center; justify-content:center; transition:background .2s;
}
.lightbox-close:hover { background:rgba(255,255,255,.25); }

/* Alert */
.alert-success {
    background:#f0fdf4; border:1px solid #bbf7d0; color:#166534;
    border-radius:12px; padding:12px 18px; margin-bottom:18px;
    display:flex; align-items:center; gap:10px; font-size:.82rem;
}
</style>
@endpush

@section('content')
<div class="proj-badge"><i class="fa fa-images"></i> Projet 03 — MySQL BLOB</div>
<div class="page-heading"><i class="fa fa-images"></i> Insertion & Affichage d'Images en Base de Données</div>

@if(session('success'))
<div class="alert-success">
    <i class="fa fa-check-circle"></i> {{ session('success') }}
</div>
@endif

{{-- Upload --}}
<div class="upload-section">
    <div class="upload-header">
        <i class="fa fa-cloud-upload-alt" style="color:rgba(255,255,255,.8);"></i>
        <h3>Insérer une image dans la base de données</h3>
    </div>
    <form action="{{ route('projets.images.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="upload-body">
            <div class="drop-zone" id="dropZone">
                <input type="file" name="image" id="imgInput" accept="image/*" onchange="previewFile(this)">
                <i class="fa fa-cloud-upload-alt"></i>
                <p><strong>Cliquez</strong> ou glissez une image ici<br>
                   <span style="font-size:.68rem;">JPEG, PNG, GIF, WebP – max 5 Mo</span></p>
            </div>
            <div class="upload-preview">
                <div class="preview-placeholder" id="previewPlaceholder"><i class="fa fa-image"></i></div>
                <img id="previewImg" class="preview-img" src="" alt="Aperçu">
                <div class="file-info" id="fileInfo"></div>
            </div>
            <button type="submit" class="btn-upload">
                <i class="fa fa-database"></i> Insérer en BDD
            </button>
        </div>
    </form>
</div>

{{-- Gallery --}}
<div class="gallery-header">
    <div class="page-heading" style="margin-bottom:0; border-bottom:none; padding-bottom:0; font-size:1rem;">
        <i class="fa fa-th-large"></i> Galerie des images enregistrées
    </div>
    <div class="gallery-count"><i class="fa fa-images"></i> {{ count($images) }} image(s)</div>
</div>

<div class="gallery-grid">
    @forelse($images as $img)
    <div class="img-card">
        <img class="img-thumb"
             src="data:{{ $img->type }};base64,{{ base64_encode($img->bin_img) }}"
             alt="{{ $img->name }}"
             onclick="openLightbox(this.src)"
             title="Cliquer pour agrandir">
        <div class="img-info">
            <div class="img-name" title="{{ $img->name }}">{{ $img->name }}</div>
            <div class="img-meta">
                <span>{{ strtoupper(explode('/',$img->type)[1] ?? $img->type) }}</span>
                <span>{{ number_format($img->size/1024,1) }} Ko</span>
            </div>
        </div>
    </div>
    @empty
    <div class="empty-gallery">
        <i class="fa fa-images"></i>
        <p style="font-weight:600; color:#5a6880;">Aucune image enregistrée</p>
        <p style="font-size:.75rem; margin-top:4px;">
            Utilisez le formulaire ci-dessus pour insérer votre première image.
        </p>
    </div>
    @endforelse
</div>

{{-- Lightbox --}}
<div class="lightbox" id="lightbox" onclick="closeLightbox()">
    <div class="lightbox-close" onclick="closeLightbox()"><i class="fa fa-times"></i></div>
    <img id="lightboxImg" src="" alt="">
</div>
@endsection

@push('scripts')
<script>
function previewFile(input) {
    if (!input.files.length) return;
    const f = input.files[0];
    document.getElementById('fileInfo').textContent = f.name + ' (' + (f.size/1024).toFixed(1) + ' Ko)';
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('previewImg').style.display = 'block';
        document.getElementById('previewPlaceholder').style.display = 'none';
    };
    reader.readAsDataURL(f);
}
function openLightbox(src) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightbox').classList.add('open');
}
function closeLightbox() {
    document.getElementById('lightbox').classList.remove('open');
}

// Drag & drop styling
const dz = document.getElementById('dropZone');
dz.addEventListener('dragover', e => { e.preventDefault(); dz.classList.add('dragover'); });
dz.addEventListener('dragleave', () => dz.classList.remove('dragover'));
dz.addEventListener('drop', e => { e.preventDefault(); dz.classList.remove('dragover'); });
</script>
@endpush
