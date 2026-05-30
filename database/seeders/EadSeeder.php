<?php

namespace Database\Seeders;

use App\Models\EAD;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EadSeeder extends Seeder
{
    public function run(): void
    {
        $equipes = [
            [
                'sigle' => 'EAD 1',
                'nom' => 'Biotechnologies analytiques et impacts physiopathologiques des déchets, de l\'environnement et de l\'eau',
                'description' => "Programmes de recherche : Biotechnologies analytiques des eaux et des déchets ; Étude d'impact environnemental et modèle de gestion des déchets solides et liquides.",
            ],
            [
                'sigle' => 'EAD 2',
                'nom' => 'Valorisation pharmacologique et cosmétique de la biodiversité végétale et des ressources marines',
                'description' => 'Programmes de recherche : Plantes médicinales et organismes marins ; Valorisation des ressources marines ; Recherche et développement de molécules actives.',
            ],
            [
                'sigle' => 'EAD 3',
                'nom' => 'Conception et développement de modèles biologiques in vitro et in vivo pour découvrir des médicaments d\'origine naturelle',
                'description' => 'Programmes de recherche : Santé et Bien-être ; Recherche et développement de molécules actives ; Diagnostic et typage (phénotypage et génotypage) des populations d\'hémoparasites ; Phytothérapie ; Allopathie.',
            ],
            [
                'sigle' => 'EAD 4',
                'nom' => 'Géosciences et Développement Durable',
                'description' => 'Programmes de recherche : Exploitation, inventaire, conservation et valorisation des géo-ressources à Madagascar ; Paléontologie ; Géologie.',
            ],
            [
                'sigle' => 'EAD 5',
                'nom' => 'Physiologie, Hygiène et Alimentation Animale',
                'description' => 'Programmes de recherche : Alimentation et Nutrition animales ; Physiologie et pathologie animales ; Études écotoxicologique, microbiologique et physiopathologique.',
            ],
            [
                'sigle' => 'EAD 6',
                'nom' => 'Aliments, Nutrition et Sécurité alimentaire',
                'description' => 'Programmes de recherche : Alimentation et Nutrition humaines ; Sécurité alimentaire.',
            ],
            [
                'sigle' => 'EAD 7',
                'nom' => 'Sciences des Sociétés et Sciences de l\'homme (3SH)',
                'description' => 'Programmes de recherche : Sciences des Sociétés et Sciences de l\'homme.',
            ],
            [
                'sigle' => 'EAD 8',
                'nom' => 'Science de la Structure de la Société et de la Matière',
                'description' => 'Programmes de recherche : Science de la Structure, de la Société et de la Matière.',
            ],
        ];

        foreach ($equipes as $equipe) {
            EAD::updateOrCreate(
                ['slug' => Str::slug($equipe['nom'])],
                [
                    'sigle' => $equipe['sigle'],
                    'nom' => $equipe['nom'],
                    'description' => $equipe['description'],
                ],
            );
        }
    }
}
