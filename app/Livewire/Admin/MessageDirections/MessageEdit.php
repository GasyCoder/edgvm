<?php

namespace App\Livewire\Admin\MessageDirections;

use App\Models\MessageDirection;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;

#[Title('Mot de la Directrice')]
class MessageEdit extends Component
{
    use WithFileUploads;

    public MessageDirection $messageDirection;

    // Champs du formulaire
    public string $nom = '';
    public ?string $fonction = null;
    public ?string $institution = null;

    public ?string $telephone = null;
    public ?string $email = null;

    public ?string $citation = null;
    public ?string $message_intro = null;

    public int $nb_doctorants = 0;
    public int $nb_equipes = 0;
    public int $nb_theses = 0;

    public bool $visible = true;

    // Upload temporaire pour la photo
    public $photo;

    /**
     * Livewire va injecter automatiquement le MessageDirection
     * à partir du paramètre {messageDirection} de la route.
     */
    public function mount(MessageDirection $messageDirection): void
    {
        $this->messageDirection = $messageDirection;

        // On remplit les propriétés Livewire
        $this->nom = $messageDirection->nom;
        $this->fonction = $messageDirection->fonction;
        $this->institution = $messageDirection->institution;
        $this->telephone = $messageDirection->telephone;
        $this->email = $messageDirection->email;
        $this->citation = $messageDirection->citation;
        $this->message_intro = $messageDirection->message_intro;
        $this->nb_doctorants = $messageDirection->nb_doctorants;
        $this->nb_equipes = $messageDirection->nb_equipes;
        $this->nb_theses = $messageDirection->nb_theses;
        $this->visible = (bool) $messageDirection->visible;
    }

    protected function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'fonction' => ['nullable', 'string', 'max:255'],
            'institution' => ['nullable', 'string', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'citation' => ['nullable', 'string'],
            'message_intro' => ['nullable', 'string'],
            'nb_doctorants' => ['required', 'integer', 'min:0'],
            'nb_equipes' => ['required', 'integer', 'min:0'],
            'nb_theses' => ['required', 'integer', 'min:0'],
            'visible' => ['boolean'],
            'photo' => ['nullable', 'image', 'max:2048'], // 2 Mo
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'nom' => $this->nom,
            'fonction' => $this->fonction,
            'institution' => $this->institution,
            'telephone' => $this->telephone,
            'email' => $this->email,
            'citation' => $this->citation,
            'message_intro' => $this->message_intro,
            'nb_doctorants' => $this->nb_doctorants,
            'nb_equipes' => $this->nb_equipes,
            'nb_theses' => $this->nb_theses,
            'visible' => $this->visible,
        ];

        if ($this->photo) {
            // Supprimer l’ancienne photo si elle existe
            if ($this->messageDirection->photo_path) {
                Storage::disk('public')->delete($this->messageDirection->photo_path);
            }

            $path = $this->photo->store('message-direction', 'public');
            $data['photo_path'] = $path;
        }

        $this->messageDirection->update($data);

        // Redirection vers l’index + message flash
        redirect()
            ->route('admin.message-directions.index')
            ->with('success', 'Mot de la Directrice mis à jour avec succès.');
    }


    public function render()
    {
        return view('livewire.admin.message-directions.message-edit');
    }
}
