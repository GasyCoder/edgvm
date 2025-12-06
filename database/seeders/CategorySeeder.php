<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nom' => 'Actualités', 'couleur' => '#3B82F6', 'description' => 'Actualités générales'],
            ['nom' => 'Événements', 'couleur' => '#10B981', 'description' => 'Événements à venir'],
            ['nom' => 'Recherche', 'couleur' => '#8B5CF6', 'description' => 'Actualités de la recherche'],
            ['nom' => 'Vie étudiante', 'couleur' => '#F59E0B', 'description' => 'Vie sur le campus'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}