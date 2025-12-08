<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MenuItem extends Model
{
    use HasFactory;

    protected $table = 'menu_items';

    protected $fillable = [
        'menu_id',
        'label',
        'url',
        'page_id',
        'parent_id',
        'ordre',
        'visible',
        'icone',
    ];

    protected $casts = [
        'visible' => 'boolean',
    ];

    // Relations
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function enfants()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('ordre');
    }

    // Scopes
    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }

    /**
     * URL finale utilisée dans le menu.
     * - Si `page_id` est renseigné → route('pages.show', $page->slug)
     * - Sinon → `url` brute (interne ou externe)
     */
    public function getResolvedUrlAttribute(): string
    {
        // 1. Page liée
        if ($this->page) {
            return route('pages.show', $this->page->slug);
        }

        // 2. URL libre
        if ($this->url) {
            // URL absolue
            if (Str::startsWith($this->url, ['http://', 'https://'])) {
                return $this->url;
            }

            // URL commençant par "/" → chemin interne
            if (Str::startsWith($this->url, '/')) {
                return $this->url;
            }

            // Sinon on préfixe par "/"
            return '/' . ltrim($this->url, '/');
        }

        // Fallback
        return '#';
    }
}
