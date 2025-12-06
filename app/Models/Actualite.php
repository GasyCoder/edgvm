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
        'category_id',
        'image_id',
        'date_publication',
        'visible',
        'est_important',
        'notifier_abonnes', 
        'notification_envoyee_le',
        'vues', 
    ];

    protected $casts = [
        'date_publication' => 'date',
        'visible' => 'boolean',
        'vues' => 'integer', 
        'est_important' => 'boolean', 
        'notifier_abonnes' => 'boolean',
        'notification_envoyee_le' => 'datetime', 
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Méthode pour incrémenter les vues
    public function incrementVues()
    {
        $this->increment('vues');
    }

    // Helper pour formater les vues
    public function getVuesFormattedAttribute()
    {
        if ($this->vues >= 1000) {
            return number_format($this->vues / 1000, 1) . 'k';
        }
        return $this->vues;
    }

    // Relations
    public function auteur()
    {
        return $this->belongsTo(User::class, 'auteur_id');
    }

    public function category() 
    {
        return $this->belongsTo(Category::class);
    }

    public function image()
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    // ← AJOUTÉ : Relation galerie d'images
    public function galerie()
    {
        return $this->belongsToMany(Media::class, 'actualite_media')
                    ->withPivot('ordre')
                    ->withTimestamps()
                    ->orderBy('ordre');
    }

    public function tags() 
    {
        return $this->belongsToMany(Tag::class);
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

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByTag($query, $tagId) 
    {
        return $query->whereHas('tags', function($q) use ($tagId) {
            $q->where('tags.id', $tagId);
        });
    }

    public function scopeImportant($query)
    {
        return $query->where('est_important', true);
    }
}