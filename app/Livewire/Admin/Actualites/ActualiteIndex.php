<?php

namespace App\Livewire\Admin\Actualites;

use App\Models\Actualite;
use Livewire\Component;
use Livewire\WithPagination;

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
            $actualite->visible = ! $actualite->visible;
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
        $query = Actualite::with(['category', 'image', 'auteur'])->withSlug();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('titre', 'like', '%'.$this->search.'%')
                    ->orWhere('contenu', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->filterVisible === 'visible') {
            $query->where('visible', true);
        } elseif ($this->filterVisible === 'hidden') {
            $query->where('visible', false);
        }

        $actualites = $query->latest()->paginate(15);

        $baseQuery = Actualite::withSlug();
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'visibles' => (clone $baseQuery)->where('visible', true)->count(),
            'cachees' => (clone $baseQuery)->where('visible', false)->count(),
        ];

        return view('livewire.admin.actualites.actualite-index', [
            'actualites' => $actualites,
            'stats' => $stats,
        ]);
    }
}
