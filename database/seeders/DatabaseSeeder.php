<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctorant;
use App\Models\Encadrant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SliderSeeder::class,
            CategorySeeder::class,
            TagSeeder::class,
            MenuAProposSeeder::class,
            MessageDirectionSeeder::class,
            SettingSeeder::class,
        ]);

    }
}