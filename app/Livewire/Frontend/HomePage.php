<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Slider;
use App\Models\EAD;
use App\Models\Actualite;
use App\Models\Partenaire;
use App\Models\MessageDirection;

class HomePage extends Component
{
    public function render()
    {
        // Slider homepage + slides visibles
        $slider = Slider::with(['slides' => function ($query) {
                $query->visible()
                    ->with('image')
                    ->orderBy('ordre');
            }])
            ->visible()
            ->byPosition('homepage')
            ->first();

        // Mot de la Directrice : dernier message visible
        $messageDirection = MessageDirection::where('visible', true)
            ->latest()
            ->first();

        // Statistiques (soit à partir du message, soit valeurs par défaut)
        $stats = [
            'doctorants'       => $messageDirection?->nb_doctorants ?? 0,
            'equipes'          => $messageDirection?->nb_equipes ?? 0,
            'theses_soutenues' => $messageDirection?->nb_theses ?? 0,
            'encadrants'       => 30,
            'publications'     => 150,
            'hdr_soutenues'    => 2,
        ];

        // Équipes d'accueil (EAD)
        $eads = EAD::with(['responsable.user', 'specialites'])
            ->withCount(['specialites', 'theses'])
            ->active()
            ->take(4)
            ->get();

        // Actualités
        $actualites = Actualite::with(['category', 'image', 'tags'])
            ->published()
            ->orderByDesc('est_important')
            ->orderByDesc('date_publication')
            ->limit(3)
            ->get();

        // Partenaires visibles
        $partenaires = Partenaire::visible()
            ->ordered()
            ->get();

        return view('livewire.frontend.home-page', [
            'slider'           => $slider,
            'messageDirection' => $messageDirection,
            'stats'            => $stats,
            'eads'             => $eads,
            'actualites'       => $actualites,
            'partenaires'      => $partenaires,
        ])->layout('layouts.frontend');
    }
}
