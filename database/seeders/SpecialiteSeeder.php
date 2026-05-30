<?php

namespace Database\Seeders;

use App\Models\EAD;
use App\Models\Specialite;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SpecialiteSeeder extends Seeder
{
    public function run(): void
    {
        // Dans ce projet : spécialité = programme de recherche, rattaché à une EAD (par sigle).
        $programmesParEad = [
            'EAD 1' => [
                'Biotechnologies analytiques des eaux et des déchets',
                'Étude d\'impact environnemental et modèle de gestion des déchets solides et liquides',
            ],
            'EAD 2' => [
                'Plantes médicinales et organismes marins',
                'Valorisation des ressources marines',
                'Recherche et développement de molécules actives',
            ],
            'EAD 3' => [
                'Santé et Bien-être',
                'Diagnostic et typage (phénotypage et génotypage) des populations d\'hémoparasites',
                'Phytothérapie',
                'Allopathie',
            ],
            'EAD 4' => [
                'Exploitation, inventaire, conservation et valorisation des géo-ressources à Madagascar',
                'Paléontologie et géologie',
            ],
            'EAD 5' => [
                'Études écotoxicologique, microbiologique et physiopathologique',
                'Alimentation et Nutrition animales',
                'Physiologie et pathologie animales',
            ],
            'EAD 6' => [
                'Alimentation et Nutrition humaines',
                'Sécurité alimentaire',
            ],
            'EAD 7' => [
                'Sciences des Sociétés et Sciences de l\'homme',
            ],
            'EAD 8' => [
                'Science de la Structure, de la Société et de la Matière',
            ],
        ];

        foreach ($programmesParEad as $sigle => $programmes) {
            $ead = EAD::where('sigle', $sigle)->first();

            if (! $ead) {
                continue;
            }

            foreach ($programmes as $nom) {
                Specialite::updateOrCreate(
                    ['slug' => Str::slug($nom)],
                    [
                        'nom' => $nom,
                        'ead_id' => $ead->id,
                    ],
                );
            }
        }
    }
}
