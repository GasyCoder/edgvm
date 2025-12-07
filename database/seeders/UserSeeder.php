<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctorant;
use App\Models\Encadrant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the user seeds.
     */
    public function run(): void
    {
        // --- Admin ---
        $admin = User::create([
            'name' => 'Administrateur EDGVM',
            'email' => 'admin@edgvm.mg',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        // --- Secrétaire ---
        User::create([
            'name' => 'Secrétaire EDGVM',
            'email' => 'secretaire@edgvm.mg',
            'password' => Hash::make('password'),
            'role' => 'secrétaire',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        // --- Encadrant ---
        $encadrantUser = User::create([
            'name' => 'Prof. Van Aldo RABEHAVANA',
            'email' => 'encadrant@edgvm.mg',
            'password' => Hash::make('password'),
            'role' => 'encadrant',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        Encadrant::create([
            'user_id' => $encadrantUser->id,
            'grade' => 'Professeur Titulaire',
            'specialite' => 'Gestion et Management',
            'phone' => '+261 34 00 000 01',
            'bureau' => 'Bureau 101',
        ]);

        // --- Doctorant ---
        $doctorantUser = User::create([
            'name' => 'Florent Doctorant',
            'email' => 'doctorant@edgvm.mg',
            'password' => Hash::make('password'),
            'role' => 'doctorant',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        Doctorant::create([
            'user_id' => $doctorantUser->id,
            'matricule' => 'D2024001',
            'niveau' => 'D1',
            'phone' => '+261 34 00 000 02',
            'adresse' => 'Mahajanga',
            'date_naissance' => '1995-05-15',
            'lieu_naissance' => 'Antananarivo',
            'date_inscription' => now(),
            'statut' => 'actif',
        ]);
    }
}
