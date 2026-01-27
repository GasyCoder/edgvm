<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActualiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => ['required', 'string', 'max:255'],
            'contenu' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'selectedTags' => ['nullable', 'array'],
            'selectedTags.*' => ['exists:tags,id'],
            'image_id' => ['nullable', 'exists:media,id'],
            'galerieImages' => ['nullable', 'array'],
            'galerieImages.*' => ['exists:media,id'],
            'date_publication' => ['nullable', 'date'],
            'visible' => ['boolean'],
            'est_important' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required' => 'Le titre est obligatoire.',
            'contenu.required' => 'Le contenu est obligatoire.',
            'category_id.required' => 'La categorie est obligatoire.',
        ];
    }
}
