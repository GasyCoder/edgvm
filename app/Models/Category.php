<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'slug',
        'description',
        'couleur',
        'visible',
    ];

    protected $casts = [
        'visible' => 'boolean',
    ];

    // Générer automatiquement le slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->nom);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('nom') && empty($category->slug)) {
                $category->slug = Str::slug($category->nom);
            }
        });
    }

    // Relations
    public function actualites()
    {
        return $this->belongsToMany(Actualite::class, 'actualite_category')->withTimestamps();
    }

    // Scopes
    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }
}
