<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PaiementRequest;
use App\Mail\DoctorantFinanceReportMailable;
use App\Models\Doctorant;
use App\Models\Paiement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PaiementController extends Controller
{
    public function store(PaiementRequest $request, Doctorant $doctorant): RedirectResponse
    {
        $data = collect($request->validated())->except('justificatif')->all();

        if ($request->hasFile('justificatif')) {
            $data['justificatif_path'] = $request->file('justificatif')->store('justificatifs', 'public');
        }

        Paiement::updateOrCreate(
            [
                'doctorant_id' => $doctorant->id,
                'niveau' => $data['niveau'],
                'annee_universitaire' => $data['annee_universitaire'],
            ],
            $data,
        );

        return back()->with('success', 'Le paiement a été enregistré.');
    }

    public function destroy(Doctorant $doctorant, Paiement $paiement): RedirectResponse
    {
        abort_unless($paiement->doctorant_id === $doctorant->id, 404);

        if ($paiement->justificatif_path) {
            Storage::disk('public')->delete($paiement->justificatif_path);
        }

        $paiement->delete();

        return back()->with('success', 'Le paiement a été supprimé.');
    }

    public function notify(Doctorant $doctorant): RedirectResponse
    {
        $email = $doctorant->getRawOriginal('email');

        if (! $email) {
            return back()->with('error', "Ce doctorant n'a pas d'adresse e-mail enregistrée.");
        }

        $paiements = $doctorant->paiements()
            ->orderBy('niveau')
            ->orderBy('annee_universitaire')
            ->get();

        $totalDu = (float) $paiements->sum('montant_du');
        $totalPaye = (float) $paiements->sum('montant_paye');
        $totalReste = max(0, $totalDu - $totalPaye);

        $statutLabels = ['paye' => 'Soldé', 'partiel' => 'Partiel', 'impaye' => 'Impayé'];

        Mail::to($email)->send(new DoctorantFinanceReportMailable([
            'nom' => trim($doctorant->nom.' '.$doctorant->prenoms),
            'total_du' => $this->ariary($totalDu),
            'total_paye' => $this->ariary($totalPaye),
            'total_reste' => $this->ariary($totalReste),
            'reste_brut' => $totalReste,
            'paiements' => $paiements->map(fn (Paiement $p) => [
                'niveau' => $p->niveau,
                'annee_universitaire' => $p->annee_universitaire,
                'montant_du' => $this->ariary((float) $p->montant_du),
                'montant_paye' => $this->ariary((float) $p->montant_paye),
                'reste' => $this->ariary((float) $p->reste),
                'statut' => $statutLabels[$p->statut] ?? $p->statut,
            ])->all(),
        ]));

        return back()->with('success', "Le rapport financier a été envoyé à {$email}.");
    }

    private function ariary(float $value): string
    {
        return number_format($value, 0, ',', ' ').' Ar';
    }
}
