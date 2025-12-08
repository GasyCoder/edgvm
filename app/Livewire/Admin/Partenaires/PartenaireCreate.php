<?php

namespace App\Livewire\Admin\Partenaires;

use App\Models\Partenaire;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;

#[Title('Nouveau partenaire')]
class PartenaireCreate extends Component
{
    use WithFileUploads;

    public string $nom = '';
    public ?string $description = null;
    public ?string $url = null;
    public int $ordre = 1;
    public bool $visible = true;

    public $logo; // fichier uploadé

    protected function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'url' => ['nullable', 'url', 'max:255'],
            'ordre' => ['required', 'integer', 'min:1'],
            'visible' => ['boolean'],
            'logo' => ['nullable', 'image', 'max:2048'], // 2 Mo
        ];
    }

    public function mount(): void
    {
        $maxOrdre = Partenaire::max('ordre');
        $this->ordre = $maxOrdre ? $maxOrdre + 1 : 1;
    }

    public function save()
    {
        $this->validate();

        $logoPath = null;

        if ($this->logo) {
            // Stockage dans storage/app/public/partenaires
            $logoPath = $this->logo->store('partenaires', 'public');
        }

        Partenaire::create([
            'nom'        => $this->nom,
            'description'=> $this->description,
            'url'        => $this->url,
            'ordre'      => $this->ordre,
            'visible'    => $this->visible,
            'logo_path'  => $logoPath,
        ]);

        return redirect()
            ->route('admin.partenaires.index')
            ->with('success', 'Partenaire créé avec succès.');
    }

    public function render()
    {
        return view('livewire.admin.partenaires.partenaire-create');
    }
}
