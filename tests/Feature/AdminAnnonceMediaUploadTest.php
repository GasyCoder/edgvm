<?php

use App\Models\Annonce;
use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('stores annonce with uploaded media', function (): void {
    Storage::fake('public');

    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $file = UploadedFile::fake()->image('annonce.jpg', 1200, 800);

    $this->actingAs($admin)
        ->post(route('admin.annonces.store'), [
            'titre' => 'Annonce test',
            'contenu_html' => '<p>Contenu</p>',
            'cible' => 'both',
            'media_file' => $file,
            'est_publie' => false,
            'envoyer_email' => false,
            'email_cible' => 'both',
        ])
        ->assertRedirect(route('admin.annonces.index'));

    $annonce = Annonce::query()->firstOrFail();
    $media = Media::query()->find($annonce->media_id);

    expect($media)->not->toBeNull();
    Storage::disk('public')->assertExists($media->chemin);
});
