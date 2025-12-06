<?php

namespace App\Livewire\Frontend;

use App\Models\EAD;
use Livewire\Component;

class EadDetailPage extends Component
{
    public EAD $ead;

    public function mount(EAD $ead)
    {
        $this->ead = $ead;
    }

    public function render()
    {
        $this->ead->load(['responsable', 'specialites', 'theses' => function($query) {
            $query->enCours()->with('doctorant'); // ← Utiliser le scope enCours()
        }]);

        return view('livewire.frontend.programme.ead-detail-page')
            ->layout('layouts.frontend')
            ->title($this->ead->nom . ' - EDGVM');
    }
}