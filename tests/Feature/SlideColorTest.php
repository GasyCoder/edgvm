<?php

use App\Models\Media;
use App\Models\Slide;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

it('can store a slide with custom colors', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $slider = Slider::query()->create([
        'nom' => 'Homepage',
        'position' => 'homepage',
        'visible' => true,
    ]);

    $image = UploadedFile::fake()->image('slide.jpg', 1920, 1080);

    $this->actingAs($admin)
        ->post(route('admin.slides.store', $slider), [
            'titre' => 'Test Slide',
            'description' => 'Test description',
            'new_image' => $image,
            'ordre' => 1,
            'visible' => true,
            'couleur_texte_titre' => '#FF5733',
            'couleur_cta' => '#33FF57',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('slides', [
        'slider_id' => $slider->id,
        'titre_highlight' => 'Test Slide',
        'couleur_texte_titre' => '#FF5733',
        'couleur_cta' => '#33FF57',
    ]);
});

it('uses default colors when not provided', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $slider = Slider::query()->create([
        'nom' => 'Homepage',
        'position' => 'homepage',
        'visible' => true,
    ]);

    $image = UploadedFile::fake()->image('slide.jpg', 1920, 1080);

    $this->actingAs($admin)
        ->post(route('admin.slides.store', $slider), [
            'titre' => 'Test Slide',
            'new_image' => $image,
            'ordre' => 1,
            'visible' => true,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('slides', [
        'slider_id' => $slider->id,
        'titre_highlight' => 'Test Slide',
        'couleur_texte_titre' => '#FFFFFF',
        'couleur_cta' => '#FFFFFF',
    ]);
});

it('can store a slide using a media library image', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $slider = Slider::query()->create([
        'nom' => 'Homepage',
        'position' => 'homepage',
        'visible' => true,
    ]);

    $media = Media::query()->create([
        'nom_original' => 'library.jpg',
        'nom_fichier' => 'library.jpg',
        'chemin' => 'images/library.jpg',
        'type' => 'image',
        'taille_bytes' => 2048,
        'mime_type' => 'image/jpeg',
        'uploader_id' => $admin->id,
    ]);

    $this->actingAs($admin)
        ->post(route('admin.slides.store', $slider), [
            'titre' => 'Media Slide',
            'media_id' => $media->id,
            'ordre' => 1,
            'visible' => true,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('slides', [
        'slider_id' => $slider->id,
        'titre_highlight' => 'Media Slide',
        'image_id' => $media->id,
    ]);
});

it('can update slide colors', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $slider = Slider::query()->create([
        'nom' => 'Homepage',
        'position' => 'homepage',
        'visible' => true,
    ]);

    $media = Media::query()->create([
        'nom_original' => 'slide.jpg',
        'nom_fichier' => 'slide.jpg',
        'chemin' => 'slides/slide.jpg',
        'type' => 'image',
        'taille_bytes' => 1024,
        'mime_type' => 'image/jpeg',
        'uploader_id' => $admin->id,
    ]);

    $slide = Slide::query()->create([
        'slider_id' => $slider->id,
        'titre_highlight' => 'Original Title',
        'image_id' => $media->id,
        'ordre' => 1,
        'visible' => true,
        'couleur_texte_titre' => '#FFFFFF',
        'couleur_cta' => '#FFFFFF',
    ]);

    $this->actingAs($admin)
        ->put(route('admin.slides.update', [$slider, $slide]), [
            'titre' => 'Updated Title',
            'ordre' => 1,
            'visible' => true,
            'couleur_texte_titre' => '#AABBCC',
            'couleur_cta' => '#DDEEFF',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('slides', [
        'id' => $slide->id,
        'titre_highlight' => 'Updated Title',
        'couleur_texte_titre' => '#AABBCC',
        'couleur_cta' => '#DDEEFF',
    ]);
});

it('can update slide image using the media library', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $slider = Slider::query()->create([
        'nom' => 'Homepage',
        'position' => 'homepage',
        'visible' => true,
    ]);

    $media = Media::query()->create([
        'nom_original' => 'first.jpg',
        'nom_fichier' => 'first.jpg',
        'chemin' => 'images/first.jpg',
        'type' => 'image',
        'taille_bytes' => 1024,
        'mime_type' => 'image/jpeg',
        'uploader_id' => $admin->id,
    ]);

    $newMedia = Media::query()->create([
        'nom_original' => 'second.jpg',
        'nom_fichier' => 'second.jpg',
        'chemin' => 'images/second.jpg',
        'type' => 'image',
        'taille_bytes' => 2048,
        'mime_type' => 'image/jpeg',
        'uploader_id' => $admin->id,
    ]);

    $slide = Slide::query()->create([
        'slider_id' => $slider->id,
        'titre_highlight' => 'Original Title',
        'image_id' => $media->id,
        'ordre' => 1,
        'visible' => true,
        'couleur_texte_titre' => '#FFFFFF',
        'couleur_cta' => '#FFFFFF',
    ]);

    $this->actingAs($admin)
        ->put(route('admin.slides.update', [$slider, $slide]), [
            'titre' => 'Updated Title',
            'media_id' => $newMedia->id,
            'ordre' => 1,
            'visible' => true,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('slides', [
        'id' => $slide->id,
        'image_id' => $newMedia->id,
    ]);
});

it('passes color data to edit page', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $slider = Slider::query()->create([
        'nom' => 'Homepage',
        'position' => 'homepage',
        'visible' => true,
    ]);

    $media = Media::query()->create([
        'nom_original' => 'slide.jpg',
        'nom_fichier' => 'slide.jpg',
        'chemin' => 'slides/slide.jpg',
        'type' => 'image',
        'taille_bytes' => 1024,
        'mime_type' => 'image/jpeg',
        'uploader_id' => $admin->id,
    ]);

    $slide = Slide::query()->create([
        'slider_id' => $slider->id,
        'titre_highlight' => 'Test Slide',
        'image_id' => $media->id,
        'ordre' => 1,
        'visible' => true,
        'couleur_texte_titre' => '#123456',
        'couleur_cta' => '#654321',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.slides.edit', [$slider, $slide]))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Slides/Edit')
            ->where('slide.couleur_texte_titre', '#123456')
            ->where('slide.couleur_cta', '#654321')
            ->where('slide.image_id', $media->id)
        );
});

it('validates color format', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $slider = Slider::query()->create([
        'nom' => 'Homepage',
        'position' => 'homepage',
        'visible' => true,
    ]);

    $image = UploadedFile::fake()->image('slide.jpg', 1920, 1080);

    $this->actingAs($admin)
        ->post(route('admin.slides.store', $slider), [
            'titre' => 'Test Slide',
            'new_image' => $image,
            'ordre' => 1,
            'visible' => true,
            'couleur_texte_titre' => 'invalid-color',
            'couleur_cta' => 'not-a-hex',
        ])
        ->assertSessionHasErrors(['couleur_texte_titre', 'couleur_cta']);
});
