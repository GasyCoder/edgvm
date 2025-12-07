<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctorant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'matricule',
        'niveau',
        'phone',
        'adresse',
        'date_inscription',
        'date_naissance',
        'lieu_naissance',
        'statut',
    ];

    protected $casts = [
        'date_inscription' => 'date',
        'date_naissance'   => 'date',
    ];

    /**
     * Relations
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function theses()
    {
        return $this->hasMany(These::class, 'doctorant_id');
    }

    /**
     * Thèse en cours (si tu en as au plus une en_cours)
     */
    public function theseActive()
    {
        return $this->hasOne(These::class, 'doctorant_id')
            ->where('statut', 'en_cours');
    }

    /**
     * 🔁 EAD via la thèse (hasOneThrough)
     * Doctorant -> Theses (doctorant_id) -> EAD (ead_id)
     */
    public function ead()
    {
        return $this->hasOneThrough(
            EAD::class,    // modèle final
            These::class,  // modèle intermédiaire
            'doctorant_id',// theses.doctorant_id = doctorants.id
            'id',          // eads.id = theses.ead_id
            'id',          // doctorants.id
            'ead_id'       // theses.ead_id
        );
    }

    /**
     * Accessors pratiques
     */

    // Nom / email via User
    public function getNameAttribute()
    {
        return $this->user?->name ?? 'Pas de compte';
    }

    public function getEmailAttribute()
    {
        return $this->user?->email ?? 'N/A';
    }

    // Sujet de la thèse en cours
    public function getSujetTheseActuelAttribute()
    {
        return $this->theseActive?->sujet_these;
    }

    // Directeur de la thèse en cours
    public function getDirecteurActuelAttribute()
    {
        $these = $this->theseActive;

        if (! $these) {
            return null;
        }

        return $these->encadrants
            ->firstWhere('pivot.role', 'directeur');
    }

    // Co-directeur de la thèse en cours
    public function getCodirecteurActuelAttribute()
    {
        $these = $this->theseActive;

        if (! $these) {
            return null;
        }

        return $these->encadrants
            ->firstWhere('pivot.role', 'codirecteur');
    }

    /**
     * A un compte utilisateur ?
     */
    public function hasUser(): bool
    {
        return !is_null($this->user_id) && $this->user()->exists();
    }

    /**
     * Scopes
     */
    public function scopeAvecTheseEnCours($query)
    {
        return $query->whereHas('theses', function ($q) {
            $q->where('statut', 'en_cours');
        });
    }

    public function scopeSansTheseEnCours($query)
    {
        return $query->whereDoesntHave('theses', function ($q) {
            $q->where('statut', 'en_cours');
        });
    }
}
