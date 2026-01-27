<?php

use App\Http\Controllers\Admin\ActualiteController as AdminActualiteController;
use App\Http\Controllers\Admin\AnnonceController as AdminAnnonceController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DoctorantController as AdminDoctorantController;
use App\Http\Controllers\Admin\DoctorantExportController;
use App\Http\Controllers\Admin\DoctorantImportController;
use App\Http\Controllers\Admin\EadController as AdminEadController;
use App\Http\Controllers\Admin\EncadrantController as AdminEncadrantController;
use App\Http\Controllers\Admin\EncadrantExportController;
use App\Http\Controllers\Admin\EncadrantImportController;
use App\Http\Controllers\Admin\EvenementController as AdminEvenementController;
use App\Http\Controllers\Admin\JuryController as AdminJuryController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\MessageDirectionController as AdminMessageDirectionController;
use App\Http\Controllers\Admin\NewsletterSubscriberController as AdminNewsletterSubscriberController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PartenaireController as AdminPartenaireController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\SlideController as AdminSlideController;
use App\Http\Controllers\Admin\SliderController as AdminSliderController;
use App\Http\Controllers\Admin\SpecialiteController as AdminSpecialiteController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\Admin\TheseController as AdminTheseController;
use App\Http\Controllers\Admin\TheseExportController;
use App\Http\Controllers\Admin\TheseImportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorantController;
use App\Http\Controllers\EncadrantController;
use App\Http\Controllers\Frontend\ActualiteController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\EvenementController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\Recherche\DoctorantController as FrontendDoctorantController;
use App\Http\Controllers\Frontend\Recherche\EadController;
use App\Http\Controllers\Frontend\Recherche\ProgrammeController;
use App\Http\Controllers\Frontend\Recherche\TheseController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\SecretaireController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Frontend
|--------------------------------------------------------------------------
*/
Route::middleware(['frontend.maintenance'])->group(function () {
    // Page d'accueil
    Route::get('/', HomeController::class)->name('home');

    // Programmes (Spécialités)
    Route::prefix('programmes')->name('programmes.')->group(function () {
        Route::get('/', [ProgrammeController::class, 'index'])->name('index');
        Route::get('/{specialite}', [ProgrammeController::class, 'show'])->name('show');
    });

    // EAD (Équipes d'Accueil Doctorales)
    Route::prefix('equipe-accueil')->name('ead.')->group(function () {
        Route::get('/', [EadController::class, 'index'])->name('index');
        Route::get('/{ead}', [EadController::class, 'show'])->name('show');
    });

    // Doctorants
    Route::prefix('doctorants')->name('doctorants.')->group(function () {
        Route::get('/', [FrontendDoctorantController::class, 'index'])->name('index');
        Route::get('/voir-{doctorant}', [FrontendDoctorantController::class, 'show'])->name('show');
    });

    // Thèses (frontend)
    Route::prefix('theses')->name('theses.')->group(function () {
        Route::get('/', [TheseController::class, 'index'])->name('index');
        Route::get('/{these}', [TheseController::class, 'show'])->name('show');
    });

    // Actualités
    Route::prefix('actualites')->name('actualites.')->group(function () {
        Route::get('/', [ActualiteController::class, 'index'])->name('index');
        Route::get('/{actualite:slug}', [ActualiteController::class, 'show'])->name('show');
    });

    // Contact
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

    // Événements
    Route::get('/evenements', [EvenementController::class, 'index'])->name('evenements.index');
    Route::get('/evenements/{evenement}', [EvenementController::class, 'show'])->name('evenements.show');

    // Pages dynamiques
    Route::get('/page/{slug}', [PageController::class, 'show'])->name('pages.show');

    Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
    Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
    Route::get('/newsletter/resubscribe/{token}', [NewsletterController::class, 'resubscribe'])->name('newsletter.resubscribe');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {

    // Redirection automatique selon le rôle
    Route::get('/dashboard', function () {
        $user = Auth::user();

        return match ($user->role) {
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
        Route::get('/parametres', [AdminSettingsController::class, 'index'])->name('settings');
        Route::put('/parametres/general', [AdminSettingsController::class, 'updateGeneral'])->name('settings.general');
        Route::put('/parametres/seo', [AdminSettingsController::class, 'updateSeo'])->name('settings.seo');
        Route::put('/parametres/maintenance', [AdminSettingsController::class, 'updateMaintenance'])->name('settings.maintenance');
        Route::put('/parametres/media', [AdminSettingsController::class, 'updateMedia'])->name('settings.media');
        Route::put('/parametres/security', [AdminSettingsController::class, 'updateSecurity'])->name('settings.security');
        Route::put('/parametres/secretaire', [AdminSettingsController::class, 'updateSecretaire'])->name('settings.secretaire');
        // Gestion des Sliders
        Route::get('/sliders', [AdminSliderController::class, 'index'])->name('sliders.index');
        Route::get('/sliders/create', [AdminSliderController::class, 'create'])->name('sliders.create');
        Route::post('/sliders', [AdminSliderController::class, 'store'])->name('sliders.store');
        Route::get('/sliders/{slider}/edit', [AdminSliderController::class, 'edit'])->name('sliders.edit');
        Route::put('/sliders/{slider}', [AdminSliderController::class, 'update'])->name('sliders.update');
        Route::delete('/sliders/{slider}', [AdminSliderController::class, 'destroy'])->name('sliders.destroy');
        Route::patch('/sliders/{slider}/toggle', [AdminSliderController::class, 'toggleVisibility'])->name('sliders.toggle');

        // Gestion des Slides
        Route::get('/sliders/{slider}/slides', [AdminSlideController::class, 'index'])->name('slides.index');
        Route::get('/sliders/{slider}/slides/create', [AdminSlideController::class, 'create'])->name('slides.create');
        Route::post('/sliders/{slider}/slides', [AdminSlideController::class, 'store'])->name('slides.store');
        Route::get('/sliders/{slider}/slides/{slide}/edit', [AdminSlideController::class, 'edit'])->name('slides.edit');
        Route::put('/sliders/{slider}/slides/{slide}', [AdminSlideController::class, 'update'])->name('slides.update');
        Route::delete('/sliders/{slider}/slides/{slide}', [AdminSlideController::class, 'destroy'])->name('slides.destroy');
        Route::patch('/sliders/{slider}/slides/{slide}/toggle', [AdminSlideController::class, 'toggleVisibility'])->name('slides.toggle');
        Route::patch('/sliders/{slider}/slides/order', [AdminSlideController::class, 'updateOrder'])->name('slides.order');

        // Gestion de la Médiathèque
        Route::get('/media', [MediaController::class, 'index'])->name('media.index');
        Route::get('/media/upload', [MediaController::class, 'create'])->name('media.upload');
        Route::post('/media', [MediaController::class, 'store'])->name('media.store');
        Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');

        // Catégories & Tags
        Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

        Route::get('/tags', [AdminTagController::class, 'index'])->name('tags.index');
        Route::post('/tags', [AdminTagController::class, 'store'])->name('tags.store');
        Route::put('/tags/{tag}', [AdminTagController::class, 'update'])->name('tags.update');
        Route::delete('/tags/{tag}', [AdminTagController::class, 'destroy'])->name('tags.destroy');

        // Gestion des Actualités
        Route::get('/actualites', [AdminActualiteController::class, 'index'])->name('actualites.index');
        Route::get('/actualites/create', [AdminActualiteController::class, 'create'])->name('actualites.create');
        Route::post('/actualites', [AdminActualiteController::class, 'store'])->name('actualites.store');
        Route::get('/actualites/{actualite}/edit', [AdminActualiteController::class, 'edit'])->name('actualites.edit');
        Route::put('/actualites/{actualite}', [AdminActualiteController::class, 'update'])->name('actualites.update');
        Route::delete('/actualites/{actualite}', [AdminActualiteController::class, 'destroy'])->name('actualites.destroy');
        Route::patch('/actualites/{actualite}/toggle', [AdminActualiteController::class, 'toggleVisibility'])
            ->name('actualites.toggle');

        Route::get('/newsletter/subscribers', [AdminNewsletterSubscriberController::class, 'index'])
            ->name('newsletter.subscribers');
        Route::post('/newsletter/subscribers', [AdminNewsletterSubscriberController::class, 'store'])
            ->name('newsletter.subscribers.store');
        Route::put('/newsletter/subscribers/{subscriber}', [AdminNewsletterSubscriberController::class, 'update'])
            ->name('newsletter.subscribers.update');
        Route::delete('/newsletter/subscribers/{subscriber}', [AdminNewsletterSubscriberController::class, 'destroy'])
            ->name('newsletter.subscribers.destroy');
        Route::patch('/newsletter/subscribers/{subscriber}/toggle', [AdminNewsletterSubscriberController::class, 'toggle'])
            ->name('newsletter.subscribers.toggle');
        Route::get('/newsletter/subscribers/export', [AdminNewsletterSubscriberController::class, 'export'])
            ->name('newsletter.subscribers.export');
        Route::post('/newsletter/subscribers/import', [AdminNewsletterSubscriberController::class, 'import'])
            ->name('newsletter.subscribers.import');

        // Spécialités
        Route::prefix('specialites')->name('specialites.')->group(function () {
            Route::get('/', [AdminSpecialiteController::class, 'index'])->name('index');
            Route::get('/create', [AdminSpecialiteController::class, 'create'])->name('create');
            Route::post('/', [AdminSpecialiteController::class, 'store'])->name('store');
            Route::get('/{specialite}/edit', [AdminSpecialiteController::class, 'edit'])->name('edit');
            Route::put('/{specialite}', [AdminSpecialiteController::class, 'update'])->name('update');
            Route::delete('/{specialite}', [AdminSpecialiteController::class, 'destroy'])->name('destroy');
        });

        // EAD
        Route::prefix('ead')->name('ead.')->group(function () {
            Route::get('/', [AdminEadController::class, 'index'])->name('index');
            Route::get('/create', [AdminEadController::class, 'create'])->name('create');
            Route::post('/', [AdminEadController::class, 'store'])->name('store');
            Route::get('/{ead}/edit', [AdminEadController::class, 'edit'])->name('edit');
            Route::put('/{ead}', [AdminEadController::class, 'update'])->name('update');
            Route::delete('/{ead}', [AdminEadController::class, 'destroy'])->name('destroy');
        });

        // Doctorants
        Route::prefix('doctorants')->name('doctorants.')->group(function () {
            Route::get('/', [AdminDoctorantController::class, 'index'])->name('index');
            Route::get('/create', [AdminDoctorantController::class, 'create'])->name('create');
            Route::post('/', [AdminDoctorantController::class, 'store'])->name('store');

            // Export/Import AVANT les routes avec {doctorant}
            Route::get('/export', [DoctorantExportController::class, 'export'])->name('export');
            Route::post('/import', [DoctorantImportController::class, 'import'])->name('import');

            // Routes avec paramètres à la fin
            Route::get('/{doctorant}', [AdminDoctorantController::class, 'show'])->name('show');
            Route::get('/{doctorant}/edit', [AdminDoctorantController::class, 'edit'])->name('edit');
            Route::put('/{doctorant}', [AdminDoctorantController::class, 'update'])->name('update');
            Route::delete('/{doctorant}', [AdminDoctorantController::class, 'destroy'])->name('destroy');
        });

        // Encadrants
        Route::prefix('encadrants')->name('encadrants.')->group(function () {
            Route::get('/', [AdminEncadrantController::class, 'index'])->name('index');
            Route::get('/create', [AdminEncadrantController::class, 'create'])->name('create');
            Route::post('/', [AdminEncadrantController::class, 'store'])->name('store');

            // Routes spécifiques AVANT les routes avec paramètres
            Route::get('/export', [EncadrantExportController::class, 'export'])->name('export');
            Route::post('/import', [EncadrantImportController::class, 'import'])->name('import');

            // Routes avec paramètres dynamiques en DERNIER
            Route::get('/{encadrant}', [AdminEncadrantController::class, 'show'])->name('show');
            Route::get('/{encadrant}/edit', [AdminEncadrantController::class, 'edit'])->name('edit');
            Route::put('/{encadrant}', [AdminEncadrantController::class, 'update'])->name('update');
            Route::delete('/{encadrant}', [AdminEncadrantController::class, 'destroy'])->name('destroy');
        });

        // Thèses
        Route::prefix('theses')->name('theses.')->group(function () {
            Route::get('/', [AdminTheseController::class, 'index'])->name('index');
            Route::get('/create', [AdminTheseController::class, 'create'])->name('create');
            Route::post('/', [AdminTheseController::class, 'store'])->name('store');

            // Routes spécifiques AVANT paramètres dynamiques
            Route::get('/export', [TheseExportController::class, 'export'])->name('export');
            Route::post('/import', [TheseImportController::class, 'import'])->name('import');

            // 🔹 Gestion du jury d'une thèse
            Route::get('/{these}/jury', [AdminTheseController::class, 'editJury'])->name('jury.edit');
            Route::put('/{these}/jury', [AdminTheseController::class, 'updateJury'])->name('jury.update');

            // Routes avec paramètres
            Route::get('/{these}', [AdminTheseController::class, 'show'])->name('show');
            Route::get('/{these}/edit', [AdminTheseController::class, 'edit'])->name('edit');
            Route::put('/{these}', [AdminTheseController::class, 'update'])->name('update');
            Route::delete('/{these}', [AdminTheseController::class, 'destroy'])->name('destroy');
        });

        // Jurys
        Route::prefix('jurys')->name('jurys.')->group(function () {
            Route::get('/', [AdminJuryController::class, 'index'])->name('index');
            Route::get('/create', [AdminJuryController::class, 'create'])->name('create');
            Route::post('/', [AdminJuryController::class, 'store'])->name('store');
            Route::get('/{jury}/edit', [AdminJuryController::class, 'edit'])->name('edit');
            Route::put('/{jury}', [AdminJuryController::class, 'update'])->name('update');
            Route::delete('/{jury}', [AdminJuryController::class, 'destroy'])->name('destroy');
        });

        // Pages
        Route::prefix('pages')->name('pages.')->group(function () {
            Route::get('/', [AdminPageController::class, 'index'])->name('index');
            Route::get('/create', [AdminPageController::class, 'create'])->name('create');
            Route::post('/', [AdminPageController::class, 'store'])->name('store');
            Route::get('/{page}/edit', [AdminPageController::class, 'edit'])->name('edit');
            Route::put('/{page}', [AdminPageController::class, 'update'])->name('update');
            Route::delete('/{page}', [AdminPageController::class, 'destroy'])->name('destroy');
            Route::patch('/{page}/toggle', [AdminPageController::class, 'toggleVisibility'])->name('toggle');
            Route::post('/{page}/sections', [AdminPageController::class, 'storeSection'])->name('sections.store');
            Route::put('/{page}/sections/{section}', [AdminPageController::class, 'updateSection'])->name('sections.update');
            Route::delete('/{page}/sections/{section}', [AdminPageController::class, 'destroySection'])->name('sections.destroy');
        });

        // Messages aux directions de programme
        Route::get('/message-directions', [AdminMessageDirectionController::class, 'index'])
            ->name('message-directions.index');
        Route::get('/message-directions/{messageDirection}', [AdminMessageDirectionController::class, 'edit'])
            ->name('message-directions.edit');
        Route::put('/message-directions/{messageDirection}', [AdminMessageDirectionController::class, 'update'])
            ->name('message-directions.update');
        Route::delete('/message-directions/{messageDirection}', [AdminMessageDirectionController::class, 'destroy'])
            ->name('message-directions.destroy');
        Route::patch('/message-directions/{messageDirection}/toggle', [AdminMessageDirectionController::class, 'toggleVisibility'])
            ->name('message-directions.toggle');

        // PARTENAIRES
        Route::prefix('partenaires')->name('partenaires.')->group(function () {
            Route::get('/', [AdminPartenaireController::class, 'index'])->name('index');
            Route::get('/create', [AdminPartenaireController::class, 'create'])->name('create');
            Route::post('/', [AdminPartenaireController::class, 'store'])->name('store');
            Route::get('/{partenaire}/edit', [AdminPartenaireController::class, 'edit'])->name('edit');
            Route::put('/{partenaire}', [AdminPartenaireController::class, 'update'])->name('update');
            Route::delete('/{partenaire}', [AdminPartenaireController::class, 'destroy'])->name('destroy');
            Route::patch('/{partenaire}/toggle', [AdminPartenaireController::class, 'toggleVisibility'])->name('toggle');
        });

        // Gestion des Événements
        Route::prefix('evenements')->name('evenements.')->group(function () {
            Route::get('/', [AdminEvenementController::class, 'index'])->name('index');
            Route::get('/create', [AdminEvenementController::class, 'create'])->name('create');
            Route::post('/', [AdminEvenementController::class, 'store'])->name('store');
            Route::get('/{evenement}/edit', [AdminEvenementController::class, 'edit'])->name('edit');
            Route::put('/{evenement}', [AdminEvenementController::class, 'update'])->name('update');
            Route::delete('/{evenement}', [AdminEvenementController::class, 'destroy'])->name('destroy');
            Route::patch('/{evenement}/toggle-publication', [AdminEvenementController::class, 'togglePublication'])->name('toggle');
            Route::patch('/{evenement}/archive', [AdminEvenementController::class, 'archiver'])->name('archive');
            Route::patch('/{evenement}/restore', [AdminEvenementController::class, 'restaurer'])->name('restore');
        });

        Route::prefix('annonces')->name('annonces.')->group(function () {
            Route::get('/', [AdminAnnonceController::class, 'index'])->name('index');
            Route::get('/create', [AdminAnnonceController::class, 'create'])->name('create');
            Route::post('/', [AdminAnnonceController::class, 'store'])->name('store');
            Route::get('/{annonce}/edit', [AdminAnnonceController::class, 'edit'])->name('edit');
            Route::put('/{annonce}', [AdminAnnonceController::class, 'update'])->name('update');
            Route::delete('/{annonce}', [AdminAnnonceController::class, 'destroy'])->name('destroy');
            Route::patch('/{annonce}/toggle', [AdminAnnonceController::class, 'togglePublie'])->name('toggle');
            Route::post('/{annonce}/send-email', [AdminAnnonceController::class, 'sendEmailNow'])->name('send-email');
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
