<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSlideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre_highlight' => ['required', 'string', 'max:255'],
            'titre_ligne1' => ['nullable', 'string', 'max:255'],
            'titre_ligne2' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'image_id' => ['nullable', 'exists:media,id'],
            'new_image' => ['nullable', 'image', 'max:5120'],
            'lien_cta' => ['nullable', 'string', 'max:255'],
            'actualite_id' => ['nullable', 'exists:actualites,id'],
            'texte_cta' => ['nullable', 'string', 'max:255'],
            'ordre' => ['required', 'integer', 'min:1'],
            'visible' => ['boolean'],
            'badge_texte' => ['nullable', 'string', 'max:255'],
            'badge_icon' => ['nullable', 'string', 'in:university,research,students'],
            'couleur_fond' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'titre_highlight.required' => 'Le titre en surbrillance est obligatoire.',
            'new_image.image' => 'Le fichier doit etre une image.',
            'new_image.max' => "L'image ne doit pas depasser 5 MB.",
            'ordre.required' => "L'ordre est obligatoire.",
        ];
    }
}
