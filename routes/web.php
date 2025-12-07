<?php


use App\Livewire\Frontend\EadsPage;
use App\Livewire\Frontend\HomePage;
use App\Livewire\Admin\Eads\EadEdit;
use App\Livewire\Frontend\AboutPage;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Admin\Eads\EadIndex;
use App\Livewire\Admin\Tags\TagIndex;
use Illuminate\Support\Facades\Route;

// Composants Admin Livewire
use App\Livewire\Admin\Eads\EadCreate;
use App\Livewire\Admin\Jurys\JuryEdit;
use App\Livewire\Frontend\ContactPage;
use App\Livewire\Admin\Jurys\JuryIndex;
use App\Livewire\Admin\Jurys\JuryCreate;
use App\Livewire\Admin\Media\MediaIndex;
use App\Livewire\Admin\Slides\SlideEdit;
use App\Livewire\Admin\Theses\TheseEdit;
use App\Livewire\Admin\Theses\TheseShow;
use App\Livewire\Frontend\EadDetailPage;
use App\Http\Controllers\AdminController;
use App\Livewire\Admin\Media\MediaUpload;
use App\Livewire\Admin\Slides\SlideIndex;
use App\Livewire\Admin\Theses\TheseIndex;
use App\Livewire\Frontend\ActualitesPage;
use App\Livewire\Frontend\DoctorantsPage;
use App\Livewire\Frontend\ProgrammesPage;
use App\Livewire\Admin\Sliders\SliderEdit;
use App\Livewire\Admin\Slides\SlideCreate;
use App\Livewire\Admin\Theses\TheseCreate;
use App\Livewire\Admin\Sliders\SliderIndex;
use App\Livewire\Admin\Sliders\SliderCreate;
use App\Livewire\Admin\Theses\TheseJuryEdit;
use App\Http\Controllers\DoctorantController;
use App\Http\Controllers\EncadrantController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\SecretaireController;
use App\Livewire\Frontend\ActualiteDetailPage;
use App\Livewire\Frontend\ProgrammeDetailPage;
use App\Http\Controllers\Admin\MediaController;
use App\Livewire\Admin\Actualites\ActualiteEdit;
use App\Livewire\Admin\Categories\CategoryIndex;
use App\Livewire\Admin\Doctorants\DoctorantEdit;
use App\Livewire\Admin\Doctorants\DoctorantShow;
use App\Livewire\Admin\Encadrants\EncadrantEdit;
use App\Livewire\Admin\Encadrants\EncadrantShow;
use App\Livewire\Admin\Actualites\ActualiteIndex;
use App\Livewire\Admin\Doctorants\DoctorantIndex;
use App\Livewire\Admin\Encadrants\EncadrantIndex;
use App\Livewire\Admin\Actualites\ActualiteCreate;
use App\Livewire\Admin\Doctorants\DoctorantCreate;
use App\Livewire\Admin\Encadrants\EncadrantCreate;
use App\Livewire\Admin\Newsletter\SubscriberIndex;
use App\Livewire\Admin\Specialites\SpecialiteEdit;
use App\Livewire\Admin\Specialites\SpecialiteIndex;
use App\Livewire\Admin\Specialites\SpecialiteCreate;
use App\Http\Controllers\Admin\TheseExportController;
use App\Http\Controllers\Admin\TheseImportController;
use App\Http\Controllers\Admin\DoctorantExportController;
use App\Http\Controllers\Admin\DoctorantImportController;
use App\Http\Controllers\Admin\EncadrantExportController;
use App\Http\Controllers\Admin\EncadrantImportController;

// Frontend Routes
Route::get('/', HomePage::class)->name('home');
Route::get('/about', AboutPage::class)->name('about');
Route::get('/organisation', AboutPage::class)->name('organisation');
Route::get('/conseil', AboutPage::class)->name('conseil');
Route::get('/comite-suivi', AboutPage::class)->name('comite-suivi');
Route::get('/reglement', AboutPage::class)->name('reglement');
// Programmes (Spécialités)
Route::get('/programmes', ProgrammesPage::class)->name('programmes');
Route::get('/programmes/{specialite}', ProgrammeDetailPage::class)->name('programmes.show');
// EAD
Route::get('/ead', EadsPage::class)->name('ead');
Route::get('/ead/{ead}', EadDetailPage::class)->name('ead.show');

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


        // Spécialités
        Route::prefix('specialites')->name('specialites.')->group(function () {
            Route::get('/', SpecialiteIndex::class)->name('index');
            Route::get('/create', SpecialiteCreate::class)->name('create');
            Route::get('/{specialite}/edit', SpecialiteEdit::class)->name('edit');
        });
        
        // EAD
        Route::prefix('ead')->name('ead.')->group(function () {
            Route::get('/', EadIndex::class)->name('index');
            Route::get('/create', EadCreate::class)->name('create');
            Route::get('/{ead}/edit', EadEdit::class)->name('edit');
        });


        // Doctorants
        Route::prefix('doctorants')->name('doctorants.')->group(function () {
            Route::get('/', DoctorantIndex::class)->name('index');
            Route::get('/create', DoctorantCreate::class)->name('create');
            
            // Export/Import AVANT les routes avec {doctorant}
            Route::get('/export', [DoctorantExportController::class, 'export'])->name('export');
            Route::post('/import', [DoctorantImportController::class, 'import'])->name('import');
            
            // Routes avec paramètres à la fin
            Route::get('/{doctorant}', DoctorantShow::class)->name('show');
            Route::get('/{doctorant}/edit', DoctorantEdit::class)->name('edit');
        });

        // Encadrants
        Route::prefix('encadrants')->name('encadrants.')->group(function () {
            Route::get('/', EncadrantIndex::class)->name('index');
            Route::get('/create', EncadrantCreate::class)->name('create');
            
            // ⚠️ IMPORTANT : Routes spécifiques AVANT les routes avec paramètres
            Route::get('/export', [EncadrantExportController::class, 'export'])->name('export');
            Route::post('/import', [EncadrantImportController::class, 'import'])->name('import');
            
            // Routes avec paramètres dynamiques en DERNIER
            Route::get('/{encadrant}', EncadrantShow::class)->name('show');
            Route::get('/{encadrant}/edit', EncadrantEdit::class)->name('edit');
        });

        // Thèses
        Route::prefix('theses')->name('theses.')->group(function () {
            Route::get('/', TheseIndex::class)->name('index');
            Route::get('/create', TheseCreate::class)->name('create');

            // Routes spécifiques AVANT paramètres dynamiques
            Route::get('/export', [TheseExportController::class, 'export'])->name('export');
            Route::post('/import', [TheseImportController::class, 'import'])->name('import');

            // 🔹 Gestion du jury d'une thèse
            Route::get('/{these}/jury', TheseJuryEdit::class)->name('jury.edit');

            // Routes avec paramètres
            Route::get('/{these}', TheseShow::class)->name('show');
            Route::get('/{these}/edit', TheseEdit::class)->name('edit');
        });

        // Jurys
        Route::prefix('jurys')->name('jurys.')->group(function () {
            Route::get('/', JuryIndex::class)->name('index');    
            Route::get('/create', JuryCreate::class)->name('create'); 
            Route::get('/{jury}/edit', JuryEdit::class)->name('edit'); 
        });
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