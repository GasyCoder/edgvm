<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSlideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'new_image' => ['nullable', 'image', 'max:5120'],
            'actualite_id' => ['nullable', 'integer', 'exists:actualites,id'],
            'texte_cta' => ['nullable', 'string', 'max:255'],
            'ordre' => ['required', 'integer', 'min:1'],
            'visible' => ['boolean'],
            'couleur_texte_titre' => ['nullable', 'string', 'max:7', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'couleur_cta' => ['nullable', 'string', 'max:7', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required' => 'Le titre est obligatoire.',
            'new_image.image' => 'Le fichier doit etre une image.',
            'new_image.max' => "L'image ne doit pas depasser 5 Mo.",
            'ordre.required' => "L'ordre est obligatoire.",
        ];
    }
}
