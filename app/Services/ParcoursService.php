<?php

namespace App\Services;

use App\Enums\StatutAnnee;
use App\Models\Doctorant;
use App\Models\Reinscription;
use Illuminate\Validation\ValidationException;

class ParcoursService
{
    /**
     * Admet le doctorant à l'année/niveau suivant (D1→D2→D3→D4→D5).
     * Clôture la réinscription courante en « admis » et en ouvre une nouvelle « en cours ».
     */
    public function promouvoir(Doctorant $doctorant, ?string $decision = null): Reinscription
    {
        $courante = $this->reinscriptionCourante($doctorant);
        $niveauSuivant = Doctorant::niveauSuivant($courante->niveau);

        if ($niveauSuivant === null) {
            throw ValidationException::withMessages([
                'parcours' => 'Niveau maximal atteint (D5). Validez la thèse pour la soutenance.',
            ]);
        }

        $this->cloturer($courante, StatutAnnee::Admis, $decision);

        return $this->ouvrir($doctorant, $niveauSuivant);
    }

    /**
     * Ajourne le doctorant : il redouble le même niveau l'année suivante.
     */
    public function ajourner(Doctorant $doctorant, ?string $decision = null): Reinscription
    {
        $courante = $this->reinscriptionCourante($doctorant);

        $this->cloturer($courante, StatutAnnee::Ajourne, $decision);

        return $this->ouvrir($doctorant, $courante->niveau);
    }

    /**
     * Valide la thèse pour la soutenance (D3 et au-delà) : la réinscription passe « validé ».
     */
    public function validerPourSoutenance(Doctorant $doctorant, ?string $decision = null): Reinscription
    {
        $courante = $this->reinscriptionCourante($doctorant);

        if (! in_array($courante->niveau, ['D3', 'D4', 'D5'], true)) {
            throw ValidationException::withMessages([
                'parcours' => 'La validation pour soutenance n\'est possible qu\'à partir de la D3.',
            ]);
        }

        $courante->update([
            'statut_annee' => StatutAnnee::Valide,
            'decision' => $decision,
            'date_decision' => now(),
        ]);

        return $courante;
    }

    /**
     * Enregistre la soutenance : le doctorant est diplômé (et passe en archives).
     */
    public function diplomer(Doctorant $doctorant): void
    {
        $doctorant->update(['statut' => 'diplome']);
    }

    /**
     * Marque l'abandon du doctorant.
     */
    public function abandonner(Doctorant $doctorant, ?string $decision = null): void
    {
        $courante = $this->reinscriptionCourante($doctorant);
        $this->cloturer($courante, StatutAnnee::Abandon, $decision);

        $doctorant->update(['statut' => 'abandonne']);
    }

    /**
     * Crée la réinscription de départ si le doctorant n'en a aucune.
     */
    public function initialiser(Doctorant $doctorant, ?string $anneeUniversitaire = null): Reinscription
    {
        return $doctorant->reinscriptions()->create([
            'annee_universitaire' => $anneeUniversitaire ?? $this->anneeCourante(),
            'niveau' => $doctorant->niveau ?: 'D1',
            'statut_annee' => StatutAnnee::EnCours,
        ]);
    }

    private function reinscriptionCourante(Doctorant $doctorant): Reinscription
    {
        $courante = $doctorant->reinscriptionCourante();

        if ($courante === null) {
            $courante = $this->initialiser($doctorant);
            $doctorant->load('reinscriptions');
        }

        return $courante;
    }

    private function cloturer(Reinscription $reinscription, StatutAnnee $statut, ?string $decision): void
    {
        $reinscription->update([
            'statut_annee' => $statut,
            'decision' => $decision,
            'date_decision' => now(),
        ]);
    }

    private function ouvrir(Doctorant $doctorant, string $niveau): Reinscription
    {
        $reinscription = $doctorant->reinscriptions()->create([
            'annee_universitaire' => $this->anneeSuivante($doctorant->reinscriptionCourante()?->annee_universitaire),
            'niveau' => $niveau,
            'statut_annee' => StatutAnnee::EnCours,
        ]);

        $doctorant->update(['niveau' => $niveau]);

        return $reinscription;
    }

    private function anneeCourante(): string
    {
        $now = now();
        $start = $now->month >= 9 ? $now->year : $now->year - 1;

        return $start.'-'.($start + 1);
    }

    private function anneeSuivante(?string $annee): string
    {
        if (! $annee || ! preg_match('/^(\d{4})-(\d{4})$/', $annee, $matches)) {
            return $this->anneeCourante();
        }

        return ((int) $matches[1] + 1).'-'.((int) $matches[2] + 1);
    }
}
