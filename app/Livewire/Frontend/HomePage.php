<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Slider;
use App\Models\Specialite;
use App\Models\Actualite;
use App\Models\Partenaire;

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
        
        // Actualités publiées avec relations (3 dernières)
        $actualites = Actualite::with(['category', 'image', 'tags'])
            ->published() // ← Utilise le scope pour filtrer visibles + date publication
            ->orderBy('est_important', 'desc') // ← Importantes en premier
            ->orderBy('date_publication', 'desc')
            ->limit(3)
            ->get();
        
        // Partenaires actifs (si vous en avez)
        $partenaires = Partenaire::where('visible', true)
            ->orderBy('ordre')
            ->get();
        
        return view('livewire.frontend.home-page', compact('slider', 'specialites', 'actualites', 'stats', 'partenaires'))
            ->layout('layouts.frontend');
    }
}