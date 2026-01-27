<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvenementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'date_debut' => ['required', 'date'],
            'heure_debut' => ['nullable', 'date_format:H:i'],
            'date_fin' => ['nullable', 'date', 'after_or_equal:date_debut'],
            'heure_fin' => ['nullable', 'date_format:H:i'],
            'lieu' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:soutenance,seminaire,conference,atelier,autre'],
            'est_important' => ['boolean'],
            'lien_inscription' => ['nullable', 'url', 'max:255'],
            'est_publie' => ['boolean'],
            'cover_image' => ['nullable', 'image', 'max:2048'],
            'document_media_id' => ['nullable', 'exists:media,id'],
            'notify_all' => ['boolean'],
            'notify_encadrants' => ['boolean'],
            'notify_doctorants' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required' => 'Le titre est obligatoire.',
            'date_debut.required' => 'La date de debut est obligatoire.',
            'date_fin.after_or_equal' => 'La date de fin doit etre apres la date de debut.',
            'type.required' => 'Le type est obligatoire.',
            'lien_inscription.url' => "Le lien d'inscription doit etre une URL valide.",
            'cover_image.image' => "L'image de couverture doit etre une image.",
            'document_media_id.exists' => 'Le document selectionne est invalide.',
        ];
    }
}
