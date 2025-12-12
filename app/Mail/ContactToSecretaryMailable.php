<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactToSecretaryMailable extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build(): self
    {
        return $this->subject('Nouveau message via le formulaire de contact EDGVM')
            // important: permettre de répondre directement au visiteur
            ->replyTo($this->data['email'], $this->data['full_name'])
            ->view('emails.contact.secretary')
            ->with(['data' => $this->data]);
    }
}
