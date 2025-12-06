<?php

use App\Livewire\Frontend\HomePage;
use App\Livewire\Frontend\AboutPage;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Admin\Tags\TagIndex;
use Illuminate\Support\Facades\Route;
use App\Livewire\Frontend\ContactPage;
use App\Livewire\Admin\Media\MediaIndex;
use App\Livewire\Admin\Slides\SlideEdit;
use App\Http\Controllers\AdminController;
use App\Livewire\Admin\Media\MediaUpload;
use App\Livewire\Admin\Slides\SlideIndex;
use App\Livewire\Frontend\ActualitesPage;

// Composants Admin Livewire
use App\Livewire\Frontend\DoctorantsPage;
use App\Livewire\Frontend\ProgrammesPage;
use App\Livewire\Admin\Sliders\SliderEdit;
use App\Livewire\Admin\Slides\SlideCreate;
use App\Livewire\Admin\Sliders\SliderIndex;
use App\Livewire\Admin\Sliders\SliderCreate;
use App\Http\Controllers\DoctorantController;
use App\Http\Controllers\EncadrantController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\SecretaireController;
use App\Livewire\Frontend\ActualiteDetailPage;
use App\Http\Controllers\Admin\MediaController;
use App\Livewire\Admin\Actualites\ActualiteEdit;
use App\Livewire\Admin\Categories\CategoryIndex;
use App\Livewire\Admin\Actualites\ActualiteIndex;
use App\Livewire\Admin\Actualites\ActualiteCreate;
use App\Livewire\Admin\Newsletter\SubscriberIndex;

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
Route::get('/actualites/{actualite:id}', ActualiteDetailPage::class)->name('actualites.show');

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

    Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
    Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
    Route::get('/newsletter/resubscribe/{token}', [NewsletterController::class, 'resubscribe'])->name('newsletter.resubscribe');

    // Admin
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Gestion des Sliders
        Route::get('/sliders', SliderIndex::class)->name('sliders.index');
        Route::get('/sliders/create', SliderCreate::class)->name('sliders.create');
        Route::get('/sliders/{slider}/edit', SliderEdit::class)->name('sliders.edit');
        
        // Gestion des Slides
        Route::get('/sliders/{slider}/slides', SlideIndex::class)->name('slides.index');
        Route::get('/sliders/{slider}/slides/create', SlideCreate::class)->name('slides.create');
        Route::get('/slides/{slide}/edit', SlideEdit::class)->name('slides.edit');
        
        // Gestion de la Médiathèque
        Route::get('/media', MediaIndex::class)->name('media.index');
        Route::get('/media/upload', [MediaController::class, 'create'])->name('media.upload'); 
        Route::post('/media', [MediaController::class, 'store'])->name('media.store'); 

        // Catégories & Tags
        Route::get('/categories', CategoryIndex::class)->name('categories.index');
        Route::get('/tags', TagIndex::class)->name('tags.index');

        // Gestion des Actualités
        Route::get('/actualites', ActualiteIndex::class)->name('actualites.index');
        Route::get('/actualites/create', ActualiteCreate::class)->name('actualites.create');
        Route::get('/actualites/{actualite}/edit', ActualiteEdit::class)->name('actualites.edit');

        Route::get('/newsletter/subscribers', SubscriberIndex::class)->name('newsletter.subscribers');

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