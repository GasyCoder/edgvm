<?php

namespace App\Livewire\Frontend\Recherche;

use App\Models\These;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Répertoire des thèses - EDGVM')]
class ThesePage extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $statut = '';  // '', 'preparation', 'en_cours', 'soutenue'
    public $viewMode = 'grid'; // 'grid' | 'list'

    protected $queryString = [
        'search'   => ['except' => ''],
        'statut'   => ['except' => ''],
        'viewMode' => ['except' => 'grid'],
        'page'     => ['except' => 1],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setStatut(?string $statut): void
    {
        $this->statut = $statut ?? '';
        $this->resetPage();
    }

    public function setViewMode(string $mode): void
    {
        if (in_array($mode, ['grid', 'list'])) {
            $this->viewMode = $mode;
        } else {
            $this->viewMode = 'grid';
        }
    }

    public function getThesesProperty()
    {
        return These::with(['doctorant', 'specialite', 'ead'])
            ->when($this->statut !== '', function ($query) {
                $query->where('statut', $this->statut);
            })
            ->when($this->search !== '', function ($query) {
                $search = '%' . $this->search . '%';

                $query->where(function ($q) use ($search) {
                    $q->where('sujet_these', 'like', $search)
                        ->orWhere('resume_these', 'like', $search)
                        ->orWhere('mots_cles', 'like', $search)
                        ->orWhereHas('doctorant', function ($sub) use ($search) {
                            $sub->where('nom', 'like', $search)
                                ->orWhere('prenom', 'like', $search);
                        });
                });
            })
            ->orderByDesc('date_debut')
            ->paginate(9);
    }

    public function render()
    {
        return view('livewire.frontend.recherche.these-page', [
            'theses' => $this->theses,
        ])->layout('layouts.frontend', [
            'meta_title' => 'Répertoire des thèses - EDGVM',
        ]);
    }
}
