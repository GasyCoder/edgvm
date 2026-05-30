<?php

namespace Database\Seeders;

use App\Models\Doctorant;
use App\Models\Encadrant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the user seeds.
     */
    public function run(): void
    {
        // --- Super administrateur ---
        $admin = User::create([
            'name' => 'Administrateur EDGVM',
            'email' => 'admin@edgvm.mg',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        // --- Direction ---
        User::create([
            'name' => 'Direction EDGVM',
            'email' => 'direction@edgvm.mg',
            'password' => Hash::make('password'),
            'role' => 'direction',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        // --- Secrétariat ---
        User::create([
            'name' => 'Secrétariat EDGVM',
            'email' => 'secretaire@edgvm.mg',
            'password' => Hash::make('password'),
            'role' => 'secretariat',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        // --- Responsable communication ---
        User::create([
            'name' => 'Communication EDGVM',
            'email' => 'communication@edgvm.mg',
            'password' => Hash::make('password'),
            'role' => 'communication',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        // --- Encadrant ---
        $encadrantUser = User::create([
            'name' => 'Aldo',
            'email' => 'encadrant@edgvm.mg',
            'password' => Hash::make('password'),
            'role' => 'encadrant',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        Encadrant::create([
            'nom' => 'RAKOTOARIMANANA',
            'prenoms' => 'Aldo',
            'email' => $encadrantUser->email,
            'genre' => 'homme',
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
