<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paiement extends Model
{
    use HasFactory;

    protected $table = 'paiements';

    protected $fillable = [
        'doctorant_id',
        'niveau',
        'annee_universitaire',
        'montant_du',
        'montant_paye',
        'date_paiement',
        'mode',
        'reference',
        'justificatif_path',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'montant_du' => 'decimal:2',
            'montant_paye' => 'decimal:2',
            'date_paiement' => 'date',
        ];
    }

    public function doctorant(): BelongsTo
    {
        return $this->belongsTo(Doctorant::class);
    }

    public function getResteAttribute(): float
    {
        return max(0, (float) $this->montant_du - (float) $this->montant_paye);
    }

    public function getStatutAttribute(): string
    {
        if ((float) $this->montant_paye <= 0) {
            return 'impaye';
        }

        return (float) $this->montant_paye < (float) $this->montant_du ? 'partiel' : 'paye';
    }
}
