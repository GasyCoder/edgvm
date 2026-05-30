<?php

use App\Models\EAD;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('auto-generates a unique slug and stores the sigle without a slug field', function (): void {
    $admin = User::factory()->create([
        'role' => 'super_admin',
        'active' => true,
        'email_verified_at' => now(),
    ]);

    $this->actingAs($admin)
        ->post(route('admin.ead.store'), [
            'nom' => 'Sciences du Vivant et Modélisation',
            'sigle' => 'SVM',
        ])
        ->assertRedirect(route('admin.ead.index'));

    $ead = EAD::first();
    expect($ead->slug)->toBe('sciences-du-vivant-et-modelisation');
    expect($ead->sigle)->toBe('SVM');

    // Un second nom identique reçoit un slug unique suffixé
    $this->actingAs($admin)
        ->post(route('admin.ead.store'), ['nom' => 'Sciences du Vivant et Modélisation'])
        ->assertRedirect();

    expect(EAD::orderByDesc('id')->first()->slug)->toBe('sciences-du-vivant-et-modelisation-2');
});
