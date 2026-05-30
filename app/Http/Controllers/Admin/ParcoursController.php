<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctorant;
use App\Services\ParcoursService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ParcoursController extends Controller
{
    public function __construct(private ParcoursService $parcours) {}

    public function store(Request $request, Doctorant $doctorant): RedirectResponse
    {
        $validated = $request->validate([
            'action' => ['required', 'in:promouvoir,ajourner,valider,diplomer,abandonner'],
            'decision' => ['nullable', 'string', 'max:2000'],
        ]);

        $decision = $validated['decision'] ?? null;

        $message = match ($validated['action']) {
            'promouvoir' => $this->run(fn () => $this->parcours->promouvoir($doctorant, $decision), 'Doctorant admis au niveau suivant.'),
            'ajourner' => $this->run(fn () => $this->parcours->ajourner($doctorant, $decision), 'Doctorant ajourné (redoublement).'),
            'valider' => $this->run(fn () => $this->parcours->validerPourSoutenance($doctorant, $decision), 'Thèse validée — en attente de soutenance.'),
            'diplomer' => $this->run(fn () => $this->parcours->diplomer($doctorant), 'Soutenance enregistrée — doctorant diplômé.'),
            'abandonner' => $this->run(fn () => $this->parcours->abandonner($doctorant, $decision), 'Abandon enregistré.'),
        };

        return back()->with('success', $message);
    }

    private function run(callable $callback, string $message): string
    {
        $callback();

        return $message;
    }
}
