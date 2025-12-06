<?php

namespace App\Livewire\Admin\Newsletter;

use App\Models\NewsletterSubscriber;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Abonnés Newsletter')]
class SubscriberIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterType = 'all';
    public $filterActif = 'all';
    
    // Pour la création/édition
    public $editing = false;
    public $subscriberId = null;
    public $email = '';
    public $nom = '';
    public $type = 'autre';
    public $actif = true;
    public $subscriberCreatedAt = null;

    protected $rules = [
        'email' => 'required|email',
        'nom' => 'nullable|string|max:255',
        'type' => 'required|in:doctorant,encadrant,autre',
        'actif' => 'boolean',
    ];

    protected $messages = [
        'email.required' => 'L\'email est obligatoire.',
        'email.email' => 'L\'email doit être valide.',
        'email.unique' => 'Cet email est déjà inscrit.',
    ];

    public function create()
    {
        $this->reset(['editing', 'subscriberId', 'email', 'nom', 'type', 'actif']);
        $this->editing = true;
        $this->actif = true;
        $this->type = 'autre';
    }

    public function edit($subscriberId)
    {
        $subscriber = NewsletterSubscriber::findOrFail($subscriberId);
        $this->subscriberId = $subscriber->id;
        $this->email = $subscriber->email;
        $this->nom = $subscriber->nom;
        $this->type = $subscriber->type;
        $this->actif = $subscriber->actif;
        $this->subscriberCreatedAt = $subscriber->created_at; // ← AJOUTÉ
        $this->editing = true;
    }

    public function save()
    {
        // Validation avec règle unique conditionnelle
        $rules = $this->rules;
        if ($this->subscriberId) {
            // Lors de la modification, ignorer l'email actuel
            $rules['email'] = 'required|email|unique:newsletter_subscribers,email,' . $this->subscriberId;
        } else {
            // Lors de la création, vérifier l'unicité
            $rules['email'] = 'required|email|unique:newsletter_subscribers,email';
        }

        $this->validate($rules);

        if ($this->subscriberId) {
            // Mise à jour
            $subscriber = NewsletterSubscriber::find($this->subscriberId);
            $subscriber->update([
                'email' => $this->email,
                'nom' => $this->nom,
                'type' => $this->type,
                'actif' => $this->actif,
            ]);
            session()->flash('success', 'Abonné mis à jour avec succès !');
        } else {
            // Création
            NewsletterSubscriber::create([
                'email' => $this->email,
                'nom' => $this->nom,
                'type' => $this->type,
                'actif' => $this->actif,
            ]);
            session()->flash('success', 'Abonné créé avec succès !');
        }

        $this->reset(['editing', 'subscriberId', 'email', 'nom', 'type', 'actif']);
    }

    public function toggleActif($subscriberId)
    {
        $subscriber = NewsletterSubscriber::find($subscriberId);
        if ($subscriber) {
            $subscriber->update(['actif' => !$subscriber->actif]);
            session()->flash('success', 'Statut mis à jour !');
        }
    }

    public function delete($subscriberId)
    {
        NewsletterSubscriber::find($subscriberId)?->delete();
        session()->flash('success', 'Abonné supprimé !');
    }

    public function render()
    {
        $query = NewsletterSubscriber::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('email', 'like', '%' . $this->search . '%')
                  ->orWhere('nom', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterType !== 'all') {
            $query->where('type', $this->filterType);
        }

        if ($this->filterActif === 'actif') {
            $query->where('actif', true);
        } elseif ($this->filterActif === 'inactif') {
            $query->where('actif', false);
        }

        $subscribers = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => NewsletterSubscriber::count(),
            'actifs' => NewsletterSubscriber::where('actif', true)->count(),
            'inactifs' => NewsletterSubscriber::where('actif', false)->count(), 
            'doctorants' => NewsletterSubscriber::where('type', 'doctorant')->count(),
            'encadrants' => NewsletterSubscriber::where('type', 'encadrant')->count(),
        ];

        return view('livewire.admin.newsletter.subscriber-index', [
            'subscribers' => $subscribers,
            'stats' => $stats,
        ]);
    }
}