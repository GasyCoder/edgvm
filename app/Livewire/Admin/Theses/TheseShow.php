<?php

namespace App\Livewire\Admin\Theses;

use App\Models\These;
use Livewire\Component;

class TheseShow extends Component
{
    public These $these;

    public function mount(These $these)
    {
        $this->these = $these->load([
            'doctorant.user',
            'doctorant.ead',
            'encadrants.user',
            'ead',
            'jurys',              // ✅ on charge aussi les jurys
        ]);
    }

    public function render()
    {
        return view('livewire.admin.theses.these-show');
    }
}
