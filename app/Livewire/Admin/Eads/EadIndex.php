<?php

namespace App\Livewire\Admin\Eads;

use App\Models\EAD;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class EadIndex extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    public $confirmingDeletion = false;
    public $eadToDelete = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($eadId)
    {
        $this->confirmingDeletion = true;
        $this->eadToDelete = $eadId;
    }

    public function cancelDelete()
    {
        $this->confirmingDeletion = false;
        $this->eadToDelete = null;
    }

    public function delete()
    {
        if ($this->eadToDelete) {
            $ead = EAD::find($this->eadToDelete);
            
            // Vérifier si l'EAD a des spécialités
            if ($ead->specialites()->count() > 0) {
                session()->flash('error', '❌ Impossible de supprimer cette EAD car elle contient des spécialités.');
                $this->cancelDelete();
                return;
            }

            $ead->delete();
            session()->flash('success', '✅ EAD supprimée avec succès !');
            $this->cancelDelete();
        }
    }

    public function render()
    {
        $query = EAD::with(['responsable'])
            ->withCount(['specialites', 'theses']);

        // Recherche
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nom', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('domaine', 'like', '%' . $this->search . '%');
            });
        }

        $eads = $query->orderBy('nom')->paginate(15);

        return view('livewire.admin.eads.ead-index', [
            'eads' => $eads,
        ])->layout('layouts.app');
    }
}