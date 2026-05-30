<?php

use App\Models\Doctorant;
use App\Models\User;
use App\Services\ParcoursService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function parcoursAdmin(): User
{
    return User::factory()->create([
        'role' => 'super_admin',
        'active' => true,
        'email_verified_at' => now(),
    ]);
}

function parcoursDoctorant(string $niveau = 'D1'): Doctorant
{
    $doctorant = Doctorant::create([
        'nom' => 'Rakoto',
        'prenoms' => 'Jean',
        'genre' => 'homme',
        'email' => 'd'.fake()->unique()->numberBetween(1, 99999).'@example.com',
        'matricule' => 'ED-P-'.fake()->unique()->numberBetween(1000, 99999),
        'niveau' => $niveau,
        'statut' => 'actif',
        'date_inscription' => now(),
    ]);

    app(ParcoursService::class)->initialiser($doctorant, '2025-2026');

    return $doctorant->fresh();
}

it('promotes a doctorant to the next level with a new reinscription', function (): void {
    $doctorant = parcoursDoctorant('D1');

    $this->actingAs(parcoursAdmin())
        ->post(route('admin.doctorants.parcours.store', $doctorant->id), ['action' => 'promouvoir'])
        ->assertRedirect();

    $doctorant->refresh()->load('reinscriptions');

    expect($doctorant->niveau)->toBe('D2');
    expect($doctorant->reinscriptions)->toHaveCount(2);

    $d1 = $doctorant->reinscriptions->firstWhere('niveau', 'D1');
    $d2 = $doctorant->reinscriptions->firstWhere('niveau', 'D2');
    expect($d1->statut_annee->value)->toBe('admis');
    expect($d2->statut_annee->value)->toBe('en_cours');
    expect($d2->annee_universitaire)->toBe('2026-2027');
});

it('ajourns a doctorant on the same level for the next year', function (): void {
    $doctorant = parcoursDoctorant('D1');

    $this->actingAs(parcoursAdmin())
        ->post(route('admin.doctorants.parcours.store', $doctorant->id), ['action' => 'ajourner'])
        ->assertRedirect();

    $doctorant->refresh()->load('reinscriptions');

    expect($doctorant->niveau)->toBe('D1');
    expect($doctorant->reinscriptions)->toHaveCount(2);
    expect($doctorant->reinscriptions->where('niveau', 'D1')->count())->toBe(2);
});

it('validates a D3 thesis for the defense', function (): void {
    $doctorant = parcoursDoctorant('D3');

    $this->actingAs(parcoursAdmin())
        ->post(route('admin.doctorants.parcours.store', $doctorant->id), ['action' => 'valider'])
        ->assertRedirect();

    $courante = $doctorant->fresh()->reinscriptionCourante();
    expect($courante->statut_annee->value)->toBe('valide');
});

it('forbids validating for defense before D3', function (): void {
    $doctorant = parcoursDoctorant('D1');

    $this->actingAs(parcoursAdmin())
        ->post(route('admin.doctorants.parcours.store', $doctorant->id), ['action' => 'valider'])
        ->assertSessionHasErrors('parcours');
});

it('diplomas a doctorant after defense and archives him', function (): void {
    $doctorant = parcoursDoctorant('D3');
    app(ParcoursService::class)->validerPourSoutenance($doctorant->fresh());

    $this->actingAs(parcoursAdmin())
        ->post(route('admin.doctorants.parcours.store', $doctorant->id), ['action' => 'diplomer'])
        ->assertRedirect();

    $doctorant->refresh();
    expect($doctorant->statut)->toBe('diplome');
    expect($doctorant->isArchived())->toBeTrue();
});

it('blocks promotion beyond D5', function (): void {
    $doctorant = parcoursDoctorant('D5');

    $this->actingAs(parcoursAdmin())
        ->post(route('admin.doctorants.parcours.store', $doctorant->id), ['action' => 'promouvoir'])
        ->assertSessionHasErrors('parcours');
});

it('requires gestion access to change the parcours', function (): void {
    $communication = User::factory()->create([
        'role' => 'communication',
        'active' => true,
        'email_verified_at' => now(),
    ]);

    $doctorant = parcoursDoctorant('D1');

    $this->actingAs($communication)
        ->post(route('admin.doctorants.parcours.store', $doctorant->id), ['action' => 'promouvoir'])
        ->assertForbidden();
});
