<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'position',
        'visible',
    ];

    protected $casts = [
        'visible' => 'boolean',
    ];

    // Relations
    public function slides()
    {
        return $this->hasMany(Slide::class)->orderBy('ordre');
    }

    // Scopes
    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }

    public function scopeByPosition($query, $position)
    {
        return $query->where('position', $position);
    }
}
