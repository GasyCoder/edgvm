<?php

namespace App\Livewire\Admin\Sliders;

use App\Models\Slider;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Gestion des Sliders')]
class SliderIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingDeletion = false;
    public $sliderToDelete = null;

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($sliderId)
    {
        $this->sliderToDelete = $sliderId;
        $this->confirmingDeletion = true;
    }

    public function deleteSlider()
    {
        $slider = Slider::findOrFail($this->sliderToDelete);
        $slider->delete();

        $this->confirmingDeletion = false;
        $this->sliderToDelete = null;

        session()->flash('success', 'Slider supprimé avec succès!');
    }

    public function toggleVisibility($sliderId)
    {
        $slider = Slider::findOrFail($sliderId);
        $slider->update(['visible' => !$slider->visible]);

        session()->flash('success', 'Visibilité mise à jour!');
    }

    public function render()
    {
        $sliders = Slider::withCount('slides')
            ->when($this->search, function ($query) {
                $query->where('nom', 'like', '%' . $this->search . '%')
                      ->orWhere('position', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.sliders.slider-index', compact('sliders'));
    }
}