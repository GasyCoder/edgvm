<?php

namespace App\Livewire\Admin\Encadrants;

use App\Models\Encadrant;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Hash;

class EncadrantIndex extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';
    
    #[Url]
    public $grade = '';

    #[Url]
    public $has_account = '';

    // Modals
    public $confirmingDeletion = false;
    public $encadrantToDelete = null;

    public $showCreateAccountModal = false;
    public $encadrantForAccount = null;
    public $accountPassword = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingGrade()
    {
        $this->resetPage();
    }

    public function updatingHasAccount()
    {
        $this->resetPage();
    }

    // Modal Suppression
    public function confirmDelete($id)
    {
        $this->confirmingDeletion = true;
        $this->encadrantToDelete = $id;
    }

    public function cancelDelete()
    {
        $this->confirmingDeletion = false;
        $this->encadrantToDelete = null;
    }

    public function delete()
    {
        $encadrant = Encadrant::find($this->encadrantToDelete);

        if (!$encadrant) {
            session()->flash('error', '❌ Encadrant non trouvé.');
            return;
        }

        // Vérifier s'il a des thèses
        if ($encadrant->theses()->count() > 0) {
            session()->flash('error', '❌ Impossible de supprimer : cet encadrant a des thèses enregistrées.');
            $this->cancelDelete();
            return;
        }

        $encadrant->user?->delete(); // Supprime aussi le User
        $encadrant->delete();
        
        session()->flash('success', '✅ Encadrant supprimé avec succès !');
        $this->cancelDelete();
    }

    public function render()
    {
        $query = Encadrant::with(['user']);

        if ($this->search) {
            $query->whereHas('user', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            })->orWhere('grade', 'like', '%' . $this->search . '%')
              ->orWhere('specialite', 'like', '%' . $this->search . '%');
        }

        if ($this->grade) {
            $query->where('grade', $this->grade);
        }

        if ($this->has_account === 'yes') {
            $query->has('user');
        } elseif ($this->has_account === 'no') {
            $query->doesntHave('user');
        }

        $encadrants = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Liste des grades pour les filtres
        $grades = Encadrant::distinct()->pluck('grade')->filter();

        return view('livewire.admin.encadrants.encadrant-index', [
            'encadrants' => $encadrants,
            'grades' => $grades,
        ])->layout('layouts.app');
    }
}