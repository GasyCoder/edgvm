<?php

namespace App\Imports;

use App\Models\Doctorant;
use App\Models\Ead;
use App\Models\Encadrant;
use App\Models\These;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ThesesImport implements SkipsOnFailure, ToModel, WithHeadingRow, WithValidation
{
    use SkipsFailures;

    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return DB::transaction(function () use ($row) {

            // Trouver le doctorant par matricule
            $doctorant = Doctorant::where('matricule', $row['matricule_doctorant'])->first();

            if (! $doctorant) {
                throw new \Exception("Doctorant avec matricule '{$row['matricule_doctorant']}' introuvable");
            }

            // Trouver le directeur par email
            $directeur = Encadrant::where('email', $row['email_directeur'])->first();
            if (! $directeur) {
                throw new \Exception("Encadrant pour le directeur '{$row['email_directeur']}' introuvable");
            }

            // Trouver le co-directeur si présent
            $codirecteur = null;
            if (! empty($row['email_codirecteur'])) {
                $codirecteur = Encadrant::where('email', $row['email_codirecteur'])->first();
            }

            // Trouver l'EAD si présent
            $ead = null;
            if (! empty($row['ead'])) {
                $ead = Ead::where('nom', $row['ead'])->first();
            }

            // Créer la thèse
            $these = These::create([
                'doctorant_id' => $doctorant->id,
                'ead_id' => $ead?->id,
                'statut' => $row['statut'] ?? 'en_cours',
                'date_debut' => ! empty($row['date_debut']) ? $row['date_debut'] : null,
                'date_fin_prevue' => ! empty($row['date_fin_prevue']) ? $row['date_fin_prevue'] : null,
                'date_soutenance' => ! empty($row['date_soutenance']) ? $row['date_soutenance'] : null,
            ]);

            // Attacher le directeur
            $these->encadrants()->attach($directeur->id, [
                'role' => 'directeur',
                'date_debut' => now(),
            ]);

            // Attacher le co-directeur si présent
            if ($codirecteur) {
                $these->encadrants()->attach($codirecteur->id, [
                    'role' => 'codirecteur',
                    'date_debut' => now(),
                ]);
            }

            return $these;
        });
    }

    /**
     * Règles de validation
     */
    public function rules(): array
    {
        return [
            'matricule_doctorant' => 'required|string|exists:doctorants,matricule',
            'email_directeur' => 'required|email|exists:encadrants,email',
            'email_codirecteur' => 'nullable|email|exists:encadrants,email',
            'ead' => 'nullable|string',
            'statut' => 'required|in:en_cours,soutenue,abandonnee,suspendue',
            'date_debut' => 'nullable|date',
            'date_fin_prevue' => 'nullable|date',
            'date_soutenance' => 'nullable|date',
        ];
    }

    /**
     * Messages de validation personnalisés
     */
    public function customValidationMessages()
    {
        return [
            'matricule_doctorant.required' => 'Le matricule du doctorant est obligatoire',
            'matricule_doctorant.exists' => 'Ce matricule de doctorant n\'existe pas',
            'email_directeur.required' => 'L\'email du directeur est obligatoire',
            'email_directeur.exists' => 'Cet email de directeur n\'existe pas',
            'email_codirecteur.exists' => 'Cet email de co-directeur n\'existe pas',
            'statut.required' => 'Le statut est obligatoire',
            'statut.in' => 'Le statut doit être : en_cours, soutenue, abandonnee ou suspendue',
        ];
    }
}
