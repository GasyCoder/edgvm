<?php

namespace App\Imports;

use App\Models\Encadrant;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EncadrantsImport implements SkipsOnFailure, ToModel, WithHeadingRow, WithValidation
{
    use SkipsFailures;

    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Encadrant([
            'nom' => $row['nom'],
            'prenoms' => $row['prenoms'] ?? null,
            'email' => $row['email'],
            'genre' => $row['genre'] ?? 'homme',
            'grade' => $row['grade'] ?? null,
            'specialite' => $row['specialite'] ?? null,
            'phone' => $row['telephone'] ?? null,
            'bureau' => $row['bureau'] ?? null,
        ]);
    }

    /**
     * Règles de validation
     */
    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'prenoms' => 'nullable|string|max:255',
            'email' => 'required|email|unique:encadrants,email',
            'genre' => 'nullable|string|in:homme,femme',
            'grade' => 'nullable|string|max:255',
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
            'nom.required' => 'Le nom est obligatoire',
            'email.required' => 'L\'email est obligatoire',
            'email.email' => 'L\'email doit être une adresse email valide',
            'email.unique' => 'Cet email existe déjà dans le système',
            'genre.in' => 'Le genre doit être homme ou femme',
        ];
    }
}
