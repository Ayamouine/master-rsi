@extends('layouts.app')
@section('title', 'Projet 5 – Statistiques')

@push('styles')
<style>
.proj-badge { display:inline-flex;align-items:center;gap:7px;background:color-mix(in srgb, var(--accent-2) 14%, var(--bg-surface) 86%);border:1px solid var(--border);color:var(--accent-2);font-size:.65rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;padding:4px 13px;border-radius:20px;margin-bottom:14px; }
.stats-grid { display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px; margin-bottom:24px; }
.stat-kpi { background:var(--bg-surface); border-radius:14px; border:1px solid var(--border); padding:20px 22px; display:flex; align-items:center; gap:14px; box-shadow:var(--shadow-soft); }
.stat-kpi-icon { width:46px; height:46px; border-radius:13px; display:flex; align-items:center; justify-content:center; font-size:1.1rem; flex-shrink:0; }
.stat-kpi-val { font-family:var(--font-sans); font-size:1.6rem; font-weight:700; line-height:1; margin-bottom:3px; }
.stat-kpi-label { font-size:.7rem; color:var(--text-muted); }

.chart-card { background:var(--bg-surface); border-radius:18px; border:1px solid var(--border); overflow:hidden; box-shadow:var(--shadow-soft); margin-bottom:20px; }
.chart-card-header { padding:18px 24px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
.chart-card-header h3 { font-size:1rem; font-weight:800; color:var(--text-main); display:flex; align-items:center; gap:9px; margin:0; }
.chart-card-header h3 i { color:var(--accent-2); }
.chart-type-btns { display:flex; gap:6px; }
.chart-type-btn { padding:5px 12px; border:1.5px solid var(--border); border-radius:8px; background:var(--bg-main); font-size:.7rem; font-weight:600; color:var(--text-muted); cursor:pointer; transition:all .2s; font-family:'DM Sans',sans-serif; }
.chart-type-btn.active,.chart-type-btn:hover { background:var(--accent-2); color:#fff; border-color:var(--accent-2); }
.chart-body { padding:24px; }
canvas#mainChart { max-height:360px; }

/* Ranking table */
.rank-table { width:100%; border-collapse:collapse; }
.rank-table thead th { background:color-mix(in srgb, var(--bg-main) 82%, var(--bg-surface) 18%); padding:10px 16px; font-size:.68rem; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:var(--text-muted); border-bottom:2px solid var(--border); }
.rank-table tbody tr { border-bottom:1px solid var(--border); transition:background .15s; }
.rank-table tbody tr:hover { background:color-mix(in srgb, var(--accent) 6%, var(--bg-main) 94%); }
.rank-table tbody td { padding:11px 16px; font-size:.8rem; color:var(--text-main); }
.rank-badge { width:28px; height:28px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-size:.72rem; font-weight:700; }
.rank-1 { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; }
.rank-2 { background:linear-gradient(135deg,#94a3b8,#64748b); color:#fff; }
.rank-3 { background:linear-gradient(135deg,#b45309,#92400e); color:#fff; }
.rank-other { background:color-mix(in srgb, var(--bg-main) 82%, var(--bg-surface) 18%); color:var(--text-muted); }
.moy-bar-wrap { display:flex; align-items:center; gap:10px; }
.moy-bar-track { flex:1; height:6px; background:color-mix(in srgb, var(--bg-main) 82%, var(--bg-surface) 18%); border-radius:3px; overflow:hidden; }
.moy-bar-fill { height:100%; border-radius:3px; background:linear-gradient(90deg,var(--accent-2),#f87171); animation:barIn .6s ease both; transform-origin:left; }
@keyframes barIn { from{transform:scaleX(0)} to{transform:scaleX(1)} }
.moy-val { font-weight:700; color:#1e2a40; font-size:.82rem; width:36px; text-align:right; }

@media(max-width:700px){ .stats-grid{grid-template-columns:1fr 1fr;} }
@media(max-width:500px){ .stats-grid{grid-template-columns:1fr;} }
</style>
@endpush

@section('content')
<div class="proj-badge"><i class="fa fa-chart-bar"></i> Projet 05 — Chart.js</div>
<div class="page-heading"><i class="fa fa-chart-bar"></i> Statistiques avec ChartJS</div>

@php
$noms = $etudiants->pluck('nom')->toArray();
$moyennes = $etudiants->pluck('moyenne')->map(fn($v)=>round($v,2))->toArray();
$avg = count($moyennes) ? round(array_sum($moyennes)/count($moyennes),2) : 0;
$max = count($moyennes) ? max($moyennes) : 0;
$min = count($moyennes) ? min($moyennes) : 0;
$sorted = $etudiants->sortByDesc('moyenne')->values();
@endphp

{{-- KPIs --}}
<div class="stats-grid">
    <div class="stat-kpi">
        <div class="stat-kpi-icon" style="background:#fef2f2;"><i class="fa fa-users" style="color:#ef4444;"></i></div>
        <div>
            <div class="stat-kpi-val" style="color:#ef4444;">{{ count($noms) }}</div>
            <div class="stat-kpi-label">Étudiants</div>
        </div>
    </div>
    <div class="stat-kpi">
        <div class="stat-kpi-icon" style="background:#fffbeb;"><i class="fa fa-star" style="color:#f59e0b;"></i></div>
        <div>
            <div class="stat-kpi-val" style="color:#f59e0b;">{{ $avg }}</div>
            <div class="stat-kpi-label">Moyenne générale / 20</div>
        </div>
    </div>
    <div class="stat-kpi">
        <div class="stat-kpi-icon" style="background:#ecfdf5;"><i class="fa fa-trophy" style="color:#10b981;"></i></div>
        <div>
            <div class="stat-kpi-val" style="color:#10b981;">{{ $max }}</div>
            <div class="stat-kpi-label">Meilleure moyenne</div>
        </div>
    </div>
</div>

{{-- Chart --}}
<div class="chart-card">
    <div class="chart-card-header">
        <h3><i class="fa fa-chart-bar"></i> Moyennes des étudiants du Master RSI</h3>
        <div class="chart-type-btns">
            <button class="chart-type-btn active" onclick="setChartType('bar',this)"><i class="fa fa-chart-bar"></i> Barres</button>
            <button class="chart-type-btn" onclick="setChartType('line',this)"><i class="fa fa-chart-line"></i> Ligne</button>
            <button class="chart-type-btn" onclick="setChartType('radar',this)"><i class="fa fa-spider"></i> Radar</button>
        </div>
    </div>
    <div class="chart-body">
        @if(count($noms) > 0)
        <canvas id="mainChart"></canvas>
        @else
        <div style="text-align:center;padding:40px;color:#8a97b0;">
            <i class="fa fa-chart-bar" style="font-size:2rem;color:#e0e6f0;margin-bottom:12px;display:block;"></i>
            <p>Aucune donnée disponible. Ajoutez des étudiants avec des moyennes.</p>
        </div>
        @endif
    </div>
</div>

{{-- Ranking --}}
@if(count($sorted) > 0)
<div class="chart-card">
    <div class="chart-card-header">
        <h3><i class="fa fa-medal"></i> Classement des étudiants</h3>
    </div>
    <div style="overflow-x:auto;">
        <table class="rank-table">
            <thead><tr><th>Rang</th><th>Nom</th><th>Moyenne</th><th>Progression</th><th>Statut</th></tr></thead>
            <tbody>
            @foreach($sorted as $i => $e)
            <tr>
                <td>
                    <span class="rank-badge {{ $i===0?'rank-1':($i===1?'rank-2':($i===2?'rank-3':'rank-other')) }}">
                        {{ $i===0?'🥇':($i===1?'🥈':($i===2?'🥉':$i+1)) }}
                    </span>
                </td>
                <td style="font-weight:600;">{{ $e->nom }}</td>
                <td><span style="font-weight:700;color:{{ $e->moyenne>=10?'#10b981':'#ef4444' }};">{{ number_format($e->moyenne,2) }}/20</span></td>
                <td>
                    <div class="moy-bar-wrap">
                        <div class="moy-bar-track"><div class="moy-bar-fill" style="width:{{ ($e->moyenne/20)*100 }}%;animation-delay:{{ $i*.08 }}s;background:{{ $e->moyenne>=10?'linear-gradient(90deg,#10b981,#34d399)':'linear-gradient(90deg,#ef4444,#f87171)' }};"></div></div>
                        <div class="moy-val">{{ number_format($e->moyenne,1) }}</div>
                    </div>
                </td>
                <td>
                    @if($e->moyenne >= 16) <span style="background:#d1fae5;color:#065f46;font-size:.65rem;font-weight:700;padding:3px 9px;border-radius:10px;">Très bien</span>
                    @elseif($e->moyenne >= 12) <span style="background:#dbeafe;color:#1e40af;font-size:.65rem;font-weight:700;padding:3px 9px;border-radius:10px;">Bien</span>
                    @elseif($e->moyenne >= 10) <span style="background:#fef9c3;color:#854d0e;font-size:.65rem;font-weight:700;padding:3px 9px;border-radius:10px;">Passable</span>
                    @else <span style="background:#fee2e2;color:#991b1b;font-size:.65rem;font-weight:700;padding:3px 9px;border-radius:10px;">Insuffisant</span>
                    @endif
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = @json($noms);
const data   = @json($moyennes);
const colors = data.map(v => v >= 10
    ? `rgba(16,185,129,${0.6 + (v/20)*0.4})`
    : `rgba(239,68,68,${0.5 + ((20-v)/20)*0.4})`);

let currentType = 'bar';
let chart;

function buildChart(type) {
    if (chart) chart.destroy();
    const ctx = document.getElementById('mainChart');
    if (!ctx) return;
    chart = new Chart(ctx, {
        type: type,
        data: {
            labels,
            datasets: [{
                label: 'Moyenne /20',
                data,
                backgroundColor: type === 'line' ? 'rgba(239,68,68,.12)' : colors,
                borderColor: type === 'line' ? '#ef4444' : colors,
                borderWidth: type === 'line' ? 2.5 : 1.5,
                borderRadius: type === 'bar' ? 8 : 0,
                pointBackgroundColor: '#ef4444',
                pointRadius: 5,
                fill: type === 'line',
                tension: .35,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.parsed.y}/20`
                    }
                }
            },
            scales: type === 'radar' ? {} : {
                y: { beginAtZero: true, max: 20, ticks: { stepSize: 2 }, grid: { color: '#f0f4fb' } },
                x: { grid: { display: false } }
            },
            animation: { duration: 700, easing: 'easeOutQuart' }
        }
    });
}
function setChartType(type, btn) {
    document.querySelectorAll('.chart-type-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    buildChart(type);
}
if (labels.length) buildChart('bar');
</script>
@endpush
