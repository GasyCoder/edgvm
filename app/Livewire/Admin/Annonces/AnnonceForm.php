<?php

namespace App\Livewire\Admin\Annonces;

use App\Jobs\SendAnnonceEmailJob;
use App\Models\Annonce;
use App\Models\Media;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AnnonceForm extends Component
{
    public ?Annonce $annonce = null;

    public string $titre = '';
    public string $contenu_html = '';

    public string $cible = 'both';
    public ?int $media_id = null;

    public bool $est_publie = false;

    public bool $envoyer_email = false;
    public ?string $email_cible = 'both';

    public function mount(?Annonce $annonce = null): void
    {
        if ($annonce && $annonce->exists) {
            $this->annonce = $annonce;

            $this->titre        = $annonce->titre;
            $this->contenu_html = $annonce->contenu_html ?? '';
            $this->cible        = $annonce->cible;
            $this->media_id     = $annonce->media_id;
            $this->est_publie   = (bool) $annonce->est_publie;

            $this->envoyer_email = (bool) $annonce->envoyer_email;
            $this->email_cible   = $annonce->email_cible ?? 'both';

            $this->dispatch('annonce-editor-refresh');
        }
    }

    protected function rules(): array
    {
        return [
            'titre' => ['required', 'string', 'max:190'],
            'contenu_html' => ['nullable', 'string'],
            'cible' => ['required', 'in:doctorant,encadrant,both'],
            'media_id' => ['nullable', 'integer', 'exists:media,id'],
            'est_publie' => ['boolean'],

            'envoyer_email' => ['boolean'],
            'email_cible' => ['nullable', 'in:doctorant,encadrant,both'],
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        $data['auteur_id'] = Auth::id();

        // gérer publie_at
        if ($data['est_publie']) {
            $data['publie_at'] = $this->annonce?->publie_at ?? now();
        } else {
            $data['publie_at'] = null;
        }

        // cohérence email
        if (!$data['envoyer_email']) {
            $data['email_cible'] = null;
        } elseif (empty($data['email_cible'])) {
            $data['email_cible'] = 'both';
        }

        $annonce = $this->annonce
            ? tap($this->annonce)->update($data)
            : Annonce::create($data);

        // Email (option)
        if ($data['envoyer_email']) {
            SendAnnonceEmailJob::dispatch($annonce->id);
        }

        session()->flash('success', $this->annonce ? 'Annonce mise à jour.' : 'Annonce créée.');

        redirect()->route('admin.annonces.index');
    }

    public function render()
    {
        return view('livewire.admin.annonces.annonce-form', [
            'medias' => Media::query()->latest()->limit(80)->get(),
        ]);
    }
}
