<?php

namespace App\Livewire\Admin\Eads;

use App\Models\EAD;
use App\Models\Encadrant;
use Livewire\Component;
use Illuminate\Support\Str;

class EadEdit extends Component
{
    public EAD $ead;
    
    public $nom = '';
    public $description = '';
    public $responsable_id = '';
    public $domaine = '';

    public function mount(EAD $ead)
    {
        $this->ead = $ead;
        $this->nom = $ead->nom;
        $this->description = $ead->description;
        $this->responsable_id = $ead->responsable_id;
        $this->domaine = $ead->domaine;
    }

    protected function rules()
    {
        return [
            'nom' => 'required|string|max:255|unique:eads,nom,' . $this->ead->id,
            'description' => 'required|string',
            'responsable_id' => 'required|exists:encadrants,id',
            'domaine' => 'nullable|string|max:255',
        ];
    }

    protected $messages = [
        'nom.required' => 'Le nom est obligatoire.',
        'nom.unique' => 'Ce nom existe déjà.',
        'description.required' => 'La description est obligatoire.',
        'responsable_id.required' => 'Le responsable est obligatoire.',
        'responsable_id.exists' => 'Le responsable sélectionné n\'existe pas.',
    ];

    public function save()
    {
        $validated = $this->validate();

        $validated['slug'] = Str::slug($this->nom);

        $this->ead->update($validated);

        session()->flash('success', '✅ EAD modifiée avec succès !');

        return redirect()->route('admin.ead.index');
    }

    public function render()
    {
        $encadrants = Encadrant::with('user')
            ->join('users', 'encadrants.user_id', '=', 'users.id')
            ->orderBy('users.name')
            ->select('encadrants.*')
            ->get();
        
        // Stats - UTILISER LES SCOPES BASÉS SUR STATUT
        $specialitesCount = $this->ead->specialites()->count();
        $thesesEnCours = $this->ead->theses()->enCours()->count(); // ← CORRIGÉ
        $thesesSoutenues = $this->ead->theses()->soutendue()->count(); // ← CORRIGÉ

        return view('livewire.admin.eads.ead-edit', [
            'encadrants' => $encadrants,
            'specialitesCount' => $specialitesCount,
            'thesesEnCours' => $thesesEnCours,
            'thesesSoutenues' => $thesesSoutenues,
        ])->layout('layouts.app');
    }
}