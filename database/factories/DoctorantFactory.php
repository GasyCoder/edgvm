<?php

namespace Database\Factories;

use App\Models\Doctorant;
use App\Models\EAD;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Doctorant>
 */
class DoctorantFactory extends Factory
{
    protected $model = Doctorant::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $genre = fake()->randomElement(['homme', 'femme']);

        return [
            'nom' => fake()->lastName(),
            'prenoms' => $genre === 'femme' ? fake()->firstNameFemale() : fake()->firstNameMale(),
            'genre' => $genre,
            'email' => fake()->unique()->safeEmail(),
            'ead_id' => EAD::query()->inRandomOrder()->value('id'),
            'matricule' => 'ED'.fake()->unique()->numberBetween(10000, 99999),
            'niveau' => fake()->randomElement(['D1', 'D2', 'D3']),
            'phone' => fake()->numerify('03# ## ### ##'),
            'adresse' => fake()->streetAddress(),
            'date_inscription' => fake()->dateTimeBetween('-3 years', 'now')->format('Y-m-d'),
            'date_naissance' => fake()->dateTimeBetween('-40 years', '-23 years')->format('Y-m-d'),
            'lieu_naissance' => fake()->city(),
            'statut' => 'actif',
        ];
    }

    public function niveau(string $niveau): static
    {
        return $this->state(fn () => ['niveau' => $niveau]);
    }
}
