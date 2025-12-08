<?php

namespace App\Livewire\Frontend\Recherche;

use App\Models\Specialite;
use App\Models\EAD;
use Livewire\Component;
use Livewire\Attributes\Url;

class ProgrammesPage extends Component
{
    #[Url]
    public $ead = '';

    #[Url]
    public $search = '';

    #[Url]
    public $view = 'grid'; // 'grid' ou 'list'

    public function filterByEad($eadSlug = null)
    {
        $this->ead = $eadSlug ?? '';
    }

    public function setView(string $view): void
    {
        $this->view = in_array($view, ['grid', 'list']) ? $view : 'grid';
    }

    public function clearFilters()
    {
        $this->reset(['ead', 'search']);
        // tu peux laisser la vue comme elle est, ou forcer :
        // $this->view = 'grid';
    }

    public function render()
    {
        $query = Specialite::with([
                'ead.responsable.user', // ← pour pouvoir afficher le responsable si besoin
                'theses',
            ])
            ->active();

        // Filtre par EAD
        if ($this->ead) {
            $query->whereHas('ead', function($q) {
                $q->where('slug', $this->ead);
            });
        }

        // Recherche
        if ($this->search) {
            $search = $this->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhereHas('ead', function($sub) use ($search) {
                      $sub->where('nom', 'like', '%' . $search . '%');
                  });
            });
        }

        $specialites = $query->orderBy('nom')->get();

        // Liste des EAD pour filtres
        $eads = EAD::active()
            ->withCount('specialites')
            ->orderBy('nom')
            ->get();

        return view('livewire.frontend.recherche.programmes-page', [
            'specialites' => $specialites,
            'eads'        => $eads,
        ])
        ->layout('layouts.frontend')
        ->title('Programmes Doctoraux - EDGVM');
    }
}
