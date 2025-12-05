<?php

namespace App\Livewire\Admin\Sliders;

use App\Models\Slider;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Créer un slider')]
class SliderCreate extends Component
{
    public $nom = '';
    public $position = 'homepage';
    public $visible = true;

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

    public function save()
    {
        $this->validate();

        Slider::create([
            'nom' => $this->nom,
            'position' => $this->position,
            'visible' => $this->visible,
        ]);

        session()->flash('success', 'Slider créé avec succès !');

        return redirect()->route('admin.sliders.index');
    }

    public function render()
    {
        return view('livewire.admin.sliders.slider-create', [
            'title' => 'Créer un slider',
            'subtitle' => 'Ajouter un nouveau slider au site'
        ]);
    }
}