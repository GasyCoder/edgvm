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
    ];

    // Relations
    public function ead()
    {
        return $this->belongsTo(EAD::class);
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function theses()
    {
        return $this->hasMany(These::class);
    }
}
