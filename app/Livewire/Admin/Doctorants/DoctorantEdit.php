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
    
    // Compte utilisateur
    public $name = '';
    public $email = '';
    public $password = '';

    // Infos doctorant
    public $matricule = '';
    public $niveau = '';
    public $phone = '';
    public $adresse = '';
    public $date_inscription = '';
    public $date_naissance = '';
    public $lieu_naissance = '';
    public $statut = '';

    // 🔹 Infos liées à la thèse en cours (pas dans la table doctorants)
    public $sujet_these = '';
    public $ead_id = '';
    public $directeur_id = '';
    public $codirecteur_id = '';

    public function mount(Doctorant $doctorant)
    {
        // On charge aussi les thèses + encadrants + ead
        $this->doctorant = $doctorant->load([
            'user',
            'theses.encadrants.user',
            'theses.ead',
        ]);

        // ---- Compte utilisateur ----
        $this->name  = $this->doctorant->user?->name ?? '';
        $this->email = $this->doctorant->user?->email ?? '';

        // ---- Données doctorant ----
        $this->matricule        = $this->doctorant->matricule;
        $this->niveau           = $this->doctorant->niveau;
        $this->phone            = $this->doctorant->phone;
        $this->adresse          = $this->doctorant->adresse;
        $this->date_inscription = $this->doctorant->date_inscription?->format('Y-m-d');
        $this->date_naissance   = $this->doctorant->date_naissance?->format('Y-m-d');
        $this->lieu_naissance   = $this->doctorant->lieu_naissance;
        $this->statut           = $this->doctorant->statut;

        // ---- Thèse en cours (s'il y en a une) ----
        $theseActive = $this->doctorant->theses
            ->firstWhere('statut', 'en_cours');

        if ($theseActive) {
            $this->sujet_these = $theseActive->sujet_these ?? '';
            $this->ead_id      = $theseActive->ead_id ?? '';

            $directeur = $theseActive->encadrants
                ->firstWhere('pivot.role', 'directeur');

            $codirecteur = $theseActive->encadrants
                ->firstWhere('pivot.role', 'codirecteur');

            $this->directeur_id   = $directeur?->id ?? '';
            $this->codirecteur_id = $codirecteur?->id ?? '';
        }
    }

    protected function rules()
    {
        return [
            // ✅ Doctorant
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email,' . $this->doctorant->user_id,
            'password'         => 'nullable|min:8',
            'matricule'        => 'required|string|unique:doctorants,matricule,' . $this->doctorant->id,
            'niveau'           => 'required|in:D1,D2,D3',
            'phone'            => 'nullable|string|max:20',
            'adresse'          => 'nullable|string',
            'date_inscription' => 'required|date',
            'date_naissance'   => 'nullable|date',
            'lieu_naissance'   => 'nullable|string|max:255',
            'statut'           => 'required|in:actif,diplome,suspendu,abandonne',

            // ✅ Thèse en cours (optionnels ici : la création impose déjà les contraintes)
            'sujet_these'    => 'nullable|string',
            'ead_id'         => 'nullable|exists:eads,id',
            'directeur_id'   => 'nullable|exists:encadrants,id',
            'codirecteur_id' => 'nullable|different:directeur_id|exists:encadrants,id',
        ];
    }

    protected $messages = [
        'name.required'      => 'Le nom est obligatoire.',
        'email.required'     => 'L\'email est obligatoire.',
        'email.unique'       => 'Cet email existe déjà.',
        'password.min'       => 'Le mot de passe doit contenir au moins 8 caractères.',
        'matricule.required' => 'Le matricule est obligatoire.',
        'matricule.unique'   => 'Ce matricule existe déjà.',
        'niveau.required'    => 'Le niveau est obligatoire.',
        'date_inscription.required' => 'La date d\'inscription est obligatoire.',
        'ead_id.exists'            => 'L’EAD sélectionnée est invalide.',
        'directeur_id.exists'      => 'Le directeur sélectionné est invalide.',
        'codirecteur_id.exists'    => 'Le co-directeur sélectionné est invalide.',
        'codirecteur_id.different' => 'Le co-directeur doit être différent du directeur.',
    ];

    public function save()
    {
        $this->validate();

        /**
         * 1) Mettre à jour l'utilisateur lié
         */
        if ($this->doctorant->user) {
            $userData = [
                'name'  => $this->name,
                'email' => $this->email,
            ];

            if ($this->password) {
                $userData['password'] = Hash::make($this->password);
            }

            $this->doctorant->user->update($userData);
        }

        /**
         * 2) Mettre à jour les infos du doctorant
         *    👉 On ne touche plus à EAD, sujet, directeur, codirecteur ici
         */
        $this->doctorant->update([
            'matricule'        => $this->matricule,
            'niveau'           => $this->niveau,
            'phone'            => $this->phone,
            'adresse'          => $this->adresse,
            'date_inscription' => $this->date_inscription,
            'date_naissance'   => $this->date_naissance,
            'lieu_naissance'   => $this->lieu_naissance,
            'statut'           => $this->statut,
        ]);

        /**
         * 3) Mettre à jour la thèse en cours (si elle existe)
         *    - sujet_these
         *    - ead_id
         *    - directeur / codirecteur -> table pivot these_encadrants
         */
        $theseActive = $this->doctorant
            ->theses()
            ->enCours()
            ->with('encadrants')
            ->first();

        if ($theseActive) {
            // 3.1. Mettre à jour le sujet + EAD
            $dataThese = [];

            if (!empty($this->sujet_these)) {
                $dataThese['sujet_these'] = $this->sujet_these;
            }

            if (!empty($this->ead_id)) {
                $dataThese['ead_id'] = $this->ead_id;
            }

            if (!empty($dataThese)) {
                $theseActive->update($dataThese);
            }

            // 3.2. Mettre à jour directeur / codirecteur
            // On supprime les anciens directeur/codirecteur
            $theseActive->encadrants()
                ->wherePivotIn('role', ['directeur', 'codirecteur'])
                ->detach();

            // On rattache les nouveaux s'ils sont fournis
            $attachData = [];

            if ($this->directeur_id) {
                $attachData[$this->directeur_id] = ['role' => 'directeur'];
            }

            if ($this->codirecteur_id) {
                $attachData[$this->codirecteur_id] = ['role' => 'codirecteur'];
            }

            if (!empty($attachData)) {
                $theseActive->encadrants()->attach($attachData);
            }
        }

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
        $thesesCount     = $this->doctorant->theses()->count();
        $thesesEnCours   = $this->doctorant->theses()->enCours()->count();
        $thesesSoutenues = $this->doctorant->theses()->soutendue()->count();

        return view('livewire.admin.doctorants.doctorant-edit', [
            'encadrants'      => $encadrants,
            'eads'            => $eads,
            'thesesCount'     => $thesesCount,
            'thesesEnCours'   => $thesesEnCours,
            'thesesSoutenues' => $thesesSoutenues,
        ])->layout('layouts.app');
    }
}
