<?php

use App\Models\Doctorant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function makeAdmin(): User
{
    return User::factory()->create([
        'role' => 'super_admin',
        'active' => true,
        'email_verified_at' => now(),
    ]);
}

function makeListedDoctorant(array $attributes = []): Doctorant
{
    return Doctorant::create(array_merge([
        'nom' => 'Rakoto',
        'prenoms' => 'Jean',
        'genre' => 'homme',
        'email' => 'd'.fake()->unique()->numberBetween(1, 99999).'@example.com',
        'matricule' => 'DOC-'.fake()->unique()->numberBetween(1000, 99999),
        'niveau' => 'D1',
        'statut' => 'actif',
        'date_inscription' => now(),
    ], $attributes));
}

it('hides finished and archived doctorants from the main list', function (): void {
    $actif = makeListedDoctorant(['statut' => 'actif']);
    $diplome = makeListedDoctorant(['statut' => 'diplome']);
    $archive = makeListedDoctorant(['statut' => 'actif', 'archived_at' => now()]);

    $this->actingAs(makeAdmin())
        ->get(route('admin.doctorants.index'))
        ->assertInertia(fn ($page) => $page
            ->where('doctorants.data', fn ($rows) => collect($rows)->pluck('id')->contains($actif->id)
                && ! collect($rows)->pluck('id')->contains($diplome->id)
                && ! collect($rows)->pluck('id')->contains($archive->id)
            )
        );
});

it('shows finished and archived doctorants in the archives view', function (): void {
    makeListedDoctorant(['statut' => 'actif']);
    $diplome = makeListedDoctorant(['statut' => 'diplome']);

    $this->actingAs(makeAdmin())
        ->get(route('admin.doctorants.index', ['archives' => 1]))
        ->assertInertia(fn ($page) => $page
            ->where('doctorants.data', fn ($rows) => collect($rows)->pluck('id')->contains($diplome->id))
        );
});

it('archives and restores doctorants in bulk', function (): void {
    $admin = makeAdmin();
    $a = makeListedDoctorant();
    $b = makeListedDoctorant();

    $this->actingAs($admin)
        ->post(route('admin.doctorants.bulk'), ['action' => 'archive', 'ids' => [$a->id, $b->id]])
        ->assertRedirect();

    expect($a->fresh()->archived_at)->not->toBeNull();
    expect($b->fresh()->archived_at)->not->toBeNull();

    $this->actingAs($admin)
        ->post(route('admin.doctorants.bulk'), ['action' => 'restore', 'ids' => [$a->id]])
        ->assertRedirect();

    expect($a->fresh()->archived_at)->toBeNull();
});

it('forbids bulk delete without records.delete permission', function (): void {
    $secretaire = User::factory()->create([
        'role' => 'communication',
        'active' => true,
        'email_verified_at' => now(),
    ]);

    $doctorant = makeListedDoctorant();

    $this->actingAs($secretaire)
        ->post(route('admin.doctorants.bulk'), ['action' => 'delete', 'ids' => [$doctorant->id]])
        ->assertForbidden();

    expect(Doctorant::find($doctorant->id))->not->toBeNull();
});

it('stores an administrative observation', function (): void {
    $doctorant = makeListedDoctorant();

    $this->actingAs(makeAdmin())
        ->put(route('admin.doctorants.observation.update', $doctorant->id), ['observation' => 'Dérogation accordée.'])
        ->assertRedirect();

    expect($doctorant->fresh()->observation)->toBe('Dérogation accordée.');
});

it('generates printable documents for selected doctorants', function (): void {
    $doctorant = makeListedDoctorant();

    $this->actingAs(makeAdmin())
        ->get(route('admin.doctorants.documents', ['type' => 'attestation', 'ids' => [$doctorant->id]]))
        ->assertOk()
        ->assertSee("Attestation d'inscription", false);
});

it('generates a complete black and white administrative report', function (): void {
    $doctorant = makeListedDoctorant(['observation' => 'Dossier complet.']);

    $this->actingAs(makeAdmin())
        ->get(route('admin.doctorants.documents', ['type' => 'rapport', 'ids' => [$doctorant->id]]))
        ->assertOk()
        ->assertSee('Rapport administratif', false)
        ->assertSee('Situation financière', false)
        ->assertSee('Dossier complet.', false);
});
