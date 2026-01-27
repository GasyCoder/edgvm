<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTheseJuryRequest extends FormRequest
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
            'jurys' => ['nullable', 'array'],
            'jurys.*.id' => ['required_with:jurys', 'exists:jurys,id'],
            'jurys.*.role' => ['nullable', 'in:president,rapporteur,examinateur,invite'],
            'jurys.*.ordre' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
