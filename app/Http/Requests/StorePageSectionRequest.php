<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePageSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => ['nullable', 'string', 'max:255'],
            'contenu' => ['nullable', 'string'],
            'ordre' => ['integer', 'min:0'],
            'image_id' => ['nullable', 'exists:media,id'],
        ];
    }
}
