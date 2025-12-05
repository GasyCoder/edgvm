<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctorant_id',
        'specialite_id',
        'statut',
        'date_depot',
        'date_validation',
        'commentaires',
    ];

    protected $casts = [
        'date_depot' => 'date',
        'date_validation' => 'date',
    ];

    // Relations
    public function doctorant()
    {
        return $this->belongsTo(Doctorant::class);
    }

    public function specialite()
    {
        return $this->belongsTo(Specialite::class);
    }

    public function documents()
    {
        return $this->hasMany(InscriptionDocument::class);
    }

    // Scopes
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeValidee($query)
    {
        return $query->where('statut', 'validee');
    }

    public function scopeRejetee($query)
    {
        return $query->where('statut', 'rejetee');
    }
}
