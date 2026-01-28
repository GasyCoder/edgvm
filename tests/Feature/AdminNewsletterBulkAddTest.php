<?php

use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('adds subscribers in bulk', function (): void {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $payload = [
        'emails' => "alpha@example.com\nbeta@example.com, alpha@example.com",
        'type' => 'doctorant',
        'actif' => false,
    ];

    $this->actingAs($admin)
        ->post(route('admin.newsletter.subscribers.bulk'), $payload)
        ->assertRedirect(route('admin.newsletter.subscribers'));

    expect(NewsletterSubscriber::query()->count())->toBe(2);

    $subscriber = NewsletterSubscriber::query()->where('email', 'alpha@example.com')->first();
    expect($subscriber)->not->toBeNull();
    expect($subscriber->type)->toBe('doctorant');
    expect($subscriber->actif)->toBeFalse();
});

it('rejects invalid emails for bulk add', function (): void {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $payload = [
        'emails' => "bad-email\nvalid@example.com",
        'type' => 'autre',
        'actif' => true,
    ];

    $this->actingAs($admin)
        ->post(route('admin.newsletter.subscribers.bulk'), $payload)
        ->assertSessionHasErrors('emails');

    expect(NewsletterSubscriber::query()->count())->toBe(0);
});
