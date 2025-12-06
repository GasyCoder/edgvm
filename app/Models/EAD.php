<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EAD extends Model
{
    use HasFactory;

    protected $table = 'eads';
    protected $fillable = [
        'nom',
        'description',
        'responsable_id',
        'domaine',
        'slug',
    ];

    /**
     * Configure le route model binding pour utiliser le slug
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Relations - SPÉCIFIER LA CLÉ ÉTRANGÈRE
    public function responsable()
    {
        return $this->belongsTo(Encadrant::class, 'responsable_id');
    }

    public function specialites()
    {
        return $this->hasMany(Specialite::class, 'ead_id');
    }

    public function theses()
    {
        return $this->hasMany(These::class, 'ead_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->has('specialites');
    }

    // Accessors
    public function getDoctoratsCountAttribute()
    {
        return $this->theses()->enCours()->count();
    }

    public function getThesesSoutenuesCountAttribute()
    {
        return $this->theses()->soutendue()->count();
    }
}