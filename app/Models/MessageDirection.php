<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'nb_doctorants',
        'nb_equipes',
        'nb_theses',
        'visible',
        'photo_path',
    ];

    protected $casts = [
        'nb_doctorants' => 'integer',
        'nb_equipes' => 'integer',
        'nb_theses' => 'integer',
        'visible' => 'boolean',
    ];
}
