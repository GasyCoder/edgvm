<?php

use App\Models\Doctorant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('requires nom and prenoms when creating a doctorant', function (): void {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $this->actingAs($admin)
        ->post(route('admin.doctorants.store'), [
            'matricule' => 'DOC-001',
            'niveau' => 'D1',
            'date_inscription' => now()->toDateString(),
            'statut' => 'actif',
        ])
        ->assertSessionHasErrors(['nom', 'genre', 'email']);
});

it('creates a doctorant with identity fields', function (): void {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $this->actingAs($admin)
        ->post(route('admin.doctorants.store'), [
            'nom' => 'Rakoto',
            'prenoms' => 'Jean',
            'genre' => 'homme',
            'email' => 'jean.rakoto@example.com',
            'matricule' => 'DOC-002',
            'niveau' => 'D1',
            'date_inscription' => now()->toDateString(),
            'statut' => 'actif',
        ])
        ->assertRedirect(route('admin.doctorants.index'));

    expect(Doctorant::query()->where('matricule', 'DOC-002')->exists())->toBeTrue();
});
