<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Evenement;

class EvenementsPage extends Component
{
    use WithPagination;

    public $typeFilter = '';
    public $perPage = 12;
    protected $paginationTheme = 'tailwind';
    protected $queryString = ['typeFilter', 'page'];

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Evenement::futurs();

        if ($this->typeFilter) {
            $query->where('type', $this->typeFilter);
        }

        $evenements = $query->paginate($this->perPage);

        return view('livewire.frontend.evenements-page', [
            'evenements' => $evenements,
        ])->layout('layouts.frontend');
    }
}
