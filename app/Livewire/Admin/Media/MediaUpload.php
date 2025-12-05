<?php

namespace App\Livewire\Admin\Media;

use App\Models\Media;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

#[Title('Uploader des médias')]
class MediaUpload extends Component
{
    use WithFileUploads;

    // ✅ Supprimez la règle "required" ici
    public $files = [];
    
    public $uploadedFiles = [];

    // ✅ Ajoutez une règle pour les fichiers individuels
    protected $rules = [
        'files.*' => 'file|max:10240', // 10MB
    ];

    // ✅ Messages de validation personnalisés
    protected $messages = [
        'files.*.file' => 'Le fichier doit être valide.',
        'files.*.max' => 'Chaque fichier ne doit pas dépasser 10 MB.',
    ];

    public function updatedFiles()
    {
        // ✅ Valider les fichiers dès qu'ils sont sélectionnés
        $this->validate([
            'files.*' => 'file|max:10240',
        ]);
        
        // ✅ S'assurer que $files reste un array
        if (!is_array($this->files)) {
            $this->files = [$this->files];
        }
        
        // ✅ Limiter le nombre de fichiers si nécessaire
        if (count($this->files) > 20) {
            $this->files = array_slice($this->files, 0, 20);
            session()->flash('error', 'Maximum 20 fichiers autorisés.');
        }
    }

    public function removeFile($index)
    {
        if (isset($this->files[$index])) {
            // ✅ Supprimer le fichier temporaire
            if (method_exists($this->files[$index], 'delete')) {
                $this->files[$index]->delete();
            }
            
            unset($this->files[$index]);
            $this->files = array_values($this->files);
        }
    }

    // ✅ Méthode pour le drag & drop
    public function uploadFiles($files)
    {
        // Convertir FileList en array
        $fileArray = [];
        foreach ($files as $file) {
            $fileArray[] = $file;
        }
        
        // Ajouter aux fichiers existants
        $this->files = array_merge($this->files, $fileArray);
        
        // Valider
        $this->updatedFiles();
    }

    public function upload()
    {
        // ✅ Vérifier qu'il y a des fichiers
        if (empty($this->files)) {
            session()->flash('error', 'Veuillez sélectionner au moins un fichier.');
            return;
        }

        $uploadedCount = 0;
        $errors = [];

        foreach ($this->files as $file) {
            try {
                // ✅ Vérifier que le fichier existe et est valide
                if (!$file) {
                    $errors[] = "Fichier invalide ou corrompu";
                    continue;
                }

                // Vérifier la taille
                if ($file->getSize() > 10240 * 1024) { // 10MB en bytes
                    $errors[] = $file->getClientOriginalName() . " dépasse la taille maximale de 10MB";
                    continue;
                }

                // Déterminer le type
                $mimeType = $file->getMimeType();
                $type = $this->getTypeFromMimeType($mimeType);

                // Générer un nom unique
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '.' . $extension;
                
                // Déterminer le dossier selon le type
                $folder = match($type) {
                    'image' => 'images',
                    'document' => 'documents',
                    'video' => 'videos',
                    default => 'others',
                };

                // ✅ Stocker le fichier
                $path = $file->storeAs($folder, $filename, 'public');

                // Créer l'enregistrement
                $media = Media::create([
                    'nom_original' => $originalName,
                    'nom_fichier' => $filename,
                    'chemin' => $path,
                    'type' => $type,
                    'taille_bytes' => $file->getSize(),
                    'mime_type' => $mimeType,
                    'uploader_id' =>Auth::id(),
                ]);

                $this->uploadedFiles[] = $media;
                $uploadedCount++;

            } catch (\Exception $e) {
                $errors[] = $file->getClientOriginalName() . ': ' . $e->getMessage();
            }
        }

        // Messages de résultat
        if ($uploadedCount > 0) {
            session()->flash('success', "{$uploadedCount} fichier(s) uploadé(s) avec succès !");
            
            // ✅ Réinitialiser les fichiers après upload réussi
            foreach ($this->files as $file) {
                if (method_exists($file, 'delete')) {
                    $file->delete();
                }
            }
            $this->files = [];
        }
        
        if (!empty($errors)) {
            session()->flash('error', 'Erreurs: ' . implode('<br>', $errors));
        }
    }

    private function getTypeFromMimeType($mimeType)
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        } elseif (str_starts_with($mimeType, 'video/')) {
            return 'video';
        } elseif (in_array($mimeType, [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ])) {
            return 'document';
        }
        
        return 'other';
    }

    // ✅ Nettoyer les fichiers temporaires si le composant est détruit
    public function cleanup()
    {
        if (is_array($this->files)) {
            foreach ($this->files as $file) {
                if (method_exists($file, 'delete')) {
                    $file->delete();
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.media.media-upload', [
            'title' => 'Uploader des médias',
        ]);
    }
}