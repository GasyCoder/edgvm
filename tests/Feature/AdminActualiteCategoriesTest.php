<?php

use App\Models\Actualite;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('stores actualite with multiple categories', function (): void {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $first = Category::query()->create([
        'nom' => 'Recherche',
        'description' => 'Recherche',
        'couleur' => '#123456',
        'visible' => true,
    ]);

    $second = Category::query()->create([
        'nom' => 'Evenements',
        'description' => 'Evenements',
        'couleur' => '#654321',
        'visible' => true,
    ]);

    $this->actingAs($admin)
        ->post(route('admin.actualites.store'), [
            'titre' => 'Actualite test',
            'contenu' => '<p>Test</p>',
            'category_ids' => [$first->id, $second->id],
            'selectedTags' => [],
            'galerieImages' => [],
            'date_publication' => now()->toDateString(),
            'visible' => true,
            'est_important' => false,
            'notifier_abonnes' => false,
        ])
        ->assertRedirect(route('admin.actualites.index'));

    $actualite = Actualite::query()->firstOrFail();

    expect($actualite->category_id)->toBe($first->id);

    $this->assertDatabaseHas('actualite_category', [
        'actualite_id' => $actualite->id,
        'category_id' => $first->id,
    ]);
    $this->assertDatabaseHas('actualite_category', [
        'actualite_id' => $actualite->id,
        'category_id' => $second->id,
    ]);
});

it('updates actualite categories and primary category', function (): void {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $first = Category::query()->create([
        'nom' => 'Recherche',
        'description' => 'Recherche',
        'couleur' => '#123456',
        'visible' => true,
    ]);

    $second = Category::query()->create([
        'nom' => 'Evenements',
        'description' => 'Evenements',
        'couleur' => '#654321',
        'visible' => true,
    ]);

    $third = Category::query()->create([
        'nom' => 'Vie academique',
        'description' => 'Vie academique',
        'couleur' => '#222222',
        'visible' => true,
    ]);

    $actualite = Actualite::query()->create([
        'titre' => 'Actualite test',
        'contenu' => '<p>Test</p>',
        'category_id' => $first->id,
        'visible' => true,
        'date_publication' => now()->toDateString(),
    ]);

    $actualite->categories()->sync([$first->id, $second->id]);

    $this->actingAs($admin)
        ->put(route('admin.actualites.update', $actualite->slug), [
            'titre' => $actualite->titre,
            'contenu' => $actualite->contenu,
            'category_ids' => [$third->id],
            'selectedTags' => [],
            'galerieImages' => [],
            'date_publication' => $actualite->date_publication?->format('Y-m-d'),
            'visible' => true,
            'est_important' => false,
        ])
        ->assertRedirect(route('admin.actualites.index'));

    $actualite->refresh();

    expect($actualite->category_id)->toBe($third->id);

    $this->assertDatabaseHas('actualite_category', [
        'actualite_id' => $actualite->id,
        'category_id' => $third->id,
    ]);
    $this->assertDatabaseMissing('actualite_category', [
        'actualite_id' => $actualite->id,
        'category_id' => $first->id,
    ]);
});
