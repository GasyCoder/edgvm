<?php

namespace App\Livewire\Admin\Doctorants;

use App\Models\Doctorant;
use App\Models\Encadrant;
use App\Models\EAD;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class DoctorantEdit extends Component
{
    public Doctorant $doctorant;
    
    public $name = '';
    public $email = '';
    public $password = '';
    public $matricule = '';
    public $niveau = '';
    public $sujet_these = '';
    public $directeur_id = '';
    public $codirecteur_id = '';
    public $ead_id = '';
    public $phone = '';
    public $adresse = '';
    public $date_inscription = '';
    public $date_naissance = '';
    public $lieu_naissance = '';
    public $statut = '';

    public function mount(Doctorant $doctorant)
    {
        $this->doctorant = $doctorant;
        $this->name = $doctorant->user->name;
        $this->email = $doctorant->user->email;
        $this->matricule = $doctorant->matricule;
        $this->niveau = $doctorant->niveau;
        $this->sujet_these = $doctorant->sujet_these;
        $this->directeur_id = $doctorant->directeur_id;
        $this->codirecteur_id = $doctorant->codirecteur_id;
        $this->ead_id = $doctorant->ead_id;
        $this->phone = $doctorant->phone;
        $this->adresse = $doctorant->adresse;
        $this->date_inscription = $doctorant->date_inscription->format('Y-m-d');
        $this->date_naissance = $doctorant->date_naissance?->format('Y-m-d');
        $this->lieu_naissance = $doctorant->lieu_naissance;
        $this->statut = $doctorant->statut;
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->doctorant->user_id,
            'password' => 'nullable|min:8',
            'matricule' => 'required|string|unique:doctorants,matricule,' . $this->doctorant->id,
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
        'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        'matricule.required' => 'Le matricule est obligatoire.',
        'matricule.unique' => 'Ce matricule existe déjà.',
        'niveau.required' => 'Le niveau est obligatoire.',
        'directeur_id.required' => 'Le directeur de thèse est obligatoire.',
        'ead_id.required' => 'L\'EAD est obligatoire.',
        'date_inscription.required' => 'La date d\'inscription est obligatoire.',
    ];

    public function save()
    {
        $validated = $this->validate();

        // Mettre à jour l'utilisateur
        $userData = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->password) {
            $userData['password'] = Hash::make($this->password);
        }

        $this->doctorant->user->update($userData);

        // Mettre à jour le doctorant
        $this->doctorant->update([
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

        session()->flash('success', '✅ Doctorant modifié avec succès !');

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

        // Stats
        $thesesCount = $this->doctorant->theses()->count();
        $thesesEnCours = $this->doctorant->theses()->enCours()->count();
        $thesesSoutenues = $this->doctorant->theses()->soutendue()->count();

        return view('livewire.admin.doctorants.doctorant-edit', [
            'encadrants' => $encadrants,
            'eads' => $eads,
            'thesesCount' => $thesesCount,
            'thesesEnCours' => $thesesEnCours,
            'thesesSoutenues' => $thesesSoutenues,
        ])->layout('layouts.app');
    }
}