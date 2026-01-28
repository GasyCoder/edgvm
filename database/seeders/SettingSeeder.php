<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // On SUPPRIME les anciens paramètres (1 seule ligne voulue)
        Setting::truncate();

        Setting::create([
            // Identité du site
            'site_name' => 'EDGVM - École Doctorale Génie du Vivant et Modélisation',
            'seo_title' => 'EDGVM | École Doctorale Génie du Vivant et Modélisation - Université de Mahajanga',
            'meta_description' => 'Site officiel de l’École Doctorale Génie du Vivant et Modélisation (EDGVM) de l’Université de Mahajanga : formations doctorales, thèses, encadrants, actualités et événements scientifiques.',
            'meta_keywords' => 'EDGVM, école doctorale, Université de Mahajanga, thèse, doctorat, génie du vivant, modélisation, recherche, Madagascar',

            // Contacts
            'site_email' => 'contact@edgvm.mg',
            'site_phone' => '+261 XX XX XXX XX',
            'site_address' => 'Université de Mahajanga, Campus d’Ambondrona, Mahajanga, Madagascar',

            // Réseaux sociaux
            'facebook_url' => 'https://www.facebook.com/edgvm.officiel',
            'twitter_url' => null,
            'linkedin_url' => null,
            'youtube_url' => null,
            'instagram_url' => null,

            // Sitemap / technique
            'sitemap_url' => null,

            // Médias (logo & favicon) – tu rempliras via l’interface
            'logo_path' => null,
            'favicon_path' => null,

            // Maintenance
            'maintenance_mode' => false,
            'maintenance_message' => 'Le site est actuellement en maintenance. Merci de votre compréhension.',

            // Statistiques Message Direction
            'message_direction_doctorants' => 109,
            'message_direction_equipes' => 8,
            'message_direction_theses' => 23,
        ]);
    }
}
