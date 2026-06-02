<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckEtudiantAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Accepte aussi bien un étudiant qu'un admin connecté
        if (!session('etudiant_id')) {
            return redirect()->route('login')
                ->with('error', 'Veuillez vous connecter.');
        }
        return $next($request);
    }
}
