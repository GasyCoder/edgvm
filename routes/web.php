<?php

use App\Livewire\Frontend\HomePage;
use App\Livewire\Frontend\AboutPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Frontend\ContactPage;
use App\Http\Controllers\AdminController;
use App\Livewire\Frontend\ActualitesPage;
use App\Livewire\Frontend\DoctorantsPage;
use App\Livewire\Frontend\ProgrammesPage;
use App\Http\Controllers\DoctorantController;
use App\Http\Controllers\EncadrantController;
use App\Http\Controllers\SecretaireController;

// Frontend Routes
Route::get('/', HomePage::class)->name('home');
Route::get('/about', AboutPage::class)->name('about');
Route::get('/organisation', AboutPage::class)->name('organisation');
Route::get('/conseil', AboutPage::class)->name('conseil');
Route::get('/comite-suivi', AboutPage::class)->name('comite-suivi');
Route::get('/reglement', AboutPage::class)->name('reglement');
Route::get('/programmes', ProgrammesPage::class)->name('programmes');
Route::get('/programmes/{id}', ProgrammesPage::class)->name('programmes.show');
Route::get('/doctorants', DoctorantsPage::class)->name('doctorants');
Route::get('/fiche-numerique', DoctorantsPage::class)->name('fiche-numerique');
Route::get('/theses-soutenues', DoctorantsPage::class)->name('theses-soutenues');
Route::get('/actualites', ActualitesPage::class)->name('actualites');
Route::get('/actualites/{id}', ActualitesPage::class)->name('actualites.show');
Route::get('/contact', ContactPage::class)->name('contact');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    
    // Redirection automatique selon le rôle
    Route::get('/dashboard', function () {
        $user = Auth::user();
        
        return match($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'secrétaire' => redirect()->route('secretaire.dashboard'),
            'encadrant' => redirect()->route('encadrant.dashboard'),
            'doctorant' => redirect()->route('doctorant.dashboard'),
            default => abort(403, 'Rôle non reconnu'),
        };
    })->name('dashboard');

    // Admin
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    });

    // Secrétaire
    Route::middleware(['role:admin,secrétaire'])->prefix('secretariat')->name('secretaire.')->group(function () {
        Route::get('/dashboard', [SecretaireController::class, 'dashboard'])->name('dashboard');
    });

    // Encadrant
    Route::middleware(['role:encadrant'])->prefix('encadrant')->name('encadrant.')->group(function () {
        Route::get('/dashboard', [EncadrantController::class, 'dashboard'])->name('dashboard');
    });

    // Doctorant
    Route::middleware(['role:doctorant'])->prefix('doctorant')->name('doctorant.')->group(function () {
        Route::get('/dashboard', [DoctorantController::class, 'dashboard'])->name('dashboard');
    });
});