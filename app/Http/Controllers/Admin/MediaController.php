<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MediaUploadRequest;
use App\Models\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class MediaController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim($request->string('search')->toString());
        $typeFilter = $request->string('type')->toString();
        $viewMode = $request->string('view')->toString();

        if (! in_array($typeFilter, ['all', 'image', 'document', 'video'], true)) {
            $typeFilter = 'all';
        }

        if (! in_array($viewMode, ['grid', 'list'], true)) {
            $viewMode = 'grid';
        }

        $query = Media::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nom_original', 'like', '%'.$search.'%')
                    ->orWhere('nom_fichier', 'like', '%'.$search.'%');
            });
        }

        if ($typeFilter !== 'all') {
            $query->where('type', $typeFilter);
        }

        $medias = $query->orderBy('created_at', 'desc')
            ->paginate(24)
            ->withQueryString()
            ->through(fn (Media $media) => [
                'id' => $media->id,
                'nom_original' => $media->nom_original,
                'nom_fichier' => $media->nom_fichier,
                'type' => $media->type,
                'taille_bytes' => $media->taille_bytes,
                'url' => $media->url,
                'created_at' => $media->created_at?->format('d/m/Y'),
            ]);

        $stats = [
            'total' => Media::count(),
            'images' => Media::where('type', 'image')->count(),
            'documents' => Media::where('type', 'document')->count(),
            'videos' => Media::where('type', 'video')->count(),
            'taille_totale' => Media::sum('taille_bytes'),
        ];

        return Inertia::render('Admin/Media/Index', [
            'filters' => [
                'search' => $search,
                'type' => $typeFilter,
                'view' => $viewMode,
            ],
            'medias' => $medias,
            'stats' => $stats,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Media/Upload');
    }

    public function store(MediaUploadRequest $request): RedirectResponse
    {
        $uploadedCount = 0;
        $errors = [];
        $lastMediaId = null;

        foreach ($request->file('files', []) as $file) {
            try {
                // Déterminer le type
                $mimeType = $file->getMimeType();
                $type = $this->getTypeFromMimeType($mimeType);

                // Générer un nom unique
                $filename = time().'_'.uniqid().'_'.$file->getClientOriginalName();

                // Déterminer le dossier
                $folder = match ($type) {
                    'image' => 'images',
                    'document' => 'documents',
                    'video' => 'videos',
                    default => 'others',
                };

                // Stocker le fichier
                $path = $file->storeAs($folder, $filename, 'public');

                // Créer l'enregistrement
                $media = Media::create([
                    'nom_original' => $file->getClientOriginalName(),
                    'nom_fichier' => $filename,
                    'chemin' => $path,
                    'type' => $type,
                    'taille_bytes' => $file->getSize(),
                    'mime_type' => $mimeType,
                    'uploader_id' => Auth::id(),
                ]);

                $uploadedCount++;
                $lastMediaId = $media->id;
            } catch (\Exception $e) {
                $errors[] = $file->getClientOriginalName().': '.$e->getMessage();
            }
        }

        if ($uploadedCount > 0) {
            $redirectTarget = $this->resolveRedirectTarget($request);
            $redirect = redirect()->to($redirectTarget)
                ->with('success', "{$uploadedCount} fichier(s) uploadé(s) avec succès !");

            if ($lastMediaId) {
                $redirect->with('uploaded_media_id', $lastMediaId);
            }

            return $redirect;
        }

        return back()->withErrors($errors)->withInput();
    }

    public function destroy(Media $media): RedirectResponse
    {
        if (Storage::disk('public')->exists($media->chemin)) {
            Storage::disk('public')->delete($media->chemin);
        }

        $media->delete();

        return redirect()->route('admin.media.index')
            ->with('success', 'Media supprime avec succes.');
    }

    private function getTypeFromMimeType(string $mimeType): string
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

    private function resolveRedirectTarget(Request $request): string
    {
        $redirectTo = $request->string('redirect_to')->toString();

        if ($redirectTo !== '') {
            if (str_starts_with($redirectTo, '/')
                && ! str_starts_with($redirectTo, '//')
                && ! str_contains($redirectTo, '://')) {
                return $redirectTo;
            }

            $baseUrl = url('/');

            if (str_starts_with($redirectTo, $baseUrl)) {
                return $redirectTo;
            }
        }

        return route('admin.media.index');
    }
}
