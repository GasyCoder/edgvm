<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialite extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'ead_id',
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
    public function ead()
    {
        return $this->belongsTo(EAD::class, 'ead_id');
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class, 'specialite_id');
    }

    public function theses()
    {
        return $this->hasMany(These::class, 'specialite_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereHas('ead');
    }

    // Accessors
    public function getThesesEnCoursAttribute()
    {
        return $this->theses()->enCours()->count();
    }

    public function getThesesSoutenuesAttribute()
    {
        return $this->theses()->soutendue()->count();
    }
}