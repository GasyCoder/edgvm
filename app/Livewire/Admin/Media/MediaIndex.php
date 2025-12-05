<?php

namespace App\Livewire\Admin\Media;

use App\Models\Media;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;

#[Title('Médiathèque')]
class MediaIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $typeFilter = 'all';
    public $viewMode = 'grid'; // grid ou list
    public $confirmingDeletion = false;
    public $mediaToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'typeFilter' => ['except' => 'all'],
        'viewMode' => ['except' => 'grid'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function toggleViewMode()
    {
        $this->viewMode = $this->viewMode === 'grid' ? 'list' : 'grid';
    }

    public function confirmDelete($mediaId)
    {
        $this->confirmingDeletion = true;
        $this->mediaToDelete = $mediaId;
    }

    public function deleteMedia()
    {
        if ($this->mediaToDelete) {
            $media = Media::find($this->mediaToDelete);
            
            if ($media) {
                // Supprimer le fichier physique
                if (Storage::disk('public')->exists($media->chemin)) {
                    Storage::disk('public')->delete($media->chemin);
                }
                
                $media->delete();
                
                session()->flash('success', 'Média supprimé avec succès !');
            }
        }

        $this->confirmingDeletion = false;
        $this->mediaToDelete = null;
    }

    public function render()
    {
        // Query de base
        $query = Media::query();

        // Recherche
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nom_original', 'like', '%' . $this->search . '%')
                  ->orWhere('nom_fichier', 'like', '%' . $this->search . '%');
            });
        }

        // Filtre par type
        if ($this->typeFilter !== 'all') {
            $query->where('type', $this->typeFilter);
        }

        // Pagination
        $medias = $query->orderBy('created_at', 'desc')->paginate(24);

        // Stats
        $stats = [
            'total' => Media::count(),
            'images' => Media::where('type', 'image')->count(),
            'documents' => Media::where('type', 'document')->count(),
            'videos' => Media::where('type', 'video')->count(),
            'taille_totale' => Media::sum('taille_bytes'),
        ];

        return view('livewire.admin.media.media-index', [
            'medias' => $medias,
            'stats' => $stats,
        ]);
    }
}