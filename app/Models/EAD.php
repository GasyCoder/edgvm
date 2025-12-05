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
    ];

    // Relations
    public function responsable()
    {
        return $this->belongsTo(Encadrant::class, 'responsable_id');
    }

    public function specialites()
    {
        return $this->hasMany(Specialite::class);
    }

    public function theses()
    {
        return $this->hasMany(These::class);
    }
}
