<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTheseRequest extends FormRequest
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
            'doctorant_id' => ['required', 'exists:doctorants,id'],
            'sujet_these' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'specialite_id' => ['nullable', 'exists:specialites,id'],
            'ead_id' => ['nullable', 'exists:eads,id'],
            'universite_soutenance' => ['nullable', 'string', 'max:255'],
            'date_debut' => ['nullable', 'date'],
            'date_prevue_fin' => ['nullable', 'date', 'after_or_equal:date_debut'],
            'date_publication' => ['nullable', 'date'],
            'statut' => ['required', 'in:en_cours,soutenue,abandonnee,suspendue,preparation,redaction'],
            'media_id' => ['nullable', 'exists:media,id'],
            'slug' => ['required_with:these_file', 'string', 'max:150', 'alpha_dash'],
            'these_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'resume_these' => ['nullable', 'string'],
            'mots_cles' => ['nullable', 'string', 'max:255'],
            'encadrants' => ['nullable', 'array'],
            'encadrants.*.id' => ['required_with:encadrants', 'exists:encadrants,id'],
            'encadrants.*.role' => ['nullable', 'in:directeur,codirecteur'],
        ];
    }

    public function messages(): array
    {
        return [
            'doctorant_id.required' => 'Le doctorant est obligatoire.',
            'sujet_these.required' => 'Le sujet est obligatoire.',
            'statut.required' => 'Le statut est obligatoire.',
            'slug.required_with' => 'Le slug est obligatoire pour uploader un fichier.',
            'slug.alpha_dash' => 'Le slug ne doit contenir que des lettres, chiffres, tirets ou underscores.',
            'these_file.mimes' => 'Le fichier doit etre un PDF.',
            'these_file.max' => 'Le fichier ne doit pas depasser 10 MB.',
        ];
    }
}
