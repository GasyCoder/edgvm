<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name',
        'seo_title',
        'meta_description',
        'meta_keywords',
        'site_email',
        'site_phone',
        'site_address',
        'facebook_url',
        'twitter_url',
        'linkedin_url',
        'youtube_url',
        'instagram_url',
        'sitemap_url',
        'logo_path',
        'favicon_path',
        'maintenance_mode',
        'maintenance_message',
        'message_direction_doctorants',
        'message_direction_equipes',
        'message_direction_theses',
    ];

    protected $casts = [
        'maintenance_mode' => 'boolean',
        'message_direction_doctorants' => 'integer',
        'message_direction_equipes' => 'integer',
        'message_direction_theses' => 'integer',
    ];

    public static function main(): self
    {
        return static::first() ?? static::create([
            'site_name' => 'EDGVM',
        ]);
    }
}
