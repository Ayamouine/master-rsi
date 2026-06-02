<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    // bootstrap/app.php  — dans withMiddleware()
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'check.etudiant' => \App\Http\Middleware\CheckEtudiantAuth::class,
        'check.admin'    => \App\Http\Middleware\CheckAdminAuth::class,  // ← ajout
    ]);
})


    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
