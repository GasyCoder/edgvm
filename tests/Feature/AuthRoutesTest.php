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

    $this->get(route('register'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page->component('Auth/Register'));

    $this->get(route('password.request'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page->component('Auth/ForgotPassword'));

    $this->get(route('password.reset', ['token' => 'test-token', 'email' => 'user@example.com']))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page->component('Auth/ResetPassword'));

    $this->get(route('two-factor.login'))
        ->assertRedirect(route('login'));
});

it('renders confirm password screen for authenticated users', function () {
    $user = User::factory()->create([
        'active' => true,
    ]);

    $this->actingAs($user)
        ->get(route('password.confirm'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page->component('Auth/ConfirmPassword'));
});

it('renders verification notice for authenticated users', function () {
    $user = User::factory()->create([
        'active' => true,
        'email_verified_at' => null,
    ]);

    $this->actingAs($user)
        ->get(route('verification.notice'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page->component('Auth/VerifyEmail'));
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
