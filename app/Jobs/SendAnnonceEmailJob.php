<?php

namespace App\Jobs;

use App\Mail\AnnonceAcademiqueMailable;
use App\Models\Annonce;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendAnnonceEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $annonceId) {}

    public function handle(): void
    {
        $annonce = Annonce::query()->findOrFail($this->annonceId);

        // Sécurité : éviter double-envoi si déjà envoyé
        // (si tu veux autoriser un renvoi, retire ce guard)
        if ($annonce->email_envoye_at) {
            return;
        }

        if (empty($annonce->email_cible)) {
            return;
        }

        $types = match ($annonce->email_cible) {
            'doctorant' => ['doctorant'],
            'encadrant' => ['encadrant'],
            default     => ['doctorant', 'encadrant'],
        };

        NewsletterSubscriber::query()
            ->actif()
            ->whereIn('type', $types)
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->orderBy('id')
            ->chunkById(200, function ($subscribers) use ($annonce) {
                foreach ($subscribers as $subscriber) {
                    // Mail "queue" (pas send) : respecte ShouldQueue du Mailable
                    Mail::to($subscriber->email)
                        ->queue(new AnnonceAcademiqueMailable($annonce, $subscriber));
                }
            });

        $annonce->update([
            'email_envoye_at' => now(),
        ]);
    }
}
