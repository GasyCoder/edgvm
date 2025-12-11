<?php

namespace App\Livewire\Admin\Pages;

use App\Models\Page;
use App\Models\PageSection;
use App\Models\Media;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Support\Str;
use Livewire\Component;

class PageEdit extends Component
{
    public Page $page;
    
    // Propriétés de la page
    public $titre;
    public $slug;
    public $contenu;
    public $visible;
    public $ordre;
    
    // Sections
    public $sections = [];
    public $newSection = [
        'titre'     => '',
        'contenu'   => '',
        'image_id'  => null,
        'ordre'     => 0,
    ];
    public $editingSection = null;
    public $confirmingDeleteSection = false;
    public $sectionToDelete = null;

    // Gestion du menu
    public $attachToMenu = false;   // afficher ou non dans un menu
    public $menuId = null;          // ID du menu sélectionné
    public $menuLabel = '';         // Libellé dans le menu
    public $menuItemId = null;      // ID du MenuItem existant pour cette page

    public function mount(Page $page)
    {
        $this->page    = $page;
        $this->titre   = $page->titre;
        $this->slug    = $page->slug;
        $this->contenu = $page->contenu;
        $this->visible = $page->visible;
        $this->ordre   = $page->ordre;
        
        $this->loadSections();
        $this->loadMenuState();
    }

    protected function loadMenuState(): void
    {
        $menuItem = MenuItem::where('page_id', $this->page->id)
            ->whereNull('parent_id')
            ->first();

        if ($menuItem) {
            $this->attachToMenu = true;
            $this->menuId       = $menuItem->menu_id;
            $this->menuLabel    = $menuItem->label;
            $this->menuItemId   = $menuItem->id;
        } else {
            $this->attachToMenu = false;
            $this->menuId       = null;
            $this->menuLabel    = '';
            $this->menuItemId   = null;
        }
    }

    public function loadSections(): void
    {
        $this->sections = $this->page
            ->sections()
            ->orderBy('ordre')
            ->get()
            ->toArray();
    }

    protected function rules(): array
    {
        return [
            'titre'   => 'required|string|max:255',
            'slug'    => 'required|string|max:255|unique:pages,slug,' . $this->page->id,
            'contenu' => 'nullable|string',
            'visible' => 'boolean',
            'ordre'   => 'integer|min:0',

            'attachToMenu' => 'boolean',
            'menuId'       => 'nullable|required_if:attachToMenu,true|exists:menus,id',
            'menuLabel'    => 'nullable|string|max:255',
        ];
    }

    protected $messages = [
        'titre.required'    => 'Le titre est obligatoire.',
        'slug.required'     => 'Le slug est obligatoire.',
        'slug.unique'       => 'Ce slug existe déjà.',
        'menuId.required_if'=> 'Veuillez choisir un menu.',
        'menuId.exists'     => 'Menu invalide.',
    ];

    public function updatedTitre($value): void
    {
        // En édition, on laisse le slug manuel pour ne pas casser les liens
        // Si tu veux qu’il suive le titre, enlève le if et garde juste la ligne de slug.
        if (! $this->page->id) {
            $this->slug = Str::slug($value);
        }
    }

    public function save()
    {
        $this->validate();

        // 1. Mise à jour de la page
        $this->page->update([
            'titre'   => $this->titre,
            'slug'    => $this->slug,
            'contenu' => $this->contenu,
            'visible' => $this->visible,
            'ordre'   => $this->ordre,
        ]);

        // 2. Synchronisation avec le menu
        if ($this->attachToMenu && $this->menuId) {
            // Chercher un MenuItem existant
            $menuItem = $this->menuItemId
                ? MenuItem::find($this->menuItemId)
                : MenuItem::where('page_id', $this->page->id)
                    ->whereNull('parent_id')
                    ->first();

            if ($menuItem) {
                // Mise à jour
                $menuItem->update([
                    'menu_id' => $this->menuId,
                    'label'   => $this->menuLabel ?: $this->titre,
                    'page_id' => $this->page->id,
                    'visible' => true,
                ]);
            } else {
                // Création
                $nextOrdre = (MenuItem::where('menu_id', $this->menuId)
                        ->whereNull('parent_id')
                        ->max('ordre') ?? 0) + 1;

                $menuItem = MenuItem::create([
                    'menu_id'   => $this->menuId,
                    'label'     => $this->menuLabel ?: $this->titre,
                    'page_id'   => $this->page->id,
                    'parent_id' => null,
                    'ordre'     => $nextOrdre,
                    'visible'   => true,
                    'icone'     => null,
                ]);
            }

            $this->menuItemId = $menuItem->id;
        } else {
            // Retirer la page de tous les menus
            MenuItem::where('page_id', $this->page->id)->delete();
            $this->menuItemId = null;
        }

        session()->flash('success', 'Page mise à jour avec succès.');

        return redirect()->route('admin.pages.index');
    }

    public function addSection(): void
    {
        $this->validate([
            'newSection.titre'   => 'nullable|string|max:255',
            'newSection.contenu' => 'nullable|string',
            'newSection.ordre'   => 'integer|min:0',
        ]);

        PageSection::create([
            'page_id'  => $this->page->id,
            'titre'    => $this->newSection['titre'],
            'contenu'  => $this->newSection['contenu'],
            'image_id' => $this->newSection['image_id'],
            'ordre'    => $this->newSection['ordre'],
        ]);

        $this->reset('newSection');
        $this->newSection = [
            'titre'     => '',
            'contenu'   => '',
            'image_id'  => null,
            'ordre'     => count($this->sections),
        ];

        $this->loadSections();
        session()->flash('success', 'Section ajoutée avec succès.');
    }

    public function editSection($sectionId): void
    {
        $section = PageSection::find($sectionId);
        if ($section) {
            $this->editingSection = $section->toArray();
        }
    }

    public function updateSection(): void
    {
        $this->validate([
            'editingSection.titre'   => 'nullable|string|max:255',
            'editingSection.contenu' => 'nullable|string',
            'editingSection.ordre'   => 'integer|min:0',
        ]);

        $section = PageSection::find($this->editingSection['id'] ?? null);
        if ($section) {
            $section->update([
                'titre'    => $this->editingSection['titre'],
                'contenu'  => $this->editingSection['contenu'],
                'image_id' => $this->editingSection['image_id'],
                'ordre'    => $this->editingSection['ordre'],
            ]);

            session()->flash('success', 'Section mise à jour avec succès.');
        }

        $this->editingSection = null;
        $this->loadSections();
    }

    public function confirmDeleteSection($sectionId): void
    {
        $this->sectionToDelete        = $sectionId;
        $this->confirmingDeleteSection = true;
    }

    public function deleteSection(): void
    {
        $section = PageSection::find($this->sectionToDelete);
        if ($section) {
            $section->delete();
            session()->flash('success', 'Section supprimée avec succès.');
        }

        $this->confirmingDeleteSection = false;
        $this->sectionToDelete         = null;
        $this->loadSections();
    }

    public function getMediasProperty()
    {
        return Media::where('type', 'image')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        $menus = Menu::orderBy('nom')->get();

        return view('livewire.admin.pages.page-edit', [
            'menus' => $menus,
        ]);
    }
}
