<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Actualite extends Model
{
    use HasFactory, SoftDeletes;

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

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function incrementVues()
    {
        $this->increment('vues');
    }

    public function getVuesFormattedAttribute()
    {
        if ($this->vues >= 1000) {
            return number_format($this->vues / 1000, 1).'k';
        }

        return $this->vues;
    }

    public function auteur()
    {
        return $this->belongsTo(User::class, 'auteur_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'actualite_category')->withTimestamps();
    }

    public function image()
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

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
        return $query->whereHas('categories', function ($q) use ($categoryId) {
            $q->where('categories.id', $categoryId);
        });
    }

    public function scopeByTag($query, $tagId)
    {
        return $query->whereHas('tags', function ($q) use ($tagId) {
            $q->where('tags.id', $tagId);
        });
    }

    public function scopeImportant($query)
    {
        return $query->where('est_important', true);
    }

    public function scopeWithSlug($query)
    {
        return $query->whereNotNull('slug')->where('slug', '!=', '');
    }

    protected static function booted()
    {
        static::creating(function ($actualite) {
            if (empty($actualite->slug) && ! empty($actualite->titre)) {
                $actualite->slug = self::generateUniqueSlug($actualite->titre);
            }
        });

        static::updating(function ($actualite) {
            if ($actualite->isDirty('titre') && empty($actualite->slug)) {
                $actualite->slug = self::generateUniqueSlug($actualite->titre, $actualite->id);
            }
        });
    }

    private static function generateUniqueSlug($titre, $ignoreId = null)
    {
        $slug = Str::slug($titre);
        $originalSlug = $slug;
        $count = 1;

        $query = self::where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->exists()) {
            $slug = $originalSlug.'-'.$count;
            $count++;
            $query = self::where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
        }

        return $slug;
    }
}
