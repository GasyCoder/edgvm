<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doctorant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'matricule',
        'niveau',
        'sujet_these',
        'directeur_id',
        'codirecteur_id',
        'ead_id',
        'phone',
        'adresse',
        'date_inscription',
        'date_naissance',
        'lieu_naissance',
        'statut',
    ];

    protected $casts = [
        'date_inscription' => 'date',
        'date_naissance' => 'date',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function directeur()
    {
        return $this->belongsTo(Encadrant::class, 'directeur_id');
    }

    public function codirecteur()
    {
        return $this->belongsTo(Encadrant::class, 'codirecteur_id');
    }

    public function ead()
    {
        return $this->belongsTo(EAD::class, 'ead_id');
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function theses()
    {
        return $this->hasMany(These::class);
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopeDiplome($query)
    {
        return $query->where('statut', 'diplome');
    }

    public function scopeSuspendu($query)
    {
        return $query->where('statut', 'suspendu');
    }

    public function scopeWithUser($query)
    {
        return $query->whereNotNull('user_id');
    }

    public function scopeWithoutUser($query)
    {
        return $query->whereNull('user_id');
    }

    public function getAgeAttribute()
    {
        if (!$this->date_naissance) {
            return null;
        }
        return $this->date_naissance->age;
    }

    // Helpers
    public function hasUser()
    {
        return !is_null($this->user_id);
    }

    public function createUserAccount($password = null)
    {
        if ($this->hasUser()) {
            return $this->user;
        }

        $user = User::create([
            'name' => $this->nom_complet,
            'email' => $this->email,
            'password' => Hash::make($password ?? 'password123'),
            'role' => 'doctorant',
            'active' => true,
        ]);

        $this->update(['user_id' => $user->id]);

        return $user;
    }

    // Accessor pour nom complet
    public function getNomCompletAttribute()
    {
        return $this->user ? $this->user->name : 'Pas de compte';
    }

    public function getNameAttribute()
    {
        // Si le doctorant a un compte user, utiliser user->name
        if ($this->user) {
            return $this->user->name;
        }
        
        // Sinon, construire le nom depuis nom et prenom
        return $this->nom . ' ' . $this->prenom;
    }
}