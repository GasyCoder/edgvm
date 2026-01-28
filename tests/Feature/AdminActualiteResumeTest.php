<?php

use App\Models\Actualite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('stores actualite resume when provided', function (): void {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $this->actingAs($admin)
        ->post(route('admin.actualites.store'), [
            'titre' => 'Actualite test',
            'resume' => 'Resume court',
            'contenu' => '<p>Contenu</p>',
            'category_ids' => [],
            'selectedTags' => [],
            'galerieImages' => [],
            'date_publication' => now()->toDateString(),
            'visible' => true,
            'est_important' => false,
            'notifier_abonnes' => false,
        ])
        ->assertRedirect(route('admin.actualites.index'));

    $actualite = Actualite::query()->firstOrFail();

    expect($actualite->resume)->toBe('Resume court');
});
