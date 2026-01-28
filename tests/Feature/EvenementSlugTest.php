<?php

use App\Models\Evenement;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('generates a unique slug for evenements', function (): void {
    $first = Evenement::query()->create([
        'titre' => 'Evenement test',
        'date_debut' => now()->addDay()->toDateString(),
        'type' => 'seminaire',
        'est_publie' => true,
        'est_archive' => false,
    ]);

    $second = Evenement::query()->create([
        'titre' => 'Evenement test',
        'date_debut' => now()->addDays(2)->toDateString(),
        'type' => 'seminaire',
        'est_publie' => true,
        'est_archive' => false,
    ]);

    expect($first->slug)->not->toBeEmpty();
    expect($second->slug)->not->toBeEmpty();
    expect($second->slug)->not->toBe($first->slug);
});
