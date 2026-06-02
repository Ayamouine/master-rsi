<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\Etudiant;
use App\Models\Admin;
use App\Models\Image;

class DashboardController extends Controller
{
    // ── Helpers ──────────────────────────────────────────────────────────────

    private function checkAuth()
    {
        if (!session('etudiant_id')) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }
        return null;
    }

    private function isAdmin(): bool
    {
        return session('is_admin', false) === true || session()->has('admin_id');
    }

    private function requireAdmin()
    {
        if (!$this->isAdmin()) {
            return redirect()->route('projets.index')
                ->with('error', 'Accès réservé à l\'administrateur.');
        }
        return null;
    }

    // ── Accueil ───────────────────────────────────────────────────────────────

    public function index()
    {
        if ($r = $this->checkAuth()) return $r;

        if ($this->isAdmin()) {
            $admin    = Admin::find(session('admin_id'));
            $etudiant = (object)[
                'nom'     => $admin->nom,
                'login'   => $admin->login,
                'note1'   => null,
                'note2'   => null,
                'moyenne' => null,
            ];
        } else {
            $etudiant = Etudiant::find(session('etudiant_id'));
        }

        return view('dashboard.home', compact('etudiant'));
    }

    // ── About ─────────────────────────────────────────────────────────────────

    public function about()
    {
        if ($r = $this->checkAuth()) return $r;

        $etudiant = $this->getAboutProfile();

        return view('dashboard.about', compact('etudiant'));
    }

    public function editAbout()
    {
        if ($r = $this->checkAuth()) return $r;

        return redirect()->route('about');
    }

    public function updateAbout(Request $request)
    {
        if ($r = $this->checkAuth()) return $r;

        $request->validate([
            'nom'      => 'required|string|max:80',
            'filiere'  => 'required|in:RSI,GI,IMI,SITD,IRISTI',
            'bio'      => 'nullable|string|max:1000',
            'phone'    => 'nullable|string|max:30',
            'email'    => 'nullable|email|max:120',
            'city'     => 'nullable|string|max:80',
            'linkedin' => 'nullable|string|max:160',
            'github'   => 'nullable|string|max:160',
            'profile_skills' => 'nullable|json',
            'profile_interests' => 'nullable|json',
            'profile_experience' => 'nullable|json',
        ]);

        $etudiant = $this->currentProfileModel();

        if (!$etudiant) {
            return redirect()->route('about')->with('error', 'Profil introuvable.');
        }

        $etudiant->nom = $request->nom;
        // only set filiere if the underlying table has that column
        try {
            if (Schema::hasColumn($etudiant->getTable(), 'filiere')) {
                $etudiant->filiere = $request->filiere;
            }
        } catch (\Throwable $e) {
            // ignore if schema cannot be checked (fallback)
        }
        $etudiant->bio = $request->bio;
        $etudiant->phone = $request->phone;
        $etudiant->email = $request->email;
        $etudiant->city = $request->city;
        $etudiant->linkedin = $request->linkedin;
        $etudiant->github = $request->github;
        if ($request->has('profile_skills')) {
            $decoded = null;
            try {
                $decoded = json_decode($request->profile_skills, true);
            } catch (\Throwable $e) { $decoded = null; }
            $etudiant->profile_skills = $decoded ?: null;
        }
        if ($request->has('profile_interests')) {
            $decoded = null;
            try {
                $decoded = json_decode($request->profile_interests, true);
            } catch (\Throwable $e) { $decoded = null; }
            $etudiant->profile_interests = $decoded ?: null;
        }
        if ($request->has('profile_experience')) {
            $decoded = null;
            try {
                $decoded = json_decode($request->profile_experience, true);
            } catch (\Throwable $e) { $decoded = null; }
            $etudiant->profile_experience = $decoded ?: null;
        }
        $etudiant->save();

        return redirect()->route('about')->with('success', 'Profil mis à jour. Votre CV a été régénéré automatiquement.');
    }

    // ── Projets ───────────────────────────────────────────────────────────────

    public function projets()
    {
        if ($r = $this->checkAuth()) return $r;
        return view('dashboard.projets.index');
    }

    public function matrices()
    {
        if ($r = $this->checkAuth()) return $r;
        return view('dashboard.projets.matrices');
    }

    // ── Projet 2 – Fichiers ───────────────────────────────────────────────────

    public function fichiers()
    {
        if ($r = $this->checkAuth()) return $r;
        $students = $this->readStudentsFromFile();
        $isAdmin  = $this->isAdmin();
        return view('dashboard.projets.fichiers', compact('students', 'isAdmin'));
    }

    public function saveFichier(Request $request)
    {
        if ($r = $this->checkAuth()) return $r;

        $request->validate([
            'cne'       => 'required|string|max:20',
            'nom'       => 'required|string|max:50',
            'prenom'    => 'required|string|max:50',
            'filiere'   => 'required|in:RSI,GI,IMI,SITD,IRISTI',
            'semestre'  => 'required|in:1,2',
            'module1'   => 'required|numeric|min:0|max:20',
            'module2'   => 'required|numeric|min:0|max:20',
            'module3'   => 'required|numeric|min:0|max:20',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // 1. Fichier TXT
        $line = implode('|', [
            $request->cne,
            $request->nom,
            $request->prenom,
            $request->module1,
            $request->module2,
            $request->module3,
        ]) . "\n";

        file_put_contents(
            storage_path('app/master_rsi2020.txt'),
            $line,
            FILE_APPEND | LOCK_EX
        );

        // 2. Moyenne du semestre
        $moyenneSem = round(
            ($request->module1 + $request->module2 + $request->module3) / 3,
            2
        );

        // 3. Récupérer ou créer l'étudiant
        $etudiant = Etudiant::firstOrNew(['login' => $request->nom]);

        $etudiant->nom     = $request->nom;
        $etudiant->filiere = $request->filiere;

        if ($request->latitude)  $etudiant->latitude  = $request->latitude;
        if ($request->longitude) $etudiant->longitude = $request->longitude;

        if (!$etudiant->exists) {
            $etudiant->pass      = bcrypt($request->cne);
            $etudiant->note1     = 0;
            $etudiant->note2     = 0;
            $etudiant->moyenne   = 0;
            $etudiant->longitude = 0.0;
            $etudiant->latitude  = 0.0;
        }

        // 4. Stocker dans note1 ou note2
        if ($request->semestre == '1') {
            $etudiant->note1 = $moyenneSem;
        } else {
            $etudiant->note2 = $moyenneSem;
        }

        // 5. Recalculer la moyenne générale
        $n1 = (float)($etudiant->note1 ?? 0);
        $n2 = (float)($etudiant->note2 ?? 0);

        if ($n1 > 0 && $n2 > 0) {
            $etudiant->moyenne = round(($n1 + $n2) / 2, 2);
        } elseif ($n1 > 0) {
            $etudiant->moyenne = $n1;
        } else {
            $etudiant->moyenne = $n2;
        }

        $etudiant->save();

        return redirect()->route('projets.fichiers')
            ->with('success', "Étudiant enregistré — Semestre {$request->semestre} : {$moyenneSem}/20");
    }

    public function editFichier(string $cne)
    {
        if ($r = $this->checkAuth()) return $r;
        if ($r = $this->requireAdmin()) return $r;

        $students = $this->readStudentsFromFile();
        $student  = collect($students)->firstWhere('cne', $cne);

        if (!$student) {
            return redirect()->route('projets.fichiers')->with('error', 'Étudiant introuvable.');
        }

        $isAdmin = true;
        return view('dashboard.projets.fichiers', compact('students', 'student', 'isAdmin'));
    }

    public function updateFichier(Request $request, string $cne)
    {
        if ($r = $this->checkAuth()) return $r;
        if ($r = $this->requireAdmin()) return $r;

        $request->validate([
            'nom'      => 'required|string|max:50',
            'prenom'   => 'required|string|max:50',
            'semestre' => 'required|in:1,2',
            'module1'  => 'required|numeric|min:0|max:20',
            'module2'  => 'required|numeric|min:0|max:20',
            'module3'  => 'required|numeric|min:0|max:20',
        ]);

        // Mise à jour fichier TXT
        $file    = storage_path('app/master_rsi2020.txt');
        $lines   = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
        $updated = false;

        $newLines = array_map(function ($line) use ($cne, $request, &$updated) {
            $parts = explode('|', $line);
            if (count($parts) === 6 && $parts[0] === $cne) {
                $updated = true;
                return implode('|', [
                    $cne,
                    $request->nom,
                    $request->prenom,
                    $request->module1,
                    $request->module2,
                    $request->module3,
                ]);
            }
            return $line;
        }, $lines);

        if ($updated) {
            file_put_contents($file, implode("\n", $newLines) . "\n", LOCK_EX);
        } else {
            return redirect()->route('projets.fichiers')
                ->with('error', 'L’étudiant à modifier n’a pas été trouvé dans le fichier.');
        }

        // Mise à jour BDD
        $moyenneSem = round(
            ($request->module1 + $request->module2 + $request->module3) / 3,
            2
        );

        $etudiant = Etudiant::where('login', $request->nom)->first();
        if ($etudiant) {
            if ($request->semestre == '1') {
                $etudiant->note1 = $moyenneSem;
            } else {
                $etudiant->note2 = $moyenneSem;
            }

            $n1 = (float)($etudiant->note1 ?? 0);
            $n2 = (float)($etudiant->note2 ?? 0);

            if ($n1 > 0 && $n2 > 0) {
                $etudiant->moyenne = round(($n1 + $n2) / 2, 2);
            } elseif ($n1 > 0) {
                $etudiant->moyenne = $n1;
            } else {
                $etudiant->moyenne = $n2;
            }

            $etudiant->save();
        }

        return redirect()->route('projets.fichiers')
            ->with('success', "Étudiant modifié — Semestre {$request->semestre} : {$moyenneSem}/20");
    }

    // ── Projet 3 – Images ─────────────────────────────────────────────────────

    public function images()
    {
        if ($r = $this->checkAuth()) return $r;
        $images = Image::all();
        return view('dashboard.projets.images', compact('images'));
    }

    public function uploadImage(Request $request)
    {
        if ($r = $this->checkAuth()) return $r;

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $file = $request->file('image');
        $safeName = mb_substr($file->getClientOriginalName(), 0, 120);

        Image::create([
            'name'    => $safeName,
            'type'    => $file->getMimeType(),
            'size'    => $file->getSize(),
            'bin_img' => file_get_contents($file->getRealPath()),
        ]);

        return redirect()->route('projets.images')
            ->with('success', 'Image insérée avec succès.');
    }

    // ── Projet 4 – Quiz ───────────────────────────────────────────────────────


    public function quiz()
    {
        if ($r = $this->checkAuth()) return $r;
        $etudiant = $this->isAdmin() ? null : Etudiant::find(session('etudiant_id'));
        return view('dashboard.projets.quiz', compact('etudiant'));
    }

    public function saveQuiz(Request $request)
    {
        if (!session('etudiant_id')) {
            return response()->json(['success' => false, 'message' => 'Non authentifié'], 401);
        }

        if ($this->isAdmin()) {
            return response()->json(['success' => true, 'note' => 0, 'message' => 'Admin — pas de sauvegarde.']);
        }

        // ✅ CORRECTION : accepter quiz1 et quiz2 (score 0-3), pas note1/note2
        $request->validate([
            'note_field' => 'required|in:quiz1,quiz2',
            'score'      => 'required|integer|min:0|max:3',
        ]);

        $etudiant = Etudiant::find(session('etudiant_id'));
        if (!$etudiant) {
            return response()->json(['success' => false, 'message' => 'Étudiant introuvable'], 404);
        }

        $field = $request->note_field; // 'quiz1' ou 'quiz2'
        $etudiant->$field = $request->score;
        $etudiant->save();

        return response()->json(['success' => true, 'note' => $request->score]);
    }

    // ── Projet 5 – Stats ──────────────────────────────────────────────────────

    public function stats()
    {
        if ($r = $this->checkAuth()) return $r;
        if ($r = $this->requireAdmin()) return $r;
        $etudiants = Etudiant::select('nom', 'filiere', 'note1', 'note2', 'moyenne')->get();
        return view('dashboard.projets.stats', compact('etudiants'));
    }

    // ── Projet 6 – Géoloc ─────────────────────────────────────────────────────

    public function geoloc()
    {
        if ($r = $this->checkAuth()) return $r;
        if ($r = $this->requireAdmin()) return $r;
        $etudiants = Etudiant::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select('nom', 'latitude', 'longitude')
            ->get();
        return view('dashboard.projets.geoloc', compact('etudiants'));
    }

    // ── Notes BDD (admin) ─────────────────────────────────────────────────────

    public function notes()
    {
        if ($r = $this->checkAuth()) return $r;
        if ($r = $this->requireAdmin()) return $r;
        $etudiants = Etudiant::all();
        return view('dashboard.projets.notes', compact('etudiants'));
    }

    public function editNote(int $id)
    {
        if ($r = $this->checkAuth()) return $r;
        if ($r = $this->requireAdmin()) return $r;
        $etudiant  = Etudiant::findOrFail($id);
        $etudiants = Etudiant::all();
        return view('dashboard.projets.notes', compact('etudiants', 'etudiant'));
    }

    public function updateNote(Request $request, int $id)
    {
        if ($r = $this->checkAuth()) return $r;
        if ($r = $this->requireAdmin()) return $r;

        $request->validate([
            'note1'   => 'required|numeric|min:0|max:20',
            'note2'   => 'required|numeric|min:0|max:20',
            'moyenne' => 'required|numeric|min:0|max:20',
        ]);

        $etudiant          = Etudiant::findOrFail($id);
        $etudiant->note1   = $request->note1;
        $etudiant->note2   = $request->note2;
        $etudiant->moyenne = $request->moyenne;
        $etudiant->save();

        return redirect()->route('projets.notes')
            ->with('success', "Notes de {$etudiant->nom} mises à jour.");
    }

    // ── Géocodage ─────────────────────────────────────────────────────────────

    public function geocodeAddress(Request $request)
    {
        if ($r = $this->checkAuth()) return $r;
        if ($r = $this->requireAdmin()) return $r;

        $request->validate(['address' => 'required|string']);

        $address  = urlencode($request->address);
        $url      = "https://nominatim.openstreetmap.org/search?q={$address}&format=json&limit=1";
        $opts     = ['http' => ['header' => "User-Agent: RSI-App/1.0\r\n"]];
        $context  = stream_context_create($opts);
        $response = @file_get_contents($url, false, $context);

        if (!$response) {
            return response()->json(['success' => false, 'message' => 'Erreur réseau'], 500);
        }

        $data = json_decode($response, true);

        if (empty($data)) {
            return response()->json(['success' => false, 'message' => 'Adresse introuvable']);
        }

        return response()->json([
            'success'   => true,
            'latitude'  => $data[0]['lat'],
            'longitude' => $data[0]['lon'],
            'display'   => $data[0]['display_name'],
        ]);
    }

    // ── Utilitaire fichier ────────────────────────────────────────────────────

    private function readStudentsFromFile(): array
    {
        $students = [];
        $file     = storage_path('app/master_rsi2020.txt');

        if (file_exists($file)) {
            $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $parts = explode('|', $line);
                if (count($parts) === 6) {
                    $students[] = [
                        'cne'     => $parts[0],
                        'nom'     => $parts[1],
                        'prenom'  => $parts[2],
                        'module1' => $parts[3],
                        'module2' => $parts[4],
                        'module3' => $parts[5],
                    ];
                }
            }
        }
        return $students;
    }

    private function currentProfileModel()
    {
        if ($this->isAdmin()) {
            return Admin::find(session('admin_id'));
        }

        return Etudiant::find(session('etudiant_id'));
    }

    private function getAboutProfile()
    {
        $profile = $this->currentProfileModel();

        if ($this->isAdmin()) {
            $profile = (object) array_merge((array) $profile, [
                'filiere'  => $profile->filiere ?? 'RSI',
                'bio'      => $profile->bio ?? 'Administrateur Master RSI.',
                'phone'    => $profile->phone ?? '+212 600-000-000',
                'email'    => $profile->email ?? ($profile->login . '@fsts.ac.ma'),
                'city'     => $profile->city ?? 'Settat',
                'linkedin' => $profile->linkedin ?? 'fst-settat',
                'github'   => $profile->github ?? 'fst-settat',
            ]);
        }

        if ($profile && !$profile->bio) {
            $profile->bio = 'Profil à compléter. Utilisez le formulaire pour générer votre CV automatiquement.';
        }

        // Determine filiere for defaults
        $filiere = $profile->filiere ?? 'RSI';

        // Default skill sets by filiere
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

        // Use stored JSON if present, otherwise defaults
        // ✅ APRÈS (corrigé)
// profile_skills est déjà un array grâce au $casts du Model
$profileSkills = is_array($profile->profile_skills)
    ? $profile->profile_skills
    : (is_string($profile->profile_skills) ? json_decode($profile->profile_skills, true) : null);

$profileInterests = is_array($profile->profile_interests)
    ? $profile->profile_interests
    : (is_string($profile->profile_interests) ? json_decode($profile->profile_interests, true) : null);

$profileExperience = is_array($profile->profile_experience)
    ? $profile->profile_experience
    : (is_string($profile->profile_experience) ? json_decode($profile->profile_experience, true) : null);

        $profile->skills = $profileSkills ?? ($defaultsSkills[$filiere] ?? []);
        $profile->interests = $profileInterests ?? ($defaultsInterests[$filiere] ?? []);
        $profile->experience = $profileExperience ?? ($defaultsExperience[$filiere] ?? []);

        return $profile;
    }
}
