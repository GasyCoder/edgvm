<?php

namespace App\Livewire\Admin\Evenements;

use Livewire\Component;
use App\Models\Evenement;
use Illuminate\Validation\Rule;

class EvenementCreate extends Component
{
    public $titre;
    public $description;
    public $date_debut;
    public $heure_debut;
    public $date_fin;
    public $heure_fin;
    public $lieu;
    public $type = 'autre';
    public $est_important = false;
    public $lien_inscription;
    public $est_publie = true;

    protected function rules()
    {
        return [
            'titre' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'date_debut' => ['required','date'],
            'heure_debut' => ['nullable','date_format:H:i'],
            'date_fin' => ['nullable','date','after_or_equal:date_debut'],
            'heure_fin' => ['nullable','date_format:H:i'],
            'lieu' => ['nullable','string','max:255'],
            'type' => ['required', Rule::in(['soutenance','seminaire','conference','atelier','autre'])],
            'est_important' => ['boolean'],
            'lien_inscription' => ['nullable','url'],
            'est_publie' => ['boolean'],
        ];
    }

    protected $validationAttributes = [
        'date_debut' => 'date de début',
        'date_fin' => 'date de fin',
        'heure_debut' => 'heure de début',
        'heure_fin' => 'heure de fin',
        'lien_inscription' => "lien d'inscription",
    ];

    public function submit()
    {
        $this->validate();

        Evenement::create([
            'titre' => $this->titre,
            'description' => $this->description,
            'date_debut' => $this->date_debut,
            'heure_debut' => $this->heure_debut,
            'date_fin' => $this->date_fin,
            'heure_fin' => $this->heure_fin,
            'lieu' => $this->lieu,
            'type' => $this->type,
            'est_important' => (bool)$this->est_important,
            'lien_inscription' => $this->lien_inscription,
            'est_publie' => (bool)$this->est_publie,
        ]);

        session()->flash('success', 'Événement créé avec succès.');
        return redirect()->route('admin.evenements.index');
    }

    public function render()
    {
        return view('livewire.admin.evenements.evenement-create');
    }
}