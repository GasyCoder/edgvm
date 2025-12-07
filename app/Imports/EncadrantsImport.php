<?php

namespace App\Imports;

use App\Models\Encadrant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class EncadrantsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return DB::transaction(function () use ($row) {
            
            // Créer le compte utilisateur
            $user = User::create([
                'name' => $row['nom_complet'],
                'email' => $row['email'],
                'password' => Hash::make('password123'),
                'role' => 'encadrant',
                'active' => true,
            ]);

            // Créer l'encadrant
            return new Encadrant([
                'user_id' => $user->id,
                'grade' => $row['grade'],
                'specialite' => $row['specialite'] ?? null,
                'phone' => $row['telephone'] ?? null,
                'bureau' => $row['bureau'] ?? null,
            ]);
        });
    }

    /**
     * Règles de validation
     */
    public function rules(): array
    {
        return [
            'nom_complet' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'grade' => 'required|string|in:Professeur Titulaire,Maître de Conférences,Maître Assistant,Assistant,Docteur',
            'specialite' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'bureau' => 'nullable|string|max:100',
        ];
    }

    /**
     * Messages de validation personnalisés
     */
    public function customValidationMessages()
    {
        return [
            'nom_complet.required' => 'Le nom complet est obligatoire',
            'email.required' => 'L\'email est obligatoire',
            'email.email' => 'L\'email doit être une adresse email valide',
            'email.unique' => 'Cet email existe déjà dans le système',
            'grade.required' => 'Le grade est obligatoire',
            'grade.in' => 'Le grade doit être l\'un des suivants: Professeur Titulaire, Maître de Conférences, Maître Assistant, Assistant, Docteur',
        ];
    }
}