<?php

use App\Models\Actualite;
use App\Models\Category;
use App\Models\EAD;
use App\Models\Encadrant;
use App\Models\Evenement;
use App\Models\Jury;
use App\Models\Media;
use App\Models\MessageDirection;
use App\Models\Page;
use App\Models\Slide;
use App\Models\Slider;
use App\Models\Specialite;
use App\Models\Tag;
use App\Models\These;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

it('renders the admin dashboard for admin users', function (): void {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertSuccessful();
});

it('renders a migrated admin placeholder page', function (): void {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.actualites.index'))
        ->assertSuccessful();
});

it('renders admin media and taxonomy pages', function (): void {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.media.index'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page->component('Admin/Media/Index'));

    $this->actingAs($admin)
        ->get(route('admin.media.upload'))
        ->assertSuccessful();

    $this->actingAs($admin)
        ->get(route('admin.categories.index'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page->component('Admin/Categories/Index'));

    $this->actingAs($admin)
        ->get(route('admin.tags.index'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page->component('Admin/Tags/Index'));
});

it('renders actualite create and edit pages', function (): void {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $category = Category::query()->create([
        'nom' => 'Actualites',
        'description' => 'Categorie test',
        'couleur' => '#123456',
        'visible' => true,
    ]);

    Tag::query()->create([
        'nom' => 'Recherche',
    ]);

    $actualite = Actualite::query()->create([
        'titre' => 'Actualite test',
        'contenu' => '<p>Contenu test.</p>',
        'category_id' => $category->id,
        'date_publication' => now()->subDay()->toDateString(),
        'visible' => true,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.actualites.create'))
        ->assertSuccessful();

    $this->actingAs($admin)
        ->get(route('admin.actualites.edit', $actualite))
        ->assertSuccessful();
});

it('renders evenements pages', function (): void {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $evenement = Evenement::query()->create([
        'titre' => 'Evenement test',
        'date_debut' => now()->addDay()->toDateString(),
        'type' => 'seminaire',
        'est_publie' => true,
        'est_archive' => false,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.evenements.index'))
        ->assertSuccessful();

    $this->actingAs($admin)
        ->get(route('admin.evenements.create'))
        ->assertSuccessful();

    $this->actingAs($admin)
        ->get(route('admin.evenements.edit', $evenement))
        ->assertSuccessful();
});

it('renders pages admin screens', function (): void {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $page = Page::query()->create([
        'titre' => 'Page test',
        'slug' => 'page-test',
        'contenu' => '<p>Contenu test</p>',
        'auteur_id' => $admin->id,
        'visible' => true,
        'ordre' => 0,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.pages.index'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page->component('Admin/Pages/Index'));

    $this->actingAs($admin)
        ->get(route('admin.pages.create'))
        ->assertSuccessful();

    $this->actingAs($admin)
        ->get(route('admin.pages.edit', $page))
        ->assertSuccessful();
});

it('renders sliders and slides screens', function (): void {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $slider = Slider::query()->create([
        'nom' => 'Homepage',
        'position' => 'homepage',
        'visible' => true,
    ]);

    $media = Media::query()->create([
        'nom_original' => 'slide.jpg',
        'nom_fichier' => 'slide.jpg',
        'chemin' => 'slides/slide.jpg',
        'type' => 'image',
        'taille_bytes' => 1024,
        'mime_type' => 'image/jpeg',
        'uploader_id' => $admin->id,
    ]);

    $slide = Slide::query()->create([
        'slider_id' => $slider->id,
        'titre_highlight' => 'Slide test',
        'image_id' => $media->id,
        'ordre' => 1,
        'visible' => true,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.sliders.index'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page->component('Admin/Sliders/Index'));

    $this->actingAs($admin)
        ->get(route('admin.sliders.create'))
        ->assertSuccessful();

    $this->actingAs($admin)
        ->get(route('admin.sliders.edit', $slider))
        ->assertSuccessful();

    $this->actingAs($admin)
        ->get(route('admin.slides.index', $slider))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page->component('Admin/Slides/Index'));

    $this->actingAs($admin)
        ->get(route('admin.slides.create', $slider))
        ->assertSuccessful();

    $this->actingAs($admin)
        ->get(route('admin.slides.edit', ['slider' => $slider, 'slide' => $slide]))
        ->assertSuccessful();
});

it('renders annonces, newsletter, partenaires, settings, and message directions', function (): void {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $annonce = \App\Models\Annonce::query()->create([
        'titre' => 'Annonce test',
        'contenu_html' => '<p>Test</p>',
        'cible' => 'both',
        'est_publie' => false,
        'auteur_id' => $admin->id,
    ]);

    $subscriber = \App\Models\NewsletterSubscriber::query()->create([
        'email' => 'test@example.com',
        'type' => 'autre',
        'actif' => true,
    ]);

    $partenaire = \App\Models\Partenaire::query()->create([
        'nom' => 'Partenaire test',
        'ordre' => 1,
        'visible' => true,
    ]);

    $message = MessageDirection::query()->create([
        'nom' => 'Directeur',
        'visible' => true,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.annonces.index'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page->component('Admin/Annonces/Index'));

    $this->actingAs($admin)
        ->get(route('admin.annonces.create'))
        ->assertSuccessful();

    $this->actingAs($admin)
        ->get(route('admin.annonces.edit', $annonce))
        ->assertSuccessful();

    $this->actingAs($admin)
        ->get(route('admin.newsletter.subscribers'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page->component('Admin/Newsletter/Index'));

    $this->actingAs($admin)
        ->get(route('admin.partenaires.index'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page->component('Admin/Partenaires/Index'));

    $this->actingAs($admin)
        ->get(route('admin.partenaires.create'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page->component('Admin/Partenaires/Create'));

    $this->actingAs($admin)
        ->get(route('admin.partenaires.edit', $partenaire))
        ->assertSuccessful();

    $this->actingAs($admin)
        ->get(route('admin.settings'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page->component('Admin/Settings/Index'));

    $this->actingAs($admin)
        ->get(route('admin.message-directions.index'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page->component('Admin/MessageDirections/Index'));

    $this->actingAs($admin)
        ->get(route('admin.message-directions.edit', $message))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page->component('Admin/MessageDirections/Edit'));
});

it('renders research admin pages', function (): void {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
        'active' => true,
    ]);

    $encadrant = Encadrant::query()->create([
        'nom' => 'Rakoto',
        'prenoms' => 'Aina',
        'email' => 'encadrant-dashboard@example.test',
        'genre' => 'homme',
        'grade' => 'Professeur',
    ]);

    $ead = EAD::query()->create([
        'nom' => 'EAD Test',
        'slug' => 'ead-test',
        'responsable_id' => $encadrant->id,
        'domaine' => 'Sciences',
    ]);

    $specialite = Specialite::query()->create([
        'nom' => 'Specialite Test',
        'slug' => 'specialite-test',
        'ead_id' => $ead->id,
    ]);

    $doctorant = \App\Models\Doctorant::query()->create([
        'matricule' => 'DOC-001',
        'niveau' => 'D1',
        'date_inscription' => now()->toDateString(),
        'statut' => 'actif',
    ]);

    $these = These::query()->create([
        'doctorant_id' => $doctorant->id,
        'sujet_these' => 'These test',
        'specialite_id' => $specialite->id,
        'ead_id' => $ead->id,
        'statut' => 'en_cours',
    ]);

    $jury = Jury::query()->create([
        'nom' => 'Jury Test',
        'grade' => 'Dr',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.ead.index'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page->component('Admin/Eads/Index'));

    $this->actingAs($admin)
        ->get(route('admin.ead.create'))
        ->assertSuccessful();

    $this->actingAs($admin)
        ->get(route('admin.ead.edit', $ead))
        ->assertSuccessful();

    $this->actingAs($admin)
        ->get(route('admin.specialites.index'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page->component('Admin/Specialites/Index'));

    $this->actingAs($admin)
        ->get(route('admin.specialites.create'))
        ->assertSuccessful();

    $this->actingAs($admin)
        ->get(route('admin.specialites.edit', $specialite))
        ->assertSuccessful();

    $this->actingAs($admin)
        ->get(route('admin.doctorants.index'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page->component('Admin/Doctorants/Index'));

    $this->actingAs($admin)
        ->get(route('admin.doctorants.create'))
        ->assertSuccessful();

    $this->actingAs($admin)
        ->get(route('admin.doctorants.show', $doctorant))
        ->assertSuccessful();

    $this->actingAs($admin)
        ->get(route('admin.doctorants.edit', $doctorant))
        ->assertSuccessful();

    $this->actingAs($admin)
        ->get(route('admin.encadrants.index'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page->component('Admin/Encadrants/Index'));

    $this->actingAs($admin)
        ->get(route('admin.encadrants.create'))
        ->assertSuccessful();

    $this->actingAs($admin)
        ->get(route('admin.encadrants.show', $encadrant))
        ->assertSuccessful();

    $this->actingAs($admin)
        ->get(route('admin.encadrants.edit', $encadrant))
        ->assertSuccessful();

    $this->actingAs($admin)
        ->get(route('admin.theses.index'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page->component('Admin/Theses/Index'));

    $this->actingAs($admin)
        ->get(route('admin.theses.create'))
        ->assertSuccessful();

    $this->actingAs($admin)
        ->get(route('admin.theses.show', $these))
        ->assertSuccessful();

    $this->actingAs($admin)
        ->get(route('admin.theses.edit', $these))
        ->assertSuccessful();

    $this->actingAs($admin)
        ->get(route('admin.theses.jury.edit', $these))
        ->assertSuccessful();

    $this->actingAs($admin)
        ->get(route('admin.jurys.index'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page->component('Admin/Jurys/Index'));

    $this->actingAs($admin)
        ->get(route('admin.jurys.create'))
        ->assertSuccessful();

    $this->actingAs($admin)
        ->get(route('admin.jurys.edit', $jury))
        ->assertSuccessful();
});
