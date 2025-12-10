<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ContactMessageForSecretary extends Notification implements ShouldQueue
{
    use Queueable;

    public array $data;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouveau message via le formulaire de contact EDGVM')
            ->greeting('Bonjour,')
            ->line('Un nouveau message a été envoyé depuis le site web de l’École Doctorale EDGVM.')
            ->line('Nom : ' . $this->data['full_name'])
            ->when(!empty($this->data['phone']), function (MailMessage $mail) {
                $mail->line('Téléphone : ' . $this->data['phone']);
            })
            ->line('E-mail : ' . $this->data['email'])
            ->line('Objet : ' . $this->data['subject_label'])
            ->line('Message :')
            ->line($this->data['message'])
            ->line('')
            ->line('— Notification automatique générée par la plateforme EDGVM.');
    }
}
