<?php

namespace App\Jobs;

use App\Models\Actualite;
use App\Models\NewsletterSubscriber;
use App\Mail\ActualiteNotificationMailable;
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

    public Actualite $actualite;

    public function __construct(Actualite $actualite)
    {
        $this->actualite = $actualite;
    }

    public function handle(): void
    {
        Log::info("Actualité #{$this->actualite->id} : préparation envoi newsletter");

        NewsletterSubscriber::actif()
            ->whereNotNull('email')
            ->select(['id','email','nom','type'])
            ->orderBy('id')
            ->chunkById(500, function ($subscribers) {
                foreach ($subscribers as $subscriber) {
                    Mail::to($subscriber->email)->queue(
                        new ActualiteNotificationMailable($this->actualite, $subscriber)
                    );
                }
            });

        // Ici tu “queues” tous les emails. Tu peux marquer “queued”/“planifiée”
        $this->actualite->update([
            'notification_envoyee_le' => now(), // si tu l'utilises comme “planifiée”
        ]);

        Log::info("Actualité #{$this->actualite->id} : envoi mis en file (queue)");
    }
}
