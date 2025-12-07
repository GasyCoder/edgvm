<?php

namespace App\Imports;

use App\Models\Doctorant;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DoctorantsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;

    public function model(array $row)
    {
        // 1. Vérifier si le doctorant existe déjà (par matricule)
        $existingDoctorant = Doctorant::where('matricule', $row['matricule'] ?? null)->first();
        if ($existingDoctorant) {
            Log::warning('Doctorant déjà existant: ' . ($row['matricule'] ?? 'N/A'));
            return null;
        }

        // 2. Vérifier si l'email existe déjà
        $email = $row['email'] ?? null;
        if ($email) {
            $existingUser = User::where('email', $email)->first();
            if ($existingUser) {
                Log::warning('Email déjà utilisé: ' . $email);
                return null;
            }
        }

        return DB::transaction(function () use ($row, $email) {
            // 3. Créer l'utilisateur
            $user = User::create([
                'name'     => $row['nom_complet'],
                'email'    => $email,
                'password' => Hash::make('doctorant123'),
                'role'     => 'doctorant',
                'active'   => true,
            ]);

            // 4. Créer le doctorant (sans EAD)
            return new Doctorant([
                'user_id'          => $user->id,
                'matricule'        => $row['matricule'],
                'niveau'           => $row['niveau'] ?? 'D1',
                'date_naissance'   => !empty($row['date_de_naissance'])
                    ? $this->parseDate($row['date_de_naissance'])
                    : null,
                'lieu_naissance'   => $row['lieu_de_naissance'] ?? null,
                'phone'            => $row['telephone'] ?? null,
                'adresse'          => $row['adresse'] ?? null,
                'date_inscription' => !empty($row['date_dinscription'])
                    ? $this->parseDate($row['date_dinscription'])
                    : now(),
                'statut'           => strtolower($row['statut'] ?? 'actif'),
            ]);
        });
    }

    private function parseDate($date)
    {
        if (is_numeric($date)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date);
        }
        
        try {
            return Carbon::parse($date);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'matricule'   => 'required',
            'nom_complet' => 'required',
            'email'       => 'required|email',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'matricule.required'   => 'Le matricule est obligatoire',
            'nom_complet.required' => 'Le nom complet est obligatoire',
            'email.required'       => 'L\'email est obligatoire',
            'email.email'          => 'L\'email doit être valide',
        ];
    }
}
