<?php

namespace App\Livewire\Admin\Jurys;

use App\Models\Jury;
use Livewire\Component;

class JuryCreate extends Component
{
    public $nom = '';
    public $grade = '';
    public $universite = '';
    public $email = '';
    public $phone = '';

    protected function rules()
    {
        return [
            'nom'        => 'required|string|max:255',
            'grade'      => 'nullable|string|max:255',
            'universite' => 'nullable|string|max:255',
            'email'      => 'nullable|email|max:255',
            'phone'      => 'nullable|string|max:30',
        ];
    }

    protected $messages = [
        'nom.required' => 'Le nom est obligatoire.',
        'email.email'  => 'L’adresse email doit être valide.',
    ];

    public function save()
    {
        $this->validate();

        Jury::create([
            'nom'        => $this->nom,
            'grade'      => $this->grade ?: null,
            'universite' => $this->universite ?: null,
            'email'      => $this->email ?: null,
            'phone'      => $this->phone ?: null,
        ]);

        session()->flash('success', 'Membre du jury créé avec succès.');
        return redirect()->route('admin.jurys.index');
    }

    public function render()
    {
        return view('livewire.admin.jurys.jury-create');
    }
}