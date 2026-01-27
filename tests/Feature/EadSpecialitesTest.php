<?php

use App\Models\EAD;
use App\Models\Encadrant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates an EAD with multiple encadrants', function (): void {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $encadrantA = Encadrant::query()->create([
        'nom' => 'Rakoto',
        'prenoms' => 'Aina',
        'email' => 'encadrant-a@example.test',
        'genre' => 'homme',
    ]);
    $encadrantB = Encadrant::query()->create([
        'nom' => 'Razan',
        'prenoms' => 'Mina',
        'email' => 'encadrant-b@example.test',
        'genre' => 'femme',
    ]);

    $this->actingAs($admin)
        ->post(route('admin.ead.store'), [
            'nom' => 'EAD Test',
            'slug' => 'ead-test',
            'encadrants' => [$encadrantA->id, $encadrantB->id],
        ])
        ->assertRedirect(route('admin.ead.index'));

    $ead = EAD::query()->where('slug', 'ead-test')->first();

    expect($ead)->not->toBeNull();
    expect($ead->encadrants()->count())->toBe(2);
});

it('syncs encadrants when updating an EAD', function (): void {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $encadrantA = Encadrant::query()->create([
        'nom' => 'Rakoto',
        'prenoms' => 'Aina',
        'email' => 'encadrant-c@example.test',
        'genre' => 'homme',
    ]);
    $encadrantB = Encadrant::query()->create([
        'nom' => 'Razan',
        'prenoms' => 'Mina',
        'email' => 'encadrant-d@example.test',
        'genre' => 'femme',
    ]);

    $ead = EAD::query()->create([
        'nom' => 'EAD Update',
        'slug' => 'ead-update',
    ]);

    $ead->encadrants()->sync([$encadrantA->id]);

    $this->actingAs($admin)
        ->put(route('admin.ead.update', $ead->slug), [
            'nom' => $ead->nom,
            'slug' => $ead->slug,
            'encadrants' => [$encadrantB->id],
        ])
        ->assertRedirect(route('admin.ead.index'));

    expect($ead->encadrants()->count())->toBe(1);
    expect($ead->encadrants()->pluck('encadrants.id')->all())->toContain($encadrantB->id);
});
