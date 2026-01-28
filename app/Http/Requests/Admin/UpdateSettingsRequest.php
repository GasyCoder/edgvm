<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'message_direction_doctorants' => ['required', 'integer', 'min:0'],
            'message_direction_equipes' => ['required', 'integer', 'min:0'],
            'message_direction_theses' => ['required', 'integer', 'min:0'],
        ];
    }
}
