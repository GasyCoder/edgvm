<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
