<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InscriptionDocument extends Model
{
    use HasFactory;

    protected $table = 'inscription_documents';
    protected $fillable = [
        'inscription_id',
        'type_document',
        'media_id',
    ];

    // Relations
    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
