<?php

namespace App\Livewire\Frontend\Recherche;

use App\Models\Doctorant;
use Livewire\Component;

class DoctorantDetail extends Component
{
    public Doctorant $doctorant;

    /**
     * Thèse principale à afficher (en cours / préparation / rédaction / soutenue…)
     */
    public $thesePrincipale;

    /**
     * Badge de statut (texte + classes Tailwind) calculé côté backend
     */
    public ?string $badgeText = null;

    public ?string $badgeClasses = null;

    public function mount(Doctorant $doctorant)
    {
        // On charge toutes les relations utiles
        $this->doctorant = $doctorant->load([
            'user',
            'ead',
            'theses.specialite',
            'theses.ead',
            'theses.encadrants',
        ]);

        // Thèse principale via l'accessor du modèle Doctorant (getThesePrincipaleAttribute)
        $this->thesePrincipale = $this->doctorant->these_principale;

        // Calcul du badge (statut de thèse prioritaire, sinon statut du doctorant)
        $this->prepareBadge();
    }

    /**
     * Calcule le texte et les classes CSS du badge de statut,
     * en donnant la priorité au statut de la thèse principale.
     */
    protected function prepareBadge(): void
    {
        $this->badgeText = null;
        $this->badgeClasses = 'bg-white/20 border-white/30 text-white';

        // 1️⃣ Priorité : statut de la thèse principale
        if ($this->thesePrincipale?->statut) {
            $statutThese = $this->thesePrincipale->statut; // ex : en_cours, soutenue, ...

            // Texte lisible : "en_cours" → "En cours"
            $this->badgeText = ucfirst(str_replace('_', ' ', $statutThese));

            $this->badgeClasses = match ($statutThese) {
                'en_cours' => 'bg-emerald-500/20 border-emerald-300/60 text-emerald-50',
                'preparation' => 'bg-amber-500/20 border-amber-300/60 text-amber-50',
                'redaction' => 'bg-indigo-500/20 border-indigo-300/60 text-indigo-50',
                'soutenue' => 'bg-blue-600/20 border-blue-300/70 text-blue-50',
                'suspendue' => 'bg-orange-500/20 border-orange-300/60 text-orange-50',
                'abandonnee' => 'bg-red-500/20 border-red-300/60 text-red-50',
                default => 'bg-white/20 border-white/30 text-white',
            };

            return;
        }

        // 2️⃣ Sinon, on se rabat sur le statut du doctorant (actif, diplômé, inactif…)
        if ($this->doctorant->statut) {
            $statutDoc = strtolower($this->doctorant->statut);
            $this->badgeText = ucfirst($this->doctorant->statut);

            $this->badgeClasses = match ($statutDoc) {
                'actif' => 'bg-emerald-500/20 border-emerald-300/60 text-emerald-50',
                'diplome' => 'bg-blue-500/20 border-blue-300/60 text-blue-50',
                'inactif' => 'bg-slate-500/30 border-slate-300/60 text-slate-50',
                default => 'bg-white/20 border-white/30 text-white',
            };
        }
    }

    /**
     * Durée de la thèse principale (en cours OU soutenue)
     * - Si date_publication existe → durée entre début et publication
     * - Sinon → durée entre début et aujourd’hui
     */
    public function getDureeTheseProperty()
    {
        $these = $this->thesePrincipale;

        if (! $these || ! $these->date_debut) {
            return null;
        }

        $debut = $these->date_debut;
        $fin = $these->date_publication ?? now();

        $totalMonths = $debut->diffInMonths($fin);
        $years = intdiv($totalMonths, 12);
        $months = $totalMonths % 12;

        if ($years > 0 && $months > 0) {
            return "{$years} an".($years > 1 ? 's' : '')." et {$months} mois";
        } elseif ($years > 0) {
            return "{$years} an".($years > 1 ? 's' : '');
        } elseif ($months > 0) {
            return "{$months} mois";
        }

        return "Moins d'un mois";
    }

    /**
     * Nombre de thèses soutenues pour ce doctorant
     */
    public function getThesesSoutenuesCountProperty()
    {
        return $this->doctorant->theses
            ->where('statut', 'soutenue')
            ->count();
    }

    /**
     * Mots-clés de la thèse principale sous forme de tableau
     */
    public function getMotsClesArrayProperty()
    {
        $these = $this->thesePrincipale;

        if (! $these || ! $these->mots_cles) {
            return [];
        }

        return array_map('trim', explode(',', $these->mots_cles));
    }

    /**
     * Historique des thèses (toutes sauf la thèse principale)
     */
    public function getThesesHistoriqueProperty()
    {
        $idPrincipale = $this->thesePrincipale?->id;

        if (! $idPrincipale) {
            return $this->doctorant->theses;
        }

        return $this->doctorant->theses->where('id', '!=', $idPrincipale);
    }

    /**
     * Indique si on doit afficher "Lire la suite" sur le résumé
     */
    public function getShowReadMoreProperty()
    {
        $these = $this->thesePrincipale;

        if (! $these || ! $these->resume_these) {
            return false;
        }

        return mb_strlen($these->resume_these) > 400;
    }

    /**
     * Résumé limité à 400 caractères pour pré-affichage
     */
    public function getResumeLimiteProperty()
    {
        $these = $this->thesePrincipale;

        if (! $these || ! $these->resume_these) {
            return null;
        }

        return \Illuminate\Support\Str::limit($these->resume_these, 400);
    }

    public function render()
    {
        return view('livewire.frontend.recherche.doctorant-detail')
            ->layout('layouts.frontend');
    }
}
