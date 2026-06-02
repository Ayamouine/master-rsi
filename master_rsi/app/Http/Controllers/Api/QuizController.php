<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        // Retourner les questions du quiz
        return response()->json([
            'quiz' => [
                [
                    'id'       => 1,
                    'question' => 'Question 1?',
                    'options'  => ['A', 'B', 'C', 'D'],
                    'correct'  => 'A',
                ],
                // Ajouter plus de questions
            ],
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'id'       => $id,
            'question' => 'Question?',
            'options'  => ['A', 'B', 'C', 'D'],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'answers'     => 'required|array',
        ]);

        // Calculer le score
        $score = $this->calculateScore($validated['answers']);

        // Mettre à jour l'étudiant
        $etudiant = Etudiant::find($validated['etudiant_id']);
        $etudiant->update([
            'quiz_score'     => $score,
            'quiz_validated' => $score >= 60, // 60% pour valider
        ]);

        return response()->json([
            'score'    => $score,
            'validated' => $score >= 60,
            'message'  => $score >= 60 ? 'Quiz réussi!' : 'Quiz non réussi',
        ]);
    }

    public function update(Request $request, $id)
    {
        return response()->json(['message' => 'Quiz mis à jour']);
    }

    private function calculateScore()
    {
        // Mock calculation - à implémenter correctement
        return rand(40, 100);
    }
}
