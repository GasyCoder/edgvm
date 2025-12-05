<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Slider;
use App\Models\Specialite;
use App\Models\Actualite;

class HomePage extends Component
{
    public function render()
    {
        // Récupérer le slider homepage avec ses slides visibles
        $slider = Slider::with(['slides' => function($query) {
            $query->visible()
                  ->with('image')
                  ->orderBy('ordre');
        }])
        ->visible()
        ->byPosition('homepage')
        ->first();
        
        $stats = [
            'doctorants' => 109,
            'encadrants' => 30,
            'theses_soutenues' => 23,
            'publications' => 150,
            'equipes' => 8,
            'hdr_soutenues' => 2,
        ];
        
        $specialites = Specialite::take(3)->get();
        $actualites = Actualite::where('visible', true)->orderBy('date_publication', 'desc')->take(3)->get();
        
        return view('livewire.frontend.home-page', compact('slider', 'specialites', 'actualites', 'stats'))
            ->layout('layouts.frontend');
    }
}