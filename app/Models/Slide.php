<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasFactory;

    protected $fillable = [
        'slider_id',
        'titre_ligne1',
        'titre_highlight',
        'titre_ligne2',
        'description',
        'image_id',
        'lien_cta',
        'texte_cta',
        'ordre',
        'visible',
        'badge_texte',
        'badge_icon',
        'couleur_fond',
        'actualite_id',
    ];

    protected $casts = [
        'visible' => 'boolean',
    ];

    // Relations
    public function slider()
    {
        return $this->belongsTo(Slider::class);
    }

    public function actualite()
    {
        return $this->belongsTo(Actualite::class, 'actualite_id');
    }

    public function image()
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    // Scopes
    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }

    // Accessor pour le titre complet (si besoin)
    public function getTitreCompletAttribute()
    {
        $titre = '';
        if ($this->titre_ligne1) {
            $titre .= $this->titre_ligne1 . ' ';
        }
        $titre .= $this->titre_highlight;
        if ($this->titre_ligne2) {
            $titre .= ' ' . $this->titre_ligne2;
        }
        return $titre;
    }


    /**
     * Retourne l'URL finale du CTA :
     * - si une actualité liée -> /actualites/{slug}
     * - sinon -> lien_cta (champ libre)
     */
    public function getCtaUrlAttribute()
    {
        if ($this->actualite && $this->actualite->slug) {
            return route('actualites.show', ['actualite' => $this->actualite->slug]);
        }

        return $this->lien_cta;
    }
}