<?php

namespace App\Livewire\Admin\Theses;

use App\Models\EAD;
use App\Models\These;
use Livewire\Component;
use Livewire\WithPagination;

class TheseIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $ead_filter = '';
    public $statut_filter = '';
    
    public $confirmingDeletion = false;
    public $theseToDelete = null;

    protected $queryString = ['search', 'ead_filter', 'statut_filter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingEadFilter()
    {
        $this->resetPage();
    }

    public function updatingStatutFilter()
    {
        $this->resetPage();
    }

    public function confirmDelete($theseId)
    {
        $this->confirmingDeletion = true;
        $this->theseToDelete = $theseId;
    }

    public function cancelDelete()
    {
        $this->confirmingDeletion = false;
        $this->theseToDelete = null;
    }

    public function delete()
    {
        if ($this->theseToDelete) {
            $these = These::find($this->theseToDelete);
            
            if ($these) {
                // Détacher les encadrants avant de supprimer
                $these->encadrants()->detach();
                $these->delete();
                
                session()->flash('success', 'Thèse supprimée avec succès !');
            }
        }

        $this->confirmingDeletion = false;
        $this->theseToDelete = null;
    }

    public function render()
    {
        $theses = These::with(['doctorant.user', 'ead', 'encadrants.user'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('doctorant.user', function ($userQuery) {
                        $userQuery->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('doctorant', function ($docQuery) {
                        $docQuery->where('sujet_these', 'like', '%' . $this->search . '%')
                                ->orWhere('matricule', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->when($this->ead_filter, function ($query) {
                $query->where('ead_id', $this->ead_filter);
            })
            ->when($this->statut_filter, function ($query) {
                $query->where('statut', $this->statut_filter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $eads = EAD::orderBy('nom')->get();
        
        $statuts = ['en_cours', 'soutenue', 'abandonnee', 'suspendue'];

        return view('livewire.admin.theses.these-index', [
            'theses' => $theses,
            'eads' => $eads,
            'statuts' => $statuts,
        ]);
    }
}