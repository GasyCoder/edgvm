<?php

namespace App\Livewire\Admin\Jurys;

use App\Models\Jury;
use Livewire\Component;
use Livewire\WithPagination;

class JuryIndex extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $jury = Jury::findOrFail($id);
        $jury->delete();

        session()->flash('success', 'Membre du jury supprimé avec succès.');
    }

    public function render()
    {
        $jurys = Jury::query()
            ->when($this->search, function ($query) {
                $query->where('nom', 'like', '%' . $this->search . '%')
                    ->orWhere('universite', 'like', '%' . $this->search . '%')
                    ->orWhere('grade', 'like', '%' . $this->search . '%');
            })
            ->orderBy('nom')
            ->paginate(10);

        return view('livewire.admin.jurys.jury-index', [
            'jurys' => $jurys,
        ]);
    }
}