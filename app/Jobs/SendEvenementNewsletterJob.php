<?php

namespace App\Jobs;

use App\Mail\EvenementCreeMailable;
use App\Models\Evenement;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEvenementNewsletterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $evenementId;

    /** @var array<string> */
    public array $types; // ['doctorant','encadrant'] ou vide => all

    public int $chunkSize = 300;

    public $tries = 3;
    public $backoff = [10, 60, 180]; // secondes

    /**
     * @param  array<string>  $types
     */
    public function __construct(int $evenementId, array $types = [])
    {
        $this->evenementId = $evenementId;
        $this->types = $types;
    }

    public function handle(): void
    {
        $evenement = Evenement::find($this->evenementId);

        if (! $evenement) {
            return;
        }

        $query = NewsletterSubscriber::actif();

        if (! empty($this->types)) {
            $query->whereIn('type', $this->types);
        }

        // Chunk pour éviter RAM/timeout
        $query->orderBy('id')->chunkById($this->chunkSize, function ($subscribers) use ($evenement) {
            foreach ($subscribers as $subscriber) {
                if (empty($subscriber->email)) {
                    continue;
                }

                // Chaque mail est queue automatiquement via ShouldQueue dans le Mailable
                Mail::to($subscriber->email)->queue(
                    new EvenementCreeMailable($evenement, $subscriber)
                );
            }
        });
    }
}