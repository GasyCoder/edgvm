<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    use HasFactory;

    protected $table = 'page_sections';
    protected $fillable = [
        'page_id',
        'titre',
        'contenu',
        'image_id',
        'ordre',
    ];

    // Relations
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function image()
    {
        return $this->belongsTo(Media::class);
    }
}
