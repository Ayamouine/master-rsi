<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Etudiant;
use App\Models\Admin;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string|max:50',
            'password' => 'required|string',
        ], [
            'login.required'    => "L'identifiant est obligatoire.",
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        // ── 1. Vérifier d'abord si c'est un Admin ──────────────────────────
        $admin = Admin::where('login', $request->login)->first();

        if ($admin && Hash::check($request->password, $admin->pass)) {
            session([
                'etudiant_id'  => 'admin_' . $admin->id,  // préfixe pour distinguer
                'etudiant_nom' => $admin->nom,
                'etudiant_log' => $admin->login,
                'is_admin'     => true,                    // ← clé principale
                'admin_id'     => $admin->id,
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'Bienvenue, ' . $admin->nom . ' (Admin) !');
        }

        // ── 2. Sinon vérifier étudiant (accepte `login` OU `nom` comme identifiant)
        $ident = trim($request->login);

        $etudiant = Etudiant::where('login', $ident)
            ->orWhere('nom', $ident)
            ->first();

        // Si l'étudiant n'existe pas en BDD, tenter de le retrouver dans le fichier master_rsi2020.txt
        if (!$etudiant) {
            $file = storage_path('app/master_rsi2020.txt');
            if (file_exists($file)) {
                $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    $parts = explode('|', $line);
                    if (count($parts) >= 3) {
                        $cne = trim($parts[0]);
                        $nom = trim($parts[1]);
                        // correspondance par nom (insensible à la casse) ou par cne
                        if (strcasecmp($nom, $ident) === 0 || strcasecmp($cne, $ident) === 0) {
                            // si le mot de passe saisi est le CNE en clair, créer l'étudiant en base
                            if ($request->password === $cne) {
                                $etudiant = new Etudiant();
                                $etudiant->login = $nom;
                                $etudiant->nom = $nom;
                                $etudiant->pass = \Illuminate\Support\Facades\Hash::make($cne);
                                $etudiant->note1 = 0;
                                $etudiant->note2 = 0;
                                $etudiant->moyenne = 0;
                                $etudiant->longitude = 0.0;
                                $etudiant->latitude = 0.0;
                                $etudiant->save();

                                session([
                                    'etudiant_id'  => $etudiant->id,
                                    'etudiant_nom' => $etudiant->nom,
                                    'etudiant_log' => $etudiant->login,
                                    'is_admin'     => false,
                                ]);

                                return redirect()->route('dashboard')
                                    ->with('success', 'Bienvenue, ' . $etudiant->nom . ' ! (compte créé depuis fichier)');
                            }
                        }
                    }
                }
            }
        }

        if ($etudiant && Hash::check($request->password, $etudiant->pass)) {
            session([
                'etudiant_id'  => $etudiant->id,
                'etudiant_nom' => $etudiant->nom,
                'etudiant_log' => $etudiant->login,
                'is_admin'     => false,
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'Bienvenue, ' . $etudiant->nom . ' !');
        }

        // Si l'étudiant existe mais le hash ne correspond pas, tenter une correspondance
        // non-destructive avec le CNE présent dans le fichier master_rsi2020.txt
        if ($etudiant) {
            $file = storage_path('app/master_rsi2020.txt');
            if (file_exists($file)) {
                $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    $parts = explode('|', $line);
                    if (count($parts) >= 3) {
                        $cne = trim($parts[0]);
                        $nom = trim($parts[1]);

                        // correspondance par nom ou par login
                        if (strcasecmp($nom, $etudiant->nom) === 0 || strcasecmp($cne, $etudiant->login) === 0) {
                            // si le mot de passe saisi est le CNE en clair, on accepte
                            if ($request->password === $cne) {
                                // mettre à jour le hash en base et normaliser le login
                                $etudiant->pass = \Illuminate\Support\Facades\Hash::make($cne);
                                $etudiant->login = $etudiant->nom;
                                $etudiant->save();

                                session([
                                    'etudiant_id'  => $etudiant->id,
                                    'etudiant_nom' => $etudiant->nom,
                                    'etudiant_log' => $etudiant->login,
                                    'is_admin'     => false,
                                ]);

                                return redirect()->route('dashboard')
                                    ->with('success', 'Bienvenue, ' . $etudiant->nom . ' ! (compte normalisé)');
                            }
                        }
                    }
                }
            }
        }

        // ── 3. Échec ────────────────────────────────────────────────────────
        return back()
            ->withInput(['login' => $request->login])
            ->withErrors(['login' => 'Identifiant ou mot de passe incorrect.']);
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login')->with('success', 'Déconnexion réussie.');
    }
}
