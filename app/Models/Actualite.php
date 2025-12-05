<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actualite extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'contenu',
        'auteur_id',
        'image_id',
        'date_publication',
        'visible',
    ];

    protected $casts = [
        'date_publication' => 'date',
        'visible' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relations
    public function auteur()
    {
        return $this->belongsTo(User::class);
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

    public function scopePublished($query)
    {
        return $query->where('visible', true)
            ->where('date_publication', '<=', now());
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('date_publication', 'desc');
    }
}
