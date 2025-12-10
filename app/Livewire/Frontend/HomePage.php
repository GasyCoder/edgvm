<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Slider;
use App\Models\EAD;
use App\Models\Actualite;
use App\Models\Partenaire;
use App\Models\MessageDirection;
use App\Models\Evenement; // <-- IMPORTANT

class HomePage extends Component
{
    public function render()
    {
        $slider = Slider::with(['slides' => function($query) {
                $query->visible()
                      ->with('image')
                      ->orderBy('ordre');
            }])
            ->visible()
            ->byPosition('homepage')
            ->first();

        // Message de la Directrice (dernier visible)
        $messageDirection = MessageDirection::where('visible', true)
            ->latest()
            ->first();

        // Stats (tu peux les adapter si besoin)
        $stats = [
            'doctorants'        => $messageDirection?->nb_doctorants ?? 0,
            'equipes'           => $messageDirection?->nb_equipes ?? 0,
            'theses_soutenues'  => $messageDirection?->nb_theses ?? 0,
            'encadrants'        => 30,
            'publications'      => 150,
            'hdr_soutenues'     => 2,
        ];

        $eads = EAD::with(['responsable.user', 'specialites'])
            ->withCount(['specialites', 'theses'])
            ->active()
            ->take(4)
            ->get();

        $actualites = Actualite::with(['category', 'image', 'tags'])
            ->latest()
            ->published()
            ->orderBy('est_important', 'desc')
            ->orderBy('date_publication', 'desc')
            ->limit(3)
            ->get();

        $partenaires = Partenaire::where('visible', true)
            ->orderBy('ordre')
            ->get();

        // ✅ Événements FUTURS uniquement (agenda)
        $evenementsFuturs = Evenement::futurs()
            ->limit(4)
            ->get();

        return view('livewire.frontend.home-page', [
                'slider'            => $slider,
                'eads'              => $eads,
                'actualites'        => $actualites,
                'stats'             => $stats,
                'partenaires'       => $partenaires,
                'messageDirection'  => $messageDirection,
                'evenementsFuturs'  => $evenementsFuturs,
            ])
            ->layout('layouts.frontend');
    }
}
