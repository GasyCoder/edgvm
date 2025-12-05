<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctorant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'matricule',
        'niveau',
        'phone',
        'adresse',
        'date_inscription',
        'statut',
    ];

    protected $casts = [
        'date_inscription' => 'date',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
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
}