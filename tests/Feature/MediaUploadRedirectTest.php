<?php

use App\Models\Media;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('redirects back to the provided target after upload and stores media', function () {
    Storage::fake('public');

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

    $file = UploadedFile::fake()->image('media.jpg', 800, 600);
    $redirectTo = route('admin.slides.create', $slider).'?media_page=1';

    $this->actingAs($admin)
        ->post(route('admin.media.store'), [
            'files' => [$file],
            'redirect_to' => $redirectTo,
        ])
        ->assertRedirect($redirectTo)
        ->assertSessionHas('uploaded_media_id');

    $media = Media::query()->firstOrFail();
    Storage::disk('public')->assertExists($media->chemin);
});
