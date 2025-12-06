<?php

namespace App\Livewire\Admin\Specialites;

use App\Models\Specialite;
use App\Models\EAD;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class SpecialiteIndex extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $ead_id = '';

    public $confirmingDeletion = false;
    public $specialiteToDelete = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingEadId()
    {
        $this->resetPage();
    }

    public function confirmDelete($specialiteId)
    {
        $this->confirmingDeletion = true;
        $this->specialiteToDelete = $specialiteId;
    }

    public function cancelDelete()
    {
        $this->confirmingDeletion = false;
        $this->specialiteToDelete = null;
    }

    public function delete()
    {
        if ($this->specialiteToDelete) {
            $specialite = Specialite::find($this->specialiteToDelete);
            
            // Vérifier si la spécialité a des thèses
            if ($specialite->theses()->count() > 0) {
                session()->flash('error', '❌ Impossible de supprimer cette spécialité car elle contient des thèses.');
                $this->cancelDelete();
                return;
            }

            $specialite->delete();
            session()->flash('success', '✅ Spécialité supprimée avec succès !');
            $this->cancelDelete();
        }
    }

    public function render()
    {
        $query = Specialite::with(['ead']);

        // Recherche
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nom', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Filtre par EAD
        if ($this->ead_id) {
            $query->where('ead_id', $this->ead_id);
        }

        $specialites = $query->orderBy('nom')->paginate(15);
        
        // Liste des EAD pour le filtre
        $eads = EAD::orderBy('nom')->get();

        return view('livewire.admin.specialites.specialite-index', [
            'specialites' => $specialites,
            'eads' => $eads,
        ])->layout('layouts.app');
    }
}