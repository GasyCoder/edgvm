<?php

namespace App\Livewire\Frontend;

use App\Models\Actualite;
use App\Models\Specialite;
use Livewire\Component;

class HomePage extends Component
{
    public function render()
    {
        // Vraies données EDGVM
        $stats = [
            'doctorants' => 109,
            'encadrants' => 30, // Estimation basée sur 8 équipes
            'theses_soutenues' => 23,
            'publications' => 150, // Estimation
            'equipes' => 8,
            'hdr_soutenues' => 2,
        ];

        $specialites = Specialite::take(3)->get();
        $actualites = Actualite::where('visible', true)->orderBy('date_publication', 'desc')
            ->take(3)
            ->get();

        return view('livewire.frontend.home-page', [
            'specialites' => $specialites,
            'actualites' => $actualites,
            'stats' => $stats,
        ])->layout('layouts.frontend');
    }
}

