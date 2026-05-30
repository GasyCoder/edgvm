<?php

namespace Database\Seeders;

use App\Models\Doctorant;
use App\Services\ParcoursService;
use Illuminate\Database\Seeder;

class ReinscriptionSeeder extends Seeder
{
    public function run(ParcoursService $parcours): void
    {
        Doctorant::query()
            ->whereDoesntHave('reinscriptions')
            ->each(fn (Doctorant $doctorant) => $parcours->initialiser($doctorant));
    }
}
