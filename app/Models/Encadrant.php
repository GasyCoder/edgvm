<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encadrant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'grade',
        'specialite',
        'phone',
        'bureau',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function eads()
    {
        return $this->hasMany(EAD::class, 'responsable_id');
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
}