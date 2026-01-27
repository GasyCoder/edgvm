<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encadrant extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenoms',
        'email',
        'genre',
        'grade',
        'specialite',
        'phone',
        'bureau',
    ];

    public function eads()
    {
        return $this->hasMany(EAD::class, 'responsable_id');
    }

    public function eadMemberships()
    {
        return $this->belongsToMany(EAD::class, 'ead_encadrants', 'encadrant_id', 'ead_id')
            ->withTimestamps();
    }

    public function theses()
    {
        return $this->belongsToMany(These::class, 'these_encadrants')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function soutenances()
    {
        return $this->belongsToMany(Soutenance::class, 'soutenance_jury')
            ->withPivot('role')
            ->withTimestamps();
    }

    // Scopes
    public function scopeByGrade($query, $grade)
    {
        return $query->where('grade', $grade);
    }

    public function scopeBySpecialite($query, $specialite)
    {
        return $query->where('specialite', $specialite);
    }

    public function getNameAttribute(): string
    {
        return trim(sprintf('%s %s', $this->nom, $this->prenoms));
    }

    public function getInitialsAttribute(): string
    {
        $name = trim($this->name);
        if ($name === '') {
            return '';
        }

        $parts = preg_split('/\s+/', $name);
        $initials = collect($parts)
            ->filter()
            ->map(fn ($part) => mb_substr($part, 0, 1))
            ->take(2)
            ->implode('');

        return mb_strtoupper($initials);
    }
}
