<?php

namespace App\Livewire\Admin\Evenements;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Evenement;

class EvenementIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterType = '';
    public $filterPublie = '';
    public $perPage = 15;
    protected $paginationTheme = 'tailwind';

    protected $queryString = ['search', 'filterType', 'filterPublie', 'page'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function updatingFilterPublie()
    {
        $this->resetPage();
    }

    public function deleteConfirm($id)
    {
        $this->dispatch('confirm-delete', [
            'id' => $id,
            'message' => "Voulez-vous vraiment supprimer cet événement ? Cette action est irréversible."
        ]);
    }

    public function delete($id)
    {
        $evenement = Evenement::findOrFail($id);
        $evenement->delete();
        session()->flash('success', 'Événement supprimé avec succès.');
        $this->resetPage();
    }

    public function render()
    {
        $query = Evenement::query();

        if ($this->search) {
            $query->where('titre', 'like', '%' . $this->search . '%');
        }

        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        if ($this->filterPublie !== '') {
            if ($this->filterPublie === 'publie') {
                $query->where('est_publie', true);
            } elseif ($this->filterPublie === 'non') {
                $query->where('est_publie', false);
            }
        }

        $evenements = $query->orderByDesc('est_important')->orderByDesc('date_debut')->paginate($this->perPage);

        return view('livewire.admin.evenements.evenement-index', [
            'evenements' => $evenements,
        ]);
    }
}