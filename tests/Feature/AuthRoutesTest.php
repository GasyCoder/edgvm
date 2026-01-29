<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

it('renders auth screens for guests', function () {
    $this->get(route('login'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page->component('Auth/Login'));

    $this->get('/register')->assertNotFound();

    $this->get('/forgot-password')->assertNotFound();

    $this->get('/reset-password/test-token?email=user@example.com')->assertNotFound();

    $this->get('/two-factor-challenge')->assertNotFound();
});

it('renders confirm password screen for authenticated users', function () {
    $user = User::factory()->create([
        'active' => true,
    ]);

    $this->actingAs($user)
        ->get('/user/confirm-password')
        ->assertNotFound();
});

it('renders verification notice for authenticated users', function () {
    $user = User::factory()->create([
        'active' => true,
        'email_verified_at' => null,
    ]);

    $this->actingAs($user)
        ->get('/email/verify')
        ->assertNotFound();
});

it('authenticates a user', function () {
    $password = 'Password123!';
    $user = User::factory()->create([
        'active' => true,
        'password' => Hash::make($password),
    ]);

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => $password,
    ])
        ->assertRedirect('/dashboard');

    $this->assertAuthenticatedAs($user);
});
