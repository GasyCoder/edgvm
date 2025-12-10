<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $titre
 * @property string|null $slug
 * @property string $contenu
 * @property int|null $auteur_id
 * @property int|null $category_id
 * @property int|null $image_id
 * @property \Illuminate\Support\Carbon|null $date_publication
 * @property bool $visible
 * @property bool $est_important
 * @property bool $notifier_abonnes
 * @property \Illuminate\Support\Carbon|null $notification_envoyee_le
 * @property int $vues
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $auteur
 * @property-read \App\Models\Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Media> $galerie
 * @property-read int|null $galerie_count
 * @property-read mixed $vues_formatted
 * @property-read \App\Models\Media|null $image
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite byCategory($categoryId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite byTag($tagId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite important()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite ordered()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite published()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite visible()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite whereAuteurId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite whereContenu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite whereDatePublication($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite whereEstImportant($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite whereNotificationEnvoyeeLe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite whereNotifierAbonnes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite whereTitre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite whereVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite whereVues($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actualite withoutTrashed()
 */
	class Actualite extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nom
 * @property string $slug
 * @property string|null $description
 * @property string $couleur
 * @property bool $visible
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Actualite> $actualites
 * @property-read int|null $actualites_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category visible()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCouleur($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereVisible($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property string $matricule
 * @property \Illuminate\Support\Carbon|null $date_naissance
 * @property string|null $lieu_naissance
 * @property string|null $phone
 * @property string|null $adresse
 * @property string $niveau
 * @property \Illuminate\Support\Carbon $date_inscription
 * @property string $statut
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\EAD|null $ead
 * @property-read mixed $codirecteur_actuel
 * @property-read mixed $directeur_actuel
 * @property-read mixed $email
 * @property-read mixed $name
 * @property-read mixed $sujet_these_actuel
 * @property-read mixed $these_principale
 * @property-read \App\Models\These|null $theseActive
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\These> $theses
 * @property-read int|null $theses_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctorant avecTheseEnCours()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctorant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctorant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctorant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctorant sansTheseEnCours()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctorant whereAdresse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctorant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctorant whereDateInscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctorant whereDateNaissance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctorant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctorant whereLieuNaissance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctorant whereMatricule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctorant whereNiveau($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctorant wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctorant whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctorant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctorant whereUserId($value)
 */
	class Doctorant extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nom
 * @property string $slug
 * @property string|null $description
 * @property int|null $responsable_id
 * @property string|null $domaine
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $doctorats_count
 * @property-read mixed $theses_soutenues_count
 * @property-read \App\Models\Encadrant|null $responsable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Specialite> $specialites
 * @property-read int|null $specialites_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\These> $theses
 * @property-read int|null $theses_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EAD active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EAD newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EAD newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EAD query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EAD whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EAD whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EAD whereDomaine($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EAD whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EAD whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EAD whereResponsableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EAD whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EAD whereUpdatedAt($value)
 */
	class EAD extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string|null $grade
 * @property string|null $specialite
 * @property string|null $phone
 * @property string|null $bureau
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EAD> $eads
 * @property-read int|null $eads_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Soutenance> $soutenances
 * @property-read int|null $soutenances_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\These> $theses
 * @property-read int|null $theses_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Encadrant byGrade($grade)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Encadrant bySpecialite($specialite)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Encadrant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Encadrant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Encadrant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Encadrant whereBureau($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Encadrant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Encadrant whereGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Encadrant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Encadrant wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Encadrant whereSpecialite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Encadrant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Encadrant whereUserId($value)
 */
	class Encadrant extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $titre
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $date_debut
 * @property \Illuminate\Support\Carbon|null $heure_debut
 * @property \Illuminate\Support\Carbon|null $date_fin
 * @property \Illuminate\Support\Carbon|null $heure_fin
 * @property string|null $lieu
 * @property string $type
 * @property bool $est_important
 * @property string|null $lien_inscription
 * @property bool $est_publie
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $date_fr
 * @property-read string|null $heure_debut_aff
 * @property-read string $jour
 * @property-read string $mois
 * @property-read string $periode_aff
 * @property-read string $type_classe
 * @property-read string $type_texte
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evenement futurs()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evenement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evenement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evenement passes()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evenement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evenement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evenement whereDateDebut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evenement whereDateFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evenement whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evenement whereEstImportant($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evenement whereEstPublie($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evenement whereHeureDebut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evenement whereHeureFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evenement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evenement whereLienInscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evenement whereLieu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evenement whereTitre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evenement whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evenement whereUpdatedAt($value)
 */
	class Evenement extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $doctorant_id
 * @property int $specialite_id
 * @property string $statut
 * @property \Illuminate\Support\Carbon $date_depot
 * @property \Illuminate\Support\Carbon|null $date_validation
 * @property string|null $commentaires
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Doctorant $doctorant
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InscriptionDocument> $documents
 * @property-read int|null $documents_count
 * @property-read \App\Models\Specialite $specialite
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inscription enAttente()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inscription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inscription rejetee()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inscription validee()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inscription whereCommentaires($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inscription whereDateDepot($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inscription whereDateValidation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inscription whereDoctorantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inscription whereSpecialiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inscription whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inscription whereUpdatedAt($value)
 */
	class Inscription extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $inscription_id
 * @property string $type_document
 * @property int $media_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Inscription $inscription
 * @property-read \App\Models\Media $media
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InscriptionDocument newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InscriptionDocument newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InscriptionDocument query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InscriptionDocument whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InscriptionDocument whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InscriptionDocument whereInscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InscriptionDocument whereMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InscriptionDocument whereTypeDocument($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InscriptionDocument whereUpdatedAt($value)
 */
	class InscriptionDocument extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nom
 * @property string|null $grade
 * @property string|null $universite
 * @property string|null $email
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\These> $theses
 * @property-read int|null $theses_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jury newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jury newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jury query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jury whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jury whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jury whereGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jury whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jury whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jury wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jury whereUniversite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jury whereUpdatedAt($value)
 */
	class Jury extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nom_original
 * @property string $nom_fichier
 * @property string $chemin
 * @property string $type
 * @property int|null $taille_bytes
 * @property string|null $mime_type
 * @property int|null $uploader_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $display_name
 * @property-read mixed $url
 * @property-read \App\Models\User|null $uploader
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereChemin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereNomFichier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereNomOriginal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereTailleBytes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereUploaderId($value)
 */
	class Media extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nom
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MenuItem> $allItems
 * @property-read int|null $all_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MenuItem> $items
 * @property-read int|null $items_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu whereUpdatedAt($value)
 */
	class Menu extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $menu_id
 * @property string $label
 * @property string|null $url
 * @property int|null $page_id
 * @property int|null $parent_id
 * @property int $ordre
 * @property bool $visible
 * @property string|null $icone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, MenuItem> $enfants
 * @property-read int|null $enfants_count
 * @property-read string $resolved_url
 * @property-read \App\Models\Menu $menu
 * @property-read \App\Models\Page|null $page
 * @property-read MenuItem|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem visible()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereIcone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereMenuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereOrdre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereVisible($value)
 */
	class MenuItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nom
 * @property string|null $fonction
 * @property string|null $institution
 * @property string|null $telephone
 * @property string|null $email
 * @property string|null $photo_path
 * @property string|null $citation
 * @property string|null $message_intro
 * @property int $nb_doctorants
 * @property int $nb_equipes
 * @property int $nb_theses
 * @property bool $visible
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MessageDirection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MessageDirection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MessageDirection query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MessageDirection whereCitation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MessageDirection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MessageDirection whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MessageDirection whereFonction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MessageDirection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MessageDirection whereInstitution($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MessageDirection whereMessageIntro($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MessageDirection whereNbDoctorants($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MessageDirection whereNbEquipes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MessageDirection whereNbTheses($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MessageDirection whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MessageDirection wherePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MessageDirection whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MessageDirection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MessageDirection whereVisible($value)
 */
	class MessageDirection extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $email
 * @property string|null $nom
 * @property string $type
 * @property bool $actif
 * @property \Illuminate\Support\Carbon|null $desabonne_le
 * @property string $token
 * @property \Illuminate\Support\Carbon $abonne_le
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscriber actif()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscriber byType($type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscriber newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscriber newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscriber query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscriber whereAbonneLe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscriber whereActif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscriber whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscriber whereDesabonneLe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscriber whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscriber whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscriber whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscriber whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscriber whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscriber whereUpdatedAt($value)
 */
	class NewsletterSubscriber extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $titre
 * @property string $slug
 * @property string|null $contenu
 * @property int|null $auteur_id
 * @property bool $visible
 * @property int $ordre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $auteur
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PageSection> $sections
 * @property-read int|null $sections_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page ordered()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page visible()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereAuteurId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereContenu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereOrdre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereTitre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereVisible($value)
 */
	class Page extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $page_id
 * @property string|null $titre
 * @property string|null $contenu
 * @property int|null $image_id
 * @property int $ordre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Media|null $image
 * @property-read \App\Models\Page $page
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PageSection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PageSection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PageSection query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PageSection whereContenu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PageSection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PageSection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PageSection whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PageSection whereOrdre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PageSection wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PageSection whereTitre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PageSection whereUpdatedAt($value)
 */
	class PageSection extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nom
 * @property string|null $description
 * @property string|null $logo_path
 * @property int|null $logo_id
 * @property string|null $url
 * @property int $ordre
 * @property bool $visible
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string|null $logo_url
 * @property-read \App\Models\Media|null $logo
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partenaire newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partenaire newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partenaire ordered()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partenaire query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partenaire visible()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partenaire whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partenaire whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partenaire whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partenaire whereLogoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partenaire whereLogoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partenaire whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partenaire whereOrdre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partenaire whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partenaire whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partenaire whereVisible($value)
 */
	class Partenaire extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $these_id
 * @property string $titre
 * @property string|null $auteurs
 * @property string|null $revue
 * @property \Illuminate\Support\Carbon|null $date_publication
 * @property string|null $url_publication
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\These $these
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereAuteurs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereDatePublication($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereRevue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereTheseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereTitre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereUrlPublication($value)
 */
	class Publication extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $cle
 * @property string|null $valeur
 * @property string $type
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteConfiguration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteConfiguration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteConfiguration query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteConfiguration whereCle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteConfiguration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteConfiguration whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteConfiguration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteConfiguration whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteConfiguration whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteConfiguration whereValeur($value)
 */
	class SiteConfiguration extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $slider_id
 * @property string|null $titre_ligne1
 * @property string $titre_highlight
 * @property string|null $titre_ligne2
 * @property string|null $description
 * @property int $image_id
 * @property string|null $lien_cta
 * @property string|null $texte_cta
 * @property int $ordre
 * @property bool $visible
 * @property string|null $badge_texte
 * @property string|null $badge_icon
 * @property string $couleur_fond
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $actualite_id
 * @property-read \App\Models\Actualite|null $actualite
 * @property-read mixed $cta_url
 * @property-read mixed $titre_complet
 * @property-read \App\Models\Media $image
 * @property-read \App\Models\Slider $slider
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slide newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slide newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slide query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slide visible()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slide whereActualiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slide whereBadgeIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slide whereBadgeTexte($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slide whereCouleurFond($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slide whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slide whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slide whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slide whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slide whereLienCta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slide whereOrdre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slide whereSliderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slide whereTexteCta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slide whereTitreHighlight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slide whereTitreLigne1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slide whereTitreLigne2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slide whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slide whereVisible($value)
 */
	class Slide extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nom
 * @property string|null $position
 * @property bool $visible
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Slide> $slides
 * @property-read int|null $slides_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider byPosition($position)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider visible()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider whereVisible($value)
 */
	class Slider extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $these_id
 * @property \Illuminate\Support\Carbon|null $date_soutenance
 * @property \Illuminate\Support\Carbon|null $heure_soutenance
 * @property string|null $lieu
 * @property string|null $resultat
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Encadrant> $jury
 * @property-read int|null $jury_count
 * @property-read \App\Models\These $these
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soutenance admis()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soutenance ajourne()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soutenance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soutenance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soutenance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soutenance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soutenance whereDateSoutenance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soutenance whereHeureSoutenance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soutenance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soutenance whereLieu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soutenance whereResultat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soutenance whereTheseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Soutenance whereUpdatedAt($value)
 */
	class Soutenance extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nom
 * @property string $slug
 * @property string|null $description
 * @property int $ead_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\EAD $ead
 * @property-read mixed $theses_en_cours
 * @property-read mixed $theses_soutenues
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Inscription> $inscriptions
 * @property-read int|null $inscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\These> $theses
 * @property-read int|null $theses_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialite active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialite query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialite whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialite whereEadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialite whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialite whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialite whereUpdatedAt($value)
 */
	class Specialite extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nom
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Actualite> $actualites
 * @property-read int|null $actualites_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereUpdatedAt($value)
 */
	class Tag extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $doctorant_id
 * @property int|null $specialite_id
 * @property string $sujet_these
 * @property string|null $description
 * @property int $ead_id
 * @property string|null $universite_soutenance
 * @property \Illuminate\Support\Carbon|null $date_debut
 * @property \Illuminate\Support\Carbon|null $date_prevue_fin
 * @property \Illuminate\Support\Carbon|null $date_publication
 * @property string $statut
 * @property int|null $media_id
 * @property string|null $resume_these
 * @property string|null $mots_cles
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Doctorant $doctorant
 * @property-read \App\Models\EAD $ead
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Encadrant> $encadrants
 * @property-read int|null $encadrants_count
 * @property-read \App\Models\Media|null $fichier
 * @property-read mixed $fichier_pdf_url
 * @property-read string $statut_badge_classes
 * @property-read string $statut_label
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Jury> $jurys
 * @property-read int|null $jurys_count
 * @property-read \App\Models\Specialite|null $specialite
 * @method static \Illuminate\Database\Eloquent\Builder<static>|These enCours()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|These enPreparation()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|These newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|These newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|These query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|These soutendue()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|These whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|These whereDateDebut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|These whereDatePrevueFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|These whereDatePublication($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|These whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|These whereDoctorantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|These whereEadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|These whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|These whereMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|These whereMotsCles($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|These whereResumeThese($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|These whereSpecialiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|These whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|These whereSujetThese($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|These whereUniversiteSoutenance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|These whereUpdatedAt($value)
 */
	class These extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property string $role
 * @property bool $active
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Actualite> $actualites
 * @property-read int|null $actualites_count
 * @property-read \App\Models\Doctorant|null $doctorant
 * @property-read \App\Models\Encadrant|null $encadrant
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Page> $pages
 * @property-read int|null $pages_count
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

