<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaiementRequest extends FormRequest
{
    public function authorize(): bool
    {
        // L'accès est déjà restreint par le middleware can:finances.access.
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'niveau' => ['required', Rule::in(['D1', 'D2', 'D3'])],
            'annee_universitaire' => ['required', 'string', 'max:20'],
            'montant_du' => ['required', 'numeric', 'min:0'],
            'montant_paye' => ['required', 'numeric', 'min:0'],
            'date_paiement' => ['nullable', 'date'],
            'mode' => ['nullable', 'string', 'max:40'],
            'reference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'justificatif' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'niveau.required' => 'Le niveau (D1, D2 ou D3) est obligatoire.',
            'annee_universitaire.required' => "L'année universitaire est obligatoire.",
            'montant_du.required' => 'Le montant dû est obligatoire.',
            'montant_paye.required' => 'Le montant payé est obligatoire.',
            'justificatif.mimes' => 'Le justificatif doit être un PDF ou une image (jpg, png).',
            'justificatif.max' => 'Le justificatif ne doit pas dépasser 5 Mo.',
        ];
    }
}
