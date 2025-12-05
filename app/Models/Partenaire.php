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
        'logo_id',
        'url',
        'ordre',
        'visible',
    ];

    protected $casts = [
        'visible' => 'boolean',
    ];

    // Relations
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
}
