<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Evenement;

class EvenementDetailPage extends Component
{
    public Evenement $evenement;

    public function mount(Evenement $evenement)
    {
        $this->evenement = $evenement;
    }

    public function render()
    {
        return view('livewire.frontend.evenement-detail-page', [
            'evenement' => $this->evenement,
        ])->layout('layouts.frontend');
    }
}