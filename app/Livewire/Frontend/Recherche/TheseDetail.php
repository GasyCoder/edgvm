<?php

namespace App\Livewire\Frontend\Recherche;

use App\Models\These;
use Livewire\Component;
use Livewire\Attributes\Title;

class TheseDetail extends Component
{
    public These $these;

    public function mount(These $these)
    {
        $this->these = $these->load([
            'doctorant',
            'specialite',
            'ead',
            'encadrants',
            'jurys',
            'fichier',
        ]);
    }

    public function render()
    {
        return view('livewire.frontend.recherche.these-detail', [
            'these' => $this->these,
        ])->layout('layouts.frontend', [
            'meta_title' => $this->these->sujet_these . ' - Thèse',
        ]);
    }
}
