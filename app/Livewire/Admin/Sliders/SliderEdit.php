<?php

namespace App\Livewire\Admin\Sliders;

use App\Models\Slider;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Modifier un slider')]
class SliderEdit extends Component
{
    public Slider $slider;
    public $nom;
    public $position;
    public $visible;

    protected $rules = [
        'nom' => 'required|string|max:255',
        'position' => 'required|string|max:255',
        'visible' => 'boolean',
    ];

    protected $messages = [
        'nom.required' => 'Le nom du slider est obligatoire.',
        'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
        'position.required' => 'La position est obligatoire.',
    ];

    public function mount(Slider $slider)
    {
        $this->slider = $slider;
        $this->nom = $slider->nom;
        $this->position = $slider->position;
        $this->visible = $slider->visible;
    }

    public function update()
    {
        $this->validate();

        $this->slider->update([
            'nom' => $this->nom,
            'position' => $this->position,
            'visible' => $this->visible,
        ]);

        session()->flash('success', 'Slider mis à jour avec succès !');

        return redirect()->route('admin.sliders.index');
    }

    public function render()
    {
        return view('livewire.admin.sliders.slider-edit', [
            'title' => 'Modifier le slider',
            'subtitle' => $this->slider->nom
        ]);
    }
}