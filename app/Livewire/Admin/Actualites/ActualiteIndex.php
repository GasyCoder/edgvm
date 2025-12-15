<?php

namespace App\Livewire\Admin\Actualites;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Actualite;

class ActualiteIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterVisible = 'all';
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

    public function updatingFilterVisible()
    {
        $this->resetPage();
    }

    public function toggleVisibility($id)
    {
        $actualite = Actualite::find($id);
        if ($actualite) {
            $actualite->visible = !$actualite->visible;
            $actualite->save();
            session()->flash('success', 'Visibilité mise à jour avec succès.');
        }
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeletion = true;
        $this->actualiteToDelete = $id;
    }

    public function deleteActualite()
    {
        if ($this->actualiteToDelete) {
            $actualite = Actualite::find($this->actualiteToDelete);
            if ($actualite) {
                $actualite->delete();
                session()->flash('success', 'Actualité supprimée avec succès.');
            }
        }

        $this->confirmingDeletion = false;
        $this->actualiteToDelete = null;
    }

    public function render()
    {
        $query = Actualite::with(['category', 'image', 'auteur'])
            ->whereNotNull('slug')  // ← AJOUTE CETTE LIGNE
            ->where('slug', '!=', '');  // ← AJOUTE CETTE LIGNE

        // Recherche
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('titre', 'like', '%' . $this->search . '%')
                  ->orWhere('contenu', 'like', '%' . $this->search . '%');
            });
        }

        // Filtre visibilité
        if ($this->filterVisible === 'visible') {
            $query->where('visible', true);
        } elseif ($this->filterVisible === 'hidden') {
            $query->where('visible', false);
        }

        $actualites = $query->latest()->paginate(15);

        // Stats
        $stats = [
            'total' => Actualite::whereNotNull('slug')->where('slug', '!=', '')->count(),
            'visibles' => Actualite::whereNotNull('slug')->where('slug', '!=', '')->where('visible', true)->count(),
            'cachees' => Actualite::whereNotNull('slug')->where('slug', '!=', '')->where('visible', false)->count(),
        ];

        return view('livewire.admin.actualites.actualite-index', [
            'actualites' => $actualites,
            'stats' => $stats,
        ]);
    }
}