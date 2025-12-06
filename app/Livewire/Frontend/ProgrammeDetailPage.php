<?php

namespace App\Livewire\Frontend;

use App\Models\Specialite;
use Livewire\Component;

class ProgrammeDetailPage extends Component
{
    public Specialite $specialite;

    public function mount(Specialite $specialite)
    {
        $this->specialite = $specialite;
    }

    public function render()
    {
        $this->specialite->load(['ead', 'ead.responsable', 'theses' => function($query) {
            $query->enCours()->with('doctorant'); // ← Utiliser le scope enCours()
        }]);

        // Autres spécialités de la même EAD
        $autresSpecialites = Specialite::where('ead_id', $this->specialite->ead_id)
            ->where('id', '!=', $this->specialite->id)
            ->limit(3)
            ->get();

        return view('livewire.frontend.programme.programme-detail-page', [
            'autresSpecialites' => $autresSpecialites,
        ])
        ->layout('layouts.frontend')
        ->title($this->specialite->nom . ' - EDGVM');
    }
}