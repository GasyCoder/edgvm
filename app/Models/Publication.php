<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = [
        'these_id',
        'titre',
        'auteurs',
        'revue',
        'date_publication',
        'url_publication',
    ];

    protected $casts = [
        'date_publication' => 'date',
    ];

    // Relations
    public function these()
    {
        return $this->belongsTo(These::class);
    }
}
