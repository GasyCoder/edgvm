<?php

use App\Models\Actualite;
use App\Models\Category;
use App\Models\Doctorant;
use App\Models\EAD;
use App\Models\Evenement;
use App\Models\Page;
use App\Models\Specialite;
use App\Models\Tag;
use App\Models\These;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    $this->ead = EAD::query()->create([
        'nom' => 'EAD Sciences',
        'slug' => 'ead-sciences',
        'description' => 'EAD test description',
        'domaine' => 'Sciences',
    ]);

    $this->specialite = Specialite::query()->create([
        'nom' => 'Programme Data',
        'slug' => 'programme-data',
        'description' => 'Programme description',
        'ead_id' => $this->ead->id,
    ]);

    $this->doctorant = Doctorant::query()->create([
        'user_id' => $this->user->id,
        'matricule' => 'DOC-001',
        'niveau' => 'D1',
        'date_inscription' => now()->subYear()->toDateString(),
        'statut' => 'actif',
    ]);

    $this->these = These::query()->create([
        'doctorant_id' => $this->doctorant->id,
        'specialite_id' => $this->specialite->id,
        'ead_id' => $this->ead->id,
        'sujet_these' => 'Sujet de test',
        'description' => 'Description de test',
        'resume_these' => 'Resume de test',
        'mots_cles' => 'test,these',
        'date_debut' => now()->subMonths(6)->toDateString(),
        'date_publication' => now()->subMonth()->toDateString(),
        'statut' => 'en_cours',
    ]);

    $this->category = Category::query()->create([
        'nom' => 'Actualites',
        'description' => 'Categorie test',
        'couleur' => '#123456',
        'visible' => true,
    ]);

    $this->tag = Tag::query()->create([
        'nom' => 'Recherche',
    ]);

    $this->actualite = Actualite::query()->create([
        'titre' => 'Actualite test',
        'contenu' => '<p>Contenu test.</p>',
        'category_id' => $this->category->id,
        'date_publication' => now()->subDay()->toDateString(),
        'visible' => true,
        'est_important' => true,
    ]);
    $this->actualite->tags()->attach($this->tag->id);

    $this->evenement = Evenement::query()->create([
        'titre' => 'Evenement test',
        'description' => 'Description evenement',
        'date_debut' => now()->addDay()->toDateString(),
        'heure_debut' => '09:00:00',
        'lieu' => 'Salle A',
        'type' => 'conference',
        'est_publie' => true,
        'est_archive' => false,
    ]);

    $this->page = Page::query()->create([
        'titre' => 'A propos',
        'slug' => 'a-propos',
        'contenu' => '<p>Page test.</p>',
        'visible' => true,
        'ordre' => 1,
    ]);
});

it('renders frontend index pages', function (): void {
    $this->get(route('home'))->assertSuccessful();
    $this->get(route('actualites.index'))->assertSuccessful();
    $this->get(route('evenements.index'))->assertSuccessful();
    $this->get(route('programmes.index'))->assertSuccessful();
    $this->get(route('ead.index'))->assertSuccessful();
    $this->get(route('doctorants.index'))->assertSuccessful();
    $this->get(route('theses.index'))->assertSuccessful();
    $this->get(route('contact'))->assertSuccessful();
});

it('renders frontend detail pages', function (): void {
    $this->get(route('actualites.show', $this->actualite))->assertSuccessful();
    $this->get(route('evenements.show', $this->evenement))->assertSuccessful();
    $this->get(route('pages.show', $this->page->slug))->assertSuccessful();
    $this->get(route('programmes.show', $this->specialite))->assertSuccessful();
    $this->get(route('ead.show', $this->ead))->assertSuccessful();
    $this->get(route('doctorants.show', $this->doctorant))->assertSuccessful();
    $this->get(route('theses.show', $this->these))->assertSuccessful();
});
