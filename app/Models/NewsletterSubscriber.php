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
        'abonne_le',
        'desabonne_le',
    ];

    protected $casts = [
        'actif'        => 'boolean',
        'abonne_le'    => 'datetime',
        'desabonne_le' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $subscriber) {
            // Normaliser email
            if (!empty($subscriber->email)) {
                $subscriber->email = strtolower(trim($subscriber->email));
            }

            if (empty($subscriber->token)) {
                $subscriber->token = Str::random(32);
            }

            if (empty($subscriber->abonne_le)) {
                $subscriber->abonne_le = now();
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
