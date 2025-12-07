<?php

namespace App\Livewire\Admin\Jurys;

use App\Models\Jury;
use Livewire\Component;

class JuryEdit extends Component
{
    public Jury $jury;

    public $nom;
    public $grade;
    public $universite;
    public $email;
    public $phone;

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

    public function mount(Jury $jury)
    {
        $this->jury = $jury;

        $this->nom        = $jury->nom;
        $this->grade      = $jury->grade;
        $this->universite = $jury->universite;
        $this->email      = $jury->email;
        $this->phone      = $jury->phone;
    }

    public function save()
    {
        $this->validate();

        $this->jury->update([
            'nom'        => $this->nom,
            'grade'      => $this->grade ?: null,
            'universite' => $this->universite ?: null,
            'email'      => $this->email ?: null,
            'phone'      => $this->phone ?: null,
        ]);

        session()->flash('success', 'Membre du jury mis à jour avec succès.');
        return redirect()->route('admin.jurys.index');
    }

    public function render()
    {
        return view('livewire.admin.jurys.jury-edit');
    }
}
