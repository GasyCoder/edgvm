<?php

namespace App\Jobs;

use App\Models\Actualite;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendActualiteNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $actualite;

    public function __construct(Actualite $actualite)
    {
        $this->actualite = $actualite;
    }

    public function handle(): void
    {
        // Récupérer tous les abonnés actifs
        $subscribers = NewsletterSubscriber::actif()->get();

        Log::info("Envoi de notification pour l'actualité #{$this->actualite->id} à {$subscribers->count()} abonnés");

        foreach ($subscribers as $subscriber) {
            try {
                Mail::send('emails.actualite-notification', [
                    'actualite' => $this->actualite,
                    'subscriber' => $subscriber,
                ], function ($message) use ($subscriber) {
                    $message->to($subscriber->email, $subscriber->nom)
                            ->subject('Nouvelle actualité : ' . $this->actualite->titre);
                });

                Log::info("Email envoyé à {$subscriber->email}");
            } catch (\Exception $e) {
                Log::error("Erreur envoi email à {$subscriber->email}: " . $e->getMessage());
            }
        }

        // Marquer comme envoyée
        $this->actualite->update([
            'notification_envoyee_le' => now(),
        ]);

        Log::info("Notification terminée pour l'actualité #{$this->actualite->id}");
    }
}