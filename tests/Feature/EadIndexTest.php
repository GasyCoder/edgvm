<?php

use App\Models\EAD;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

it('lists every team without pagination or filters', function (): void {
    EAD::query()->create(['nom' => 'EAD Alpha', 'slug' => 'ead-alpha']);
    EAD::query()->create(['nom' => 'EAD Beta', 'slug' => 'ead-beta']);
    EAD::query()->create(['nom' => 'EAD Gamma', 'slug' => 'ead-gamma']);

    $this->get(route('ead.index'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Frontend/Recherche/Eads/Index')
            ->has('eads', 3)
            ->missing('filters')
            ->missing('domaines')
        );
});
