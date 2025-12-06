<?php

namespace App\Livewire\Admin\Actualites;

use App\Models\Actualite;
use App\Models\Media;
use App\Models\Category;
use App\Models\Tag;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Modifier l\'actualité')]
class ActualiteEdit extends Component
{
    public Actualite $actualite;
    
    public $titre;
    public $contenu;
    public $category_id; // ← AJOUTÉ
    public $selectedTags = []; // ← AJOUTÉ
    public $image_id;
    public $date_publication;
    public $visible;

    public $showMediaSelector = false;
    public $selectedImage = null;

    protected $rules = [
        'titre' => 'required|string|max:255',
        'contenu' => 'required|string',
        'category_id' => 'required|exists:categories,id', // ← AJOUTÉ
        'selectedTags' => 'nullable|array', // ← AJOUTÉ
        'selectedTags.*' => 'exists:tags,id',
        'image_id' => 'nullable|exists:media,id',
        'date_publication' => 'nullable|date',
        'visible' => 'boolean',
    ];

    public function mount(Actualite $actualite)
    {
        $this->actualite = $actualite;
        $this->titre = $actualite->titre;
        $this->contenu = $actualite->contenu;
        $this->category_id = $actualite->category_id; // ← AJOUTÉ
        $this->selectedTags = $actualite->tags->pluck('id')->toArray(); // ← AJOUTÉ
        $this->image_id = $actualite->image_id;
        $this->date_publication = $actualite->date_publication?->format('Y-m-d');
        $this->visible = $actualite->visible;
        
        if ($this->image_id) {
            $this->selectedImage = Media::find($this->image_id);
        }
    }

    public function selectImage($mediaId)
    {
        $this->image_id = $mediaId;
        $this->selectedImage = Media::find($mediaId);
        $this->showMediaSelector = false;
    }

    public function removeImage()
    {
        $this->image_id = null;
        $this->selectedImage = null;
    }

    public function save()
    {
        $this->validate();

        $this->actualite->update([
            'titre' => $this->titre,
            'contenu' => $this->contenu,
            'category_id' => $this->category_id, // ← AJOUTÉ
            'image_id' => $this->image_id,
            'date_publication' => $this->date_publication,
            'visible' => $this->visible,
        ]);

        // Synchroniser les tags ← AJOUTÉ
        $this->actualite->tags()->sync($this->selectedTags);

        session()->flash('success', 'Actualité mise à jour avec succès !');
        
        return redirect()->route('admin.actualites.index');
    }

    public function render()
    {
        $medias = $this->showMediaSelector 
            ? Media::where('type', 'image')->orderBy('created_at', 'desc')->paginate(12)
            : collect();

        $categories = Category::visible()->orderBy('nom')->get(); // ← AJOUTÉ
        $tags = Tag::orderBy('nom')->get(); // ← AJOUTÉ

        return view('livewire.admin.actualites.actualite-edit', [
            'medias' => $medias,
            'categories' => $categories, // ← AJOUTÉ
            'tags' => $tags, // ← AJOUTÉ
        ]);
    }
}