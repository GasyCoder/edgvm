<?php

namespace App\Livewire\Admin\Encadrants;

use App\Models\Encadrant;
use Livewire\Component;

class EncadrantShow extends Component
{
    public Encadrant $encadrant;

    public function mount(Encadrant $encadrant)
    {
        $this->encadrant = $encadrant->load(['user', 'theses.doctorant.user', 'theses.ead']);
    }

    public function render()
    {
        return view('livewire.admin.encadrants.encadrant-show');
    }
}