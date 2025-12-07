<?php

namespace App\Livewire\Admin\Doctorants;

use App\Models\EAD;
use App\Models\User;
use Livewire\Component;
use App\Models\Doctorant;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class DoctorantIndex extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';
    
    #[Url]
    public $niveau = '';
    
    #[Url]
    public $statut = '';

    #[Url]
    public $ead_filter = '';

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

    public function updatingNiveau()
    {
        $this->resetPage();
    }

    public function updatingStatut()
    {
        $this->resetPage();
    }

    public function updatingEadFilter()
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

        if ($doctorant->user) {
            session()->flash('error', '❌ Ce doctorant a déjà un compte utilisateur.');
            $this->closeCreateAccountModal();
            return;
        }

        // Vérifier si l’email existe déjà côté users
        $emailSource = $doctorant->user?->email; // via accessor éventuel
        if ($emailSource && User::where('email', $emailSource)->exists()) {
            session()->flash('error', '❌ Cet email est déjà utilisé par un autre compte.');
            return;
        }

        try {
            $email = $emailSource ?: $doctorant->matricule . '@temp.univ.mg';

            // Créer le compte utilisateur
            $user = User::create([
                'name'     => $doctorant->name ?? 'Doctorant',
                'email'    => $email,
                'password' => Hash::make($this->accountPassword),
                'role'     => 'doctorant',
                'active'   => true,
            ]);

            // Lier le user au doctorant
            $doctorant->update(['user_id' => $user->id]);

            session()->flash('success', '✅ Compte utilisateur créé avec succès !');
            $this->closeCreateAccountModal();
        } catch (\Exception $e) {
            session()->flash('error', '❌ Erreur lors de la création du compte : ' . $e->getMessage());
        }
    }

    public function render()
    {
        $doctorants = Doctorant::with([
                'user',
                // on charge les thèses + EAD + encadrants pour l’affichage éventuel
                'theses.ead',
                'theses.encadrants.user',
            ])
            ->when($this->search, function ($query) {
                $search = $this->search;

                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%')
                                  ->orWhere('email', 'like', '%' . $search . '%');
                    })
                    ->orWhere('matricule', 'like', '%' . $search . '%')
                    ->orWhereHas('theses', function ($tq) use ($search) {
                        $tq->where('sujet_these', 'like', '%' . $search . '%');
                    });
                });
            })
            ->when($this->niveau, function ($query) {
                $query->where('niveau', $this->niveau);
            })
            ->when($this->statut, function ($query) {
                $query->where('statut', $this->statut);
            })
            ->when($this->ead_filter, function ($query) {
                $query->whereHas('theses', function ($tq) {
                    $tq->where('ead_id', $this->ead_filter);
                });
            })
            ->when($this->has_account, function ($query) {
                if ($this->has_account === 'yes') {
                    $query->whereNotNull('user_id');
                } elseif ($this->has_account === 'no') {
                    $query->whereNull('user_id');
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $eads = EAD::orderBy('nom')->get();

        // 🔹 Stats simples sur les doctorants
        $totalDoctorants        = Doctorant::count();
        $doctorantsActifs       = Doctorant::where('statut', 'actif')->count();
        $doctorantsSansCompte   = Doctorant::whereNull('user_id')->count();

        return view('livewire.admin.doctorants.doctorant-index', [
            'doctorants'            => $doctorants,
            'eads'                  => $eads,
            'totalDoctorants'       => $totalDoctorants,
            'doctorantsActifs'      => $doctorantsActifs,
            'doctorantsSansCompte'  => $doctorantsSansCompte,
        ]);
    }
}
