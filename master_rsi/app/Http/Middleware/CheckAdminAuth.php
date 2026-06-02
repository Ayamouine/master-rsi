<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('etudiant_id')) {
            return redirect()->route('login')
                ->with('error', 'Veuillez vous connecter.');
        }

        if (session('is_admin', false) !== true && !session()->has('admin_id')) {
            return redirect()->route('dashboard')
                ->with('error', 'Acces reserve a l administrateur.');
        }

        return $next($request);
    }
}

