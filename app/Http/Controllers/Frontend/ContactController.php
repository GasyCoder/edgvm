<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactMessageRequest;
use App\Mail\ContactConfirmationMailable;
use App\Mail\ContactToSecretaryMailable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function __invoke(Request $request): Response
    {
        return $this->index($request);
    }

    public function index(Request $request): Response
    {
        $subjectOptions = $this->subjectOptions();
        $captcha = $this->ensureCaptcha($request);

        return Inertia::render('Frontend/Contact', [
            'subjectOptions' => $subjectOptions,
            'captcha' => $captcha,
        ]);
    }

    public function store(ContactMessageRequest $request): RedirectResponse
    {
        $captcha = $request->session()->get('contact_captcha', []);
        $answer = (int) $request->input('captchaAnswer');

        if (! $this->captchaIsValid($captcha, $answer)) {
            $this->refreshCaptcha($request);

            return back()->withErrors([
                'captchaAnswer' => 'La vérification anti-robot est incorrecte.',
            ])->withInput();
        }

        $subjectOptions = $this->subjectOptions();

        $data = [
            'full_name' => $request->input('fullName'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'subject_key' => $request->input('subject'),
            'subject_label' => $subjectOptions[$request->input('subject')] ?? $request->input('subject'),
            'message' => $request->input('messageContent'),
        ];

        try {
            Mail::to('secretaire@edgvm.mg')->queue(
                new ContactToSecretaryMailable($data)
            );

            Mail::to($data['email'])->queue(
                new ContactConfirmationMailable($data)
            );

            Log::info('Contact EDGVM: emails queued', ['email' => $data['email'], 'subject' => $data['subject_key']]);
        } catch (\Throwable $e) {
            Log::error('Contact EDGVM: erreur envoi mail', [
                'email' => $data['email'],
                'exception' => $e->getMessage(),
            ]);

            return back()->with('error', "Une erreur s'est produite lors de l'envoi. Veuillez réessayer.");
        }

        $this->refreshCaptcha($request);

        return back()->with('success', 'Merci, votre message a bien été envoyé. Nous vous répondrons dans les meilleurs délais.');
    }

    private function subjectOptions(): array
    {
        return [
            'candidature' => 'Candidature doctorale',
            'information_programme' => 'Informations sur les parcours et équipes',
            'evenement' => 'Événements / séminaires / soutenances',
            'partenariat' => 'Partenariat / collaboration',
            'technique' => 'Problème technique sur la plateforme',
            'autre' => 'Autre demande',
        ];
    }

    private function ensureCaptcha(Request $request): array
    {
        if ($request->boolean('refresh') || ! $request->session()->has('contact_captcha')) {
            return $this->refreshCaptcha($request);
        }

        return $request->session()->get('contact_captcha');
    }

    private function refreshCaptcha(Request $request): array
    {
        $captcha = [
            'a' => random_int(1, 9),
            'b' => random_int(1, 9),
        ];

        $request->session()->put('contact_captcha', $captcha);

        return $captcha;
    }

    private function captchaIsValid(array $captcha, int $answer): bool
    {
        if (! isset($captcha['a'], $captcha['b'])) {
            return false;
        }

        return $answer === ($captcha['a'] + $captcha['b']);
    }
}
