<?php

namespace App\Livewire\Admin\Encadrants;

use App\Models\Encadrant;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EncadrantCreate extends Component
{
    public $name = '';
    public $email = '';
    public $grade = '';
    public $specialite = '';
    public $phone = '';
    public $bureau = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'grade' => 'required|string|max:255',
            'specialite' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bureau' => 'nullable|string|max:255',
        ];
    }

    protected $messages = [
        'name.required' => 'Le nom complet est obligatoire.',
        'email.required' => 'L\'email est obligatoire.',
        'email.unique' => 'Cet email existe déjà.',
        'grade.required' => 'Le grade est obligatoire.',
    ];

    public function save()
    {
        $validated = $this->validate();

        try {
            DB::transaction(function () {
                // 1. Créer l'utilisateur
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make('password123'),
                    'role' => 'encadrant',
                    'active' => true,
                ]);

                // 2. Créer l'encadrant
                Encadrant::create([
                    'user_id' => $user->id,
                    'grade' => $this->grade,
                    'specialite' => $this->specialite,
                    'phone' => $this->phone,
                    'bureau' => $this->bureau,
                ]);
            });

            session()->flash('success', '✅ Encadrant créé avec succès ! Mot de passe : password123');

            return redirect()->route('admin.encadrants.index');
            
        } catch (\Exception $e) {
            session()->flash('error', '❌ Erreur : ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.encadrants.encadrant-create')->layout('layouts.app');
    }
}