<?php

namespace App\Livewire\Admin\Actualites;

use App\Models\Tag;
use App\Models\Media;
use Livewire\Component;
use App\Models\Category;
use App\Models\Actualite;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendActualiteNotification;

#[Title('Nouvelle actualité')]
class ActualiteCreate extends Component
{
    public $titre = '';
    public $contenu = '';
    public $category_id = null;
    public $selectedTags = [];
    public $image_id = null;
    public $date_publication;
    public $visible = true;
    public $est_important = false;
    public $notifier_abonnes = false;

    // Pour la sélection d'images
    public $showMediaSelector = false;
    public $mediaSelectorType = 'featured'; // 'featured' ou 'gallery'
    public $selectedImage = null;
    
    // ← AJOUTÉ : Galerie d'images
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
        'notifier_abonnes' => 'boolean',
    ];

    protected $messages = [
        'titre.required' => 'Le titre est obligatoire.',
        'contenu.required' => 'Le contenu est obligatoire.',
        'category_id.required' => 'La catégorie est obligatoire.',
    ];

    public function mount()
    {
        $this->date_publication = now()->format('Y-m-d');
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

        $actualite = Actualite::create([
            'titre' => $this->titre,
            'contenu' => $this->contenu,
            'auteur_id' => Auth::id(),
            'category_id' => $this->category_id,
            'image_id' => $this->image_id,
            'date_publication' => $this->date_publication,
            'visible' => $this->visible,
            'est_important' => $this->est_important,
            'notifier_abonnes' => $this->notifier_abonnes,
        ]);

        // Attacher les tags
        if (!empty($this->selectedTags)) {
            $actualite->tags()->attach($this->selectedTags);
        }

        // ← AJOUTÉ : Attacher les images de la galerie
        if (!empty($this->galerieImages)) {
            foreach ($this->galerieImages as $index => $mediaId) {
                $actualite->galerie()->attach($mediaId, ['ordre' => $index]);
            }
        }

        // Envoyer notification si demandé
        if ($this->notifier_abonnes && $actualite->visible) {
            SendActualiteNotification::dispatch($actualite);
            session()->flash('success', 'Actualité créée et notification envoyée aux abonnés !');
        } else {
            session()->flash('success', 'Actualité créée avec succès !');
        }
        
        return redirect()->route('admin.actualites.index');
    }

    public function render()
    {
        $medias = $this->showMediaSelector 
            ? Media::where('type', 'image')->orderBy('created_at', 'desc')->paginate(12)
            : collect();

        $categories = Category::visible()->orderBy('nom')->get();
        $tags = Tag::orderBy('nom')->get();
        
        // ← AJOUTÉ : Charger les images de la galerie
        $galerieImagesData = !empty($this->galerieImages) 
            ? Media::whereIn('id', $this->galerieImages)->get()
            : collect();

        return view('livewire.admin.actualites.actualite-create', [
            'medias' => $medias,
            'categories' => $categories,
            'tags' => $tags,
            'galerieImagesData' => $galerieImagesData,
        ]);
    }
}