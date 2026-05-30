<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            EadSeeder::class,
            SpecialiteSeeder::class,
            SliderSeeder::class,
            CategorySeeder::class,
            TagSeeder::class,
            MenuAProposSeeder::class,
            MessageDirectionSeeder::class,
            SettingSeeder::class,
            ReinscriptionSeeder::class,
        ]);

    }
}
