<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\EtudiantController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\NotesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ── Public Auth Routes ──────────────────────────────────────────────────
Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('/login',      [AuthController::class, 'login'])->name('login');
    Route::post('/register',   [AuthController::class, 'register'])->name('register');
    Route::post('/logout',     [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
    Route::get('/user',        [AuthController::class, 'user'])->middleware('auth:sanctum')->name('user');
});

// ── Protected Routes (Require Authentication) ──────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // ── Dashboard ──────────────────────────────────────────────────────
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/',      [DashboardController::class, 'index'])->name('index');
        Route::get('/stats', [DashboardController::class, 'stats'])->name('stats');
        Route::get('/about', [DashboardController::class, 'about'])->name('about');
    });

    // ── Etudiants ──────────────────────────────────────────────────────
    Route::prefix('etudiants')->name('etudiants.')->group(function () {
        Route::get('/',              [EtudiantController::class, 'index'])->name('index');
        Route::get('/{id}',          [EtudiantController::class, 'show'])->name('show');
        Route::post('/',             [EtudiantController::class, 'store'])->name('store');
        Route::put('/{id}',          [EtudiantController::class, 'update'])->name('update');
        Route::delete('/{id}',       [EtudiantController::class, 'destroy'])->name('destroy');
    });

    // ── Projets ────────────────────────────────────────────────────────
    Route::prefix('projets')->name('projets.')->group(function () {
        Route::get('/',       fn() => ['projets' => ['fichiers', 'images', 'quiz', 'stats', 'geoloc', 'notes']])->name('index');
        Route::get('/matrices',  [DashboardController::class, 'matrices'])->name('matrices');
    });

    // ── Fichiers ───────────────────────────────────────────────────────
    Route::prefix('fichiers')->name('fichiers.')->group(function () {
        Route::get('/',           [DashboardController::class, 'fichiers'])->name('index');
        Route::post('/',          [DashboardController::class, 'saveFichier'])->name('store');
        Route::get('/{cne}',      [DashboardController::class, 'showFichier'])->name('show');
        Route::put('/{cne}',      [DashboardController::class, 'updateFichier'])->name('update');
        Route::post('/geocode',   [DashboardController::class, 'geocodeAddress'])->name('geocode');
    });

    // ── Images ─────────────────────────────────────────────────────────
    Route::prefix('images')->name('images.')->group(function () {
        Route::get('/',           [ImageController::class, 'index'])->name('index');
        Route::post('/',          [ImageController::class, 'upload'])->name('upload');
        Route::get('/{id}',       [ImageController::class, 'show'])->name('show');
        Route::delete('/{id}',    [ImageController::class, 'destroy'])->name('destroy');
    });

    // ── Quiz ───────────────────────────────────────────────────────────
    Route::prefix('quiz')->name('quiz.')->group(function () {
        Route::get('/',       [QuizController::class, 'index'])->name('index');
        Route::post('/',      [QuizController::class, 'store'])->name('store');
        Route::get('/{id}',   [QuizController::class, 'show'])->name('show');
        Route::put('/{id}',   [QuizController::class, 'update'])->name('update');
    });

    // ── Notes (Admin only) ─────────────────────────────────────────────
    Route::prefix('notes')->middleware('check.admin')->name('notes.')->group(function () {
        Route::get('/',       [NotesController::class, 'index'])->name('index');
        Route::post('/',      [NotesController::class, 'store'])->name('store');
        Route::put('/{id}',   [NotesController::class, 'update'])->name('update');
        Route::delete('/{id}', [NotesController::class, 'destroy'])->name('destroy');
    });

    // ── Géoloc ─────────────────────────────────────────────────────────
    Route::prefix('geoloc')->name('geoloc.')->group(function () {
        Route::get('/', [DashboardController::class, 'geoloc'])->name('index');
    });
});
