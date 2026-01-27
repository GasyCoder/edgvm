<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorantRequest extends FormRequest
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
            'prenoms' => ['nullable', 'string', 'max:255'],
            'genre' => ['required', 'in:homme,femme'],
            'email' => ['required', 'email', 'max:255', 'unique:doctorants,email'],
            'ead_id' => ['nullable', 'exists:eads,id'],
            'matricule' => ['required', 'string', 'max:255', 'unique:doctorants,matricule'],
            'niveau' => ['required', 'in:D1,D2,D3'],
            'phone' => ['nullable', 'string', 'max:255'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'date_inscription' => ['required', 'date'],
            'date_naissance' => ['nullable', 'date'],
            'lieu_naissance' => ['nullable', 'string', 'max:255'],
            'statut' => ['required', 'in:actif,diplome,suspendu,abandonne'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom est obligatoire.',
            'genre.required' => 'Le genre est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit etre valide.',
            'email.unique' => 'Cet email existe deja.',
            'matricule.required' => 'Le matricule est obligatoire.',
            'matricule.unique' => 'Ce matricule existe deja.',
            'niveau.required' => 'Le niveau est obligatoire.',
            'date_inscription.required' => 'La date d\'inscription est obligatoire.',
            'statut.required' => 'Le statut est obligatoire.',
        ];
    }
}
