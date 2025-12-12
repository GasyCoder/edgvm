<?php

namespace App\Mail;

use App\Models\Actualite;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActualiteNotificationMailable extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Actualite $actualite;
    public NewsletterSubscriber $subscriber;

    public function __construct(Actualite $actualite, NewsletterSubscriber $subscriber)
    {
        $this->actualite = $actualite;
        $this->subscriber = $subscriber;
    }

    public function build(): self
    {
        return $this->subject('Nouvelle actualité : ' . $this->actualite->titre)
            ->view('emails.actualite-notification')
            ->with([
                'actualite' => $this->actualite,
                'subscriber' => $this->subscriber,
            ]);
    }
}