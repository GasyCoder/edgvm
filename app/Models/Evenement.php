<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Evenement extends Model
{
    use HasFactory;

    protected $table = 'evenements';

    protected $fillable = [
        'titre',
        'description',
        'date_debut',
        'heure_debut',
        'date_fin',
        'heure_fin',
        'lieu',
        'type',
        'est_important',
        'lien_inscription',
        'est_publie',
    ];

    protected $casts = [
        'date_debut'    => 'date',
        'date_fin'      => 'date',
        'heure_debut'   => 'datetime:H:i',
        'heure_fin'     => 'datetime:H:i',
        'est_important' => 'boolean',
        'est_publie'    => 'boolean',
    ];

    /* ------------------------
       Scopes
    -------------------------*/

    /**
     * Événements futurs (date_debut >= aujourd'hui), publiés, triés croissant.
     */
    public function scopeFuturs(Builder $query): Builder
    {
        $today = Carbon::today()->toDateString();
        return $query->whereDate('date_debut', '>=', $today)
                     ->where('est_publie', true)
                     ->orderBy('date_debut', 'asc');
    }

    /**
     * Événements passés (date_debut < aujourd'hui).
     */
    public function scopePasses(Builder $query): Builder
    {
        $today = Carbon::today()->toDateString();
        return $query->whereDate('date_debut', '<', $today)
                     ->orderByDesc('date_debut');
    }

    /* ------------------------
       Accessors (attributes)
    -------------------------*/

    /**
     * Date formatée en français ex: "15 Décembre 2024"
     */
    public function getDateFrAttribute(): string
    {
        $date = $this->date_debut ? Carbon::parse($this->date_debut) : null;
        if (! $date) {
            return '';
        }
        setlocale(LC_TIME, 'fr_FR.UTF-8');
        return $date->isoFormat('D MMMM YYYY'); // exemple: 15 décembre 2024
    }

    /**
     * Retourne le jour (numérique) pour l'affichage en grand.
     */
    public function getJourAttribute(): string
    {
        return $this->date_debut ? Carbon::parse($this->date_debut)->format('d') : '';
    }

    /**
     * Retourne le mois court avec première lettre capitale (ex: "Déc").
     */
    public function getMoisAttribute(): string
    {
        if (! $this->date_debut) {
            return '';
        }
        $mois = Carbon::parse($this->date_debut)->locale('fr')->isoFormat('MMM'); // ex: déc.
        // Capitalize and remove trailing dot
        $mois = ucfirst(str_replace('.', '', $mois));
        return $mois;
    }

    /**
     * Classe Tailwind suivant le type d'événement.
     * (purple pour soutenance, blue pour seminaire, green pour conference, orange pour atelier, gray pour autre)
     */
    public function getTypeClasseAttribute(): string
    {
        return match ($this->type) {
            'soutenance' => 'bg-purple-600 text-white',
            'seminaire'  => 'bg-blue-600 text-white',
            'conference' => 'bg-green-600 text-white',
            'atelier'    => 'bg-orange-500 text-white',
            default      => 'bg-gray-400 text-white',
        };
    }

    /**
     * Texte lisible pour le type.
     */
    public function getTypeTexteAttribute(): string
    {
        return match ($this->type) {
            'soutenance' => 'Soutenance',
            'seminaire'  => 'Séminaire',
            'conference' => 'Conférence',
            'atelier'    => 'Atelier',
            default      => 'Autre',
        };
    }

    /**
     * Retourne l'heure de début formatée 'HH:mm' si disponible.
     */
    public function getHeureDebutAffAttribute(): ?string
    {
        return $this->heure_debut ? Carbon::parse($this->heure_debut)->format('H:i') : null;
    }

    /**
     * Retourne l'intervalle date/heure si date_fin présente sinon date_debut seul.
     */
    public function getPeriodeAffAttribute(): string
    {
        $debut = $this->date_debut ? Carbon::parse($this->date_debut)->isoFormat('D MMM YYYY') : '';
        $fin = $this->date_fin ? Carbon::parse($this->date_fin)->isoFormat('D MMM YYYY') : null;
        if ($fin && $fin !== $debut) {
            return $debut . ' — ' . $fin;
        }
        return $debut;
    }
}
