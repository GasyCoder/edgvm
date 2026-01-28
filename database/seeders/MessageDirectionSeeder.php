<?php

namespace Database\Seeders;

use App\Models\MessageDirection;
use Illuminate\Database\Seeder;

class MessageDirectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MessageDirection::create([
            'nom' => 'Mme ROUKIA',
            'fonction' => "Directrice de l'EDGVM",
            'institution' => 'Université de Mahajanga',
            'telephone' => '+261 34 25 520 65',
            'email' => 'roukiadjoudi@gmail.com',
            'photo_path' => null, // tu pourras mettre 'directrice.jpg' par ex.
            'citation' => "Bienvenue à l'École Doctorale Génie du Vivant et Modélisation. Notre mission est de former les chercheurs qui contribueront à l'avancement des connaissances et au développement durable.",
            'message_intro' => "Avec 8 équipes d'accueil et 109 doctorants, nous offrons un environnement de recherche d'excellence.",
            'visible' => true,
        ]);
    }
}
