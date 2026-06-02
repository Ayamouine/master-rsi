@extends('layouts.app')
@section('title', 'Projet 4 – Quiz')

@push('styles')
<style>
.proj-badge {
    display:inline-flex; align-items:center; gap:7px;
    background:color-mix(in srgb, var(--accent-2) 14%, var(--bg-surface) 86%); border:1px solid var(--border); color:var(--accent-2);
    font-size:.65rem; font-weight:700; letter-spacing:1.5px;
    text-transform:uppercase; padding:4px 13px; border-radius:20px; margin-bottom:14px;
}

/* Quiz Cards Grid */
.quiz-grid {
    display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-bottom:32px;
}
@media(max-width:700px){ .quiz-grid { grid-template-columns:1fr; } }

.quiz-card {
    background:var(--bg-surface); border-radius:18px; border:1px solid var(--border);
    overflow:hidden; box-shadow:var(--shadow-soft);
    transition:box-shadow .2s, transform .2s; cursor:pointer;
}
.quiz-card:hover { box-shadow:0 8px 24px rgba(0,0,0,.12); transform:translateY(-3px); }

.quiz-card-banner {
    height:140px; display:flex; align-items:center; justify-content:center;
    font-size:3rem; position:relative; overflow:hidden;
}
.quiz-card-banner.js-banner {
    background:linear-gradient(135deg,#f59e0b,var(--accent-2));
}
.quiz-card-banner.php-banner {
    background:linear-gradient(135deg,var(--accent-3),var(--accent));
}
.quiz-card-banner .badge-num {
    position:absolute; top:12px; left:14px;
    background:rgba(255,255,255,.2); color:#fff;
    font-size:.65rem; font-weight:700; letter-spacing:1px;
    padding:3px 10px; border-radius:12px; text-transform:uppercase;
}
.quiz-card-body { padding:16px 20px; }
.quiz-card-title {
    font-size:1.05rem;
    font-weight:800; color:var(--text-main); margin-bottom:6px;
}
.quiz-card-desc { font-size:.76rem; color:var(--text-muted); margin-bottom:14px; }
.btn-start-quiz {
    display:inline-flex; align-items:center; gap:6px;
    padding:8px 18px; border:none; border-radius:10px; cursor:pointer;
    font-size:.78rem; font-weight:700; font-family:'DM Sans',sans-serif;
    transition:filter .2s, transform .15s;
}
.btn-start-quiz.js { background:linear-gradient(135deg,#f59e0b,var(--accent-2)); color:#fff; }
.btn-start-quiz.php { background:linear-gradient(135deg,var(--accent-3),var(--accent)); color:#fff; }
.btn-start-quiz:hover { filter:brightness(1.1); transform:translateY(-1px); }

/* Modal Overlay */
.quiz-modal-overlay {
    display:none; position:fixed; inset:0; background:rgba(0,0,0,.6);
    z-index:9999; align-items:center; justify-content:center; padding:20px;
}
.quiz-modal-overlay.open { display:flex; }
.quiz-modal {
    background:var(--bg-surface); border-radius:20px; width:100%; max-width:640px;
    max-height:90vh; overflow-y:auto; box-shadow:0 24px 80px rgba(0,0,0,.3);
    animation:slideUp .3s ease;
}
@keyframes slideUp {
    from { transform:translateY(40px); opacity:0; }
    to   { transform:translateY(0);    opacity:1; }
}
.quiz-modal-header {
    padding:24px 28px 16px; border-bottom:1px solid var(--border); text-align:center;
}
.quiz-modal-header h2 {
    font-family:var(--font-sans); font-size:1.5rem;
    font-weight:700; margin-bottom:4px;
}
.quiz-modal-header h2.js-color { color:var(--accent-2); }
.quiz-modal-header h2.php-color { color:var(--accent-3); }
.quiz-modal-header p { font-size:.8rem; color:var(--text-muted); }
.quiz-modal-body { padding:24px 28px; }

/* Questions */
.question-block { margin-bottom:24px; }
.question-text {
    font-size:.9rem; font-weight:700; color:var(--text-main);
    margin-bottom:12px; line-height:1.5;
}
.question-num {
    display:inline-block; width:24px; height:24px; border-radius:50%;
    text-align:center; line-height:24px; font-size:.7rem; font-weight:800;
    margin-right:8px; flex-shrink:0;
}
.question-num.js-num { background:color-mix(in srgb, var(--accent-2) 20%, var(--bg-main) 80%); color:#92400e; }
.question-num.php-num { background:color-mix(in srgb, var(--accent-3) 20%, var(--bg-main) 80%); color:#4c1d95; }

.options-list { list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:8px; }
.option-item { display:flex; align-items:center; gap:10px; }
.option-item label {
    display:flex; align-items:center; gap:10px; cursor:pointer;
    font-size:.82rem; color:var(--text-main); padding:10px 14px; border-radius:10px;
    border:1px solid var(--border); width:100%; transition:all .18s;
}
.option-item label:hover { border-color:var(--accent-3); background:color-mix(in srgb, var(--accent-3) 8%, var(--bg-main) 92%); }
.option-item input[type=radio] { accent-color:var(--accent-3); width:16px; height:16px; flex-shrink:0; }
.option-item.js-opt label:hover { border-color:var(--accent-2); background:color-mix(in srgb, var(--accent-2) 10%, var(--bg-main) 90%); }
.option-item.js-opt input[type=radio] { accent-color:var(--accent-2); }

/* Submit */
.quiz-footer { padding:0 28px 28px; }
.btn-submit-quiz {
    width:100%; padding:13px; border:none; border-radius:12px; cursor:pointer;
    font-size:.88rem; font-weight:700; font-family:'DM Sans',sans-serif;
    display:flex; align-items:center; justify-content:center; gap:8px;
    transition:filter .2s, transform .15s;
}
.btn-submit-quiz.js { background:linear-gradient(135deg,#f59e0b,var(--accent-2)); color:#fff; }
.btn-submit-quiz.php { background:linear-gradient(135deg,var(--accent-3),var(--accent)); color:#fff; }
.btn-submit-quiz:hover { filter:brightness(1.1); transform:translateY(-1px); }

/* Score history */
.score-section { background:var(--bg-surface); border-radius:18px; border:1px solid var(--border); overflow:hidden; box-shadow:var(--shadow-soft); }
.score-header { background:linear-gradient(135deg,var(--accent),var(--accent-3)); padding:16px 24px; display:flex; align-items:center; gap:10px; }
.score-header h3 { font-size:1rem; font-weight:800; color:#fff; margin:0; }
.score-body { padding:20px 24px; }
.score-row { display:flex; justify-content:space-between; align-items:center; padding:10px 0; border-bottom:1px solid var(--border); font-size:.82rem; }
.score-row:last-child { border-bottom:none; }
.score-label { color:var(--text-main); font-weight:600; }
.score-value { font-weight:700; padding:3px 12px; border-radius:20px; }
.score-js { background:color-mix(in srgb, var(--accent-2) 18%, var(--bg-main) 82%); color:#92400e; }
.score-php { background:color-mix(in srgb, var(--accent-3) 18%, var(--bg-main) 82%); color:#4c1d95; }
.no-scores { text-align:center; color:var(--text-muted); font-size:.8rem; padding:16px 0; }
</style>
@endpush

@section('content')
<div class="proj-badge" data-anim><i class="fa fa-question-circle"></i> Projet 04 — Quiz Interactif</div>
<div class="page-heading" data-anim><i class="fa fa-question-circle"></i> Quiz – Testez vos Connaissances</div>

{{-- Quiz Cards --}}
<div class="quiz-grid">
    {{-- Quiz 1 : JavaScript --}}
    <div class="quiz-card" onclick="openQuiz('js')">
        <div class="quiz-card-banner js-banner">
            <span class="badge-num">Quiz N°1</span>
            <span style="font-size:2.5rem;">⚡</span>
        </div>
        <div class="quiz-card-body">
            <div class="quiz-card-title">Quiz 1 – JavaScript</div>
            <div class="quiz-card-desc">Tester vos connaissances en JavaScript · 3 questions</div>
            <button class="btn-start-quiz js" onclick="openQuiz('js'); event.stopPropagation();">
                <i class="fa fa-play"></i> Commencer
            </button>
        </div>
    </div>

    {{-- Quiz 2 : PHP --}}
    <div class="quiz-card" onclick="openQuiz('php')">
        <div class="quiz-card-banner php-banner">
            <span class="badge-num">Quiz N°2</span>
            <span style="font-size:2.5rem;">🐘</span>
        </div>
        <div class="quiz-card-body">
            <div class="quiz-card-title">Quiz 2 – PHP</div>
            <div class="quiz-card-desc">Tester vos connaissances en PHP · 3 questions</div>
            <button class="btn-start-quiz php" onclick="openQuiz('php'); event.stopPropagation();">
                <i class="fa fa-play"></i> Commencer
            </button>
        </div>
    </div>
</div>

{{-- Scores --}}
<div class="score-section">
    <div class="score-header">
        <i class="fa fa-trophy" style="color:#fbbf24;"></i>
        <h3>Mes résultats enregistrés</h3>
    </div>
    <div class="score-body">
        {{-- ✅ CORRECTION : on utilise quiz1 et quiz2 (scores 0-3), pas note1/note2 (moyennes académiques) --}}
        @if($etudiant && ($etudiant->quiz1 !== null || $etudiant->quiz2 !== null))
            <div class="score-row">
                <span class="score-label"><i class="fa fa-bolt" style="color:#d97706;margin-right:6px;"></i>Quiz JavaScript</span>
                <span class="score-value score-js">
                    {{ $etudiant->quiz1 !== null ? $etudiant->quiz1 . ' / 3' : '—' }}
                </span>
            </div>
            <div class="score-row">
                <span class="score-label"><i class="fa fa-code" style="color:#6366f1;margin-right:6px;"></i>Quiz PHP</span>
                <span class="score-value score-php">
                    {{ $etudiant->quiz2 !== null ? $etudiant->quiz2 . ' / 3' : '—' }}
                </span>
            </div>
        @else
            <div class="no-scores">
                <i class="fa fa-inbox" style="font-size:1.5rem;display:block;margin-bottom:8px;color:#e2e8f0;"></i>
                Aucun résultat enregistré. Complétez un quiz !
            </div>
        @endif
    </div>
</div>

{{-- ==================== MODAL QUIZ JS ==================== --}}
<div class="quiz-modal-overlay" id="modal-js">
    <div class="quiz-modal">
        <div class="quiz-modal-header">
            <h2 class="js-color"><i class="fa fa-bolt"></i> Quiz 1 JavaScript</h2>
            <p>Tester vos Connaissances en <strong>javascript</strong></p>
        </div>
        <div class="quiz-modal-body">

            {{-- Q1 --}}
            <div class="question-block">
                <div class="question-text">
                    <span class="question-num js-num">1</span>
                    Dans quel élément on met le code <strong>javascript</strong> ?
                </div>
                <ul class="options-list">
                    <li class="option-item js-opt"><label><input type="radio" name="js_q1" value="a"> a. &lt;script&gt;</label></li>
                    <li class="option-item js-opt"><label><input type="radio" name="js_q1" value="b"> b. &lt;js&gt;</label></li>
                    <li class="option-item js-opt"><label><input type="radio" name="js_q1" value="c"> c. &lt;body&gt;</label></li>
                    <li class="option-item js-opt"><label><input type="radio" name="js_q1" value="d"> d. &lt;link&gt;</label></li>
                </ul>
            </div>

            {{-- Q2 --}}
            <div class="question-block">
                <div class="question-text">
                    <span class="question-num js-num">2</span>
                    Quel attribut utiliser pour faire référence à un script javascript externe ?
                </div>
                <ul class="options-list">
                    <li class="option-item js-opt"><label><input type="radio" name="js_q2" value="a"> a. src</label></li>
                    <li class="option-item js-opt"><label><input type="radio" name="js_q2" value="b"> b. rel</label></li>
                    <li class="option-item js-opt"><label><input type="radio" name="js_q2" value="c"> c. type</label></li>
                    <li class="option-item js-opt"><label><input type="radio" name="js_q2" value="d"> d. href</label></li>
                </ul>
            </div>

            {{-- Q3 --}}
            <div class="question-block">
                <div class="question-text">
                    <span class="question-num js-num">3</span>
                    Comment afficher "hello" sur un message <strong>alert</strong> ?
                </div>
                <ul class="options-list">
                    <li class="option-item js-opt"><label><input type="radio" name="js_q3" value="a"> a. msg("hello")</label></li>
                    <li class="option-item js-opt"><label><input type="radio" name="js_q3" value="b"> b. alertbox("hello")</label></li>
                    <li class="option-item js-opt"><label><input type="radio" name="js_q3" value="c"> c. documentwrite("hello")</label></li>
                    <li class="option-item js-opt"><label><input type="radio" name="js_q3" value="d"> d. alert("hello")</label></li>
                </ul>
            </div>
        </div>
        <div class="quiz-footer" style="display:flex;gap:12px;">
            <button class="btn-submit-quiz js" onclick="submitQuiz('js')" style="flex:1;">
                <i class="fa fa-paper-plane"></i> Submit result
            </button>
            <button onclick="closeQuiz('js')"
                style="padding:13px 20px;border:1px solid #e2e8f0;border-radius:12px;background:#fff;cursor:pointer;font-size:.82rem;color:#64748b;">
                Fermer
            </button>
        </div>
    </div>
</div>

{{-- ==================== MODAL QUIZ PHP ==================== --}}
<div class="quiz-modal-overlay" id="modal-php">
    <div class="quiz-modal">
        <div class="quiz-modal-header">
            <h2 class="php-color"><i class="fa fa-code"></i> Quiz 2 PHP</h2>
            <p>Tester vos Connaissances en <strong>PHP</strong></p>
        </div>
        <div class="quiz-modal-body">

            {{-- Q1 --}}
            <div class="question-block">
                <div class="question-text">
                    <span class="question-num php-num">1</span>
                    Que signifie PHP ?
                </div>
                <ul class="options-list">
                    <li class="option-item"><label><input type="radio" name="php_q1" value="a"> a. Page Helper Process</label></li>
                    <li class="option-item"><label><input type="radio" name="php_q1" value="b"> b. Programming Home Pages</label></li>
                    <li class="option-item"><label><input type="radio" name="php_q1" value="c"> c. PHP: Hypertext Preprocessor</label></li>
                </ul>
            </div>

            {{-- Q2 --}}
            <div class="question-block">
                <div class="question-text">
                    <span class="question-num php-num">2</span>
                    Quelle fonction retourne la longueur d'une chaine de texte ?
                </div>
                <ul class="options-list">
                    <li class="option-item"><label><input type="radio" name="php_q2" value="a"> a. strlen</label></li>
                    <li class="option-item"><label><input type="radio" name="php_q2" value="b"> b. strlength</label></li>
                    <li class="option-item"><label><input type="radio" name="php_q2" value="c"> c. length</label></li>
                    <li class="option-item"><label><input type="radio" name="php_q2" value="d"> d. substr</label></li>
                </ul>
            </div>

            {{-- Q3 --}}
            <div class="question-block">
                <div class="question-text">
                    <span class="question-num php-num">3</span>
                    Sachant que $num = 6. Quelle est la valeur de : $num += 2 ?
                </div>
                <ul class="options-list">
                    <li class="option-item"><label><input type="radio" name="php_q3" value="a"> a. 3</label></li>
                    <li class="option-item"><label><input type="radio" name="php_q3" value="b"> b. 8</label></li>
                    <li class="option-item"><label><input type="radio" name="php_q3" value="c"> c. 10</label></li>
                    <li class="option-item"><label><input type="radio" name="php_q3" value="d"> d. 12</label></li>
                </ul>
            </div>
        </div>
        <div class="quiz-footer" style="display:flex;gap:12px;">
            <button class="btn-submit-quiz php" onclick="submitQuiz('php')" style="flex:1;">
                <i class="fa fa-paper-plane"></i> Submit result
            </button>
            <button onclick="closeQuiz('php')"
                style="padding:13px 20px;border:1px solid #e2e8f0;border-radius:12px;background:#fff;cursor:pointer;font-size:.82rem;color:#64748b;">
                Fermer
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Réponses correctes
const answers = {
    js:  { js_q1: 'a', js_q2: 'a', js_q3: 'd' },
    php: { php_q1: 'c', php_q2: 'a', php_q3: 'b' }
};

function openQuiz(type) {
    document.getElementById('modal-' + type).classList.add('open');
}
function closeQuiz(type) {
    document.getElementById('modal-' + type).classList.remove('open');
}

// Fermer en cliquant l'overlay
document.querySelectorAll('.quiz-modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('open');
        }
    });
});

function submitQuiz(type) {
    const correct = answers[type];
    let score = 0;
    let total = Object.keys(correct).length;
    let allAnswered = true;

    for (const [name, rightVal] of Object.entries(correct)) {
        const chosen = document.querySelector(`input[name="${name}"]:checked`);
        if (!chosen) { allAnswered = false; break; }
        if (chosen.value === rightVal) score++;
    }

    if (!allAnswered) {
        alert('⚠️ Veuillez répondre à toutes les questions avant de soumettre.');
        return;
    }

    const quizLabel = type === 'js' ? 'JavaScript' : 'PHP';
    alert(`✅ Quiz ${quizLabel} terminé !\n\nVotre note : ${score} / ${total}`);

    // ✅ CORRECTION : on envoie quiz1 ou quiz2 (pas note1/note2)
    const noteField = type === 'js' ? 'quiz1' : 'quiz2';
    fetch('{{ route("projets.quiz.save") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ note_field: noteField, score: score })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            closeQuiz(type);
            window.location.reload();
        }
    })
    .catch(() => {
        closeQuiz(type);
    });
}
</script>
@endpush
