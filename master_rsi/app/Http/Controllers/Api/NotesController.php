<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use Illuminate\Http\Request;

class NotesController extends Controller
{
    public function index()
    {
        $notes = Etudiant::select('id', 'nom', 'prenom', 'cne', 'quiz_score')
                         ->paginate(15);
        return response()->json($notes);
    }

    public function store(Request $request)
    {
        // Admin crée une nouvelle notation
        $validated = $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'note'        => 'required|numeric|min:0|max:20',
            'projet'      => 'required|string',
        ]);

        // Sauvegarder dans une table notes (à créer si nécessaire)
        return response()->json([
            'message' => 'Note enregistrée',
            'note'    => $validated,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $etudiant = Etudiant::findOrFail($id);

        $validated = $request->validate([
            'quiz_score' => 'nullable|numeric|min:0|max:100',
        ]);

        $etudiant->update($validated);
        return response()->json($etudiant);
    }

    public function destroy($id)
    {
        // Supprimer une note
        return response()->json(['message' => 'Note supprimée']);
    }
}
