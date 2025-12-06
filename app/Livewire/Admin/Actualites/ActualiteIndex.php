<?php

namespace App\Livewire\Admin\Actualites;

use App\Models\Actualite;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Actualités')]
class ActualiteIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterVisible = 'all'; // all, visible, hidden
    public $confirmingDeletion = false;
    public $actualiteToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterVisible' => ['except' => 'all'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleVisibility($actualiteId)
    {
        $actualite = Actualite::find($actualiteId);
        if ($actualite) {
            $actualite->update(['visible' => !$actualite->visible]);
            session()->flash('success', 'Visibilité mise à jour !');
        }
    }

    public function confirmDelete($actualiteId)
    {
        $this->confirmingDeletion = true;
        $this->actualiteToDelete = $actualiteId;
    }

    public function deleteActualite()
    {
        if ($this->actualiteToDelete) {
            Actualite::find($this->actualiteToDelete)?->delete();
            session()->flash('success', 'Actualité supprimée avec succès !');
        }

        $this->confirmingDeletion = false;
        $this->actualiteToDelete = null;
    }

    public function render()
    {
        $query = Actualite::with(['auteur', 'image', 'category']); 

        // Recherche
        if ($this->search) {
            $query->where('titre', 'like', '%' . $this->search . '%');
        }

        // Filtre visibilité
        if ($this->filterVisible === 'visible') {
            $query->where('visible', true);
        } elseif ($this->filterVisible === 'hidden') {
            $query->where('visible', false);
        }

        $actualites = $query->orderBy('date_publication', 'desc')->paginate(10);

        $stats = [
            'total' => Actualite::count(),
            'visibles' => Actualite::where('visible', true)->count(),
            'cachees' => Actualite::where('visible', false)->count(),
        ];

        return view('livewire.admin.actualites.actualite-index', [
            'actualites' => $actualites,
            'stats' => $stats,
        ]);
    }
}