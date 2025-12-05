<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soutenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'these_id',
        'date_soutenance',
        'heure_soutenance',
        'lieu',
        'resultat',
    ];

    protected $casts = [
        'date_soutenance' => 'date',
        'heure_soutenance' => 'datetime',
    ];

    // Relations
    public function these()
    {
        return $this->belongsTo(These::class);
    }

    public function jury()
    {
        return $this->belongsToMany(Encadrant::class, 'soutenance_jury')
            ->withPivot('role')
            ->withTimestamps();
    }

    // Scopes
    public function scopeAdmis($query)
    {
        return $query->where('resultat', 'admis');
    }

    public function scopeAjourne($query)
    {
        return $query->where('resultat', 'ajourne');
    }
}
