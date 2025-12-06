<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    /**
     * S'abonner à la newsletter (frontend - on fera ça plus tard)
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email',
            'nom' => 'nullable|string|max:255',
            'type' => 'required|in:doctorant,encadrant,autre',
        ]);

        NewsletterSubscriber::create([
            'email' => $request->email,
            'nom' => $request->nom,
            'type' => $request->type,
            'actif' => true,
        ]);

        return back()->with('success', 'Merci ! Vous êtes maintenant abonné(e) à notre newsletter.');
    }

    /**
     * Se désabonner via le lien dans l'email
     */
    public function unsubscribe($token)
    {
        $subscriber = NewsletterSubscriber::where('token', $token)->first();

        if (!$subscriber) {
            return view('newsletter.unsubscribe-error');
        }

        // Désactiver l'abonné + enregistrer la date
        $subscriber->update([
            'actif' => false,
            'desabonne_le' => now(), // ← AJOUTÉ
        ]);

        return view('newsletter.unsubscribe-success', [
            'subscriber' => $subscriber,
        ]);
    }

    /**
     * Réabonner (optionnel)
     */
    public function resubscribe($token)
    {
        $subscriber = NewsletterSubscriber::where('token', $token)->first();

        if (!$subscriber) {
            return view('newsletter.unsubscribe-error');
        }

        // Réactiver + supprimer la date de désinscription
        $subscriber->update([
            'actif' => true,
            'desabonne_le' => null, // ← AJOUTÉ (réinitialiser)
        ]);

        return view('newsletter.resubscribe-success', [
            'subscriber' => $subscriber,
        ]);
    }
}