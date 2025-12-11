<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Evenement;

class EvenementsPage extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public string $typeFilter = '';
    public int $perPage = 12;

    // Newsletter
    public string $newsletterEmail = '';
    public ?string $newsletterNom = null;

    protected $queryString = [
        'typeFilter' => ['except' => ''],
        'page'       => ['except' => 1],
    ];

    protected $rules = [
        'newsletterEmail' => 'required|email',
        'newsletterNom'   => 'nullable|string|max:255',
    ];

    public function updatingTypeFilter(): void
    {
        $this->resetPage();
    }

    public function subscribe(): void
    {
        $this->validate();

        // Ici tu pourras brancher ton vrai système de newsletter
        // (modèle NewsletterSubscriber, job, etc.)
        // Pour l’instant on fait simple : message de succès.

        $this->reset('newsletterEmail', 'newsletterNom');

        session()->flash(
            'newsletter_success',
            "Merci, votre inscription à la newsletter EDGVM est bien enregistrée."
        );
    }

    public function render()
    {
        // 🔹 Requête simple pour être sûr de VOIR quelque chose.
        // Quand tout est OK, tu pourras remettre des conditions (futurs, publiés, etc.)
        $query = Evenement::query()
            ->orderBy('date_debut', 'asc');

        if ($this->typeFilter !== '') {
            $query->where('type', $this->typeFilter);
        }

        $evenements = $query->paginate($this->perPage);

        return view('livewire.frontend.evenements-page', [
            'evenements'   => $evenements,
            'typeFilter'   => $this->typeFilter,
        ])->layout('layouts.frontend');
    }
}
