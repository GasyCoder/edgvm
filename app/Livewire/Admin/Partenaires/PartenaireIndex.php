<?php

namespace App\Livewire\Admin\Partenaires;

use App\Models\Partenaire;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Partenaires')]
class PartenaireIndex extends Component
{
    use WithPagination;

    public string $search = '';

    protected $listeners = [
        'partnerDeleted' => '$refresh',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function toggleVisibility(int $id): void
    {
        $partenaire = Partenaire::findOrFail($id);
        $partenaire->visible = ! $partenaire->visible;
        $partenaire->save();

        session()->flash('success', 'Visibilité mise à jour.');
    }

    public function delete(int $id): void
    {
        $partenaire = Partenaire::findOrFail($id);
        $partenaire->delete();

        session()->flash('success', 'Partenaire supprimé avec succès.');
        $this->resetPage();
    }

    public function render()
    {
        $partenaires = Partenaire::query()
            ->when($this->search, function ($query) {
                $query->where('nom', 'like', '%'.$this->search.'%');
            })
            ->orderBy('ordre')
            ->orderBy('nom')
            ->paginate(10);

        return view('livewire.admin.partenaires.partenaire-index', [
            'partenaires' => $partenaires,
        ]);
    }
}
