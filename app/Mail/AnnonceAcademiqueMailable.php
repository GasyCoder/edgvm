<?php

namespace App\Mail;

use App\Models\Annonce;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AnnonceAcademiqueMailable extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Annonce $annonce;
    public ?NewsletterSubscriber $subscriber;

    public function __construct(Annonce $annonce, ?NewsletterSubscriber $subscriber = null)
    {
        $this->annonce = $annonce;
        $this->subscriber = $subscriber;
    }

    public function build(): self
    {
        // Optionnel : si tu crées une page frontend de détails
        $urlAnnonce = null; // route('annonces.show', $this->annonce) par exemple
        $urlUnsubscribe = $this->subscriber
            ? route('newsletter.unsubscribe', $this->subscriber->token)
            : null;

        return $this->subject('Annonce académique EDGVM : ' . $this->annonce->titre)
            ->view('emails.annonce.annonce-template')
            ->with([
                'annonce' => $this->annonce,
                'notifiable' => $this->subscriber,
                'urlAnnonce' => $urlAnnonce,
                'urlUnsubscribe' => $urlUnsubscribe,
                'mediaUrl' => $this->annonce->media_url,
                'mediaName' => $this->annonce->media_name,
            ]);
    }
}
