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

    public function archiver(int $id): void
    {
        $evenement = Evenement::findOrFail($id);

        if (! $evenement->est_termine) {
            session()->flash('error', "Cet événement n'est pas encore terminé. Vous pourrez l'archiver une fois terminé.");
            return;
        }

        $evenement->update([
            'est_archive' => true,
            'est_publie'  => false,
        ]);

        session()->flash('success', 'Événement archivé avec succès.');
        $this->resetPage();
    }

    public function restaurer(int $id): void
    {
        $evenement = Evenement::findOrFail($id);

        $evenement->update([
            'est_archive' => false,
        ]);

        session()->flash('success', 'Événement restauré depuis les archives.');
        $this->resetPage();
    }

    public function togglePublication(int $id): void
    {
        $evenement = Evenement::findOrFail($id);

        // Sécurité : on ne publie pas un événement archivé
        if ($evenement->est_archive) {
            session()->flash('error', "Cet événement est archivé et ne peut pas être publié.");
            return;
        }

        $nouvelleValeur = ! $evenement->est_publie;

        $evenement->update([
            'est_publie' => $nouvelleValeur,
        ]);

        session()->flash(
            'success',
            $nouvelleValeur ? 'Événement publié.' : 'Événement mis en brouillon.'
        );
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

        $evenements = $query
            ->orderBy('est_archive')          // non archivés d’abord
            ->orderByDesc('est_important')
            ->orderByDesc('date_debut')
            ->paginate($this->perPage);

        return view('livewire.admin.evenements.evenement-index', [
            'evenements' => $evenements,
        ]);
    }
}
