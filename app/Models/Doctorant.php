<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Doctorant extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (self $doctorant): void {
            if (empty($doctorant->uuid)) {
                $doctorant->uuid = (string) Str::uuid();
            }
        });
    }

    protected $fillable = [
        'user_id',
        'nom',
        'prenoms',
        'genre',
        'email',
        'ead_id',
        'matricule',
        'niveau',
        'phone',
        'adresse',
        'date_inscription',
        'date_naissance',
        'lieu_naissance',
        'statut',
        'observation',
        'archived_at',
    ];

    /**
     * Statuts considérés comme terminés (soutenu / abandon) → relèvent des archives.
     */
    public const STATUTS_TERMINES = ['diplome', 'abandonne'];

    protected $casts = [
        'date_inscription' => 'date',
        'date_naissance' => 'date',
        'archived_at' => 'datetime',
    ];

    public function isArchived(): bool
    {
        return $this->archived_at !== null || in_array($this->statut, self::STATUTS_TERMINES, true);
    }

    /**
     * Doctorants actifs / en cours de suivi (liste principale).
     */
    public function scopeActifs($query)
    {
        return $query->whereNull('archived_at')
            ->whereNotIn('statut', self::STATUTS_TERMINES);
    }

    /**
     * Doctorants terminés ou archivés manuellement.
     */
    public function scopeArchives($query)
    {
        return $query->where(function ($q) {
            $q->whereNotNull('archived_at')
                ->orWhereIn('statut', self::STATUTS_TERMINES);
        });
    }

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

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function reinscriptions()
    {
        return $this->hasMany(Reinscription::class);
    }

    /**
     * Niveaux du cursus doctoral (D3 standard, D4/D5 sur dérogation).
     */
    public const NIVEAUX = ['D1', 'D2', 'D3', 'D4', 'D5'];

    public static function niveauSuivant(string $niveau): ?string
    {
        $index = array_search($niveau, self::NIVEAUX, true);

        if ($index === false || ! isset(self::NIVEAUX[$index + 1])) {
            return null;
        }

        return self::NIVEAUX[$index + 1];
    }

    /**
     * La réinscription de l'année la plus récente.
     */
    public function reinscriptionCourante(): ?Reinscription
    {
        return $this->reinscriptions->sortByDesc('annee_universitaire')->first();
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

    public function eadDirect(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(EAD::class, 'ead_id');
    }

    /**
     * Accessors pratiques
     */

    // Nom / email via User
    public function getNameAttribute()
    {
        $fullName = trim("{$this->nom} {$this->prenoms}");

        if ($fullName !== '') {
            return $fullName;
        }

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
        return ! is_null($this->user_id) && $this->user()->exists();
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

    /**
     * Attribut "thèse principale" (en_cours > préparation > rédaction > soutenue > le reste)
     */
    public function getThesePrincipaleAttribute()
    {
        // Si les thèses ne sont pas encore chargées, on les charge
        if (! $this->relationLoaded('theses')) {
            $this->load('theses.specialite', 'theses.ead');
        }

        return $this->theses
            ->sortBy(function ($these) {
                return match ($these->statut) {
                    'en_cours' => 0,
                    'preparation' => 1,
                    'redaction' => 2,
                    'soutenue' => 3,
                    'suspendue' => 4,
                    'abandonnee' => 5,
                    default => 99,
                };
            })
            ->first();
    }
}
