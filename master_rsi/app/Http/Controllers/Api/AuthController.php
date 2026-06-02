<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;
use App\Models\Etudiant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login - générer un token Sanctum
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants sont invalides.'],
            ]);
        }

        // Déterminer le type d'utilisateur
        $type = 'student';
        $role = null;

        if (Admin::where('user_id', $user->id)->exists()) {
            $type = 'admin';
            $role = Admin::where('user_id', $user->id)->first()?->role;
        } elseif (Etudiant::where('user_id', $user->id)->exists()) {
            $type = 'student';
        }

        return response()->json([
            'message' => 'Authentification réussie',
            'token'   => $user->createToken('auth-token')->plainTextToken,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'type'  => $type,
                'role'  => $role,
            ],
        ]);
    }

    /**
     * Register - créer un nouvel utilisateur
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'type'     => 'required|in:student,admin',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Créer l'entrée correspondante selon le type
        if ($validated['type'] === 'admin') {
            Admin::create([
                'user_id' => $user->id,
                'role'    => 'teacher',
            ]);
        } else {
            Etudiant::create([
                'user_id' => $user->id,
                'cne'     => 'CNE-' . uniqid(),
            ]);
        }

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'user'    => $user,
            'token'   => $user->createToken('auth-token')->plainTextToken,
        ], 201);
    }

    /**
     * Logout - révoquer le token
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnecté avec succès']);
    }

    /**
     * Get current user
     */
    public function user(Request $request)
    {
        $user = $request->user();
        $type = 'student';
        $role = null;

        if (Admin::where('user_id', $user->id)->exists()) {
            $type = 'admin';
            $role = Admin::where('user_id', $user->id)->first()?->role;
        }

        return response()->json([
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'type'  => $type,
            'role'  => $role,
        ]);
    }
}
