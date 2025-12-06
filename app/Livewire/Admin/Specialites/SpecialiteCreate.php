<?php

namespace App\Livewire\Admin\Specialites;

use App\Models\Specialite;
use App\Models\EAD;
use Livewire\Component;
use Illuminate\Support\Str;

class SpecialiteCreate extends Component
{
    public $nom = '';
    public $description = '';
    public $ead_id = '';

    protected function rules()
    {
        return [
            'nom' => 'required|string|max:255|unique:specialites,nom',
            'description' => 'required|string',
            'ead_id' => 'required|exists:eads,id',
        ];
    }

    protected $messages = [
        'nom.required' => 'Le nom est obligatoire.',
        'nom.unique' => 'Ce nom existe déjà.',
        'description.required' => 'La description est obligatoire.',
        'ead_id.required' => 'L\'EAD est obligatoire.',
        'ead_id.exists' => 'L\'EAD sélectionnée n\'existe pas.',
    ];

    public function save()
    {
        $validated = $this->validate();

        $validated['slug'] = Str::slug($this->nom);

        Specialite::create($validated);

        session()->flash('success', '✅ Spécialité créée avec succès !');

        return redirect()->route('admin.specialites.index');
    }

    public function render()
    {
        $eads = EAD::orderBy('nom')->get();

        return view('livewire.admin.specialites.specialite-create', [
            'eads' => $eads,
        ])->layout('layouts.app');
    }
}