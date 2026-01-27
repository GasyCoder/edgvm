<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMessageDirectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'fonction' => ['nullable', 'string', 'max:255'],
            'institution' => ['nullable', 'string', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'citation' => ['nullable', 'string'],
            'message_intro' => ['nullable', 'string'],
            'nb_doctorants' => ['required', 'integer', 'min:0'],
            'nb_equipes' => ['required', 'integer', 'min:0'],
            'nb_theses' => ['required', 'integer', 'min:0'],
            'visible' => ['boolean'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
