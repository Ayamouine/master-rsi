<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Comptes admin disponibles :
     * ┌──────────┬─────────────┬───────────────┐
     * │  login   │  password   │  nom          │
     * ├──────────┼─────────────┼───────────────┤
     * │  admin   │  admin2025  │  Administrateur│
     * └──────────┴─────────────┴───────────────┘
     */
    public function run(): void
    {
        DB::table('admins')->truncate();

        DB::table('admins')->insert([
            [
                'login' => 'admin',
                'pass'  => Hash::make('admin2025'),
                'nom'   => 'Administrateur',
            ],
        ]);

        $this->command->info('✅  Admin inséré avec succès !');
    }
}
