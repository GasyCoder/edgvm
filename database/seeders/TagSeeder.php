<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'Formation',
            'Inscription',
            'Examen',
            'Concours',
            'Conférence',
            'Séminaire',
            'Publication',
            'Partenariat',
        ];

        foreach ($tags as $tag) {
            Tag::create(['nom' => $tag]);
        }
    }
}