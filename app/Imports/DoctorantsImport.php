<?php

namespace App\Imports;

use App\Models\Doctorant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DoctorantsImport implements SkipsOnError, ToModel, WithHeadingRow, WithValidation
{
    use SkipsErrors;

    public function model(array $row)
    {
        // 1. Vérifier si le doctorant existe déjà (par matricule)
        $existingDoctorant = Doctorant::where('matricule', $row['matricule'] ?? null)->first();
        if ($existingDoctorant) {
            Log::warning('Doctorant déjà existant: '.($row['matricule'] ?? 'N/A'));

            return null;
        }

        return new Doctorant([
            'nom' => $row['nom'] ?? null,
            'prenoms' => $row['prenoms'] ?? null,
            'genre' => $this->normalizeGenre($row['genre'] ?? null),
            'email' => $row['email'] ?? null,
            'ead_id' => $this->resolveEadId($row),
            'matricule' => $row['matricule'],
            'niveau' => $row['niveau'] ?? 'D1',
            'date_naissance' => ! empty($row['date_de_naissance'])
                ? $this->parseDate($row['date_de_naissance'])
                : null,
            'lieu_naissance' => $row['lieu_de_naissance'] ?? null,
            'phone' => $row['telephone'] ?? null,
            'adresse' => $row['adresse'] ?? null,
            'date_inscription' => ! empty($row['date_dinscription'])
                ? $this->parseDate($row['date_dinscription'])
                : now(),
            'statut' => strtolower($row['statut'] ?? 'actif'),
        ]);
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

    private function normalizeGenre(?string $value): string
    {
        $value = strtolower(trim((string) $value));

        return match ($value) {
            'f', 'femme' => 'femme',
            default => 'homme',
        };
    }

    private function resolveEadId(array $row): ?int
    {
        if (! empty($row['ead_id'])) {
            return (int) $row['ead_id'];
        }

        if (! empty($row['ead'])) {
            $ead = \App\Models\EAD::where('nom', $row['ead'])->first();

            return $ead?->id;
        }

        return null;
    }

    public function rules(): array
    {
        return [
            'matricule' => 'required',
            'nom' => 'required',
            'prenoms' => 'nullable',
            'genre' => 'required|in:homme,femme,Homme,Femme,h,f,H,F',
            'email' => 'required|email|unique:doctorants,email',
            'ead_id' => 'nullable|exists:eads,id',
            'ead' => 'nullable|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'matricule.required' => 'Le matricule est obligatoire',
            'nom.required' => 'Le nom est obligatoire',
            'genre.required' => 'Le genre est obligatoire',
            'genre.in' => 'Le genre doit etre Homme ou Femme',
            'email.required' => 'L\'email est obligatoire',
            'email.email' => 'L\'email doit etre valide',
        ];
    }
}
