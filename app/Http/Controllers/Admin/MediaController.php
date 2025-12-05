<?php

namespace App\Http\Controllers\Admin;

use App\Models\Media;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{

    public function create()
    {
        return view('admin.media.upload');
    }

    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|file|max:10240', // 10MB
        ]);

        $uploadedCount = 0;
        $errors = [];

        foreach ($request->file('files') as $file) {
            try {
                // Déterminer le type
                $mimeType = $file->getMimeType();
                $type = $this->getTypeFromMimeType($mimeType);

                // Générer un nom unique
                $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                
                // Déterminer le dossier
                $folder = match($type) {
                    'image' => 'images',
                    'document' => 'documents',
                    'video' => 'videos',
                    default => 'others',
                };

                // Stocker le fichier
                $path = $file->storeAs($folder, $filename, 'public');

                // Créer l'enregistrement
                Media::create([
                    'nom_original' => $file->getClientOriginalName(),
                    'nom_fichier' => $filename,
                    'chemin' => $path,
                    'type' => $type,
                    'taille_bytes' => $file->getSize(),
                    'mime_type' => $mimeType,
                    'uploader_id' => Auth::id(),
                ]);

                $uploadedCount++;

            } catch (\Exception $e) {
                $errors[] = $file->getClientOriginalName() . ': ' . $e->getMessage();
            }
        }

        if ($uploadedCount > 0) {
            return redirect()->route('admin.media.index')
                ->with('success', "{$uploadedCount} fichier(s) uploadé(s) avec succès !");
        }

        return back()->withErrors($errors)->withInput();
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
}