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
        'sujet_these',
        'description',
        'specialite_id',
        'ead_id',
        'universite_soutenance',
        'date_debut',
        'date_prevue_fin',
        'date_publication',
        'statut',
        'media_id',
        'resume_these',
        'mots_cles',
    ];

    protected $casts = [
        'date_debut'       => 'date',
        'date_prevue_fin'  => 'date',
        'date_publication' => 'date',
    ];

    // ==================== RELATIONS ====================

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
        return $this->belongsTo(EAD::class, 'ead_id');
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

    public function directeur()
    {
        return $this->encadrants->firstWhere('pivot.role', 'directeur');
    }

    public function codirecteur()
    {
        return $this->encadrants->firstWhere('pivot.role', 'codirecteur');
    }

    public function jurys()
    {
        return $this->belongsToMany(Jury::class, 'these_jury')
            ->withPivot(['role', 'ordre'])
            ->withTimestamps();
    }

    // ==================== SCOPES ====================

    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    public function scopeSoutendue($query)
    {
        return $query->where('statut', 'soutenue');
    }

    public function scopeEnPreparation($query)
    {
        return $query->where('statut', 'preparation');
    }

    // ==================== ACCESSORS ====================

    public function getFichierPdfUrlAttribute()
    {
        return $this->fichier?->url;
    }

    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            'preparation' => 'En préparation',
            'en_cours'    => 'En cours',
            'soutenue'    => 'Soutenue',
            default       => 'Non défini',
        };
    }

    public function getStatutBadgeClassesAttribute(): string
    {
        return match ($this->statut) {
            'preparation' => 'bg-amber-50 text-amber-700 border-amber-200',
            'en_cours'    => 'bg-blue-50 text-blue-700 border-blue-200',
            'soutenue'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            default       => 'bg-gray-100 text-gray-600 border-gray-200',
        };
    }
}
