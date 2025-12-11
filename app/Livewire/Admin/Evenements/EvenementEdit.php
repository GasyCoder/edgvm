<?php

namespace App\Livewire\Admin\Evenements;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Evenement;
use App\Models\Media;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;

class EvenementEdit extends Component
{
    use WithFileUploads;

    public Evenement $evenement;

    // Champs du formulaire (comme dans create)
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
    public $cover_image;        // nouvelle image uploadée (optionnelle)
    public $document_media_id;  // id du Media (PDF) existant
    public $pdfSearch = '';     // texte de recherche dans les PDF

    public function mount(Evenement $evenement)
    {
        $this->evenement = $evenement;

        // Remplir les propriétés simples à partir du modèle
        $this->titre           = $evenement->titre;
        $this->description     = $evenement->description;
        $this->lieu            = $evenement->lieu;
        $this->type            = $evenement->type;
        $this->est_important   = (bool) $evenement->est_important;
        $this->lien_inscription = $evenement->lien_inscription;
        $this->est_publie      = (bool) $evenement->est_publie;

        // Dates (format pour <input type="date">)
        $this->date_debut = $evenement->date_debut
            ? $evenement->date_debut->format('Y-m-d')
            : null;

        $this->date_fin = $evenement->date_fin
            ? $evenement->date_fin->format('Y-m-d')
            : null;

        // Heures (format pour <input type="time">)
        $this->heure_debut = $evenement->heure_debut
            ? Carbon::parse($evenement->heure_debut)->format('H:i')
            : null;

        $this->heure_fin = $evenement->heure_fin
            ? Carbon::parse($evenement->heure_fin)->format('H:i')
            : null;

        // Document PDF associé déjà lié à l’événement
        $this->document_media_id = $evenement->document_id; // colonne document_id sur la table evenements
    }

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

            // Upload image (nouvelle cover éventuelle)
            'cover_image'      => ['nullable','image','max:2048'], // 2 Mo

            // PDF choisi dans la médiathèque
            'document_media_id' => ['nullable','exists:media,id'],
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

        // Si pas de recherche (ou 1 seul caractère) → 10 plus récents
        if ($search === '' || strlen($search) < 2) {
            return $query
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }

        // Recherche active (≥ 2 caractères)
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

        // Si le PDF sélectionné ne fait pas partie de la liste courante, on le charge ponctuellement
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

        // 1) Gestion de l'image de couverture
        // - Si une nouvelle image est uploadée, on la store et on remplace
        // - Sinon, on garde l'ancienne image_id
        $imageMediaId = $this->evenement->image_id;

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
            ]);

            $imageMediaId = $mediaImg->id;
        }

        // 2) Document PDF associé (pris dans la médiathèque)
        $pdfMediaId = $this->document_media_id ?: null;

        // 3) Mise à jour du modèle
        $this->evenement->update([
            'titre'            => $this->titre,
            'description'      => $this->description,
            'date_debut'       => $this->date_debut,
            'heure_debut'      => $this->heure_debut,
            'date_fin'         => $this->date_fin,
            'heure_fin'        => $this->heure_fin,
            'lieu'             => $this->lieu,
            'type'             => $this->type,
            'est_important'    => (bool) $this->est_important,
            'lien_inscription' => $this->lien_inscription,
            'est_publie'       => (bool) $this->est_publie,
            'image_id'         => $imageMediaId,
            'document_id'      => $pdfMediaId,
        ]);

        session()->flash('success', 'Événement mis à jour avec succès.');

        return redirect()->route('admin.evenements.index');
    }

    public function render()
    {
        // $this->evenement est disponible dans la vue (pour est_archive, est_termine, etc.)
        return view('livewire.admin.evenements.evenement-edit');
    }
}