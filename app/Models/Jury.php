<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jury extends Model
{
    use HasFactory;

    protected $table = 'jurys';

    protected $fillable = [
        'nom',
        'grade',
        'universite',
        'email',
        'phone',
    ];

    public function theses()
    {
        return $this->belongsToMany(These::class, 'these_jury')
            ->withPivot(['role', 'ordre'])
            ->withTimestamps();
    }
}
