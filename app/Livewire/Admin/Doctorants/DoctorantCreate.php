<?php

namespace App\Livewire\Admin\Doctorants;

use App\Models\Doctorant;
use App\Models\User;
use App\Models\Encadrant;
use App\Models\EAD;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class DoctorantCreate extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $matricule = '';
    public $niveau = 'D1';
    public $sujet_these = '';
    public $directeur_id = '';
    public $codirecteur_id = '';
    public $ead_id = '';
    public $phone = '';
    public $adresse = '';
    public $date_inscription = '';
    public $date_naissance = '';
    public $lieu_naissance = '';
    public $statut = 'actif';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'matricule' => 'required|string|unique:doctorants,matricule',
            'niveau' => 'required|in:D1,D2,D3',
            'sujet_these' => 'nullable|string',
            'directeur_id' => 'required|exists:encadrants,id',
            'codirecteur_id' => 'nullable|exists:encadrants,id',
            'ead_id' => 'required|exists:eads,id',
            'phone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string',
            'date_inscription' => 'required|date',
            'date_naissance' => 'nullable|date',
            'lieu_naissance' => 'nullable|string|max:255',
            'statut' => 'required|in:actif,diplome,suspendu,abandonne',
        ];
    }

    protected $messages = [
        'name.required' => 'Le nom est obligatoire.',
        'email.required' => 'L\'email est obligatoire.',
        'email.unique' => 'Cet email existe déjà.',
        'password.required' => 'Le mot de passe est obligatoire.',
        'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        'matricule.required' => 'Le matricule est obligatoire.',
        'matricule.unique' => 'Ce matricule existe déjà.',
        'niveau.required' => 'Le niveau est obligatoire.',
        'directeur_id.required' => 'Le directeur de thèse est obligatoire.',
        'directeur_id.exists' => 'Le directeur sélectionné n\'existe pas.',
        'ead_id.required' => 'L\'EAD est obligatoire.',
        'ead_id.exists' => 'L\'EAD sélectionnée n\'existe pas.',
        'date_inscription.required' => 'La date d\'inscription est obligatoire.',
    ];

    public function save()
    {
        $validated = $this->validate();

        // Créer l'utilisateur
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'doctorant',
            'active' => true,
        ]);

        // Créer le doctorant
        Doctorant::create([
            'user_id' => $user->id,
            'matricule' => $this->matricule,
            'niveau' => $this->niveau,
            'sujet_these' => $this->sujet_these,
            'directeur_id' => $this->directeur_id,
            'codirecteur_id' => $this->codirecteur_id,
            'ead_id' => $this->ead_id,
            'phone' => $this->phone,
            'adresse' => $this->adresse,
            'date_inscription' => $this->date_inscription,
            'date_naissance' => $this->date_naissance,
            'lieu_naissance' => $this->lieu_naissance,
            'statut' => $this->statut,
        ]);

        session()->flash('success', '✅ Doctorant créé avec succès !');

        return redirect()->route('admin.doctorants.index');
    }

    public function render()
    {
        $encadrants = Encadrant::with('user')
            ->join('users', 'encadrants.user_id', '=', 'users.id')
            ->orderBy('users.name')
            ->select('encadrants.*')
            ->get();

        $eads = EAD::orderBy('nom')->get();

        return view('livewire.admin.doctorants.doctorant-create', [
            'encadrants' => $encadrants,
            'eads' => $eads,
        ])->layout('layouts.app');
    }
}