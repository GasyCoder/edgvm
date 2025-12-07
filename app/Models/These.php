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
        'media_id',        // ✅ lien vers table media
        'resume_these',
        'mots_cles',
    ];

    protected $casts = [
        'date_debut'       => 'date',
        'date_prevue_fin'  => 'date',
        'date_publication' => 'date',
    ];

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

    // Encadrants via pivot
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

    // ✅ URL pratique du PDF
    public function getFichierPdfUrlAttribute()
    {
        // Media a déjà un accessor "url"
        return $this->fichier?->url;
    }
}
