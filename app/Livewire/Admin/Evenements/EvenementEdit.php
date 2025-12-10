<?php

namespace App\Livewire\Admin\Evenements;

use Livewire\Component;
use App\Models\Evenement;
use Illuminate\Validation\Rule;

class EvenementEdit extends Component
{
    public Evenement $evenement;

    public function mount(Evenement $evenement)
    {
        $this->evenement = $evenement;
    }

    protected function rules()
    {
        return [
            'evenement.titre' => ['required','string','max:255'],
            'evenement.description' => ['nullable','string'],
            'evenement.date_debut' => ['required','date'],
            'evenement.heure_debut' => ['nullable','date_format:H:i'],
            'evenement.date_fin' => ['nullable','date','after_or_equal:evenement.date_debut'],
            'evenement.heure_fin' => ['nullable','date_format:H:i'],
            'evenement.lieu' => ['nullable','string','max:255'],
            'evenement.type' => ['required', Rule::in(['soutenance','seminaire','conference','atelier','autre'])],
            'evenement.est_important' => ['boolean'],
            'evenement.lien_inscription' => ['nullable','url'],
            'evenement.est_publie' => ['boolean'],
        ];
    }

    protected $validationAttributes = [
        'evenement.date_debut' => 'date de début',
        'evenement.date_fin' => 'date de fin',
        'evenement.heure_debut' => 'heure de début',
        'evenement.heure_fin' => 'heure de fin',
    ];

    public function submit()
    {
        $this->validate();

        $this->evenement->save();

        session()->flash('success', 'Événement mis à jour avec succès.');
        return redirect()->route('admin.evenements.index');
    }

    public function render()
    {
        return view('livewire.admin.evenements.evenement-edit', [
            'evenement' => $this->evenement,
        ]);
    }
}