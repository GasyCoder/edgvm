<?php

namespace App\Livewire\Admin\Doctorants;

use App\Models\Doctorant;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Hash;

class DoctorantIndex extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';
    
    #[Url]
    public $statut = '';

    #[Url]
    public $ead_id = '';

    #[Url]
    public $has_account = '';

    // Modals
    public $confirmingDeletion = false;
    public $doctorantToDelete = null;

    public $showCreateAccountModal = false;
    public $doctorantForAccount = null;
    public $accountPassword = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatut()
    {
        $this->resetPage();
    }

    public function updatingEadId()
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
        $this->doctorantToDelete = $id;
    }

    public function cancelDelete()
    {
        $this->confirmingDeletion = false;
        $this->doctorantToDelete = null;
    }

    public function delete()
    {
        $doctorant = Doctorant::find($this->doctorantToDelete);

        if (!$doctorant) {
            session()->flash('error', '❌ Doctorant non trouvé.');
            return;
        }

        if ($doctorant->theses()->count() > 0) {
            session()->flash('error', '❌ Impossible de supprimer : ce doctorant a des thèses enregistrées.');
            $this->cancelDelete();
            return;
        }

        $doctorant->delete();
        session()->flash('success', '✅ Doctorant supprimé avec succès !');
        $this->cancelDelete();
    }

    // Modal Créer Compte
    public function openCreateAccountModal($doctorantId)
    {
        $this->doctorantForAccount = $doctorantId;
        $this->accountPassword = '';
        $this->showCreateAccountModal = true;
    }

    public function closeCreateAccountModal()
    {
        $this->showCreateAccountModal = false;
        $this->doctorantForAccount = null;
        $this->accountPassword = '';
    }

    public function createUserAccount()
    {
        $this->validate([
            'accountPassword' => 'required|min:8',
        ], [
            'accountPassword.required' => 'Le mot de passe est obligatoire.',
            'accountPassword.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ]);

        $doctorant = Doctorant::find($this->doctorantForAccount);

        if (!$doctorant) {
            session()->flash('error', '❌ Doctorant non trouvé.');
            return;
        }

        if ($doctorant->hasUser()) {
            session()->flash('error', '❌ Ce doctorant a déjà un compte utilisateur.');
            $this->closeCreateAccountModal();
            return;
        }

        if (User::where('email', $doctorant->email)->exists()) {
            session()->flash('error', '❌ Cet email est déjà utilisé par un autre compte.');
            return;
        }

        try {
            $doctorant->createUserAccount($this->accountPassword);
            session()->flash('success', '✅ Compte utilisateur créé avec succès !');
            $this->closeCreateAccountModal();
        } catch (\Exception $e) {
            session()->flash('error', '❌ Erreur lors de la création du compte : ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = Doctorant::with(['user', 'directeur.user', 'ead', 'theses']);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nom', 'like', '%' . $this->search . '%')
                  ->orWhere('prenom', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('matricule', 'like', '%' . $this->search . '%')
                  ->orWhere('sujet_these', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statut) {
            $query->where('statut', $this->statut);
        }

        if ($this->ead_id) {
            $query->where('ead_id', $this->ead_id);
        }

        if ($this->has_account === 'yes') {
            $query->withUser();
        } elseif ($this->has_account === 'no') {
            $query->withoutUser();
        }

        $doctorants = $query->orderBy('created_at', 'desc')->paginate(15);
        $eads = \App\Models\EAD::orderBy('nom')->get();

        return view('livewire.admin.doctorants.doctorant-index', [
            'doctorants' => $doctorants,
            'eads' => $eads,
        ])->layout('layouts.app');
    }
}