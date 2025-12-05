<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteConfiguration extends Model
{
    protected $table = 'site_configurations';
    protected $fillable = ['cle', 'valeur', 'type', 'description'];
    public $timestamps = true;

    // Récupérer une configuration par clé
    public static function get($cle, $default = null)
    {
        $config = self::where('cle', $cle)->first();
        return $config ? $config->valeur : $default;
    }

    // Récupérer un média (logo, favicon, etc.)
    public static function getMedia($cle)
    {
        $mediaId = self::get($cle);
        return $mediaId ? Media::find($mediaId) : null;
    }
}
