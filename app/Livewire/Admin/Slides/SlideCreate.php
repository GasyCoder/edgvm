<?php

namespace App\Livewire\Admin\Slides;

use App\Models\Media;
use App\Models\Slide;
use App\Models\Slider;
use Livewire\Component;
use App\Models\Actualite;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

#[Title('Créer un slide')]
class SlideCreate extends Component
{
    use WithFileUploads;

    public Slider $slider;
    
    // Champs du formulaire
    public $titre_ligne1 = '';
    public $titre_highlight = '';
    public $titre_ligne2 = '';
    public $description = '';
    public $image_id = null;
    public $new_image = null;
    public $lien_cta = '';
    public $actualite_id = null;
    public $texte_cta = '';
    public $ordre = 1;
    public $visible = true;
    public $badge_texte = '';
    public $actualiteResults = [];
    public $searchActualite = '';
    public $badge_icon = 'university';
    public $couleur_fond = 'from-ed-primary/95 via-ed-secondary/90 to-teal-800/95';

    protected $rules = [
        'titre_highlight' => 'required|string|max:255',
        'titre_ligne1' => 'nullable|string|max:255',
        'titre_ligne2' => 'nullable|string|max:255',
        'description' => 'nullable|string|max:500',
        'image_id' => 'nullable|exists:media,id',
        'new_image' => 'nullable|image|max:5120', // 5MB max
        'lien_cta' => 'nullable|string|max:255',
        'actualite_id' => 'nullable|exists:actualites,id',
        'texte_cta' => 'nullable|string|max:255',
        'ordre' => 'required|integer|min:1',
        'visible' => 'boolean',
        'badge_texte' => 'nullable|string|max:255',
        'badge_icon' => 'nullable|string|in:university,research,students',
        'couleur_fond' => 'required|string',
    ];

    protected $messages = [
        'titre_highlight.required' => 'Le titre en surbrillance est obligatoire.',
        'new_image.image' => 'Le fichier doit être une image.',
        'new_image.max' => 'L\'image ne doit pas dépasser 5 MB.',
        'ordre.required' => 'L\'ordre est obligatoire.',
        'ordre.min' => 'L\'ordre doit être au minimum 1.',
    ];

    public function mount(Slider $slider)
    {
        $this->slider = $slider;
        // Définir l'ordre par défaut comme le prochain disponible
        $this->ordre = $slider->slides()->max('ordre') + 1;
    }

    public function save()
    {
        $this->validate();

        // upload image inchangé...
        if ($this->new_image) {
            $filename = time() . '_' . $this->new_image->getClientOriginalName();
            $path = $this->new_image->storeAs('slides', $filename, 'public');

            $media = Media::create([
                'nom_original' => $this->new_image->getClientOriginalName(),
                'nom_fichier' => $filename,
                'chemin' => $path,
                'type' => 'image',
                'taille_bytes' => $this->new_image->getSize(),
                'mime_type' => $this->new_image->getMimeType(),
                'uploader_id' => Auth::id(),
            ]);

            $this->image_id = $media->id;
        }

        // Priorité : si actualite_id renseigné, générer le lien depuis le slug
        if ($this->actualite_id) {
            $actualite = Actualite::find($this->actualite_id);
            if ($actualite) {
                // génère /actualites/{slug}
                $this->lien_cta = route('actualites.show', ['actualite' => $actualite->slug]);
            }
        }

        Slide::create([
            'slider_id' => $this->slider->id,
            'titre_ligne1' => $this->titre_ligne1,
            'titre_highlight' => $this->titre_highlight,
            'titre_ligne2' => $this->titre_ligne2,
            'description' => $this->description,
            'image_id' => $this->image_id,
            'lien_cta' => $this->lien_cta,
            'texte_cta' => $this->texte_cta,
            'ordre' => $this->ordre,
            'visible' => $this->visible,
            'badge_texte' => $this->badge_texte,
            'badge_icon' => $this->badge_icon,
            'couleur_fond' => $this->couleur_fond,
            'actualite_id' => $this->actualite_id,
        ]);

        session()->flash('success', 'Slide créé avec succès !');

        return redirect()->route('admin.slides.index', $this->slider);
    }

       // Livewire lifecycle hook: update search on typing (debounced in blade)
    public function updatedSearchActualite($value)
    {
        $this->actualiteResults = Actualite::visible()
            ->where('titre', 'like', '%' . $value . '%')
            ->orderBy('date_publication', 'desc')
            ->limit(10)
            ->get()
            ->map(function($a){
                return [
                    'id' => $a->id,
                    'titre' => $a->titre,
                    'slug' => $a->slug,
                    'date_publication' => $a->date_publication ? $a->date_publication->format('Y-m-d') : null,
                ];
            })->toArray();
    }

    // when admin clicks a result
    public function selectActualite($id)
    {
        $actualite = Actualite::find($id);
        if (! $actualite) return;

        $this->actualite_id = $actualite->id;
        $this->lien_cta = route('actualites.show', ['actualite' => $actualite->slug]);

        // clear search UI
        $this->searchActualite = '';
        $this->actualiteResults = [];
    }

    // allow clearing selection to enter manual link
    public function clearActualiteSelection()
    {
        $this->actualite_id = null;
        $this->lien_cta = '';
    }


    /**
     * Computed property accessible en Blade par $actPreview
     */
    public function getActPreviewProperty()
    {
        if (! $this->actualite_id) {
            return null;
        }

        // Charge l'actualité liée (sélectif, on ne charge que les colonnes nécessaires)
        return Actualite::select('id', 'titre', 'slug', 'date_publication')
                        ->find($this->actualite_id);
    }



    public function render()
    {
        $medias = Media::where('type', 'image')->orderBy('created_at', 'desc')->get();
        $actualites = Actualite::visible()->orderBy('date_publication','desc')->get();

        // Récupère la computed property (ou null)
        $actPreview = $this->getActPreviewProperty();

        return view('livewire.admin.slides.slide-create', [
            'medias' => $medias,
            'actualites' => $actualites,
            'actPreview' => $actPreview,   // <-- passe la variable à la vue
            'title' => 'Créer un slide',
            'subtitle' => $this->slider->nom
        ]);
    }

}