<?php

use App\Models\Doctorant;
use App\Models\These;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('exposes a uuid and resolves detail pages by uuid (not by id)', function (): void {
    $user = User::factory()->create();

    $doctorant = Doctorant::create([
        'user_id' => $user->id,
        'matricule' => 'D-UUID-TEST',
        'statut' => 'en_cours',
        'date_inscription' => now(),
    ]);

    $these = These::create([
        'doctorant_id' => $doctorant->id,
        'sujet_these' => 'Sujet de thèse de test',
        'statut' => 'en_cours',
    ]);

    expect($doctorant->uuid)->not->toBeEmpty();
    expect($these->uuid)->not->toBeEmpty();

    // Les fiches se résolvent par UUID
    $this->get(route('doctorants.show', $doctorant->uuid))->assertSuccessful();
    $this->get(route('theses.show', $these->uuid))->assertSuccessful();

    // Les anciennes URLs par identifiant entier ne résolvent plus
    $this->get('/doctorants/voir-'.$doctorant->id)->assertNotFound();
    $this->get('/theses/'.$these->id)->assertNotFound();
});
