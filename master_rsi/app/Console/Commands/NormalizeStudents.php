<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\Etudiant;

class NormalizeStudents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'normalize:students';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Normalise les comptes étudiants : login = nom, pass = bcrypt(CNE). Sauvegarde avant modification.';

    public function handle(): int
    {
        $this->info('Démarrage de la normalisation des étudiants...');

        // 1) sauvegarde
        $backupPath = storage_path('app/backup_etudiants_before_normalize.json');
        $all = Etudiant::all()->toArray();
        file_put_contents($backupPath, json_encode($all, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $this->info("Sauvegarde des étudiants écrite dans: {$backupPath}");

        // 2) lire le fichier master_rsi2020.txt
        $file = storage_path('app/master_rsi2020.txt');
        if (!file_exists($file)) {
            $this->error('Fichier master_rsi2020.txt introuvable : ' . $file);
            return 1;
        }

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $updated = 0;
        $created = 0;

        foreach ($lines as $line) {
            $parts = explode('|', trim($line));
            if (count($parts) < 3) continue;
            $cne = trim($parts[0]);
            $nom = trim($parts[1]);
            $prenom = trim($parts[2]);

            // Rechercher par nom ou par login égal au CNE
            $et = Etudiant::where('nom', $nom)->orWhere('login', $cne)->first();

            if ($et) {
                $et->login = $nom;
                $et->nom = $nom;
                $et->pass = Hash::make($cne);
                if (!$et->exists) {
                    $et->note1 = $et->note1 ?? 0;
                    $et->note2 = $et->note2 ?? 0;
                    $et->moyenne = $et->moyenne ?? 0;
                }
                $et->longitude = $et->longitude ?? 0.0;
                $et->latitude = $et->latitude ?? 0.0;
                $et->save();
                $updated++;
            } else {
                // créer un nouvel étudiant si absent
                $new = new Etudiant();
                $new->login = $nom;
                $new->nom = $nom;
                $new->pass = Hash::make($cne);
                $new->note1 = 0;
                $new->note2 = 0;
                $new->moyenne = 0;
                $new->longitude = 0.0;
                $new->latitude = 0.0;
                $new->save();
                $created++;
            }
        }

        $this->info("Normalisation terminée — mis à jour: {$updated}, créés: {$created}");
        $this->info('Si vous souhaitez revenir en arrière, restaurez le fichier: ' . $backupPath);
        return 0;
    }
}
