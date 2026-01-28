<?php

namespace App\Http\Controllers\Admin;

use App\Exports\NewsletterSubscribersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportNewsletterSubscribersRequest;
use App\Http\Requests\StoreNewsletterSubscriberRequest;
use App\Http\Requests\StoreNewsletterSubscribersBulkRequest;
use App\Http\Requests\UpdateNewsletterSubscriberRequest;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

class NewsletterSubscriberController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim($request->string('search')->toString());
        $filterType = $request->string('type')->toString() ?: 'all';
        $filterActif = $request->string('actif')->toString() ?: 'all';

        $query = NewsletterSubscriber::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                    ->orWhere('nom', 'like', "%{$search}%");
            });
        }

        if ($filterType !== 'all') {
            $query->where('type', $filterType);
        }

        if ($filterActif === 'actif') {
            $query->where('actif', true);
        } elseif ($filterActif === 'inactif') {
            $query->where('actif', false);
        }

        $subscribers = $query->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString()
            ->through(fn (NewsletterSubscriber $subscriber) => [
                'id' => $subscriber->id,
                'email' => $subscriber->email,
                'nom' => $subscriber->nom,
                'type' => $subscriber->type,
                'actif' => $subscriber->actif,
                'created_at' => $subscriber->created_at?->format('d/m/Y'),
            ]);

        $stats = [
            'total' => NewsletterSubscriber::count(),
            'actifs' => NewsletterSubscriber::where('actif', true)->count(),
            'inactifs' => NewsletterSubscriber::where('actif', false)->count(),
            'doctorants' => NewsletterSubscriber::where('type', 'doctorant')->count(),
            'encadrants' => NewsletterSubscriber::where('type', 'encadrant')->count(),
        ];

        return Inertia::render('Admin/Newsletter/Index', [
            'filters' => [
                'search' => $search,
                'type' => $filterType,
                'actif' => $filterActif,
            ],
            'stats' => $stats,
            'subscribers' => $subscribers,
        ]);
    }

    public function store(StoreNewsletterSubscriberRequest $request): RedirectResponse
    {
        NewsletterSubscriber::create([
            'email' => strtolower(trim($request->validated('email'))),
            'nom' => $request->validated('nom') ?: null,
            'type' => $request->validated('type'),
            'actif' => $request->boolean('actif'),
        ]);

        return redirect()->route('admin.newsletter.subscribers')
            ->with('success', 'Abonne cree avec succes.');
    }

    public function update(UpdateNewsletterSubscriberRequest $request, NewsletterSubscriber $subscriber): RedirectResponse
    {
        $subscriber->update([
            'email' => strtolower(trim($request->validated('email'))),
            'nom' => $request->validated('nom') ?: null,
            'type' => $request->validated('type'),
            'actif' => $request->boolean('actif'),
        ]);

        return redirect()->route('admin.newsletter.subscribers')
            ->with('success', 'Abonne mis a jour avec succes.');
    }

    public function toggle(NewsletterSubscriber $subscriber): RedirectResponse
    {
        $new = ! $subscriber->actif;

        $subscriber->update([
            'actif' => $new,
            'desabonne_le' => $new ? null : now(),
            'abonne_le' => $subscriber->abonne_le ?? now(),
        ]);

        return redirect()->route('admin.newsletter.subscribers')
            ->with('success', 'Statut mis a jour.');
    }

    public function destroy(NewsletterSubscriber $subscriber): RedirectResponse
    {
        $subscriber->delete();

        return redirect()->route('admin.newsletter.subscribers')
            ->with('success', 'Abonne supprime.');
    }

    public function export(Request $request)
    {
        $search = trim($request->string('search')->toString());
        $filterType = $request->string('type')->toString() ?: 'all';
        $filterActif = $request->string('actif')->toString() ?: 'all';

        $fileName = 'newsletter_subscribers_'.now()->format('Ymd_His').'.xlsx';

        return Excel::download(
            new NewsletterSubscribersExport($search, $filterType, $filterActif),
            $fileName
        );
    }

    public function import(ImportNewsletterSubscribersRequest $request): RedirectResponse
    {
        Excel::import(new \App\Imports\NewsletterSubscribersImport, $request->file('importFile'));

        return redirect()->route('admin.newsletter.subscribers')
            ->with('success', 'Import termine avec succes.');
    }

    public function bulkStore(StoreNewsletterSubscribersBulkRequest $request): RedirectResponse
    {
        $emailsRaw = (string) $request->validated('emails');
        $type = $request->validated('type') ?? 'autre';
        $actif = $request->boolean('actif', true);

        $emails = collect(preg_split('/[\s,;]+/', $emailsRaw, -1, PREG_SPLIT_NO_EMPTY))
            ->map(fn ($email) => strtolower(trim((string) $email)))
            ->filter()
            ->unique()
            ->values();

        if ($emails->isEmpty()) {
            return back()->withErrors([
                'emails' => 'Veuillez saisir au moins un email valide.',
            ]);
        }

        $invalidEmails = $emails->filter(fn ($email) => ! filter_var($email, FILTER_VALIDATE_EMAIL));
        if ($invalidEmails->isNotEmpty()) {
            return back()->withErrors([
                'emails' => 'Emails invalides: '.mb_strimwidth($invalidEmails->join(', '), 0, 160, '...'),
            ]);
        }

        $existing = NewsletterSubscriber::query()
            ->whereIn('email', $emails)
            ->get(['email', 'abonne_le', 'desabonne_le', 'token'])
            ->keyBy('email');

        $now = now();
        $data = $emails->map(function (string $email) use ($existing, $type, $actif, $now) {
            $current = $existing->get($email);

            return [
                'email' => $email,
                'nom' => null,
                'type' => $type,
                'actif' => $actif,
                'abonne_le' => $current?->abonne_le ?? $now,
                'desabonne_le' => $actif ? null : ($current?->desabonne_le ?? $now),
                'token' => $current?->token ?? Str::random(32),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->all();

        NewsletterSubscriber::upsert(
            $data,
            ['email'],
            ['nom', 'type', 'actif', 'abonne_le', 'desabonne_le', 'updated_at']
        );

        return redirect()->route('admin.newsletter.subscribers')
            ->with('success', 'Abonnes ajoutes avec succes.');
    }
}
