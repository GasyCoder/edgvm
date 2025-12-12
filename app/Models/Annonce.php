<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'contenu_html',
        'cible',
        'media_id',
        'est_publie',
        'publie_at',
        'envoyer_email',
        'email_cible',
        'email_envoye_at',
        'auteur_id',
    ];

    protected $casts = [
        'est_publie'      => 'boolean',
        'envoyer_email'   => 'boolean',
        'publie_at'       => 'datetime',
        'email_envoye_at' => 'datetime',
    ];

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function auteur()
    {
        return $this->belongsTo(User::class, 'auteur_id');
    }

    public function getMediaUrlAttribute(): ?string
    {
        return $this->media?->url;
    }

    public function getMediaNameAttribute(): ?string
    {
        return $this->media?->display_name;
    }
}