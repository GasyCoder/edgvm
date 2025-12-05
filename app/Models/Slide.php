<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasFactory;

    protected $fillable = [
        'slider_id',
        'titre',
        'description',
        'image_id',
        'lien_cta',
        'texte_cta',
        'ordre',
        'visible',
    ];

    protected $casts = [
        'visible' => 'boolean',
    ];

    // Relations
    public function slider()
    {
        return $this->belongsTo(Slider::class);
    }

    public function image()
    {
        return $this->belongsTo(Media::class);
    }

    // Scopes
    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }
}
