<?php

namespace App\Livewire\Admin\Encadrants;

use App\Models\Encadrant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class EncadrantEdit extends Component
{
    public Encadrant $encadrant;
    
    // Champs du formulaire
    public $name;
    public $email;
    public $grade;
    public $specialite;
    public $phone;
    public $bureau;
    
    // Gestion du compte utilisateur
    public $has_account = false;
    public $create_account = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'unique:users,email,' . ($this->encadrant->user_id ?? 'NULL')
            ],
            'grade' => 'required|string|in:Professeur Titulaire,Maître de Conférences,Maître Assistant,Assistant,Docteur',
            'specialite' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bureau' => 'nullable|string|max:100',
        ];
    }

    public function mount(Encadrant $encadrant)
    {
        $this->encadrant = $encadrant;
        
        // Charger les données
        if ($encadrant->user) {
            $this->has_account = true;
            $this->name = $encadrant->user->name;
            $this->email = $encadrant->user->email;
        } else {
            $this->has_account = false;
            $this->name = '';
            $this->email = '';
        }
        
        $this->grade = $encadrant->grade;
        $this->specialite = $encadrant->specialite;
        $this->phone = $encadrant->phone;
        $this->bureau = $encadrant->bureau;
    }

    public function save()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                // Gérer le compte utilisateur
                if ($this->create_account && !$this->has_account) {
                    // Créer un nouveau compte
                    $user = User::create([
                        'name' => $this->name,
                        'email' => $this->email,
                        'password' => Hash::make('password123'),
                        'role' => 'encadrant',
                        'active' => true,
                    ]);
                    
                    $this->encadrant->user_id = $user->id;
                } elseif ($this->has_account && $this->encadrant->user) {
                    // Mettre à jour le compte existant
                    $this->encadrant->user->update([
                        'name' => $this->name,
                        'email' => $this->email,
                    ]);
                }

                // Mettre à jour l'encadrant
                $this->encadrant->update([
                    'grade' => $this->grade,
                    'specialite' => $this->specialite,
                    'phone' => $this->phone,
                    'bureau' => $this->bureau,
                ]);
            });

            session()->flash('success', 'Encadrant modifié avec succès !');
            return redirect()->route('admin.encadrants.show', $this->encadrant);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la modification : ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.encadrants.encadrant-edit');
    }
}