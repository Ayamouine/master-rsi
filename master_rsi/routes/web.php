<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// ── Accueil ─────────────────────────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));

// ── Auth (commune étudiant + admin) ─────────────────────────────────────────
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

// ── Pages protégées ──────────────────────────────────────────────────────────
Route::middleware(['check.etudiant'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/about',     [DashboardController::class, 'about'])->name('about');
    Route::get('/about/edit', [DashboardController::class, 'editAbout'])->name('about.edit');
    Route::put('/about',      [DashboardController::class, 'updateAbout'])->name('about.update');

    Route::prefix('projets')->name('projets.')->group(function () {

        Route::get('/',         [DashboardController::class, 'projets'])->name('index');
        Route::get('/matrices', [DashboardController::class, 'matrices'])->name('matrices');

        // Projet 2 – Fichiers
        // GET  : accessible à tous (étudiant + admin)
        // POST/PUT : DashboardController vérifie is_admin avant d'agir
        Route::get('/fichiers',              [DashboardController::class, 'fichiers'])->name('fichiers');
        Route::post('/fichiers',             [DashboardController::class, 'saveFichier'])->name('fichiers.save');
        Route::get('/fichiers/{cne}/edit',   [DashboardController::class, 'editFichier'])->name('fichiers.edit');
        Route::put('/fichiers/{cne}',        [DashboardController::class, 'updateFichier'])->name('fichiers.update');
        Route::post('/fichiers/geocode', [DashboardController::class, 'geocodeAddress'])->name('fichiers.geocode');


        // Projet 3 – Images
        Route::get('/images',  [DashboardController::class, 'images'])->name('images');
        Route::post('/images', [DashboardController::class, 'uploadImage'])->name('images.upload');

        // Projet 4 – Quiz
        Route::get('/quiz',       [DashboardController::class, 'quiz'])->name('quiz');
        Route::post('/quiz/save', [DashboardController::class, 'saveQuiz'])->name('quiz.save');

        // Projet 5 – Stats (admin only)
        Route::middleware(['check.admin'])->group(function () {
            Route::get('/stats',  [DashboardController::class, 'stats'])->name('stats');

            // Projet 6 – Géoloc (admin only)
            Route::get('/geoloc', [DashboardController::class, 'geoloc'])->name('geoloc');
        });

        // ── Privilège admin uniquement ──────────────────────────────────────
        Route::get('/notes',            [DashboardController::class, 'notes'])->name('notes');
        Route::get('/notes/{id}/edit',  [DashboardController::class, 'editNote'])->name('notes.edit');
        Route::put('/notes/{id}',       [DashboardController::class, 'updateNote'])->name('notes.update');
    });
});
// Dans le groupe projets
