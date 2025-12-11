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
        'est_archive',
        'image_id',      // image de couverture
        'document_id',   // document PDF associé
    ];

    protected $casts = [
        'date_debut'    => 'date',
        'date_fin'      => 'date',
        'heure_debut'   => 'datetime:H:i',
        'heure_fin'     => 'datetime:H:i',
        'est_important' => 'boolean',
        'est_publie'    => 'boolean',
        'est_archive'   => 'boolean',
        'image_id'      => 'integer',
        'document_id'   => 'integer',
    ];

    /* ------------------------
       Relations
    -------------------------*/

    public function image()
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function document()
    {
        return $this->belongsTo(Media::class, 'document_id');
    }
    

    /* ------------------------
       Scopes
    -------------------------*/

    /**
     * Événements futurs (date_debut >= aujourd'hui), publiés, non archivés.
     */
    public function scopeFuturs(Builder $query): Builder
    {
        $today = Carbon::today()->toDateString();

        return $query
            ->whereDate('date_debut', '>=', $today)
            ->where('est_publie', true)
            ->where('est_archive', false)
            ->orderBy('date_debut', 'asc')
            ->orderBy('heure_debut', 'asc');
    }

    /**
     * Événements passés (date_debut < aujourd'hui).
     */
    public function scopePasses(Builder $query): Builder
    {
        $today = Carbon::today()->toDateString();

        return $query
            ->whereDate('date_debut', '<', $today)
            ->orderByDesc('date_debut');
    }

    /* ------------------------
       Accessors
    -------------------------*/

    public function getDateFrAttribute(): string
    {
        $date = $this->date_debut ? Carbon::parse($this->date_debut) : null;
        if (! $date) {
            return '';
        }
        setlocale(LC_TIME, 'fr_FR.UTF-8');
        return $date->isoFormat('D MMMM YYYY');
    }

    public function getJourAttribute(): string
    {
        return $this->date_debut ? Carbon::parse($this->date_debut)->format('d') : '';
    }

    public function getMoisAttribute(): string
    {
        if (! $this->date_debut) {
            return '';
        }
        $mois = Carbon::parse($this->date_debut)->locale('fr')->isoFormat('MMM');
        $mois = ucfirst(str_replace('.', '', $mois));
        return $mois;
    }

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

    public function getHeureDebutAffAttribute(): ?string
    {
        return $this->heure_debut ? Carbon::parse($this->heure_debut)->format('H:i') : null;
    }

    public function getPeriodeAffAttribute(): string
    {
        $debut = $this->date_debut ? Carbon::parse($this->date_debut)->isoFormat('D MMM YYYY') : '';
        $fin   = $this->date_fin ? Carbon::parse($this->date_fin)->isoFormat('D MMM YYYY') : null;

        if ($fin && $fin !== $debut) {
            return $debut . ' — ' . $fin;
        }

        return $debut;
    }

    /**
     * Indique si l’événement est terminé.
     */
    public function getEstTermineAttribute(): bool
    {
        if (! $this->date_debut && ! $this->date_fin) {
            return false;
        }

        $date = $this->date_fin ?? $this->date_debut;

        if (! $date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        if ($this->heure_fin) {
            $time = $this->heure_fin instanceof Carbon
                ? $this->heure_fin
                : Carbon::parse($this->heure_fin);

            $dateFin = $date->copy()->setTime($time->hour, $time->minute);
        } elseif ($this->heure_debut) {
            $time = $this->heure_debut instanceof Carbon
                ? $this->heure_debut
                : Carbon::parse($this->heure_debut);

            $dateFin = $date->copy()->setTime($time->hour, $time->minute);
        } else {
            $dateFin = $date->copy()->endOfDay();
        }

        return $dateFin->lt(now());
    }
}
