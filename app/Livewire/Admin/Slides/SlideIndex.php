<?php

namespace App\Livewire\Admin\Slides;

use App\Models\Slider;
use App\Models\Slide;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Gestion des slides')]
class SlideIndex extends Component
{
    use WithPagination;

    public Slider $slider;
    public $search = '';
    public $confirmingDeletion = false;
    public $slideToDelete = null;

    protected $queryString = ['search'];

    public function mount(Slider $slider)
    {
        $this->slider = $slider;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($slideId)
    {
        $this->slideToDelete = $slideId;
        $this->confirmingDeletion = true;
    }

    public function deleteSlide()
    {
        $slide = Slide::findOrFail($this->slideToDelete);
        $slide->delete();

        $this->confirmingDeletion = false;
        $this->slideToDelete = null;

        session()->flash('success', 'Slide supprimé avec succès!');
    }

    public function toggleVisibility($slideId)
    {
        $slide = Slide::findOrFail($slideId);
        $slide->update(['visible' => !$slide->visible]);

        session()->flash('success', 'Visibilité mise à jour!');
    }

    public function updateOrder($slides)
    {
        foreach ($slides as $slide) {
            Slide::where('id', $slide['value'])->update(['ordre' => $slide['order']]);
        }

        session()->flash('success', 'Ordre mis à jour!');
    }

    public function render()
    {
        $slides = $this->slider->slides()
            ->with('image')
            ->when($this->search, function ($query) {
                $query->where('titre_highlight', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('ordre')
            ->paginate(10);

        return view('livewire.admin.slides.slide-index', [
            'slides' => $slides,
            'title' => 'Gestion des slides',
            'subtitle' => $this->slider->nom
        ]);
    }
}