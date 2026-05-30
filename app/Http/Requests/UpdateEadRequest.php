<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEadRequest extends FormRequest
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
            'sigle' => ['nullable', 'string', 'max:50'],
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
            'responsable_id.exists' => 'Le responsable sélectionné est invalide.',
        ];
    }
}
