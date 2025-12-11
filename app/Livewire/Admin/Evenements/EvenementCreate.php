<?php

namespace App\Livewire\Admin\Evenements;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Evenement;
use App\Models\Media;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Mail\EvenementCreeMailable;

class EvenementCreate extends Component
{
    use WithFileUploads;

    // Champs principaux
    public $titre;
    public $description;
    public $date_debut;
    public $heure_debut;
    public $date_fin;
    public $heure_fin;
    public $lieu;
    public $type = 'autre';
    public $est_important = false;
    public $lien_inscription;
    public $est_publie = true;

    // Médias
    public $cover_image;        // upload image
    public $document_media_id;  // id du Media (PDF) existant
    public $pdfSearch = '';     // texte de recherche dans les PDF

    // Newsletter (checkbox)
    public $notify_all = false;
    public $notify_encadrants = false;
    public $notify_doctorants = false;

    protected function rules()
    {
        return [
            'titre'            => ['required','string','max:255'],
            'description'      => ['nullable','string'],
            'date_debut'       => ['required','date'],
            'heure_debut'      => ['nullable','date_format:H:i'],
            'date_fin'         => ['nullable','date','after_or_equal:date_debut'],
            'heure_fin'        => ['nullable','date_format:H:i'],
            'lieu'             => ['nullable','string','max:255'],
            'type'             => ['required', Rule::in(['soutenance','seminaire','conference','atelier','autre'])],
            'est_important'    => ['boolean'],
            'lien_inscription' => ['nullable','url'],
            'est_publie'       => ['boolean'],

            // Upload image
            'cover_image'      => ['nullable','image','max:2048'], // 2 Mo

            // PDF choisi dans la médiathèque
            'document_media_id' => ['nullable','exists:media,id'],

            // Newsletter
            'notify_all'        => ['boolean'],
            'notify_encadrants' => ['boolean'],
            'notify_doctorants' => ['boolean'],
        ];
    }

    protected $validationAttributes = [
        'date_debut'        => 'date de début',
        'date_fin'          => 'date de fin',
        'heure_debut'       => 'heure de début',
        'heure_fin'         => 'heure de fin',
        'lien_inscription'  => "lien d'inscription",
        'cover_image'       => "image de couverture",
        'document_media_id' => "document PDF associé",
    ];

    /**
     * Liste des PDF filtrés par recherche (type = document)
     * - Sans recherche ou recherche < 2 caractères : 10 derniers documents
     * - Avec recherche (≥ 2 caractères) : résultats filtrés, max 50
     */
    public function getPdfMediasProperty()
    {
        $query = Media::where('type', 'document');
        $search = trim((string) $this->pdfSearch);

        if ($search === '' || strlen($search) < 2) {
            return $query
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }

        $term = '%' . $search . '%';

        return $query
            ->where(function ($q) use ($term) {
                $q->where('nom_original', 'like', $term)
                  ->orWhere('nom_fichier', 'like', $term);
            })
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();
    }

    /**
     * PDF actuellement sélectionné (ou null)
     */
    public function getSelectedPdfProperty()
    {
        if (! $this->document_media_id) {
            return null;
        }

        // On réutilise la collection déjà chargée si possible
        $fromList = $this->pdfMedias->firstWhere('id', $this->document_media_id);

        if ($fromList) {
            return $fromList;
        }

        // Si le PDF sélectionné ne fait pas partie de la liste, on le charge une fois
        return Media::find($this->document_media_id);
    }

    /**
     * Sélection d’un PDF dans la liste
     */
    public function selectPdf(int $mediaId): void
    {
        $this->document_media_id = $mediaId;
    }

    /**
     * Effacer la sélection du PDF
     */
    public function clearPdf(): void
    {
        $this->document_media_id = null;
    }

    public function submit()
    {
        $this->validate();

        // 1) Sauvegarde de l'image de couverture (upload)
        $imageMediaId = null;

        if ($this->cover_image) {
            $file = $this->cover_image;
            $path = $file->store('evenements/images', 'public');

            $mediaImg = Media::create([
                'nom_original' => $file->getClientOriginalName(),
                'nom_fichier'  => basename($path),
                'chemin'       => $path,
                'type'         => 'image',
                'taille_bytes' => $file->getSize(),
                'mime_type'    => $file->getMimeType(),
                'uploader_id'  => Auth::id(),
            ]);

            $imageMediaId = $mediaImg->id;
        }

        // 2) Document PDF associé (pris dans la médiathèque)
        $pdfMediaId = $this->document_media_id ?: null;

        // 3) Créer l’événement
        $evenement = Evenement::create([
            'titre'            => $this->titre,
            'description'      => $this->description,
            'date_debut'       => $this->date_debut,
            'heure_debut'      => $this->heure_debut,
            'date_fin'         => $this->date_fin,
            'heure_fin'        => $this->heure_fin,
            'lieu'             => $this->lieu,
            'type'             => $this->type,
            'est_important'    => (bool)$this->est_important,
            'lien_inscription' => $this->lien_inscription,
            'est_publie'       => (bool)$this->est_publie,
            'est_archive'      => false,
            'image_id'         => $imageMediaId,
            'document_id'      => $pdfMediaId,
        ]);

                // 4) Envoi des emails newsletter (Mail + Mailable)
        if ($this->notify_all || $this->notify_encadrants || $this->notify_doctorants) {

            $query = NewsletterSubscriber::actif();

            if (! $this->notify_all) {
                $types = [];

                if ($this->notify_encadrants) {
                    $types[] = 'encadrant';
                }

                if ($this->notify_doctorants) {
                    $types[] = 'doctorant';
                }

                if (count($types) > 0) {
                    $query->whereIn('type', $types);
                } else {
                    // aucun type coché → pas d'envoi
                    $query->whereRaw('1 = 0');
                }
            }

            $subscribers = $query->get();

            foreach ($subscribers as $subscriber) {
                if (! $subscriber->email) {
                    continue;
                }

                Mail::to($subscriber->email)->queue(
                    new EvenementCreeMailable($evenement, $subscriber)
                );
            }
        }

        session()->flash('success', 'Événement créé avec succès.');
        return redirect()->route('admin.evenements.index');
    }

    public function render()
    {
        return view('livewire.admin.evenements.evenement-create');
    }
}
