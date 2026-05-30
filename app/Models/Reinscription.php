<?php

namespace App\Models;

use App\Enums\StatutAnnee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reinscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctorant_id',
        'annee_universitaire',
        'niveau',
        'statut_annee',
        'decision',
        'date_decision',
    ];

    protected function casts(): array
    {
        return [
            'statut_annee' => StatutAnnee::class,
            'date_decision' => 'date',
        ];
    }

    public function doctorant(): BelongsTo
    {
        return $this->belongsTo(Doctorant::class);
    }
}
