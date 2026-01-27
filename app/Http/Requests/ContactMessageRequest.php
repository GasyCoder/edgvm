<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactMessageRequest extends FormRequest
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
            'fullName' => 'required|string|min:3|max:100',
            'phone' => 'nullable|string|max:30',
            'email' => 'required|email|max:120',
            'subject' => 'required|in:candidature,information_programme,evenement,partenariat,technique,autre',
            'messageContent' => 'required|string|min:10|max:250',
            'captchaAnswer' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'fullName.required' => 'Le nom complet est obligatoire.',
            'email.required' => 'L’adresse e-mail est obligatoire.',
            'email.email' => 'Veuillez saisir une adresse e-mail valide.',
            'subject.required' => 'Veuillez sélectionner un objet.',
            'subject.in' => 'L’objet sélectionné n’est pas valide.',
            'messageContent.required' => 'Le message est obligatoire.',
            'messageContent.min' => 'Le message doit contenir au moins 10 caractères.',
            'messageContent.max' => 'Le message ne peut pas dépasser 250 caractères.',
            'captchaAnswer.required' => 'Merci de compléter la vérification anti-robot.',
            'captchaAnswer.integer' => 'La réponse à la vérification doit être un nombre.',
        ];
    }
}
