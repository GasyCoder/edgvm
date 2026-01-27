<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'maintenance_enabled' => ['boolean'],
            'maintenance_message' => ['nullable', 'string', 'max:500'],
        ];
    }
}
