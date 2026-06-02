<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EtudiantSeeder extends Seeder
{
    /**
     * Insère des étudiants de test dans la table 'etudiants'.
     *
     * Comptes disponibles après le seeder :
     * ┌────────────────┬─────────────┬──────────────┐
     * │  login         │  password   │  nom         │
     * ├────────────────┼─────────────┼──────────────┤
     * │  ahmed         │  ahmed123   │  Ahmed       │
     * │  sara          │  sara123    │  Sara        │
     * │  anouar        │  anouar123  │  Anouar      │
     * │  amine         │  amine123   │  Amine       │
     * │  badr          │  badr123    │  Badr        │
     * │  admin         │  admin2025  │  Administrateur │
     * └────────────────┴─────────────┴──────────────┘
     */
    public function run(): void
    {
        DB::table('etudiants')->truncate(); // Vider avant d'insérer

        $etudiants = [
            [
                'login'     => 'ahmed',
                'pass'      => Hash::make('ahmed123'),
                'nom'       => 'Ahmed',
                'note1'     => 12,
                'note2'     => 14,
                'moyenne'   => 13.0,
                'longitude' => -7.6192,
                'latitude'  => 33.0000,
            ],
            [
                'login'     => 'sara',
                'pass'      => Hash::make('sara123'),
                'nom'       => 'Sara',
                'note1'     => 15,
                'note2'     => 17,
                'moyenne'   => 16.0,
                'longitude' => -7.5893,
                'latitude'  => 33.0090,
            ],
            [
                'login'     => 'anouar',
                'pass'      => Hash::make('anouar123'),
                'nom'       => 'Anouar',
                'note1'     => 10,
                'note2'     => 13,
                'moyenne'   => 11.5,
                'longitude' => -7.6350,
                'latitude'  => 32.9950,
            ],
            [
                'login'     => 'amine',
                'pass'      => Hash::make('amine123'),
                'nom'       => 'Amine',
                'note1'     => 16,
                'note2'     => 18,
                'moyenne'   => 17.0,
                'longitude' => -7.6080,
                'latitude'  => 33.0150,
            ],
            [
                'login'     => 'badr',
                'pass'      => Hash::make('badr123'),
                'nom'       => 'Badr',
                'note1'     => 14,
                'note2'     => 19,
                'moyenne'   => 16.5,
                'longitude' => -7.6020,
                'latitude'  => 33.0200,
            ],
           
        ];

        DB::table('etudiants')->insert($etudiants);

        $this->command->info('✅  6 étudiants insérés avec succès !');
        $this->command->table(
            ['Login', 'Mot de passe', 'Nom'],
            collect($etudiants)->map(fn($e) => [
                $e['login'],
                // Afficher le mot de passe en clair uniquement ici pour le dev
                match($e['login']) {
                    'ahmed'  => 'ahmed123',
                    'sara'   => 'sara123',
                    'anouar' => 'anouar123',
                    'amine'  => 'amine123',
                    'badr'   => 'badr123',
                    'admin'  => 'admin2025',
                    default  => '???',
                },
                $e['nom'],
            ])->toArray()
        );
    }
}
