<?php

namespace App\Livewire\Admin\Annonces;

use App\Jobs\SendAnnonceEmailJob;
use App\Models\Annonce;
use Livewire\Component;
use Livewire\WithPagination;

class AnnonceIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterCible = '';
    public string $filterPublie = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterCible' => ['except' => ''],
        'filterPublie' => ['except' => ''],
    ];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterCible() { $this->resetPage(); }
    public function updatingFilterPublie() { $this->resetPage(); }

    public function togglePublie(int $id): void
    {
        $annonce = Annonce::findOrFail($id);

        $annonce->est_publie = ! $annonce->est_publie;
        $annonce->publie_at = $annonce->est_publie ? now() : null;
        $annonce->save();

        session()->flash('success', 'Statut de publication mis à jour.');
    }

    public function sendEmailNow(int $id): void
    {
        $annonce = Annonce::findOrFail($id);

        if (empty($annonce->email_cible)) {
            session()->flash('error', 'Email cible non défini (doctorant / encadrant / les deux).');
            return;
        }

        SendAnnonceEmailJob::dispatch($annonce->id);

        session()->flash('success', 'Envoi email déclenché (queue).');
    }

    public function render()
    {
        $q = Annonce::query()->latest();

        if ($this->search !== '') {
            $q->where(function ($qq) {
                $qq->where('titre', 'like', "%{$this->search}%");
            });
        }

        if ($this->filterCible !== '') {
            $q->where('cible', $this->filterCible);
        }

        if ($this->filterPublie !== '') {
            $q->where('est_publie', $this->filterPublie === '1');
        }

        return view('livewire.admin.annonces.annonce-index', [
            'annonces' => $q->paginate(12),
        ]);
    }
}
