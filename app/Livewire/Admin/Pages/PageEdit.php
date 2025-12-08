<?php

namespace App\Livewire\Admin\Pages;

use App\Models\Page;
use App\Models\PageSection;
use App\Models\Media;
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
        'titre' => '',
        'contenu' => '',
        'image_id' => null,
        'ordre' => 0,
    ];
    public $editingSection = null;
    public $confirmingDeleteSection = false;
    public $sectionToDelete = null;

    public function mount(Page $page)
    {
        $this->page = $page;
        $this->titre = $page->titre;
        $this->slug = $page->slug;
        $this->contenu = $page->contenu;
        $this->visible = $page->visible;
        $this->ordre = $page->ordre;
        
        $this->loadSections();
    }

    public function loadSections()
    {
        $this->sections = $this->page->sections()->orderBy('ordre')->get()->toArray();
    }

    protected function rules()
    {
        return [
            'titre' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $this->page->id,
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
        if (!$this->page->id) {
            $this->slug = Str::slug($value);
        }
    }

    public function save()
    {
        $this->validate();

        $this->page->update([
            'titre' => $this->titre,
            'slug' => $this->slug,
            'contenu' => $this->contenu,
            'visible' => $this->visible,
            'ordre' => $this->ordre,
        ]);

        session()->flash('success', 'Page mise à jour avec succès.');

        return redirect()->route('admin.pages.index');
    }

    public function addSection()
    {
        $this->validate([
            'newSection.titre' => 'nullable|string|max:255',
            'newSection.contenu' => 'nullable|string',
            'newSection.ordre' => 'integer|min:0',
        ]);

        PageSection::create([
            'page_id' => $this->page->id,
            'titre' => $this->newSection['titre'],
            'contenu' => $this->newSection['contenu'],
            'image_id' => $this->newSection['image_id'],
            'ordre' => $this->newSection['ordre'],
        ]);

        $this->reset('newSection');
        $this->newSection = [
            'titre' => '',
            'contenu' => '',
            'image_id' => null,
            'ordre' => count($this->sections),
        ];

        $this->loadSections();
        session()->flash('success', 'Section ajoutée avec succès.');
    }

    public function editSection($sectionId)
    {
        $section = PageSection::find($sectionId);
        if ($section) {
            $this->editingSection = $section->toArray();
        }
    }

    public function updateSection()
    {
        $this->validate([
            'editingSection.titre' => 'nullable|string|max:255',
            'editingSection.contenu' => 'nullable|string',
            'editingSection.ordre' => 'integer|min:0',
        ]);

        $section = PageSection::find($this->editingSection['id']);
        if ($section) {
            $section->update([
                'titre' => $this->editingSection['titre'],
                'contenu' => $this->editingSection['contenu'],
                'image_id' => $this->editingSection['image_id'],
                'ordre' => $this->editingSection['ordre'],
            ]);

            session()->flash('success', 'Section mise à jour avec succès.');
        }

        $this->editingSection = null;
        $this->loadSections();
    }

    public function confirmDeleteSection($sectionId)
    {
        $this->sectionToDelete = $sectionId;
        $this->confirmingDeleteSection = true;
    }

    public function deleteSection()
    {
        $section = PageSection::find($this->sectionToDelete);
        if ($section) {
            $section->delete();
            session()->flash('success', 'Section supprimée avec succès.');
        }

        $this->confirmingDeleteSection = false;
        $this->sectionToDelete = null;
        $this->loadSections();
    }

    public function getMediasProperty()
    {
        return Media::where('type', 'image')->orderBy('created_at', 'desc')->get();
    }

    public function render()
    {
        return view('livewire.admin.pages.page-edit');
    }
}