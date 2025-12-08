<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partenaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'logo_id',    // tu peux le garder si tu veux plus tard lier à Media
        'logo_path',  // nouveau champ pour l’upload direct
        'url',
        'ordre',
        'visible',
    ];

    protected $casts = [
        'visible' => 'boolean',
    ];

    // Relation éventuelle avec Media (si tu veux la garder)
    public function logo()
    {
        return $this->belongsTo(Media::class);
    }

    // Scopes
    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('ordre');
    }

    // Helper pour avoir l’URL du logo (upload direct ou Media)
    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo_path) {
            return asset('storage/'.$this->logo_path);
        }

        if ($this->logo && $this->logo->url) {
            return $this->logo->url;
        }

        return null;
    }
}
