<?php

namespace App\Mail;

use App\Models\Evenement;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EvenementCreeMailable extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Evenement $evenement;
    public ?NewsletterSubscriber $subscriber;

    public function __construct(Evenement $evenement, ?NewsletterSubscriber $subscriber = null)
    {
        $this->evenement  = $evenement;
        $this->subscriber = $subscriber;
    }

    public function build(): self
    {
        $urlEvenements = route('evenements.index');

        return $this->subject('Nouvel événement EDGVM : ' . $this->evenement->titre)
            ->view('emails.newsletter.evenement-cree')
            ->with([
                'evenement'     => $this->evenement,
                'notifiable'    => $this->subscriber,
                'urlEvenements' => $urlEvenements,
            ]);
    }
}
