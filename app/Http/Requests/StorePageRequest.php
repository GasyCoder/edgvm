<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:pages,slug'],
            'contenu' => ['nullable', 'string'],
            'visible' => ['boolean'],
            'ordre' => ['integer', 'min:0'],
            'attach_to_menu' => ['boolean'],
            'menu_id' => ['nullable', 'required_if:attach_to_menu,true', 'exists:menus,id'],
            'menu_label' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required' => 'Le titre est obligatoire.',
            'slug.required' => 'Le slug est obligatoire.',
            'slug.unique' => 'Ce slug existe deja.',
            'menu_id.required_if' => 'Veuillez choisir un menu.',
            'menu_id.exists' => 'Menu invalide.',
        ];
    }
}
