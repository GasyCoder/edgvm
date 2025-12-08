<?php

namespace App\Livewire\Admin\Partenaires;

use App\Models\Partenaire;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Title('Modifier un partenaire')]
class PartenaireEdit extends Component
{
    use WithFileUploads;

    public Partenaire $partenaire;

    public string $nom = '';
    public ?string $description = null;
    public ?string $url = null;
    public int $ordre = 1;
    public bool $visible = true;

    public $logo; // nouveau fichier uploadé (optionnel)

    protected function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'url' => ['nullable', 'url', 'max:255'],
            'ordre' => ['required', 'integer', 'min:1'],
            'visible' => ['boolean'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function mount(Partenaire $partenaire): void
    {
        $this->partenaire   = $partenaire;
        $this->nom          = $partenaire->nom;
        $this->description  = $partenaire->description;
        $this->url          = $partenaire->url;
        $this->ordre        = $partenaire->ordre;
        $this->visible      = (bool) $partenaire->visible;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'nom'        => $this->nom,
            'description'=> $this->description,
            'url'        => $this->url,
            'ordre'      => $this->ordre,
            'visible'    => $this->visible,
        ];

        if ($this->logo) {
            // Supprimer l’ancien logo si présent
            if ($this->partenaire->logo_path) {
                Storage::disk('public')->delete($this->partenaire->logo_path);
            }

            $path = $this->logo->store('partenaires', 'public');
            $data['logo_path'] = $path;
        }

        $this->partenaire->update($data);

        return redirect()
            ->route('admin.partenaires.index')
            ->with('success', 'Partenaire mis à jour avec succès.');
    }

    public function render()
    {
        return view('livewire.admin.partenaires.partenaire-edit');
    }
}
