<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Image;
use App\Models\Admin;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Bienvenue au Dashboard',
            'stats'   => $this->getStats(),
        ]);
    }

    public function stats()
    {
        return response()->json($this->getStats());
    }

    public function about(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Non authentifié'], 401);
        }

        // Defaults by filiere
        $defaultsSkills = [
            'RSI' => [['Réseaux', 88, '#22d3ee', '#0ea5e9'], ['Linux', 84, '#f59e0b', '#fbbf24'], ['Laravel', 76, '#8b5cf6', '#c4b5fd'], ['Sécurité', 78, '#f472b6', '#fb7185']],
            'GI' => [['Gestion SI', 86, '#22d3ee', '#38bdf8'], ['UML', 80, '#f59e0b', '#fbbf24'], ['SQL', 77, '#8b5cf6', '#a78bfa'], ['Projet', 82, '#f472b6', '#fb7185']],
            'IMI' => [['Python', 90, '#22d3ee', '#0ea5e9'], ['Data', 84, '#f59e0b', '#fbbf24'], ['IA', 79, '#8b5cf6', '#c4b5fd'], ['Statistiques', 88, '#f472b6', '#fb7185']],
            'SITD' => [['Frontend', 88, '#22d3ee', '#38bdf8'], ['Backend', 82, '#f59e0b', '#fbbf24'], ['Cloud', 74, '#8b5cf6', '#a78bfa'], ['DevOps', 70, '#f472b6', '#fb7185']],
            'IRISTI' => [['Réseaux', 89, '#22d3ee', '#38bdf8'], ['Crypto', 81, '#f59e0b', '#fbbf24'], ['SOC', 76, '#8b5cf6', '#c4b5fd'], ['Audit', 83, '#f472b6', '#fb7185']],
        ];

        $defaultsInterests = [
            'RSI' => ['Réseaux','Linux','Cyber','Web','Labs'],
            'GI' => ['Management','Innovation','Business','Data','Process'],
            'IMI' => ['Data','IA','Viz','Research','Python'],
            'SITD' => ['UI','Cloud','Web App','Docker','API'],
            'IRISTI' => ['Security','CTF','SOC','Audit','Threat Intel'],
        ];

        $defaultsExperience = [
            'RSI' => [
                ['Projet académique', 'Dashboard Master RSI', '2025-2026', 'Application web complète avec auth, statistiques, géolocalisation et gestion des étudiants.', ['Laravel','MySQL','Chart.js'], 'cyan'],
                ['Stage', 'Administration système', 'Été 2024', 'Configuration réseau, surveillance des services et durcissement des postes de travail.', ['Cisco','Linux','Wireshark'], 'pink'],
            ],
            'GI' => [
                ['Projet académique', 'ERP simplifié', '2025-2026', 'Conception d’un module de pilotage pour la gestion interne et les tableaux de bord.', ['UML','SQL','PHP'], 'cyan'],
                ['Stage', 'Audit SI', 'Été 2024', 'Analyse des processus métier et propositions d’amélioration digitale.', ['ITIL','Power BI','Office'], 'pink'],
            ],
            'IMI' => [
                ['Projet académique', 'Classification IA', '2025-2026', 'Modèle de machine learning appliqué à des jeux de données réels.', ['Python','Pandas','Sklearn'], 'cyan'],
                ['Stage', 'Data Analyst', 'Été 2024', 'Dashboards et analyses statistiques pour la prise de décision.', ['Power BI','SQL','Python'], 'pink'],
            ],
            'SITD' => [
                ['Projet académique', 'Plateforme web', '2025-2026', 'Stack moderne frontend/backend avec interface interactive et responsive.', ['React','Node.js','Docker'], 'cyan'],
                ['Stage', 'Full stack', 'Été 2024', 'Développement d’applications métier et optimisation UI/UX.', ['API','UI','Git'], 'pink'],
            ],
            'IRISTI' => [
                ['Projet académique', 'SOC simulé', '2025-2026', 'Détection d’intrusions et analyse d’événements de sécurité.', ['Snort','Splunk','Linux'], 'cyan'],
                ['Stage', 'Sécurité réseau', 'Été 2024', 'Audit, remédiation et tests de pénétration contrôlés.', ['Kali','Nessus','Crypto'], 'pink'],
            ],
        ];

        // Priorité à l'admin
        $admin = Admin::where('user_id', $user->id)->first();
        if ($admin) {
            $filiere = $admin->filiere ?? 'RSI';
            if (is_array($admin->profile_skills)) {
                $skills = $admin->profile_skills;
            } elseif (is_string($admin->profile_skills)) {
                $skills = json_decode($admin->profile_skills, true) ?: ($defaultsSkills[$filiere] ?? []);
            } else {
                $skills = $defaultsSkills[$filiere] ?? [];
            }

            if (is_array($admin->profile_interests)) {
                $interests = $admin->profile_interests;
            } elseif (is_string($admin->profile_interests)) {
                $interests = json_decode($admin->profile_interests, true) ?: ($defaultsInterests[$filiere] ?? []);
            } else {
                $interests = $defaultsInterests[$filiere] ?? [];
            }

            if (is_array($admin->profile_experience)) {
                $experience = $admin->profile_experience;
            } elseif (is_string($admin->profile_experience)) {
                $experience = json_decode($admin->profile_experience, true) ?: ($defaultsExperience[$filiere] ?? []);
            } else {
                $experience = $defaultsExperience[$filiere] ?? [];
            }

            return response()->json([
                'nom'      => $admin->nom ?? $user->name,
                'filiere'  => $filiere,
                'bio'      => $admin->bio ?? 'Administrateur Master RSI.',
                'phone'    => $admin->phone ?? '+212 600-000-000',
                'email'    => $admin->email ?? $user->email,
                'city'     => $admin->city ?? 'Settat',
                'linkedin' => $admin->linkedin ?? 'fst-settat',
                'github'   => $admin->github ?? 'fst-settat',
                'skills'   => $skills,
                'interests'=> $interests,
                'experience'=> $experience,
            ]);
        }

        $etudiant = Etudiant::where('user_id', $user->id)->first();
        if ($etudiant) {
            $filiere = $etudiant->filiere ?? null;
            if (is_array($etudiant->profile_skills)) {
                $skills = $etudiant->profile_skills;
            } elseif (is_string($etudiant->profile_skills)) {
                $skills = json_decode($etudiant->profile_skills, true) ?: ($defaultsSkills[$filiere] ?? []);
            } else {
                $skills = $defaultsSkills[$filiere] ?? [];
            }

            if (is_array($etudiant->profile_interests)) {
                $interests = $etudiant->profile_interests;
            } elseif (is_string($etudiant->profile_interests)) {
                $interests = json_decode($etudiant->profile_interests, true) ?: ($defaultsInterests[$filiere] ?? []);
            } else {
                $interests = $defaultsInterests[$filiere] ?? [];
            }

            if (is_array($etudiant->profile_experience)) {
                $experience = $etudiant->profile_experience;
            } elseif (is_string($etudiant->profile_experience)) {
                $experience = json_decode($etudiant->profile_experience, true) ?: ($defaultsExperience[$filiere] ?? []);
            } else {
                $experience = $defaultsExperience[$filiere] ?? [];
            }

            return response()->json([
                'nom'      => $etudiant->nom ?? $user->name,
                'filiere'  => $filiere,
                'bio'      => $etudiant->bio ?? 'Profil à compléter. Utilisez le formulaire pour générer votre CV automatiquement.',
                'phone'    => $etudiant->phone ?? null,
                'email'    => $etudiant->email ?? $user->email,
                'city'     => $etudiant->city ?? null,
                'linkedin' => $etudiant->linkedin ?? null,
                'github'   => $etudiant->github ?? null,
                'skills'   => $skills,
                'interests'=> $interests,
                'experience'=> $experience,
            ]);
        }

        // Fallback: info de l'application
        return response()->json([
            'name'    => 'Master RSI',
            'version' => '2.0.0',
            'description' => 'Plateforme de gestion des projets RSI',
        ]);
    }

    public function matrices()
    {
        return response()->json([
            'matrices' => [
                'projet_1' => ['matrices' => []],
                'projet_2' => ['matrices' => []],
                'projet_3' => ['matrices' => []],
            ],
        ]);
    }

    public function fichiers()
    {
        $fichiers = Etudiant::with('user')->paginate(10);
        return response()->json($fichiers);
    }

    public function showFichier($cne)
    {
        $etudiant = Etudiant::where('cne', $cne)->firstOrFail();
        return response()->json($etudiant);
    }

    public function saveFichier(Request $request)
    {
        $validated = $request->validate([
            'cne'       => 'required|unique:etudiants',
            'nom'       => 'required|string',
            'prenom'    => 'required|string',
            'filiere'   => 'required|string',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $etudiant = Etudiant::create($validated);
        return response()->json($etudiant, 201);
    }

    public function updateFichier(Request $request, $cne)
    {
        $etudiant = Etudiant::where('cne', $cne)->firstOrFail();

        $validated = $request->validate([
            'nom'       => 'string',
            'prenom'    => 'string',
            'filiere'   => 'string',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $etudiant->update($validated);
        return response()->json($etudiant);
    }

    public function geocodeAddress(Request $request)
    {
        // Mock geocoding - à intégrer avec une API réelle
        return response()->json([
            'latitude'  => 32.8872,
            'longitude' => -5.5898,
        ]);
    }

    public function geoloc()
    {
        $etudiants = Etudiant::whereNotNull('latitude')
                            ->whereNotNull('longitude')
                            ->get(['id', 'nom', 'prenom', 'latitude', 'longitude']);

        return response()->json([
            'locations' => $etudiants,
        ]);
    }

    private function getStats()
    {
        return [
            'etudiants' => Etudiant::count(),
            'images'    => Image::count(),
            'projets'   => 6,
        ];
    }
}
