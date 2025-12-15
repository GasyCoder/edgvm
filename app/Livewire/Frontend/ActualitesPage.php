<?php

namespace App\Livewire\Frontend;

use App\Models\Actualite;
use App\Models\Category;
use App\Models\Tag;
use App\Models\NewsletterSubscriber;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class ActualitesPage extends Component
{
    use WithPagination;

    #[Url]
    public $category = '';
    
    #[Url]
    public $tag = '';
    
    #[Url]
    public $search = '';
    
    #[Url]
    public $view = 'grid';

    public $newsletterEmail = '';
    public $newsletterNom = '';
    public $newsletterType = 'autre';

    public function filterByCategory($categorySlug)
    {
        $this->category = $categorySlug;
        $this->resetPage();
    }

    public function filterByTag($tagSlug)
    {
        $this->tag = $tagSlug;
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['category', 'tag', 'search']);
        $this->resetPage();
    }

    public function setView($view)
    {
        $this->view = $view;
    }

    public function subscribe()
    {
        $this->validate([
            'newsletterEmail' => 'required|email|unique:newsletter_subscribers,email',
            'newsletterNom' => 'nullable|string|max:255',
            'newsletterType' => 'required|in:doctorant,encadrant,autre',
        ], [
            'newsletterEmail.required' => 'L\'email est obligatoire.',
            'newsletterEmail.email' => 'L\'email doit être valide.',
            'newsletterEmail.unique' => 'Cet email est déjà inscrit.',
        ]);

        NewsletterSubscriber::create([
            'email' => $this->newsletterEmail,
            'nom' => $this->newsletterNom,
            'type' => $this->newsletterType,
            'actif' => true,
        ]);

        session()->flash('newsletter_success', '✅ Merci ! Vous êtes maintenant abonné(e) à notre newsletter.');
        
        $this->reset(['newsletterEmail', 'newsletterNom', 'newsletterType']);
    }

    public function calculateReadingTime($content)
    {
        $wordCount = str_word_count(strip_tags($content));
        $minutes = ceil($wordCount / 200);
        return $minutes;
    }

    public function render()
    {
        // ✅ AJOUTE LE FILTRE SLUG ICI
        $query = Actualite::with(['category', 'image', 'tags', 'auteur'])
            ->whereNotNull('slug')  // ← AJOUTE
            ->where('slug', '!=', '')  // ← AJOUTE
            ->published();

        // Filtre par catégorie
        if ($this->category) {
            $query->whereHas('category', function($q) {
                $q->where('slug', $this->category);
            });
        }

        // Filtre par tag
        if ($this->tag) {
            $query->whereHas('tags', function($q) {
                $q->where('slug', $this->tag);
            });
        }

        // Recherche
        if ($this->search) {
            $query->where(function($q) {
                $q->where('titre', 'like', '%' . $this->search . '%')
                  ->orWhere('contenu', 'like', '%' . $this->search . '%');
            });
        }

        $actualites = $query->orderBy('est_important', 'desc')
            ->orderBy('date_publication', 'desc')
            ->paginate($this->view === 'list' ? 10 : 9);

        // ✅ AJOUTE LE FILTRE SLUG POUR ACTUALITÉS IMPORTANTES
        $actuImportantes = Actualite::with(['category', 'image'])
            ->whereNotNull('slug')  // ← AJOUTE
            ->where('slug', '!=', '')  // ← AJOUTE
            ->published()
            ->where('est_important', true)
            ->limit(3)
            ->get();

        $categories = Category::visible()
            ->withCount(['actualites' => function($q) {
                $q->whereNotNull('slug')  // ← AJOUTE
                  ->where('slug', '!=', '')  // ← AJOUTE
                  ->published();
            }])
            ->having('actualites_count', '>', 0)
            ->orderBy('nom')
            ->get();

        $tags = Tag::withCount(['actualites' => function($q) {
                $q->whereNotNull('slug')  // ← AJOUTE
                  ->where('slug', '!=', '')  // ← AJOUTE
                  ->published();
            }])
            ->having('actualites_count', '>', 0)
            ->orderBy('nom')
            ->get();

        // ✅ AJOUTE LE FILTRE SLUG POUR PLUS LUS
        $plusLus = Actualite::with(['category', 'image'])
            ->whereNotNull('slug')  // ← AJOUTE
            ->where('slug', '!=', '')  // ← AJOUTE
            ->published()
            ->orderBy('vues', 'desc')
            ->limit(5)
            ->get();

        return view('livewire.frontend.actualites-page', [
            'actualites' => $actualites,
            'categories' => $categories,
            'tags' => $tags,
            'plusLus' => $plusLus,
        ])
        ->layout('layouts.frontend')
        ->title('Actualités - EDGVM');
    }
}