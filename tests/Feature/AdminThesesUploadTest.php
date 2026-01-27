<?php

use App\Models\Doctorant;
use App\Models\Media;
use App\Models\These;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('requires a slug when uploading a thesis file', function (): void {
    Storage::fake('public');

    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $doctorant = Doctorant::query()->create([
        'matricule' => 'DOC-001',
        'niveau' => 'D1',
        'date_inscription' => now()->toDateString(),
        'statut' => 'actif',
    ]);

    $this->actingAs($admin)
        ->from(route('admin.theses.create'))
        ->post(route('admin.theses.store'), [
            'doctorant_id' => $doctorant->id,
            'sujet_these' => 'Sujet test',
            'statut' => 'en_cours',
            'these_file' => UploadedFile::fake()->create('these.pdf', 120, 'application/pdf'),
        ])
        ->assertSessionHasErrors('slug');
});

it('uploads a thesis file with slug and stores media', function (): void {
    Storage::fake('public');

    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $doctorant = Doctorant::query()->create([
        'matricule' => 'DOC-002',
        'niveau' => 'D1',
        'date_inscription' => now()->toDateString(),
        'statut' => 'actif',
    ]);

    $this->actingAs($admin)
        ->post(route('admin.theses.store'), [
            'doctorant_id' => $doctorant->id,
            'sujet_these' => 'Sujet test',
            'statut' => 'en_cours',
            'slug' => 'these-test',
            'these_file' => UploadedFile::fake()->create('these.pdf', 120, 'application/pdf'),
        ])
        ->assertRedirect(route('admin.theses.index'));

    Storage::disk('public')->assertExists('documents/these-test.pdf');

    expect(Media::where('nom_fichier', 'these-test.pdf')->exists())->toBeTrue();
    expect(These::query()->first()?->media_id)->not->toBeNull();
});

it('updates a thesis with a new uploaded file and slug', function (): void {
    Storage::fake('public');

    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $doctorant = Doctorant::query()->create([
        'matricule' => 'DOC-003',
        'niveau' => 'D1',
        'date_inscription' => now()->toDateString(),
        'statut' => 'actif',
    ]);

    $these = These::query()->create([
        'doctorant_id' => $doctorant->id,
        'sujet_these' => 'Sujet initial',
        'statut' => 'en_cours',
    ]);

    $this->actingAs($admin)
        ->put(route('admin.theses.update', $these), [
            'doctorant_id' => $doctorant->id,
            'sujet_these' => 'Sujet initial',
            'statut' => 'en_cours',
            'slug' => 'these-update',
            'these_file' => UploadedFile::fake()->create('these.pdf', 120, 'application/pdf'),
        ])
        ->assertRedirect(route('admin.theses.index'));

    Storage::disk('public')->assertExists('documents/these-update.pdf');

    expect(Media::where('nom_fichier', 'these-update.pdf')->exists())->toBeTrue();
    expect(These::query()->first()?->media_id)->not->toBeNull();
});
