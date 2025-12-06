<?php

namespace App\Livewire\Admin\Specialites;

use App\Models\Specialite;
use App\Models\EAD;
use Livewire\Component;
use Illuminate\Support\Str;

class SpecialiteEdit extends Component
{
    public Specialite $specialite;
    
    public $nom = '';
    public $description = '';
    public $ead_id = '';

    public function mount(Specialite $specialite)
    {
        $this->specialite = $specialite;
        $this->nom = $specialite->nom;
        $this->description = $specialite->description;
        $this->ead_id = $specialite->ead_id;
    }

    protected function rules()
    {
        return [
            'nom' => 'required|string|max:255|unique:specialites,nom,' . $this->specialite->id,
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

        $this->specialite->update($validated);

        session()->flash('success', '✅ Spécialité modifiée avec succès !');

        return redirect()->route('admin.specialites.index');
    }

    public function render()
    {
        $eads = EAD::orderBy('nom')->get();
        
        // Stats - UTILISER LES SCOPES BASÉS SUR STATUT
        $thesesEnCours = $this->specialite->theses()->enCours()->count(); // ← CORRIGÉ
        $thesesSoutenues = $this->specialite->theses()->soutendue()->count(); // ← CORRIGÉ

        return view('livewire.admin.specialites.specialite-edit', [
            'eads' => $eads,
            'thesesEnCours' => $thesesEnCours,
            'thesesSoutenues' => $thesesSoutenues,
        ])->layout('layouts.app');
    }
}