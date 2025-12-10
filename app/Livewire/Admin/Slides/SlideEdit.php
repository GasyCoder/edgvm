<?php

namespace App\Livewire\Admin\Slides;

use App\Models\Media;
use App\Models\Slide;
use App\Models\Actualite;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Title('Modifier un slide')]
class SlideEdit extends Component
{
    use WithFileUploads;

    public Slide $slide;
    
    // Champs du formulaire
    public $titre_ligne1;
    public $titre_highlight;
    public $titre_ligne2;
    public $description;
    public $image_id;
    public $new_image = null;
    public $lien_cta;
    public $texte_cta;
    public $ordre;
    public $visible;
    public $badge_texte;
    public $badge_icon;
    public $couleur_fond;

    // Pour la recherche / sélection d'actualité (typeahead)
    public $actualite_id = null;
    public $searchActualite = '';
    public $actualiteResults = [];
    public $actPreview = null; // objet Actualite en cache (pour affichage)

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

    public function mount(Slide $slide)
    {
        $this->slide = $slide;
        $this->titre_ligne1 = $slide->titre_ligne1;
        $this->titre_highlight = $slide->titre_highlight;
        $this->titre_ligne2 = $slide->titre_ligne2;
        $this->description = $slide->description;
        $this->image_id = $slide->image_id;
        $this->lien_cta = $slide->lien_cta;
        $this->texte_cta = $slide->texte_cta;
        $this->ordre = $slide->ordre;
        $this->visible = $slide->visible;
        $this->badge_texte = $slide->badge_texte;
        $this->badge_icon = $slide->badge_icon;
        $this->couleur_fond = $slide->couleur_fond;

        // Si le slide est déjà lié à une actualité, charger l'aperçu (cache)
        if ($slide->actualite_id) {
            $this->actualite_id = $slide->actualite_id;
            $this->actPreview = Actualite::select('id','titre','slug','date_publication')
                                         ->find($this->actualite_id);
            if ($this->actPreview && $this->actPreview->slug) {
                $this->lien_cta = route('actualites.show', ['actualite' => $this->actPreview->slug]);
            }
        }
    }

    public function update()
    {
        $this->validate();

        // Gérer l'upload de la nouvelle image si présente
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

        // Si une actualité est sélectionnée, forcer le lien depuis le slug
        if ($this->actualite_id) {
            $a = Actualite::select('id','slug')->find($this->actualite_id);
            if ($a && $a->slug) {
                $this->lien_cta = route('actualites.show', ['actualite' => $a->slug]);
            }
        }

        $this->slide->update([
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

        session()->flash('success', 'Slide mis à jour avec succès !');

        return redirect()->route('admin.slides.index', $this->slide->slider);
    }

    /**
     * Recherche typeahead: se déclenche quand searchActualite change (wire:model.debounce)
     */
    public function updatedSearchActualite($value)
    {
        if (empty($value)) {
            $this->actualiteResults = [];
            return;
        }

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

    /**
     * Lorsque l'admin clique sur un résultat : mettre à jour la selection et l'aperçu (cache)
     */
    public function selectActualite($id)
    {
        $actualite = Actualite::select('id','titre','slug','date_publication')->find($id);
        if (! $actualite) return;

        $this->actualite_id = $actualite->id;
        $this->actPreview = $actualite; // cache
        $this->lien_cta = route('actualites.show', ['actualite' => $actualite->slug]);

        // clear search UI
        $this->searchActualite = '';
        $this->actualiteResults = [];
    }

    /**
     * Permet d'annuler la sélection pour saisir un lien manuel
     */
    public function clearActualiteSelection()
    {
        $this->actualite_id = null;
        $this->actPreview = null;
        $this->lien_cta = '';
    }

    public function render()
    {
        $medias = Media::where('type', 'image')->orderBy('created_at', 'desc')->get();
        
        return view('livewire.admin.slides.slide-edit', [
            'medias' => $medias,
            'title' => 'Modifier le slide',
            'subtitle' => $this->slide->slider->nom
            // note: actPreview est une propriété publique, accessible directement en Blade
        ]);
    }
}
