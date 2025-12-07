<?php

namespace App\Livewire\Admin\Theses;

use App\Models\These;
use App\Models\Doctorant;
use App\Models\Encadrant;
use App\Models\EAD;
use App\Models\Media; // ✅ IMPORTANT
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class TheseCreate extends Component
{
    use WithFileUploads;

    // Champs du formulaire
    public $doctorant_id;
    public $directeur_id;
    public $codirecteur_id;
    public $ead_id;
    public $statut = 'en_cours';
    public $date_debut;
    public $date_fin_prevue;
    public $sujet_these = '';

    // ✅ Champs pour la partie « publication »
    public $universite_soutenance;
    public $date_publication;
    public $resume_these;
    public $mots_cles;

    // ✅ Gestion du média
    public $media;           // upload direct (input file)
    public $media_id = null; // id du Media sélectionné (bibliothèque ou upload)
    public $showMediaLibrary = false;

    protected function rules()
    {
        return [
            'doctorant_id'    => 'required|exists:doctorants,id',
            'sujet_these'     => 'required|string|min:10',
            'directeur_id'    => 'required|exists:encadrants,id',
            'codirecteur_id'  => 'nullable|exists:encadrants,id|different:directeur_id',
            'ead_id'          => 'nullable|exists:eads,id',
            'statut'          => 'required|in:en_cours,soutenue,abandonnee,suspendue',
            'date_debut'      => 'nullable|date',
            'date_fin_prevue' => 'nullable|date|after_or_equal:date_debut',

            'universite_soutenance' => 'nullable|string|max:255',
            'date_publication'      => 'nullable|date',
            'resume_these'          => 'nullable|string',
            'mots_cles'             => 'nullable|string',

            'media'                 => 'nullable|file|mimes:pdf|max:10240',
            'media_id'              => 'nullable|exists:media,id',
        ];
    }

    protected $messages = [
        'doctorant_id.required'   => 'Veuillez sélectionner un doctorant.',
        'doctorant_id.exists'     => 'Le doctorant sélectionné est invalide.',
        'sujet_these.required'    => 'Le sujet de thèse est obligatoire.',
        'sujet_these.min'         => 'Le sujet de thèse doit contenir au moins 10 caractères.',
        'directeur_id.required'   => 'Le directeur de thèse est obligatoire.',
        'directeur_id.exists'     => 'Le directeur sélectionné est invalide.',
        'codirecteur_id.exists'   => 'Le co-directeur sélectionné est invalide.',
        'codirecteur_id.different'=> 'Le co-directeur doit être différent du directeur.',
        'ead_id.exists'           => 'L’EAD sélectionnée est invalide.',
        'statut.required'         => 'Le statut est obligatoire.',
        'statut.in'               => 'Le statut sélectionné est invalide.',
        'date_fin_prevue.after_or_equal' => 'La date de fin prévue doit être postérieure ou égale à la date de début.',

        'media.mimes'             => 'Le fichier doit être un PDF.',
        'media.max'               => 'Le fichier PDF ne doit pas dépasser 10 Mo.',
    ];

    // 🚀 Ouvrir / fermer la médiathèque
    public function openMediaLibrary()
    {
        $this->showMediaLibrary = true;
    }

    public function closeMediaLibrary()
    {
        $this->showMediaLibrary = false;
    }

    // Quand on clique sur « Utiliser ce fichier »
    public function selectMedia($mediaId)
    {
        $this->media_id = $mediaId;
        $this->showMediaLibrary = false;
        session()->flash('success', 'Fichier PDF sélectionné depuis la médiathèque.');
    }

    public function save()
    {
        $this->validate();

        try {
            DB::transaction(function () {

                // 🔹 1. Si un PDF est uploadé depuis le PC → on crée un Media et on prend son id
                if ($this->media) {
                    $path = $this->media->store('documents', 'public');

                    $media = Media::create([
                        'nom_original' => $this->media->getClientOriginalName(),
                        'nom_fichier'  => basename($path),
                        'chemin'       => $path,
                        'type'         => 'document',         // ✅ important pour le filtre
                        'taille_bytes' => $this->media->getSize(),
                        'mime_type'    => $this->media->getMimeType(),
                        'uploader_id'  => auth()->id(),
                    ]);

                    $this->media_id = $media->id;
                }

                // 🔹 2. Création de la thèse
                $these = These::create([
                    'doctorant_id'         => $this->doctorant_id,
                    'ead_id'               => $this->ead_id,
                    'universite_soutenance'=> $this->universite_soutenance,
                    'statut'               => $this->statut,
                    'date_debut'           => $this->date_debut,
                    'date_prevue_fin'      => $this->date_fin_prevue,
                    'date_publication'     => $this->date_publication,
                    'sujet_these'          => $this->sujet_these,
                    'resume_these'         => $this->resume_these,
                    'mots_cles'            => $this->mots_cles,
                    'media_id'             => $this->media_id,
                ]);

                // 🔹 3. Attacher le directeur
                $these->encadrants()->attach($this->directeur_id, [
                    'role' => 'directeur',
                ]);

                // 🔹 4. Attacher le co-directeur si présent
                if ($this->codirecteur_id) {
                    $these->encadrants()->attach($this->codirecteur_id, [
                        'role' => 'codirecteur',
                    ]);
                }
            });

            session()->flash('success', 'Thèse créée avec succès !');
            return redirect()->route('admin.theses.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    public function render()
    {
        // Doctorants éligibles
        $doctorants = Doctorant::with('user')
            ->whereDoesntHave('theses', function ($query) {
                $query->where('statut', 'en_cours');
            })
            ->orWhereHas('theses', function ($query) {
                $query->whereIn('statut', ['soutenue', 'abandonnee']);
            })
            ->get();

        $encadrants = Encadrant::with('user')
            ->whereHas('user')
            ->orderBy('grade', 'desc')
            ->get();

        $eads = EAD::orderBy('nom')->get();

        // ✅ Récupérer VRAIMENT les PDF de la table `media`
        $mediaDocuments = Media::where('type', 'document')
            ->where(function ($q) {
                $q->where('mime_type', 'like', 'application/pdf%')
                  ->orWhere('chemin', 'like', '%.pdf');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.admin.theses.these-create', [
            'doctorants'      => $doctorants,
            'encadrants'      => $encadrants,
            'eads'            => $eads,
            'mediaDocuments'  => $mediaDocuments, // ✅ envoyé à la vue
        ]);
    }
}
