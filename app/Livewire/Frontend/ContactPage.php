<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ContactMessageForSecretary;
use App\Notifications\ContactMessageConfirmation;

#[Title('Contact | EDGVM')]
class ContactPage extends Component
{
    public string $fullName = '';
    public ?string $phone = '';
    public string $email = '';
    public string $subject = '';
    public string $messageContent = '';
    public string $captchaAnswer = '';

    public int $captchaA = 0;
    public int $captchaB = 0;

    public array $subjectOptions = [
        'candidature'            => 'Candidature doctorale',
        'information_programme'  => 'Informations sur les parcours et équipes',
        'evenement'              => 'Événements / séminaires / soutenances',
        'partenariat'            => 'Partenariat / collaboration',
        'technique'              => 'Problème technique sur la plateforme',
        'autre'                  => 'Autre demande',
    ];

    public function mount(): void
    {
        if ($this->subject === '') {
            $this->subject = 'information_programme';
        }

        $this->generateCaptcha();
    }

    protected function rules(): array
    {
        return [
            'fullName'       => 'required|string|min:3|max:100',
            'phone'          => 'nullable|string|max:30',
            'email'          => 'required|email|max:120',
            'subject'        => 'required|in:' . implode(',', array_keys($this->subjectOptions)),
            'messageContent' => 'required|string|min:10|max:250',
            'captchaAnswer'  => 'required|integer',
        ];
    }

    protected function messages(): array
    {
        return [
            'fullName.required'       => 'Le nom complet est obligatoire.',
            'email.required'          => 'L’adresse e-mail est obligatoire.',
            'email.email'             => 'Veuillez saisir une adresse e-mail valide.',
            'subject.required'        => 'Veuillez sélectionner un objet.',
            'subject.in'              => 'L’objet sélectionné n’est pas valide.',
            'messageContent.required' => 'Le message est obligatoire.',
            'messageContent.min'      => 'Le message doit contenir au moins 10 caractères.',
            'messageContent.max'      => 'Le message ne peut pas dépasser 250 caractères.',
            'captchaAnswer.required'  => 'Merci de compléter la vérification anti-robot.',
            'captchaAnswer.integer'   => 'La réponse à la vérification doit être un nombre.',
        ];
    }

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function generateCaptcha(): void
    {
        $this->captchaA = random_int(1, 9);
        $this->captchaB = random_int(1, 9);
        $this->captchaAnswer = '';
    }

    public function submit(): void
    {
        $this->validate();

        // Vérification captcha
        if ((int) $this->captchaAnswer !== $this->captchaA + $this->captchaB) {
            $this->addError('captchaAnswer', 'La vérification anti-robot est incorrecte.');
            $this->generateCaptcha();
            return;
        }

        // On prépare les données AVANT reset
        $data = [
            'full_name'      => $this->fullName,
            'phone'          => $this->phone,
            'email'          => $this->email,
            'subject_key'    => $this->subject,
            'subject_label'  => $this->subjectOptions[$this->subject] ?? $this->subject,
            'message'        => $this->messageContent,
        ];

        try {
            // 1) Notification vers le secrétariat EDGVM
            Notification::route('mail', 'secretaire@edgvm.mg')
                ->notify(new ContactMessageForSecretary($data));

            // 2) Notification de confirmation vers l'utilisateur
            Notification::route('mail', $data['email'])
                ->notify(new ContactMessageConfirmation($data));

            Log::info('Nouveau message de contact EDGVM envoyé avec succès', $data);
        } catch (\Throwable $e) {
            Log::error('Erreur lors de l’envoi des notifications de contact EDGVM', [
                'data'      => $data,
                'exception' => $e->getMessage(),
            ]);
        }

        // Reset du formulaire
        $this->reset([
            'fullName',
            'phone',
            'email',
            'subject',
            'messageContent',
            'captchaAnswer',
        ]);

        $this->subject = 'information_programme';
        $this->generateCaptcha();

        session()->flash('success', 'Merci, votre message a bien été envoyé. Nous vous répondrons dans les meilleurs délais.');
    }

    public function render()
    {
        return view('livewire.frontend.contact-page')->layout('layouts.frontend');
    }
}
