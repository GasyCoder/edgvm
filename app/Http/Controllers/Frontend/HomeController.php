<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Actualite;
use App\Models\EAD;
use App\Models\Evenement;
use App\Models\MessageDirection;
use App\Models\Partenaire;
use App\Models\Slider;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        $slider = Slider::with([
            'slides' => function ($query) {
                $query->visible()
                    ->with('image')
                    ->orderBy('ordre');
            },
        ])
            ->visible()
            ->byPosition('homepage')
            ->first();

        $messageDirection = MessageDirection::where('visible', true)
            ->latest()
            ->first();

        $stats = [
            'doctorants' => $messageDirection?->nb_doctorants ?? 0,
            'equipes' => $messageDirection?->nb_equipes ?? 0,
            'theses_soutenues' => $messageDirection?->nb_theses ?? 0,
            'encadrants' => 30,
            'publications' => 150,
            'hdr_soutenues' => 2,
        ];

        $eads = EAD::with(['responsable', 'specialites'])
            ->withCount(['specialites', 'theses'])
            ->active()
            ->take(4)
            ->get();

        $actualites = Actualite::with(['category', 'image', 'tags'])
            ->withSlug()
            ->published()
            ->orderBy('est_important', 'desc')
            ->orderBy('date_publication', 'desc')
            ->limit(3)
            ->get();

        $partenaires = Partenaire::where('visible', true)
            ->orderBy('ordre')
            ->get();

        $evenementsFuturs = Evenement::futurs()
            ->where('est_publie', true)
            ->where('est_archive', false)
            ->latest('date_debut')
            ->limit(3)
            ->get();

        // Transform slider data for Vue
        $sliderData = null;
        if ($slider && $slider->slides->count() > 0) {
            $sliderData = [
                'id' => $slider->id,
                'slides' => $slider->slides->map(function ($slide, $index) {
                    return [
                        'id' => $slide->id,
                        'index' => $index,
                        'badge_texte' => $slide->badge_texte,
                        'titre_ligne1' => $slide->titre_ligne1,
                        'titre_highlight' => $slide->titre_highlight,
                        'titre_ligne2' => $slide->titre_ligne2,
                        'titre_complet' => $slide->titre_complet,
                        'description' => $slide->description,
                        'couleur_fond' => $slide->couleur_fond,
                        'lien_cta' => $slide->lien_cta,
                        'texte_cta' => $slide->texte_cta,
                        'image_url' => $slide->image?->url,
                    ];
                })->toArray(),
            ];
        }

        // Transform actualites for Vue
        $actualitesData = $actualites->map(function ($actualite) {
            return [
                'id' => $actualite->id,
                'titre' => $actualite->titre,
                'slug' => $actualite->slug,
                'extrait' => $actualite->extrait,
                'contenu' => $actualite->contenu,
                'date_publication' => $actualite->date_publication?->format('Y-m-d'),
                'date_formatted' => $actualite->date_publication?->translatedFormat('d M Y'),
                'est_important' => $actualite->est_important,
                'vues' => $actualite->vues ?? 0,
                'image_url' => $actualite->image?->url,
                'category' => $actualite->category ? [
                    'id' => $actualite->category->id,
                    'nom' => $actualite->category->nom,
                    'slug' => $actualite->category->slug,
                    'couleur' => $actualite->category->couleur,
                ] : null,
                'tags' => $actualite->tags->map(fn ($tag) => [
                    'id' => $tag->id,
                    'nom' => $tag->nom,
                    'slug' => $tag->slug,
                ])->toArray(),
            ];
        })->toArray();

        // Transform evenements for Vue
        $evenementsData = $evenementsFuturs->map(function ($evenement) {
            return [
                'id' => $evenement->id,
                'titre' => $evenement->titre,
                'slug' => $evenement->slug,
                'lieu' => $evenement->lieu,
                'type' => $evenement->type,
                'type_texte' => $evenement->type_texte ?? ucfirst($evenement->type ?? 'evenement'),
                'type_classe' => $evenement->type_classe ?? 'bg-blue-100 text-blue-700',
                'est_important' => $evenement->est_important ?? false,
                'date_debut' => $evenement->date_debut?->format('Y-m-d'),
                'jour' => $evenement->date_debut?->format('d'),
                'mois' => $evenement->date_debut?->translatedFormat('M'),
                'date_debut_formatted' => $evenement->date_debut?->translatedFormat('d M Y'),
                'heure_debut' => $evenement->heure_debut,
                'heure_debut_aff' => $evenement->heure_debut ? substr($evenement->heure_debut, 0, 5) : null,
                'lien_inscription' => $evenement->lien_inscription ?? null,
            ];
        })->toArray();

        // Transform EADs for Vue
        $eadsData = $eads->map(function ($ead) {
            return [
                'id' => $ead->id,
                'nom' => $ead->nom,
                'sigle' => $ead->sigle,
                'slug' => $ead->slug,
                'description' => $ead->description,
                'domaine' => $ead->domaine ?? null,
                'specialites_count' => $ead->specialites_count,
                'theses_count' => $ead->theses_count,
                'responsable' => $ead->responsable ? [
                    'nom_complet' => $ead->responsable->name,
                ] : null,
            ];
        })->toArray();

        // Transform partenaires for Vue
        $partenairesData = $partenaires->map(function ($partenaire) {
            return [
                'id' => $partenaire->id,
                'nom' => $partenaire->nom,
                'logo_url' => $partenaire->logo_url,
                'url' => $partenaire->url ?? $partenaire->site_web ?? null,
                'description' => $partenaire->description ?? null,
            ];
        })->toArray();

        // Transform message direction for Vue
        $messageDirectionData = $messageDirection ? [
            'id' => $messageDirection->id,
            'nom' => $messageDirection->nom,
            'fonction' => $messageDirection->fonction ?? null,
            'institution' => $messageDirection->institution ?? null,
            'citation' => $messageDirection->citation ?? null,
            'message_intro' => $messageDirection->message_intro ?? null,
            'email' => $messageDirection->email ?? null,
            'telephone' => $messageDirection->telephone ?? null,
            'photo_path' => $messageDirection->photo_path,
            'photo_url' => $messageDirection->photo_path ? asset('storage/'.$messageDirection->photo_path) : null,
        ] : null;

        return Inertia::render('Frontend/Home', [
            'slider' => $sliderData,
            'stats' => $stats,
            'eads' => $eadsData,
            'actualites' => $actualitesData,
            'evenementsFuturs' => $evenementsData,
            'partenaires' => $partenairesData,
            'messageDirection' => $messageDirectionData,
        ]);
    }
}
