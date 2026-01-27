<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnnonceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => ['required', 'string', 'max:190'],
            'contenu_html' => ['nullable', 'string'],
            'cible' => ['required', 'in:doctorant,encadrant,both'],
            'media_id' => ['nullable', 'integer', 'exists:media,id'],
            'est_publie' => ['boolean'],
            'envoyer_email' => ['boolean'],
            'email_cible' => ['nullable', 'required_if:envoyer_email,true', 'in:doctorant,encadrant,both'],
        ];
    }
}
