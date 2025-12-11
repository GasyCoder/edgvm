<?php

namespace App\Livewire\Admin\Pages;

use App\Models\Page;
use App\Models\Menu;
use App\Models\MenuItem;
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

    // 🔹 Nouveaux champs pour le menu
    public $attachToMenu = false;
    public $menuId = null;
    public $menuLabel = '';

    protected function rules()
    {
        return [
            'titre'        => 'required|string|max:255',
            'slug'         => 'required|string|max:255|unique:pages,slug',
            'contenu'      => 'nullable|string',
            'visible'      => 'boolean',
            'ordre'        => 'integer|min:0',

            // Règles menu
            'attachToMenu' => 'boolean',
            'menuId'       => 'nullable|required_if:attachToMenu,true|exists:menus,id',
            'menuLabel'    => 'nullable|string|max:255',
        ];
    }

    protected $messages = [
        'titre.required'   => 'Le titre est obligatoire.',
        'slug.required'    => 'Le slug est obligatoire.',
        'slug.unique'      => 'Ce slug existe déjà.',
        'menuId.required_if' => 'Veuillez choisir un menu.',
        'menuId.exists'      => 'Menu invalide.',
    ];

    public function mount()
    {
        // Option : pré-cocher et pré-sélectionner le menu "À propos" si il existe
        $menuAPropos = Menu::where('slug', 'a-propos')->first();

        if ($menuAPropos) {
            $this->attachToMenu = true;
            $this->menuId = $menuAPropos->id;
        }
    }

    public function updatedTitre($value)
    {
        if (empty($this->slug)) {
            $this->slug = Str::slug($value);
        }
    }

    public function save()
    {
        $this->validate();

        // 1. Créer la page
        $page = Page::create([
            'titre'     => $this->titre,
            'slug'      => $this->slug,
            'contenu'   => $this->contenu,
            'auteur_id' => Auth::id(),
            'visible'   => $this->visible,
            'ordre'     => $this->ordre,
        ]);

        // 2. Si on a demandé d’attacher au menu, créer un MenuItem
        if ($this->attachToMenu && $this->menuId) {
            // Calcul du prochain ordre dans ce menu (niveau racine)
            $nextOrdre = (MenuItem::where('menu_id', $this->menuId)
                    ->whereNull('parent_id')
                    ->max('ordre') ?? 0) + 1;

            MenuItem::create([
                'menu_id'   => $this->menuId,
                'label'     => $this->menuLabel ?: $this->titre,
                'page_id'   => $page->id,
                'parent_id' => null,
                'ordre'     => $nextOrdre,
                'visible'   => true,
                'icone'     => null,
            ]);
        }

        session()->flash('success', 'Page créée avec succès.');

        return redirect()->route('admin.pages.index');
    }

    public function render()
    {
        // Tous les menus disponibles pour le select
        $menus = Menu::orderBy('nom')->get();

        return view('livewire.admin.pages.page-create', [
            'menus' => $menus,
        ]);
    }
}