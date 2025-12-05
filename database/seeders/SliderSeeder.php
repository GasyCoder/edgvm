<?php

namespace Database\Seeders;

use App\Models\Slider;
use App\Models\Slide;
use App\Models\Media;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Début du SliderSeeder...');
        
        try {
            $slider = Slider::create([
                'nom' => 'Slider Homepage',
                'position' => 'homepage',
                'visible' => true,
            ]);
            
            $this->command->info('✅ Slider créé : ' . $slider->nom . ' (ID: ' . $slider->id . ')');

            // Créer des médias fictifs
            $media1 = Media::create([
                'nom_original' => 'slide-1.jpg',
                'nom_fichier' => 'slide-1.jpg',
                'chemin' => 'slides/slide-1.jpg',
                'type' => 'image',
                'taille_bytes' => 1024000,
                'mime_type' => 'image/jpeg',
            ]);
            
            $this->command->info('✅ Media 1 créé (ID: ' . $media1->id . ')');

            $media2 = Media::create([
                'nom_original' => 'slide-2.jpg',
                'nom_fichier' => 'slide-2.jpg',
                'chemin' => 'slides/slide-2.jpg',
                'type' => 'image',
                'taille_bytes' => 1024000,
                'mime_type' => 'image/jpeg',
            ]);
            
            $this->command->info('✅ Media 2 créé (ID: ' . $media2->id . ')');

            $media3 = Media::create([
                'nom_original' => 'slide-3.jpg',
                'nom_fichier' => 'slide-3.jpg',
                'chemin' => 'slides/slide-3.jpg',
                'type' => 'image',
                'taille_bytes' => 1024000,
                'mime_type' => 'image/jpeg',
            ]);
            
            $this->command->info('✅ Media 3 créé (ID: ' . $media3->id . ')');

            // Slide 1
            $slide1 = Slide::create([
                'slider_id' => $slider->id,
                'titre_ligne1' => 'Le',
                'titre_highlight' => 'Génie du Vivant',
                'titre_ligne2' => 'au Service de l\'Humanité',
                'description' => 'École Doctorale de recherche scientifique d\'excellence',
                'image_id' => $media1->id,
                'lien_cta' => '/register',
                'texte_cta' => 'Candidater maintenant',
                'ordre' => 1,
                'visible' => true,
                'badge_texte' => 'Université de Mahajanga',
                'badge_icon' => 'university',
                'couleur_fond' => 'from-ed-primary/95 via-ed-secondary/90 to-teal-800/95',
            ]);
            
            $this->command->info('✅ Slide 1 créé (ID: ' . $slide1->id . ')');

            // Slide 2
            $slide2 = Slide::create([
                'slider_id' => $slider->id,
                'titre_ligne1' => null,
                'titre_highlight' => 'Excellence',
                'titre_ligne2' => 'en Recherche Scientifique',
                'description' => 'Biotechnologie • Environnement • Santé • Géosciences',
                'image_id' => $media2->id,
                'lien_cta' => '/programmes',
                'texte_cta' => 'Explorer nos recherches',
                'ordre' => 2,
                'visible' => true,
                'badge_texte' => '8 Équipes de Recherche',
                'badge_icon' => 'research',
                'couleur_fond' => 'from-teal-800/95 via-ed-primary/90 to-green-900/95',
            ]);
            
            $this->command->info('✅ Slide 2 créé (ID: ' . $slide2->id . ')');

            // Slide 3
            $slide3 = Slide::create([
                'slider_id' => $slider->id,
                'titre_ligne1' => 'Rejoignez',
                'titre_highlight' => '109 Doctorants',
                'titre_ligne2' => 'en Formation',
                'description' => '23 thèses soutenues • 20 soutenances en cours',
                'image_id' => $media3->id,
                'lien_cta' => '/contact',
                'texte_cta' => 'Nous contacter',
                'ordre' => 3,
                'visible' => true,
                'badge_texte' => 'Formation Doctorale d\'Excellence',
                'badge_icon' => 'students',
                'couleur_fond' => 'from-green-900/95 via-ed-secondary/90 to-teal-700/95',
            ]);
            
            $this->command->info('✅ Slide 3 créé (ID: ' . $slide3->id . ')');
            
            $count = $slider->slides()->count();
            $this->command->info("🎉 SUCCÈS : {$count} slides créés pour le slider '{$slider->nom}'");
            
        } catch (\Exception $e) {
            $this->command->error('❌ ERREUR : ' . $e->getMessage());
            $this->command->error('Fichier : ' . $e->getFile());
            $this->command->error('Ligne : ' . $e->getLine());
        }
    }
}