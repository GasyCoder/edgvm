<?php

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('updates message direction statistics in settings', function (): void {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $settings = Setting::query()->create([
        'site_name' => 'EDGVM',
    ]);

    $this->actingAs($admin)
        ->put(route('admin.settings.statistics'), [
            'message_direction_doctorants' => 120,
            'message_direction_equipes' => 12,
            'message_direction_theses' => 34,
        ])
        ->assertRedirect(route('admin.settings'));

    $settings->refresh();

    expect($settings->message_direction_doctorants)->toBe(120)
        ->and($settings->message_direction_equipes)->toBe(12)
        ->and($settings->message_direction_theses)->toBe(34);
});

it('validates message direction statistics values', function (): void {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    Setting::query()->create([
        'site_name' => 'EDGVM',
    ]);

    $this->actingAs($admin)
        ->from(route('admin.settings'))
        ->put(route('admin.settings.statistics'), [
            'message_direction_doctorants' => -1,
            'message_direction_equipes' => -5,
            'message_direction_theses' => -10,
        ])
        ->assertSessionHasErrors([
            'message_direction_doctorants',
            'message_direction_equipes',
            'message_direction_theses',
        ]);
});
