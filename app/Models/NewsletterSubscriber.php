<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'nom',
        'type',
        'actif',
        'token',
        'desabonne_le',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'abonne_le' => 'datetime',
        'desabonne_le' => 'datetime',
    ];

    // Générer token automatiquement
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subscriber) {
            if (empty($subscriber->token)) {
                $subscriber->token = Str::random(32);
            }
        });
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}