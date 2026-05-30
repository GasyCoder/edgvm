<?php

use App\Models\Doctorant;
use App\Models\Jury;
use App\Models\These;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a these with optional jury members attached', function (): void {
    $admin = User::factory()->create([
        'role' => 'super_admin',
        'active' => true,
        'email_verified_at' => now(),
    ]);

    $doctorant = Doctorant::create([
        'nom' => 'Rakoto',
        'prenoms' => 'Jean',
        'genre' => 'homme',
        'email' => 'jean@example.com',
        'matricule' => 'ED-JURY-1',
        'niveau' => 'D1',
        'statut' => 'actif',
        'date_inscription' => now(),
    ]);

    $jury = Jury::create([
        'nom' => 'Pr. Andry',
        'grade' => 'Professeur',
        'universite' => 'Université de Mahajanga',
    ]);

    $this->actingAs($admin)
        ->post(route('admin.theses.store'), [
            'doctorant_id' => $doctorant->id,
            'sujet_these' => 'Étude des biotechnologies analytiques',
            'statut' => 'en_cours',
            'jurys' => [
                ['id' => $jury->id, 'role' => 'president'],
            ],
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.theses.index'));

    $these = These::query()->where('doctorant_id', $doctorant->id)->firstOrFail();

    expect($these->jurys)->toHaveCount(1);
    expect($these->jurys->first()->pivot->role)->toBe('president');
});

it('creates a these without any jury (jury is optional)', function (): void {
    $admin = User::factory()->create([
        'role' => 'super_admin',
        'active' => true,
        'email_verified_at' => now(),
    ]);

    $doctorant = Doctorant::create([
        'nom' => 'Rasoa',
        'prenoms' => 'Marie',
        'genre' => 'femme',
        'email' => 'marie@example.com',
        'matricule' => 'ED-JURY-2',
        'niveau' => 'D1',
        'statut' => 'actif',
        'date_inscription' => now(),
    ]);

    $this->actingAs($admin)
        ->post(route('admin.theses.store'), [
            'doctorant_id' => $doctorant->id,
            'sujet_these' => 'Valorisation des ressources marines',
            'statut' => 'en_cours',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.theses.index'));

    expect(These::query()->where('doctorant_id', $doctorant->id)->firstOrFail()->jurys)->toHaveCount(0);
});
