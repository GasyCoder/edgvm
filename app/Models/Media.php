<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';
    protected $fillable = [
        'nom_original',
        'nom_fichier',
        'chemin',
        'type',
        'taille_bytes',
        'mime_type',
        'uploader_id',
    ];

    // Relations
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    // Accessors
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->chemin);
    }

    public function getDisplayNameAttribute()
    {
        return $this->nom_original;
    }
}
