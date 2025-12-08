<?php

namespace App\Livewire\Frontend\Recherche;

use App\Models\EAD;
use Livewire\Component;
use Livewire\Attributes\Url;

class EadsPage extends Component
{
    #[Url]
    public $search = '';

    #[Url]
    public $domaine = '';

    #[Url]
    public $view = 'grid'; // 'grid' ou 'list'

    public function setView(string $view): void
    {
        $this->view = in_array($view, ['grid', 'list']) ? $view : 'grid';
    }

    public function setDomaine(?string $domaine): void
    {
        $this->domaine = $domaine ?? '';
    }

    public function render()
    {
        // Base query pour les domaines + données
        $baseQuery = EAD::query()->active();

        // Domaines distincts pour les filtres
        $domaines = (clone $baseQuery)
            ->whereNotNull('domaine')
            ->orderBy('domaine')
            ->pluck('domaine')
            ->unique()
            ->values();

        $query = $baseQuery
            ->with(['responsable', 'specialites', 'theses']); // responsable bien préchargé

        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('domaine', 'like', "%{$search}%");
            });
        }

        if ($this->domaine) {
            $query->where('domaine', $this->domaine);
        }

        $eads = $query->orderBy('nom')->get();

        return view('livewire.frontend.recherche.eads-page', [
            'eads'     => $eads,
            'domaines' => $domaines,
        ])
        ->layout('layouts.frontend')
        ->title('Équipes d\'Accueil Doctorales - EDGVM');
    }
}
