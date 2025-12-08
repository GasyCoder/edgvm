<?php

namespace App\Livewire\Frontend\Recherche;

use App\Models\Doctorant;
use App\Models\EAD;
use Livewire\Component;
use Livewire\WithPagination;

class DoctorantsPage extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatut = '';
    public $filterEad = '';
    public $viewMode = 'grid'; // 'grid' ou 'list'
    public $perPage = 12;

    protected $queryString = [
        'search'       => ['except' => ''],
        'filterStatut' => ['except' => ''],
        'filterEad'    => ['except' => ''],
        'viewMode'     => ['except' => 'grid'],
    ];

    // Quand on modifie les filtres / recherche → revenir page 1
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatut()
    {
        $this->resetPage();
    }

    public function updatingFilterEad()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'filterStatut', 'filterEad']);
        $this->resetPage();
    }

    public function setViewMode(string $mode): void
    {
        $this->viewMode = $mode;
    }

    /**
     * Liste des doctorants à afficher (avec thèses).
     */
    public function getDoctorantsProperty()
    {
        $query = Doctorant::with([
                'user',
                'theses.specialite',
                'theses.ead',
            ])
            // 🔹 On n’affiche que les doctorants qui ont au moins une thèse
            ->whereHas('theses');

        // 🔍 Recherche par nom OU matricule
        if ($this->search) {
            $search = $this->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($sub) use ($search) {
                    $sub->where('name', 'like', '%' . $search . '%');
                })->orWhere('matricule', 'like', '%' . $search . '%');
            });
        }

        // 🔎 Filtre par statut de thèse (en_cours, soutenue, etc.)
        if ($this->filterStatut) {
            $statut = $this->filterStatut;

            $query->whereHas('theses', function ($q) use ($statut) {
                $q->where('statut', $statut);
            });
        }

        // 🔎 Filtre par EAD (via les thèses)
        if ($this->filterEad) {
            $eadId = $this->filterEad;

            $query->whereHas('theses', function ($q) use ($eadId) {
                $q->where('ead_id', $eadId);
            });
        }

        return $query->paginate($this->perPage);
    }

    /**
     * Liste des EAD pour le filtre.
     */
    public function getEadsProperty()
    {
        return EAD::orderBy('nom')->get();
    }

    /**
     * Total des doctorants qui ont au moins une thèse.
     */
    public function getTotalDoctorantsProperty()
    {
        return Doctorant::whereHas('theses')->count();
    }

    /**
     * Doctorants "actifs" (statut doctorant) qui ont au moins une thèse.
     */
    public function getDoctorantsActifsProperty()
    {
        return Doctorant::where('statut', 'actif')
            ->whereHas('theses')
            ->count();
    }

    public function render()
    {
        return view('livewire.frontend.recherche.doctorants-page')
            ->layout('layouts.frontend', [
                'meta_title'       => 'Doctorants',
                'meta_description' => 'Liste des doctorants inscrits à l\'EDGVM.',
            ]);
    }
}
