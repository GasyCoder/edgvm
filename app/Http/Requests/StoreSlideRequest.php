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
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'new_image' => ['nullable', 'image', 'max:5120'],
            'lien_cta' => ['nullable', 'string', 'max:255'],
            'texte_cta' => ['nullable', 'string', 'max:255'],
            'ordre' => ['required', 'integer', 'min:1'],
            'visible' => ['boolean'],
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
