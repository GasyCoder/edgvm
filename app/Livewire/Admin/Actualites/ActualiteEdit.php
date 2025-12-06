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
    public $category_id;
    public $selectedTags = [];
    public $image_id;
    public $date_publication;
    public $visible;
    public $est_important;

    // Pour la sélection d'images
    public $showMediaSelector = false;
    public $mediaSelectorType = 'featured'; // 'featured' ou 'gallery'
    public $selectedImage = null;
    
    // Galerie d'images
    public $galerieImages = [];

    protected $rules = [
        'titre' => 'required|string|max:255',
        'contenu' => 'required|string',
        'category_id' => 'required|exists:categories,id',
        'selectedTags' => 'nullable|array',
        'selectedTags.*' => 'exists:tags,id',
        'image_id' => 'nullable|exists:media,id',
        'galerieImages' => 'nullable|array',
        'galerieImages.*' => 'exists:media,id',
        'date_publication' => 'nullable|date',
        'visible' => 'boolean',
        'est_important' => 'boolean',
    ];

    protected $messages = [
        'titre.required' => 'Le titre est obligatoire.',
        'contenu.required' => 'Le contenu est obligatoire.',
        'category_id.required' => 'La catégorie est obligatoire.',
    ];

    public function mount(Actualite $actualite)
    {
        $this->actualite = $actualite;
        $this->titre = $actualite->titre;
        $this->contenu = $actualite->contenu;
        $this->category_id = $actualite->category_id;
        $this->selectedTags = $actualite->tags->pluck('id')->toArray();
        $this->image_id = $actualite->image_id;
        $this->date_publication = $actualite->date_publication?->format('Y-m-d');
        $this->visible = $actualite->visible;
        $this->est_important = $actualite->est_important;
        
        // Charger les images de la galerie existantes
        $this->galerieImages = $actualite->galerie->pluck('id')->toArray();
        
        if ($this->image_id) {
            $this->selectedImage = Media::find($this->image_id);
        }
    }

    public function openMediaSelector($type = 'featured')
    {
        $this->mediaSelectorType = $type;
        $this->showMediaSelector = true;
    }

    public function selectImage($mediaId)
    {
        if ($this->mediaSelectorType === 'featured') {
            $this->image_id = $mediaId;
            $this->selectedImage = Media::find($mediaId);
        } else {
            // Ajouter à la galerie si pas déjà présent
            if (!in_array($mediaId, $this->galerieImages)) {
                $this->galerieImages[] = $mediaId;
            }
        }
        $this->showMediaSelector = false;
    }

    public function removeImage()
    {
        $this->image_id = null;
        $this->selectedImage = null;
    }

    public function removeGalerieImage($mediaId)
    {
        $this->galerieImages = array_diff($this->galerieImages, [$mediaId]);
    }

    public function save()
    {
        $this->validate();

        $this->actualite->update([
            'titre' => $this->titre,
            'contenu' => $this->contenu,
            'category_id' => $this->category_id,
            'image_id' => $this->image_id,
            'date_publication' => $this->date_publication,
            'visible' => $this->visible,
            'est_important' => $this->est_important,
        ]);

        // Synchroniser les tags
        $this->actualite->tags()->sync($this->selectedTags);

        // Synchroniser la galerie avec ordre
        $galerieData = [];
        foreach ($this->galerieImages as $index => $mediaId) {
            $galerieData[$mediaId] = ['ordre' => $index];
        }
        $this->actualite->galerie()->sync($galerieData);

        session()->flash('success', 'Actualité mise à jour avec succès !');
        
        return redirect()->route('admin.actualites.index');
    }

    public function render()
    {
        $medias = $this->showMediaSelector 
            ? Media::where('type', 'image')->orderBy('created_at', 'desc')->paginate(12)
            : collect();

        $categories = Category::visible()->orderBy('nom')->get();
        $tags = Tag::orderBy('nom')->get();
        
        // Charger les images de la galerie
        $galerieImagesData = !empty($this->galerieImages) 
            ? Media::whereIn('id', $this->galerieImages)->get()
            : collect();

        return view('livewire.admin.actualites.actualite-edit', [
            'medias' => $medias,
            'categories' => $categories,
            'tags' => $tags,
            'galerieImagesData' => $galerieImagesData,
        ]);
    }
}