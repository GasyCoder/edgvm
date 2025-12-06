<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class These extends Model
{
    use HasFactory;

    protected $table = 'theses';
    protected $fillable = [
        'doctorant_id',
        'titre',
        'description',
        'specialite_id',
        'ead_id',
        'date_debut',
        'date_prevue_fin',
        'statut',
        'media_id',
        'resume_these',
        'mots_cles',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_prevue_fin' => 'date',
    ];

    // Relations - AVEC CLÉS ÉTRANGÈRES EXPLICITES
    public function doctorant()
    {
        return $this->belongsTo(Doctorant::class, 'doctorant_id');
    }

    public function specialite()
    {
        return $this->belongsTo(Specialite::class, 'specialite_id');
    }

    public function ead()
    {
        return $this->belongsTo(EAD::class, 'ead_id'); // ← IMPORTANT : Spécifier 'ead_id'
    }

    public function fichier()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function encadrants()
    {
        return $this->belongsToMany(Encadrant::class, 'these_encadrants')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function soutenance()
    {
        return $this->hasOne(Soutenance::class, 'these_id'); // ← Explicite aussi
    }

    public function publications()
    {
        return $this->hasMany(Publication::class, 'these_id'); // ← Explicite aussi
    }

    // Scopes
    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    public function scopeSoutendue($query)
    {
        return $query->where('statut', 'soutendue');
    }

    // Scope pour thèses sans date de soutenance (si vous utilisez aussi ce champ)
    public function scopeNonSoutendue($query)
    {
        return $query->whereIn('statut', ['en_cours', 'preparation', 'redaction']);
    }
}