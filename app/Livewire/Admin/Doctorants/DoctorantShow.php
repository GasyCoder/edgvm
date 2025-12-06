<?php

namespace App\Livewire\Admin\Doctorants;

use App\Models\Doctorant;
use Livewire\Component;

class DoctorantShow extends Component
{
    public Doctorant $doctorant;

    public function mount(Doctorant $doctorant)
    {
        $this->doctorant = $doctorant->load([
            'directeur.user',
            'codirecteur.user',
            'ead',
            'theses.specialite',
            'user'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.doctorants.doctorant-show')
            ->layout('layouts.app');
    }
}