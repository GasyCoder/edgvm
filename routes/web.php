<?php


use App\Livewire\Frontend\HomePage;
use App\Livewire\Frontend\PageShow;
use App\Livewire\Admin\Eads\EadEdit;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Admin\Eads\EadIndex;
use App\Livewire\Admin\Tags\TagIndex;
use Illuminate\Support\Facades\Route;

// Composants Admin Livewire
use App\Livewire\Admin\Eads\EadCreate;
use App\Livewire\Admin\Jurys\JuryEdit;
use App\Livewire\Admin\Pages\PageEdit;
use App\Livewire\Frontend\ContactPage;
use App\Livewire\Admin\Jurys\JuryIndex;
use App\Livewire\Admin\Pages\PageIndex;
use App\Livewire\Admin\Jurys\JuryCreate;
use App\Livewire\Admin\Media\MediaIndex;
use App\Livewire\Admin\Pages\PageCreate;
use App\Livewire\Admin\Slides\SlideEdit;
use App\Livewire\Admin\Theses\TheseEdit;
use App\Livewire\Admin\Theses\TheseShow;
use App\Http\Controllers\AdminController;
use App\Livewire\Admin\Slides\SlideIndex;
use App\Livewire\Admin\Theses\TheseIndex;
use App\Livewire\Frontend\ActualitesPage;
use App\Livewire\Frontend\EvenementsPage;
use App\Livewire\Admin\Sliders\SliderEdit;
use App\Livewire\Admin\Slides\SlideCreate;
use App\Livewire\Admin\Theses\TheseCreate;
use App\Livewire\Admin\Sliders\SliderIndex;
use App\Livewire\Admin\Sliders\SliderCreate;
use App\Livewire\Admin\Theses\TheseJuryEdit;
use App\Http\Controllers\DoctorantController;
use App\Http\Controllers\EncadrantController;
use App\Livewire\Frontend\Recherche\EadsPage;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\SecretaireController;
use App\Livewire\Frontend\ActualiteDetailPage;
use App\Livewire\Frontend\EvenementDetailPage;
use App\Livewire\Frontend\Recherche\ThesePage;
use App\Http\Controllers\Admin\MediaController;
use App\Livewire\Admin\Actualites\ActualiteEdit;
use App\Livewire\Admin\Categories\CategoryIndex;
use App\Livewire\Admin\Doctorants\DoctorantEdit;
use App\Livewire\Admin\Doctorants\DoctorantShow;
use App\Livewire\Admin\Encadrants\EncadrantEdit;
use App\Livewire\Admin\Encadrants\EncadrantShow;
use App\Livewire\Admin\Evenements\EvenementEdit;
use App\Livewire\Frontend\Recherche\TheseDetail;
use App\Livewire\Admin\Actualites\ActualiteIndex;
use App\Livewire\Admin\Doctorants\DoctorantIndex;
use App\Livewire\Admin\Encadrants\EncadrantIndex;
use App\Livewire\Admin\Evenements\EvenementIndex;
use App\Livewire\Admin\Actualites\ActualiteCreate;
use App\Livewire\Admin\Doctorants\DoctorantCreate;
use App\Livewire\Admin\Encadrants\EncadrantCreate;
use App\Livewire\Admin\Evenements\EvenementCreate;
use App\Livewire\Admin\Newsletter\SubscriberIndex;
use App\Livewire\Admin\Partenaires\PartenaireEdit;
use App\Livewire\Admin\Specialites\SpecialiteEdit;
use App\Livewire\Frontend\Recherche\EadDetailPage;
use App\Livewire\Admin\Partenaires\PartenaireIndex;
use App\Livewire\Admin\Specialites\SpecialiteIndex;
use App\Livewire\Frontend\Recherche\DoctorantsPage;
use App\Livewire\Frontend\Recherche\ProgrammesPage;
use App\Livewire\Admin\Partenaires\PartenaireCreate;
use App\Livewire\Admin\Specialites\SpecialiteCreate;
use App\Livewire\Frontend\Recherche\DoctorantDetail;
use App\Http\Controllers\Admin\TheseExportController;
use App\Http\Controllers\Admin\TheseImportController;
use App\Livewire\Admin\MessageDirections\MessageEdit;
use App\Livewire\Admin\MessageDirections\MessageForm;
use App\Livewire\Admin\MessageDirections\MessageIndex;
use App\Livewire\Frontend\Recherche\ProgrammeDetailPage;
use App\Http\Controllers\Admin\DoctorantExportController;
use App\Http\Controllers\Admin\DoctorantImportController;
use App\Http\Controllers\Admin\EncadrantExportController;
use App\Http\Controllers\Admin\EncadrantImportController;

