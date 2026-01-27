<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MediaUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'files' => ['required', 'array'],
            'files.*' => ['required', 'file', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'files.required' => 'Veuillez selectionner au moins un fichier.',
            'files.*.file' => 'Le fichier doit etre valide.',
            'files.*.max' => 'Chaque fichier ne doit pas depasser 10 MB.',
        ];
    }
}
