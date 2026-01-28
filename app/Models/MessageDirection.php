<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageDirection extends Model
{
    use HasFactory;

    // Si ta table s'appelle bien "message_directions", pas besoin de $table.
    // Sinon, décommente et adapte :
    // protected $table = 'message_directions';

    protected $fillable = [
        'nom',
        'fonction',
        'institution',
        'telephone',
        'email',
        'citation',
        'message_intro',
        'visible',
        'photo_path',
    ];

    protected $casts = [
        'visible' => 'boolean',
    ];
}
