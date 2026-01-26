<?php

namespace App\Livewire\Frontend;

use App\Models\Actualite;
use App\Models\NewsletterSubscriber;
use Livewire\Component;

class ActualiteDetailPage extends Component
{
    public Actualite $actualite;

    public $newsletterEmail = '';

    public $newsletterNom = '';

    public $newsletterType = 'autre';

    public function mount(Actualite $actualite)
    {
        if (! $actualite->visible || $actualite->date_publication > now()) {
            abort(404);
        }

        $this->actualite = $actualite;
        $this->incrementUniqueView();
    }

    private function incrementUniqueView(): void
    {
        $key = 'actualite_viewed_'.$this->actualite->id;

        if (! session()->has($key)) {
            $this->actualite->incrementVues();
            session()->put($key, true);
        }
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

    public function render()
    {
        $this->actualite->load(['category', 'image', 'tags', 'auteur', 'galerie']);

        $similaires = Actualite::with(['category', 'image'])
            ->withSlug()
            ->published()
            ->where('id', '!=', $this->actualite->id)
            ->where('category_id', $this->actualite->category_id)
            ->limit(3)
            ->get();

        if ($similaires->count() < 3) {
            $similaires = Actualite::with(['category', 'image'])
                ->withSlug()
                ->published()
                ->where('id', '!=', $this->actualite->id)
                ->latest('date_publication')
                ->limit(3)
                ->get();
        }

        $precedent = Actualite::withSlug()
            ->published()
            ->where('date_publication', '<', $this->actualite->date_publication)
            ->orderBy('date_publication', 'desc')
            ->first();

        $suivant = Actualite::withSlug()
            ->published()
            ->where('date_publication', '>', $this->actualite->date_publication)
            ->orderBy('date_publication', 'asc')
            ->first();

        return view('livewire.frontend.actualite-detail-page', [
            'similaires' => $similaires,
            'precedent' => $precedent,
            'suivant' => $suivant,
        ])
            ->layout('layouts.frontend')
            ->title($this->actualite->titre.' - EDGVM');
    }
}
