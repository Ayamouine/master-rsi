@extends('layouts.app')
@section('title', 'Projet 1 – Matrices JS')

@push('styles')
<style>
.proj-badge { display:inline-flex;align-items:center;gap:7px;background:color-mix(in srgb, var(--accent) 14%, var(--bg-surface) 86%);border:1px solid var(--border);color:var(--accent);font-size:.65rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;padding:4px 13px;border-radius:20px;margin-bottom:14px; }
.matrices-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px; }
.mat-card { background:var(--bg-surface); border-radius:16px; border:1px solid var(--border); overflow:hidden; box-shadow:var(--shadow-soft); }
.mat-card-header { background:linear-gradient(135deg,var(--accent),var(--accent-3)); padding:14px 20px; display:flex; align-items:center; gap:10px; }
.mat-card-header h3 { font-size:.95rem; font-weight:800; color:#fff; margin:0; }
.mat-card-header .badge-n { background:rgba(255,255,255,.16); border:1px solid rgba(255,255,255,.22); color:#fff; font-size:.62rem; font-weight:700; padding:2px 8px; border-radius:12px; margin-left:auto; }
.mat-card-body { padding:20px; }
.field-group { margin-bottom:14px; }
.field-label { font-size:.72rem; font-weight:600; color:var(--text-muted); margin-bottom:5px; display:flex; align-items:center; gap:5px; }
.field-label i { color:var(--accent-2); font-size:.65rem; }
.field-input {
    width:100%; padding:9px 13px; border:1.5px solid var(--border); border-radius:9px;
    font-size:.82rem; color:var(--text-main); background:color-mix(in srgb, var(--bg-main) 82%, var(--bg-surface) 18%);
    transition:border-color .2s, box-shadow .2s;
    font-family:'DM Sans',sans-serif;
}
.field-input:focus { outline:none; border-color:var(--accent); box-shadow:0 0 0 3px rgba(6,182,212,.14); background:var(--bg-surface); }
.btn-gen {
    width:100%; padding:10px; border:none; border-radius:10px; cursor:pointer;
    background:linear-gradient(135deg,var(--accent),var(--accent-3)); color:#fff;
    font-size:.8rem; font-weight:700; font-family:'DM Sans',sans-serif;
    display:flex; align-items:center; justify-content:center; gap:7px;
    transition:filter .2s, transform .15s;
}
.btn-gen:hover { filter:brightness(1.1); transform:translateY(-1px); }
.mat-output {
    margin-top:12px; background:color-mix(in srgb, var(--bg-main) 78%, var(--bg-surface) 22%); border:1.5px solid var(--border); border-radius:10px;
    padding:12px 14px; font-family:'Courier New',monospace; font-size:.8rem;
    color:var(--text-main); min-height:90px; white-space:pre; overflow:auto;
    transition:background .3s;
}
.mat-output.has-data { background:color-mix(in srgb, var(--accent) 10%, var(--bg-main) 90%); border-color:var(--accent); color:var(--text-main); }

/* Result row */
.result-row { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
.result-card { background:var(--bg-surface); border-radius:16px; border:1px solid var(--border); overflow:hidden; box-shadow:var(--shadow-soft); }
.result-header { padding:12px 18px; display:flex; align-items:center; gap:9px; }
.result-header.sum { background:linear-gradient(135deg,#10b981,#14b8a6); }
.result-header.prod { background:linear-gradient(135deg,var(--accent-3),var(--accent)); }
.result-header h3 { font-size:.88rem; font-weight:700; color:#fff; margin:0; }
.result-header i { color:rgba(255,255,255,.8); }
.result-body { padding:18px; }
.btn-calc {
    width:100%; padding:10px; border:none; border-radius:10px; cursor:pointer;
    color:#fff; font-size:.8rem; font-weight:700; font-family:'DM Sans',sans-serif;
    display:flex; align-items:center; justify-content:center; gap:7px;
    transition:filter .2s, transform .15s; margin-bottom:12px;
}
.btn-calc.sum { background:linear-gradient(135deg,#10b981,#14b8a6); }
.btn-calc.prod { background:linear-gradient(135deg,var(--accent-3),var(--accent)); }
.btn-calc:hover { filter:brightness(1.1); transform:translateY(-1px); }

@media(max-width:700px){ .matrices-grid,.result-row{grid-template-columns:1fr;} }
</style>
@endpush

@section('content')
<div class="proj-badge" data-anim><i class="fa fa-table"></i> Projet 01 — JavaScript</div>
<div class="page-heading" data-anim><i class="fa fa-table"></i> Manipulation de Matrices avec JavaScript</div>

<div class="matrices-grid">
    @foreach([1,2] as $n)
    <div class="mat-card" data-anim>
        <div class="mat-card-header">
            <i class="fa fa-th" style="color:#e8a020;"></i>
            <h3>Matrice N°{{ $n }}</h3>
            <span class="badge-n">M{{ $n }}</span>
        </div>
        <div class="mat-card-body">
            <div style="font-size:.68rem;font-weight:700;color:#8a97b0;letter-spacing:1.5px;text-transform:uppercase;margin-bottom:12px;">Saisie des dimensions</div>
            <div class="field-group">
                <div class="field-label"><i class="fa fa-arrows-alt-v"></i> Nombre de lignes</div>
                <input type="number" id="rows{{ $n }}" class="field-input" placeholder="ex: 3" min="1" max="8">
            </div>
            <div class="field-group">
                <div class="field-label"><i class="fa fa-arrows-alt-h"></i> Nombre de colonnes</div>
                <input type="number" id="cols{{ $n }}" class="field-input" placeholder="ex: 3" min="1" max="8">
            </div>
            <button class="btn-gen" onclick="generate({{ $n }})">
                <i class="fa fa-random"></i> Générer des valeurs aléatoires
            </button>
            <div style="font-size:.68rem;font-weight:600;color:#8a97b0;margin-top:14px;margin-bottom:5px;display:flex;align-items:center;gap:5px;"><i class="fa fa-eye" style="color:#e8a020;"></i> Valeurs générées</div>
            <div class="mat-output" id="mat{{ $n }}">—</div>
        </div>
    </div>
    @endforeach
</div>

<div class="result-row">
    <div class="result-card" data-anim>
        <div class="result-header sum"><i class="fa fa-plus-circle"></i><h3>Calculer la Somme M1 + M2</h3></div>
        <div class="result-body">
            <button class="btn-calc sum" onclick="calculate('sum')"><i class="fa fa-plus"></i> Calculer Somme</button>
            <div style="font-size:.68rem;font-weight:600;color:#8a97b0;margin-bottom:5px;display:flex;align-items:center;gap:5px;"><i class="fa fa-eye" style="color:#10b981;"></i> Résultat de la Somme</div>
            <div class="mat-output" id="resultSum">—</div>
        </div>
    </div>
    <div class="result-card" data-anim>
        <div class="result-header prod"><i class="fa fa-times-circle"></i><h3>Calculer le Produit M1 × M2</h3></div>
        <div class="result-body">
            <button class="btn-calc prod" onclick="calculate('prod')"><i class="fa fa-times"></i> Calculer Produit</button>
            <div style="font-size:.68rem;font-weight:600;color:#8a97b0;margin-bottom:5px;display:flex;align-items:center;gap:5px;"><i class="fa fa-eye" style="color:#8b5cf6;"></i> Résultat du Produit</div>
            <div class="mat-output" id="resultProd">—</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let M = [null, null, null]; // 1-indexed

function generate(n) {
    const r = parseInt(document.getElementById('rows'+n).value);
    const c = parseInt(document.getElementById('cols'+n).value);
    if (!r || !c || r < 1 || c < 1) { alert('Entrez des dimensions valides (≥ 1).'); return; }
    if (r > 8 || c > 8) { alert('Maximum 8×8 pour l\'affichage.'); return; }
    const mat = [];
    for (let i = 0; i < r; i++) {
        mat[i] = [];
        for (let j = 0; j < c; j++) mat[i][j] = Math.floor(Math.random() * 9) + 1;
    }
    M[n] = mat;
    const el = document.getElementById('mat'+n);
    el.textContent = matToStr(mat);
    el.classList.add('has-data');
}

function matToStr(mat) {
    return mat.map(row => row.map(v => String(v).padStart(3)).join(' ')).join('\n');
}

function calculate(type) {
    if (!M[1] || !M[2]) { alert('Générez d\'abord les deux matrices.'); return; }
    const r1=M[1].length, c1=M[1][0].length, r2=M[2].length, c2=M[2][0].length;
    if (type === 'sum') {
        if (r1!==r2 || c1!==c2) { alert('Les matrices doivent avoir les mêmes dimensions pour la somme.'); return; }
        const res = M[1].map((row,i) => row.map((v,j) => v + M[2][i][j]));
        const el = document.getElementById('resultSum');
        el.textContent = matToStr(res); el.classList.add('has-data');
    } else {
        if (c1!==r2) { alert('Pour le produit, le nb de colonnes de M1 doit égaler le nb de lignes de M2.'); return; }
        const res = [];
        for (let i=0;i<r1;i++){res[i]=[];for(let j=0;j<c2;j++){res[i][j]=0;for(let k=0;k<c1;k++)res[i][j]+=M[1][i][k]*M[2][k][j];}}
        const el = document.getElementById('resultProd');
        el.textContent = matToStr(res); el.classList.add('has-data');
    }
}
</script>
@endpush