/*
|--------------------------------------------------------------------------
| Routes Frontend
|--------------------------------------------------------------------------
*/

// Page d'accueil
Route::get('/', HomePage::class)->name('home');

// Programmes (Spécialités)
Route::prefix('programmes')->name('programmes.')->group(function () {
    Route::get('/', ProgrammesPage::class)->name('index');
    Route::get('/{specialite}', ProgrammeDetailPage::class)->name('show');
});

// EAD (Équipes d'Accueil Doctorales)
Route::prefix('equipe-accueil')->name('ead.')->group(function () {
    Route::get('/', EadsPage::class)->name('index');
    Route::get('/{ead}', EadDetailPage::class)->name('show');
});

// Doctorants
Route::prefix('doctorants')->name('doctorants.')->group(function () {
    Route::get('/', DoctorantsPage::class)->name('index');
    Route::get('/voir-{doctorant}', DoctorantDetail::class)->name('show');
});


// Thèses (frontend)
Route::prefix('theses')->name('theses.')->group(function () {
    Route::get('/', ThesePage::class)->name('index');
    Route::get('/{these}', TheseDetail::class)->name('show');
});

// Actualités
Route::prefix('actualites')->name('actualites.')->group(function () {
    Route::get('/', ActualitesPage::class)->name('index');
    Route::get('/{actualite:slug}', ActualiteDetailPage::class)->name('show');
});


// Contact
Route::get('/contact', ContactPage::class)->name('contact');

Route::get('/evenements', EvenementsPage::class)
    ->name('evenements.index');

Route::get('/evenements/{evenement}', EvenementDetailPage::class)
    ->name('evenements.show');

/*
|--------------------------------------------------------------------------
| Pages Dynamiques (gérées depuis l'admin)
|--------------------------------------------------------------------------
| Toutes les pages statiques sont gérées via la base de données
| Créer les pages dans l'admin avec les slugs suivants :
| - a-propos
| - organisation
| - conseil-scientifique
| - comite-suivi
| - reglement-interieur
*/
Route::get('/page/{slug}', PageShow::class)->name('pages.show');

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
            
            // Routes spécifiques AVANT les routes avec paramètres
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

        // Pages
        Route::prefix('pages')->name('pages.')->group(function () {
            Route::get('/', PageIndex::class)->name('index');
            Route::get('/create', PageCreate::class)->name('create');
            Route::get('/{page}/edit', PageEdit::class)->name('edit');
        });

        // Messages aux directions de programme
        Route::get('/message-directions', MessageIndex::class)
            ->name('message-directions.index');
        Route::get('/message-directions/{messageDirection}', MessageEdit::class)
            ->name('message-directions.edit');


            // PARTENAIRES
        Route::prefix('partenaires')->name('partenaires.')->group(function () {
            Route::get('/', PartenaireIndex::class)
                ->name('index');

            Route::get('/create', PartenaireCreate::class)
                ->name('create');

            Route::get('/{partenaire}/edit', PartenaireEdit::class)
                ->name('edit');
        });


        // Gestion des Événements 
        Route::prefix('evenements')->name('evenements.')->group(function () {
            // GET  /admin/evenements           -> admin.evenements.index
            Route::get('/', EvenementIndex::class)
                ->name('index');

            // GET  /admin/evenements/create    -> admin.evenements.create
            Route::get('/create', EvenementCreate::class)
                ->name('create');

            // GET  /admin/evenements/{evenement}/edit -> admin.evenements.edit
            Route::get('/{evenement}/edit', EvenementEdit::class)
                ->name('edit');
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