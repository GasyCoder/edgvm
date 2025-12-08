<?php

namespace App\Livewire\Admin\Pages;

use App\Models\Page;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PageCreate extends Component
{
    public $titre = '';
    public $slug = '';
    public $contenu = '';
    public $visible = true;
    public $ordre = 0;

    protected function rules()
    {
        return [
            'titre' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug',
            'contenu' => 'nullable|string',
            'visible' => 'boolean',
            'ordre' => 'integer|min:0',
        ];
    }

    protected $messages = [
        'titre.required' => 'Le titre est obligatoire.',
        'slug.required' => 'Le slug est obligatoire.',
        'slug.unique' => 'Ce slug existe déjà.',
    ];

    public function updatedTitre($value)
    {
        $this->slug = Str::slug($value);
    }

    public function save()
    {
        $this->validate();

        Page::create([
            'titre' => $this->titre,
            'slug' => $this->slug,
            'contenu' => $this->contenu,
            'auteur_id' => Auth::id(),
            'visible' => $this->visible,
            'ordre' => $this->ordre,
        ]);

        session()->flash('success', 'Page créée avec succès.');

        return redirect()->route('admin.pages.index');
    }

    public function render()
    {
        return view('livewire.admin.pages.page-create');
    }
}