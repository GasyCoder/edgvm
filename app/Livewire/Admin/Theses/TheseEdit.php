<?php

namespace App\Livewire\Admin\Theses;

use App\Models\These;
use App\Models\Encadrant;
use App\Models\EAD;
use App\Models\Media;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class TheseEdit extends Component
{
    use WithFileUploads;

    public These $these;

    // Champs principaux
    public $directeur_id;
    public $codirecteur_id;
    public $ead_id;
    public $statut;
    public $date_debut;
    public $date_fin_prevue;

    // Métadonnées de la thèse
    public $sujet_these;
    public $universite_soutenance;
    public $date_publication;
    public $resume_these;
    public $mots_cles;

    // Gestion du fichier PDF
    public $media;          // upload depuis le PC
    public $media_id;       // ID media choisi (upload ou médiathèque)
    public $showMediaLibrary = false;

    protected function rules()
    {
        return [
            'sujet_these'           => 'required|string|min:10',
            'directeur_id'          => 'required|exists:encadrants,id',
            'codirecteur_id'        => 'nullable|exists:encadrants,id|different:directeur_id',
            'ead_id'                => 'nullable|exists:eads,id',
            'statut'                => 'required|in:en_cours,soutenue,abandonnee,suspendue',
            'date_debut'            => 'nullable|date',
            'date_fin_prevue'       => 'nullable|date|after_or_equal:date_debut',
            'universite_soutenance' => 'nullable|string|max:255',
            'date_publication'      => 'nullable|date',
            'resume_these'          => 'nullable|string',
            'mots_cles'             => 'nullable|string|max:255',
            'media'                 => 'nullable|file|mimes:pdf|max:20480', // 20 Mo
        ];
    }

    protected $messages = [
        'sujet_these.required'       => 'Le sujet de thèse est obligatoire.',
        'sujet_these.min'            => 'Le sujet de thèse doit contenir au moins 10 caractères.',
        'directeur_id.required'      => 'Le directeur de thèse est obligatoire.',
        'directeur_id.exists'        => 'Le directeur sélectionné est invalide.',
        'codirecteur_id.exists'      => 'Le co-directeur sélectionné est invalide.',
        'codirecteur_id.different'   => 'Le co-directeur doit être différent du directeur.',
        'ead_id.exists'              => 'L’EAD sélectionnée est invalide.',
        'statut.required'            => 'Le statut est obligatoire.',
        'statut.in'                  => 'Le statut sélectionné est invalide.',
        'date_fin_prevue.after_or_equal' => 'La date de fin prévue doit être postérieure ou égale à la date de début.',
        'media.mimes'                => 'Le fichier doit être un PDF.',
        'media.max'                  => 'Le fichier PDF ne doit pas dépasser 20 Mo.',
    ];

    public function mount(These $these)
    {
        $this->these = $these->load(['doctorant.user', 'encadrants', 'ead', 'fichier']);

        // Encadrants
        $directeur = $this->these->encadrants()->wherePivot('role', 'directeur')->first();
        $codirecteur = $this->these->encadrants()->wherePivot('role', 'codirecteur')->first();

        $this->directeur_id = $directeur?->id;
        $this->codirecteur_id = $codirecteur?->id;

        // Champs de la thèse
        $this->ead_id                = $this->these->ead_id;
        $this->statut                = $this->these->statut;
        $this->date_debut            = $this->these->date_debut?->format('Y-m-d');
        $this->date_fin_prevue       = $this->these->date_prevue_fin?->format('Y-m-d');
        $this->sujet_these           = $this->these->sujet_these;
        $this->universite_soutenance = $this->these->universite_soutenance;
        $this->date_publication      = $this->these->date_publication?->format('Y-m-d');
        $this->resume_these          = $this->these->resume_these;
        $this->mots_cles             = $this->these->mots_cles;
        $this->media_id              = $this->these->media_id;
    }

    public function openMediaLibrary()
    {
        $this->showMediaLibrary = true;
    }

    public function closeMediaLibrary()
    {
        $this->showMediaLibrary = false;
    }

    public function selectMedia(int $mediaId)
    {
        $this->media_id = $mediaId;
        $this->media = null;              // on ignore l’upload local si on choisit dans la médiathèque
        $this->showMediaLibrary = false;
    }

    public function save()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                // 1) Gestion du fichier PDF
                $mediaId = $this->media_id;

                if ($this->media) {
                    $path = $this->media->store('theses', 'public');

                    $media = Media::create([
                        'nom_original' => $this->media->getClientOriginalName(),
                        'nom_fichier'  => basename($path),
                        'chemin'       => $path,
                        'type'         => 'document',
                        'taille_bytes' => $this->media->getSize(),
                        'mime_type'    => $this->media->getMimeType(),
                        'uploader_id'  => auth()->id(),
                    ]);

                    $mediaId = $media->id;
                }

                // 2) Mise à jour de la thèse
                $this->these->update([
                    'ead_id'                => $this->ead_id,
                    'statut'                => $this->statut,
                    'date_debut'            => $this->date_debut,
                    'date_prevue_fin'       => $this->date_fin_prevue,
                    'sujet_these'           => $this->sujet_these,
                    'universite_soutenance' => $this->universite_soutenance,
                    'date_publication'      => $this->date_publication,
                    'resume_these'          => $this->resume_these,
                    'mots_cles'             => $this->mots_cles,
                    'media_id'              => $mediaId,
                ]);

                // 3) Encadrants : on reset et on rattache proprement
                $this->these->encadrants()->detach();

                $this->these->encadrants()->attach($this->directeur_id, [
                    'role' => 'directeur',
                ]);

                if ($this->codirecteur_id) {
                    $this->these->encadrants()->attach($this->codirecteur_id, [
                        'role' => 'codirecteur',
                    ]);
                }
            });

            session()->flash('success', 'Thèse modifiée avec succès !');
            return redirect()->route('admin.theses.show', $this->these);

        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la modification : ' . $e->getMessage());
        }
    }

    public function render()
    {
        $encadrants = Encadrant::with('user')
            ->whereHas('user')
            ->orderBy('grade', 'desc')
            ->get();

        $eads = EAD::orderBy('nom')->get();

        $mediaDocuments = Media::where('type', 'document')
            ->where('mime_type', 'application/pdf')
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        return view('livewire.admin.theses.these-edit', [
            'encadrants'      => $encadrants,
            'eads'            => $eads,
            'mediaDocuments'  => $mediaDocuments,
        ]);
    }
}
