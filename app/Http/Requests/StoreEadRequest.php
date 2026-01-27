<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:eads,slug'],
            'description' => ['nullable', 'string'],
            'responsable_id' => ['nullable', 'exists:encadrants,id'],
            'encadrants' => ['nullable', 'array'],
            'encadrants.*' => ['nullable', 'integer', 'exists:encadrants,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom est obligatoire.',
            'slug.required' => 'Le slug est obligatoire.',
            'slug.unique' => 'Ce slug existe deja.',
            'responsable_id.exists' => 'Le responsable selectionne est invalide.',
        ];
    }
}
