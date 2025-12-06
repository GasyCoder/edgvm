<?php

namespace App\Livewire\Frontend;

use App\Models\Actualite;
use App\Models\NewsletterSubscriber;
use Livewire\Component;

class ActualiteDetailPage extends Component
{
    public Actualite $actualite;
    
    // Newsletter
    public $newsletterEmail = '';
    public $newsletterNom = '';
    public $newsletterType = 'autre';

    public function mount(Actualite $actualite)
    {
        if (!$actualite->visible || $actualite->date_publication > now()) {
            abort(404);
        }

        $this->actualite = $actualite;
        
        // Incrémenter les vues ← AJOUTÉ
        $this->incrementUniqueView();
    }

    /**
     * Incrémente les vues une seule fois par session navigateur
     */
    private function incrementUniqueView(): void
    {
        // Clé unique pour cette actu dans la session
        $key = 'actualite_viewed_' . $this->actualite->id;

        // Si pas encore vue dans cette session → on incrémente
        if (!session()->has($key)) {
            // Utilise ton helper de modèle
            $this->actualite->incrementVues();

            // On mémorise dans la session que cette actu a été vue
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
        // Charger les relations + galerie ← AJOUTÉ
        $this->actualite->load(['category', 'image', 'tags', 'auteur', 'galerie']);

        $similaires = Actualite::with(['category', 'image'])
            ->published()
            ->where('id', '!=', $this->actualite->id)
            ->where('category_id', $this->actualite->category_id)
            ->limit(3)
            ->get();

        if ($similaires->count() < 3) {
            $similaires = Actualite::with(['category', 'image'])
                ->published()
                ->where('id', '!=', $this->actualite->id)
                ->latest('date_publication')
                ->limit(3)
                ->get();
        }

        // Articles précédent/suivant
        $precedent = Actualite::published()
            ->where('date_publication', '<', $this->actualite->date_publication)
            ->orderBy('date_publication', 'desc')
            ->first();

        $suivant = Actualite::published()
            ->where('date_publication', '>', $this->actualite->date_publication)
            ->orderBy('date_publication', 'asc')
            ->first();

        return view('livewire.frontend.actualite-detail-page', [
            'similaires' => $similaires,
            'precedent' => $precedent,
            'suivant' => $suivant,
        ])
        ->layout('layouts.frontend')
        ->title($this->actualite->titre . ' - EDGVM');
    }
}