<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use Illuminate\Http\Request;

class EtudiantController extends Controller
{
    public function index()
    {
        $etudiants = Etudiant::with('user')->paginate(15);
        return response()->json($etudiants);
    }

    public function show($id)
    {
        $etudiant = Etudiant::findOrFail($id);
        return response()->json($etudiant);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'       => 'required|exists:users,id',
            'cne'           => 'required|unique:etudiants',
            'nom'           => 'required|string',
            'prenom'        => 'required|string',
            'filiere'       => 'required|string',
            'latitude'      => 'nullable|numeric',
            'longitude'     => 'nullable|numeric',
            'quiz_score'    => 'nullable|numeric',
            'quiz_validated' => 'nullable|boolean',
        ]);

        $etudiant = Etudiant::create($validated);
        return response()->json($etudiant, 201);
    }

    public function update(Request $request, $id)
    {
        $etudiant = Etudiant::findOrFail($id);

        $validated = $request->validate([
            'nom'            => 'string',
            'prenom'         => 'string',
            'filiere'        => 'string',
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',
            'quiz_score'     => 'nullable|numeric',
            'quiz_validated' => 'nullable|boolean',
        ]);

        $etudiant->update($validated);
        return response()->json($etudiant);
    }

    public function destroy($id)
    {
        $etudiant = Etudiant::findOrFail($id);
        $etudiant->delete();
        return response()->json(['message' => 'Étudiant supprimé']);
    }
}
