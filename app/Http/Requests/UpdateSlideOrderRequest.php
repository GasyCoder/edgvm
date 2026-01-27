<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSlideOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'slides' => ['required', 'array'],
            'slides.*.id' => ['required', 'exists:slides,id'],
            'slides.*.ordre' => ['required', 'integer', 'min:1'],
        ];
    }
}
