<?php

namespace App\Livewire\Frontend;

use App\Models\Actualite;
use App\Models\Category;
use App\Models\NewsletterSubscriber;
use App\Models\Tag;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

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
        $query = Actualite::with(['category', 'image', 'tags', 'auteur'])
            ->withSlug()
            ->published();

        if ($this->category) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $this->category));
        }

        if ($this->tag) {
            $query->whereHas('tags', fn ($q) => $q->where('slug', $this->tag));
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('titre', 'like', '%'.$this->search.'%')
                    ->orWhere('contenu', 'like', '%'.$this->search.'%');
            });
        }

        $actualites = $query->orderBy('est_important', 'desc')
            ->orderBy('date_publication', 'desc')
            ->paginate($this->view === 'list' ? 10 : 9);

        $categories = Category::visible()
            ->withCount(['actualites' => fn ($q) => $q->withSlug()->published()])
            ->having('actualites_count', '>', 0)
            ->orderBy('nom')
            ->get();

        $tags = Tag::withCount(['actualites' => fn ($q) => $q->withSlug()->published()])
            ->having('actualites_count', '>', 0)
            ->orderBy('nom')
            ->get();

        $plusLus = Actualite::with(['category', 'image'])
            ->withSlug()
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
