<?php

use App\Models\Doctorant;
use App\Models\User;
use App\Support\RolePermissions;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function userWithRole(string $role): User
{
    return User::factory()->create([
        'role' => $role,
        'active' => true,
        'email_verified_at' => now(),
    ]);
}

it('maps abilities per role correctly', function (): void {
    expect(RolePermissions::allows('super_admin', 'systeme.access'))->toBeTrue();
    expect(RolePermissions::allows('super_admin', 'gestion.access'))->toBeTrue();

    expect(RolePermissions::allows('direction', 'gestion.access'))->toBeTrue();
    expect(RolePermissions::allows('direction', 'systeme.access'))->toBeFalse();

    expect(RolePermissions::allows('secretariat', 'gestion.access'))->toBeTrue();
    expect(RolePermissions::allows('secretariat', 'contenu.access'))->toBeFalse();

    expect(RolePermissions::allows('communication', 'contenu.access'))->toBeTrue();
    expect(RolePermissions::allows('communication', 'gestion.access'))->toBeFalse();

    // Les communications (événements/annonces/e-mails) sont partagées
    expect(RolePermissions::allows('secretariat', 'communications.access'))->toBeTrue();
    expect(RolePermissions::allows('direction', 'communications.access'))->toBeTrue();
    expect(RolePermissions::allows('communication', 'communications.access'))->toBeTrue();
    expect(RolePermissions::allows('secretariat', 'contenu.access'))->toBeFalse();
});

it('lets secretariat, direction and communication handle communications', function (string $role): void {
    $user = userWithRole($role);

    $this->actingAs($user)->get(route('admin.evenements.index'))->assertSuccessful();
    $this->actingAs($user)->get(route('admin.annonces.index'))->assertSuccessful();
    $this->actingAs($user)->get(route('admin.newsletter.subscribers'))->assertSuccessful();
})->with(['secretariat', 'direction', 'communication']);

it('keeps public site content out of secretariat reach', function (): void {
    // Événements (communication) : OK pour le secrétariat
    $this->actingAs(userWithRole('secretariat'))->get(route('admin.evenements.index'))->assertSuccessful();
    // Actualités (contenu du site public) : interdit au secrétariat
    $this->actingAs(userWithRole('secretariat'))->get(route('admin.actualites.index'))->assertForbidden();
});

it('protects academic management routes by ability', function (): void {
    // Communication n'a pas accès aux données académiques (dossiers doctorants)
    $this->actingAs(userWithRole('communication'))
        ->get(route('admin.doctorants.index'))
        ->assertForbidden();

    // Secrétariat et Direction y ont accès
    $this->actingAs(userWithRole('secretariat'))
        ->get(route('admin.doctorants.index'))
        ->assertSuccessful();

    $this->actingAs(userWithRole('super_admin'))
        ->get(route('admin.doctorants.index'))
        ->assertSuccessful();
});

it('separates content and system access', function (): void {
    // Le secrétariat ne gère pas les contenus du site public
    $this->actingAs(userWithRole('secretariat'))
        ->get(route('admin.actualites.index'))
        ->assertForbidden();

    // La communication, oui
    $this->actingAs(userWithRole('communication'))
        ->get(route('admin.actualites.index'))
        ->assertSuccessful();

    // Les paramètres système sont réservés au super administrateur
    $this->actingAs(userWithRole('direction'))
        ->get(route('admin.settings'))
        ->assertForbidden();

    $this->actingAs(userWithRole('super_admin'))
        ->get(route('admin.settings'))
        ->assertSuccessful();
});

it('restricts academic hard-delete to direction and super admin', function (): void {
    $makeDoctorant = fn () => Doctorant::create([
        'matricule' => 'M'.fake()->unique()->numerify('######'),
        'statut' => 'en_cours',
        'date_inscription' => now(),
    ]);

    // Le secrétariat ne peut pas supprimer définitivement
    $this->actingAs(userWithRole('secretariat'))
        ->delete(route('admin.doctorants.destroy', $makeDoctorant()->id))
        ->assertForbidden();

    // La direction le peut (passe la barrière records.delete)
    $this->actingAs(userWithRole('direction'))
        ->delete(route('admin.doctorants.destroy', $makeDoctorant()->id))
        ->assertRedirect();
});
