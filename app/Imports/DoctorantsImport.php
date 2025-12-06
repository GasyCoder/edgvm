<?php

namespace App\Imports;

use App\Models\Doctorant;
use App\Models\Encadrant;
use App\Models\EAD;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DoctorantsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;

    public function model(array $row)
    {
        // Vérifier si le doctorant existe déjà
        $existingDoctorant = Doctorant::where('matricule', $row['matricule'])->first();
        if ($existingDoctorant) {
            Log::warning('Doctorant déjà existant: ' . $row['matricule']);
            return null;
        }

        // Chercher le directeur par nom
        $directeur = null;
        if (!empty($row['directeur_de_these'])) {
            $directeur = Encadrant::whereHas('user', function($q) use ($row) {
                $q->where('name', 'like', '%' . trim($row['directeur_de_these']) . '%');
            })->first();
        }

        // Chercher le co-directeur
        $codirecteur = null;
        if (!empty($row['co_directeur'])) {
            $codirecteur = Encadrant::whereHas('user', function($q) use ($row) {
                $q->where('name', 'like', '%' . trim($row['co_directeur']) . '%');
            })->first();
        }

        // Chercher l'EAD
        $ead = null;
        if (!empty($row['ead'])) {
            $ead = EAD::where('nom', 'like', '%' . trim($row['ead']) . '%')->first();
        }

        return new Doctorant([
            'matricule' => $row['matricule'],
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
            'email' => $row['email'],
            'niveau' => $row['niveau'] ?? 'D1',
            'sujet_these' => $row['sujet_de_these'] ?? null,
            'directeur_id' => $directeur?->id,
            'codirecteur_id' => $codirecteur?->id,
            'ead_id' => $ead?->id,
            'date_naissance' => !empty($row['date_de_naissance']) ? $this->parseDate($row['date_de_naissance']) : null,
            'lieu_naissance' => $row['lieu_de_naissance'] ?? null,
            'phone' => $row['telephone'] ?? null,
            'adresse' => $row['adresse'] ?? null,
            'date_inscription' => !empty($row['date_dinscription']) ? $this->parseDate($row['date_dinscription']) : now(),
            'statut' => $row['statut'] ?? 'actif',
        ]);
    }

    private function parseDate($date)
    {
        if (is_numeric($date)) {
            // Excel date format (nombre de jours depuis 1900)
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
            'matricule' => 'required',
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'matricule.required' => 'Le matricule est obligatoire',
            'nom.required' => 'Le nom est obligatoire',
            'prenom.required' => 'Le prénom est obligatoire',
            'email.required' => 'L\'email est obligatoire',
            'email.email' => 'L\'email doit être valide',
        ];
    }
}