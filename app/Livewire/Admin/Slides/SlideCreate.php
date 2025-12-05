<?php

namespace App\Livewire\Admin\Slides;

use App\Models\Media;
use App\Models\Slide;
use App\Models\Slider;
use Livewire\Component;
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
    public $texte_cta = '';
    public $ordre = 1;
    public $visible = true;
    public $badge_texte = '';
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

        // Gérer l'upload de l'image si présente
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
        ]);

        session()->flash('success', 'Slide créé avec succès !');

        return redirect()->route('admin.slides.index', $this->slider);
    }

    public function render()
    {
        $medias = Media::where('type', 'image')->orderBy('created_at', 'desc')->get();
        
        return view('livewire.admin.slides.slide-create', [
            'medias' => $medias,
            'title' => 'Créer un slide',
            'subtitle' => $this->slider->nom
        ]);
    }
}