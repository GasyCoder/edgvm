<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnnonceRequest;
use App\Http\Requests\UpdateAnnonceRequest;
use App\Jobs\SendAnnonceEmailJob;
use App\Models\Annonce;
use App\Models\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AnnonceController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim($request->string('search')->toString());
        $filterCible = $request->string('cible')->toString();
        $filterPublie = $request->string('publie')->toString();

        if (! in_array($filterCible, ['doctorant', 'encadrant', 'both', ''], true)) {
            $filterCible = '';
        }

        if (! in_array($filterPublie, ['all', '1', '0'], true)) {
            $filterPublie = 'all';
        }

        $query = Annonce::query()->latest();

        if ($search !== '') {
            $query->where('titre', 'like', "%{$search}%");
        }

        if ($filterCible !== '') {
            $query->where('cible', $filterCible);
        }

        if ($filterPublie !== 'all') {
            $query->where('est_publie', $filterPublie === '1');
        }

        $annonces = $query->paginate(12)
            ->withQueryString()
            ->through(fn (Annonce $annonce) => [
                'id' => $annonce->id,
                'titre' => $annonce->titre,
                'cible' => $annonce->cible,
                'est_publie' => $annonce->est_publie,
                'publie_at' => $annonce->publie_at?->format('d/m/Y H:i'),
                'envoyer_email' => $annonce->envoyer_email,
                'email_cible' => $annonce->email_cible,
                'email_envoye_at' => $annonce->email_envoye_at?->format('d/m/Y H:i'),
            ]);

        return Inertia::render('Admin/Annonces/Index', [
            'filters' => [
                'search' => $search,
                'cible' => $filterCible,
                'publie' => $filterPublie,
            ],
            'annonces' => $annonces,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Annonces/Create', [
            'medias' => $this->mediaOptions(),
        ]);
    }

    public function store(StoreAnnonceRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['auteur_id'] = Auth::id();

        if ($request->hasFile('media_file')) {
            $data['media_id'] = $this->storeUploadedMedia($request->file('media_file'))->id;
        }

        if ($data['est_publie'] ?? false) {
            $data['publie_at'] = now();
        } else {
            $data['publie_at'] = null;
        }

        if (! ($data['envoyer_email'] ?? false)) {
            $data['email_cible'] = null;
        } elseif (empty($data['email_cible'])) {
            $data['email_cible'] = 'both';
        }

        $annonce = Annonce::create($data);

        if ($data['envoyer_email'] ?? false) {
            SendAnnonceEmailJob::dispatch($annonce->id);
        }

        return redirect()->route('admin.annonces.index')
            ->with('success', 'Annonce creee.');
    }

    public function edit(Annonce $annonce): Response
    {
        return Inertia::render('Admin/Annonces/Edit', [
            'annonce' => [
                'id' => $annonce->id,
                'titre' => $annonce->titre,
                'contenu_html' => $annonce->contenu_html,
                'cible' => $annonce->cible,
                'media_id' => $annonce->media_id,
                'est_publie' => $annonce->est_publie,
                'envoyer_email' => $annonce->envoyer_email,
                'email_cible' => $annonce->email_cible,
            ],
            'medias' => $this->mediaOptions(),
        ]);
    }

    public function update(UpdateAnnonceRequest $request, Annonce $annonce): RedirectResponse
    {
        $data = $request->validated();
        $data['auteur_id'] = Auth::id();

        if ($request->hasFile('media_file')) {
            $data['media_id'] = $this->storeUploadedMedia($request->file('media_file'))->id;
        }

        if ($data['est_publie'] ?? false) {
            $data['publie_at'] = $annonce->publie_at ?? now();
        } else {
            $data['publie_at'] = null;
        }

        if (! ($data['envoyer_email'] ?? false)) {
            $data['email_cible'] = null;
        } elseif (empty($data['email_cible'])) {
            $data['email_cible'] = 'both';
        }

        $annonce->update($data);

        if ($data['envoyer_email'] ?? false) {
            SendAnnonceEmailJob::dispatch($annonce->id);
        }

        return redirect()->route('admin.annonces.index')
            ->with('success', 'Annonce mise a jour.');
    }

    public function destroy(Annonce $annonce): RedirectResponse
    {
        $annonce->delete();

        return redirect()->route('admin.annonces.index')
            ->with('success', 'Annonce supprimee.');
    }

    public function togglePublie(Annonce $annonce): RedirectResponse
    {
        $annonce->est_publie = ! $annonce->est_publie;
        $annonce->publie_at = $annonce->est_publie ? now() : null;
        $annonce->save();

        return redirect()->route('admin.annonces.index')
            ->with('success', 'Statut de publication mis a jour.');
    }

    public function sendEmailNow(Annonce $annonce): RedirectResponse
    {
        if (empty($annonce->email_cible)) {
            return redirect()->route('admin.annonces.index')
                ->with('error', 'Email cible non defini.');
        }

        SendAnnonceEmailJob::dispatch($annonce->id);

        return redirect()->route('admin.annonces.index')
            ->with('success', 'Envoi email declenche.');
    }

    private function mediaOptions(): array
    {
        return Media::query()
            ->latest()
            ->limit(80)
            ->get()
            ->map(fn (Media $media) => [
                'id' => $media->id,
                'nom' => $media->display_name,
                'type' => $media->type,
                'url' => $media->url,
            ])
            ->toArray();
    }

    private function storeUploadedMedia(UploadedFile $file): Media
    {
        $mimeType = $file->getMimeType();
        $type = $this->getTypeFromMimeType($mimeType);
        $filename = time().'_'.uniqid().'_'.$file->getClientOriginalName();

        $folder = match ($type) {
            'image' => 'images',
            'document' => 'documents',
            'video' => 'videos',
            default => 'others',
        };

        $path = $file->storeAs($folder, $filename, 'public');

        return Media::create([
            'nom_original' => $file->getClientOriginalName(),
            'nom_fichier' => $filename,
            'chemin' => $path,
            'type' => $type,
            'taille_bytes' => $file->getSize(),
            'mime_type' => $mimeType,
            'uploader_id' => Auth::id(),
        ]);
    }

    private function getTypeFromMimeType(?string $mimeType): string
    {
        if (! $mimeType) {
            return 'others';
        }

        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        }

        if (str_starts_with($mimeType, 'video/')) {
            return 'video';
        }

        if (in_array($mimeType, [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ], true)) {
            return 'document';
        }

        return 'others';
    }
}
