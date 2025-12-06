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
    public $category_id = null; // ← AJOUTÉ
    public $selectedTags = []; // ← AJOUTÉ
    public $image_id = null;
    public $date_publication;
    public $visible = true;

    // Pour la sélection d'image
    public $showMediaSelector = false;
    public $selectedImage = null;
    public $est_important = false; // ← AJOUTÉ
    public $notifier_abonnes = false; // ← AJOUTÉ

    protected $rules = [
        'titre' => 'required|string|max:255',
        'contenu' => 'required|string',
        'category_id' => 'required|exists:categories,id', // ← AJOUTÉ (obligatoire)
        'selectedTags' => 'nullable|array', // ← AJOUTÉ (optionnel)
        'selectedTags.*' => 'exists:tags,id',
        'image_id' => 'nullable|exists:media,id',
        'date_publication' => 'nullable|date',
        'visible' => 'boolean',
        'est_important' => 'boolean', // ← AJOUTÉ
        'notifier_abonnes' => 'boolean', // ← AJOUTÉ
    ];

    protected $messages = [
        'titre.required' => 'Le titre est obligatoire.',
        'contenu.required' => 'Le contenu est obligatoire.',
        'category_id.required' => 'La catégorie est obligatoire.', // ← AJOUTÉ
    ];

    public function mount()
    {
        $this->date_publication = now()->format('Y-m-d');
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

        $actualite = Actualite::create([
            'titre' => $this->titre,
            'contenu' => $this->contenu,
            'auteur_id' => Auth::id(),
            'category_id' => $this->category_id,
            'image_id' => $this->image_id,
            'date_publication' => $this->date_publication,
            'visible' => $this->visible,
            'est_important' => $this->est_important, // ← AJOUTÉ
            'notifier_abonnes' => $this->notifier_abonnes, // ← AJOUTÉ
        ]);

        if (!empty($this->selectedTags)) {
            $actualite->tags()->attach($this->selectedTags);
        }

        // ← AJOUTÉ : Envoyer notification si demandé
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

        $categories = Category::visible()->orderBy('nom')->get(); // ← AJOUTÉ
        $tags = Tag::orderBy('nom')->get(); // ← AJOUTÉ

        return view('livewire.admin.actualites.actualite-create', [
            'medias' => $medias,
            'categories' => $categories, // ← AJOUTÉ
            'tags' => $tags, // ← AJOUTÉ
        ]);
    }
}