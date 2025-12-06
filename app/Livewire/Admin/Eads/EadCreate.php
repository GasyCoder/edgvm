<?php

namespace App\Livewire\Admin\Eads;

use App\Models\EAD;
use App\Models\Encadrant;
use Livewire\Component;
use Illuminate\Support\Str;

class EadCreate extends Component
{
    public $nom = '';
    public $description = '';
    public $responsable_id = '';
    public $domaine = '';

    protected function rules()
    {
        return [
            'nom' => 'required|string|max:255|unique:eads,nom',
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

        EAD::create($validated);

        session()->flash('success', '✅ EAD créée avec succès !');

        return redirect()->route('admin.ead.index');
    }

    public function render()
    {
        $encadrants = Encadrant::with('user')
            ->join('users', 'encadrants.user_id', '=', 'users.id')
            ->orderBy('users.name')
            ->select('encadrants.*') // Important : sélectionner uniquement les colonnes encadrants
            ->get();

        return view('livewire.admin.eads.ead-create', [
            'encadrants' => $encadrants,
        ])->layout('layouts.app');
    }
}