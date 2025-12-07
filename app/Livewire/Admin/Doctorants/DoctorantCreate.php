<?php

namespace App\Livewire\Admin\Doctorants;

use App\Models\Doctorant;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DoctorantCreate extends Component
{
    public $name         = '';
    public $email        = '';
    public $matricule    = '';
    public $niveau       = 'D1';
    public $phone        = '';
    public $adresse      = '';
    public $date_inscription = '';
    public $date_naissance   = '';
    public $lieu_naissance   = '';
    public $statut       = 'actif';

    protected function rules()
    {
        return [
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email',
            'matricule'        => 'required|string|unique:doctorants,matricule',
            'niveau'           => 'required|in:D1,D2,D3',
            'phone'            => 'nullable|string|max:20',
            'adresse'          => 'nullable|string',
            'date_inscription' => 'required|date',
            'date_naissance'   => 'nullable|date',
            'lieu_naissance'   => 'nullable|string|max:255',
            'statut'           => 'required|in:actif,diplome,suspendu,abandonne',
        ];
    }

    protected $messages = [
        'name.required'             => 'Le nom complet est obligatoire.',
        'email.required'            => 'L\'email est obligatoire.',
        'email.unique'              => 'Cet email existe déjà.',
        'matricule.required'        => 'Le matricule est obligatoire.',
        'matricule.unique'          => 'Ce matricule existe déjà.',
        'niveau.required'           => 'Le niveau est obligatoire.',
        'date_inscription.required' => 'La date d\'inscription est obligatoire.',
    ];

    public function save()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                // 1. Créer l'utilisateur
                $user = User::create([
                    'name'     => $this->name,
                    'email'    => $this->email,
                    'password' => Hash::make('doctorant123'), // mot de passe par défaut
                    'role'     => 'doctorant',
                    'active'   => true,
                ]);

                // 2. Créer le doctorant ( uniquement les champs présents dans la table )
                Doctorant::create([
                    'user_id'         => $user->id,
                    'matricule'       => $this->matricule,
                    'niveau'          => $this->niveau,
                    'phone'           => $this->phone,
                    'adresse'         => $this->adresse,
                    'date_inscription'=> $this->date_inscription,
                    'date_naissance'  => $this->date_naissance,
                    'lieu_naissance'  => $this->lieu_naissance,
                    'statut'          => $this->statut,
                ]);
            });

            session()->flash('success', '✅ Doctorant créé avec succès ! Mot de passe par défaut : doctorant123');

            return redirect()->route('admin.doctorants.index');
            
        } catch (\Exception $e) {
            session()->flash('error', '❌ Erreur : ' . $e->getMessage());
        }
    }

    public function render()
    {
        // plus besoin d'encadrants / EAD ici
        return view('livewire.admin.doctorants.doctorant-create')
            ->layout('layouts.app');
    }
}
