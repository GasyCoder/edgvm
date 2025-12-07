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
     * Route model binding par slug
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Relations
     */
    public function ead()
    {
        return $this->belongsTo(EAD::class, 'ead_id');
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class, 'specialite_id');
    }

    /**
     * Thèses liées à cette spécialité via l'EAD
     * On utilise ead_id côté Specialite et côté These
     */
    public function theses()
    {
        // theses.ead_id == specialites.ead_id
        return $this->hasMany(These::class, 'ead_id', 'ead_id');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->whereHas('ead');
    }

    /**
     * Accessors : stats sur les thèses
     */
    public function getThesesEnCoursAttribute()
    {
        return $this->theses()->enCours()->count();
    }

    public function getThesesSoutenuesAttribute()
    {
        return $this->theses()->soutendue()->count();
    }
}