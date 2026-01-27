<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNewsletterSubscriberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', Rule::unique('newsletter_subscribers', 'email')],
            'nom' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:doctorant,encadrant,autre'],
            'actif' => ['boolean'],
        ];
    }
}
