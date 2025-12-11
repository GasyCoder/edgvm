<?php

namespace App\Notifications;

use App\Models\Evenement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EvenementCreeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Evenement $evenement)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $evenement = $this->evenement;

        // Page liste des événements (tu as déjà cette route)
        $urlEvenements = route('evenements.index');

        return (new MailMessage)
            ->subject('Nouvel événement EDGVM : ' . $evenement->titre)
            ->view('emails.newsletter.evenement-cree', [
                'evenement'     => $evenement,
                'notifiable'    => $notifiable,
                'urlEvenements' => $urlEvenements,
            ]);
    }
}
