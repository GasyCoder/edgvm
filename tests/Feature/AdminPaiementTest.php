<?php

use App\Mail\DoctorantFinanceReportMailable;
use App\Models\Doctorant;
use App\Models\Paiement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

function makeDoctorant(): Doctorant
{
    return Doctorant::create([
        'matricule' => 'DOC-PAY-'.fake()->unique()->numberBetween(1000, 9999),
        'nom' => 'Rakoto',
        'prenoms' => 'Jean',
        'niveau' => 'D1',
        'statut' => 'actif',
        'date_inscription' => now(),
    ]);
}

it('lets a secretariat record a payment with a justificatif', function (): void {
    Storage::fake('public');

    $secretaire = User::factory()->create([
        'role' => 'secretariat',
        'active' => true,
        'email_verified_at' => now(),
    ]);

    $doctorant = makeDoctorant();

    $this->actingAs($secretaire)
        ->post(route('admin.doctorants.paiements.store', $doctorant->id), [
            'niveau' => 'D1',
            'annee_universitaire' => '2025-2026',
            'montant_du' => 1200000,
            'montant_paye' => 800000,
            'date_paiement' => '2026-01-15',
            'mode' => 'Espèces',
            'justificatif' => UploadedFile::fake()->create('recu.pdf', 100, 'application/pdf'),
        ])
        ->assertRedirect();

    $paiement = Paiement::first();

    expect($paiement)->not->toBeNull();
    expect($paiement->doctorant_id)->toBe($doctorant->id);
    expect((int) $paiement->reste)->toBe(400000);
    expect($paiement->statut)->toBe('partiel');
    expect($paiement->justificatif_path)->not->toBeNull();
    Storage::disk('public')->assertExists($paiement->justificatif_path);
});

it('upserts the payment per niveau and année universitaire', function (): void {
    $secretaire = User::factory()->create([
        'role' => 'secretariat',
        'active' => true,
        'email_verified_at' => now(),
    ]);

    $doctorant = makeDoctorant();

    $payload = [
        'niveau' => 'D1',
        'annee_universitaire' => '2025-2026',
        'montant_du' => 1200000,
        'montant_paye' => 500000,
    ];

    $this->actingAs($secretaire)->post(route('admin.doctorants.paiements.store', $doctorant->id), $payload);
    $this->actingAs($secretaire)->post(route('admin.doctorants.paiements.store', $doctorant->id), [
        ...$payload,
        'montant_paye' => 1200000,
    ]);

    expect(Paiement::count())->toBe(1);
    expect((int) Paiement::first()->montant_paye)->toBe(1200000);
    expect(Paiement::first()->statut)->toBe('paye');
});

it('forbids a doctorant from recording a payment', function (): void {
    $doctorantUser = User::factory()->create([
        'role' => 'doctorant',
        'active' => true,
        'email_verified_at' => now(),
    ]);

    $doctorant = makeDoctorant();

    $this->actingAs($doctorantUser)
        ->post(route('admin.doctorants.paiements.store', $doctorant->id), [
            'niveau' => 'D1',
            'annee_universitaire' => '2025-2026',
            'montant_du' => 1000000,
            'montant_paye' => 1000000,
        ])
        ->assertForbidden();

    expect(Paiement::count())->toBe(0);
});

it('sends the finance report by email to the doctorant', function (): void {
    Mail::fake();

    $secretaire = User::factory()->create([
        'role' => 'secretariat',
        'active' => true,
        'email_verified_at' => now(),
    ]);

    $doctorant = makeDoctorant();
    $doctorant->update(['email' => 'doctorant@example.com']);

    Paiement::create([
        'doctorant_id' => $doctorant->id,
        'niveau' => 'D1',
        'annee_universitaire' => '2025-2026',
        'montant_du' => 700000,
        'montant_paye' => 400000,
    ]);

    $this->actingAs($secretaire)
        ->post(route('admin.doctorants.finances.notify', $doctorant->id))
        ->assertRedirect();

    Mail::assertQueued(DoctorantFinanceReportMailable::class, fn ($mail) => $mail->hasTo('doctorant@example.com'));
});

it('only allows users with records.delete to delete a payment', function (): void {
    $direction = User::factory()->create([
        'role' => 'direction',
        'active' => true,
        'email_verified_at' => now(),
    ]);

    $doctorant = makeDoctorant();
    $paiement = Paiement::create([
        'doctorant_id' => $doctorant->id,
        'niveau' => 'D1',
        'annee_universitaire' => '2025-2026',
        'montant_du' => 1000000,
        'montant_paye' => 1000000,
    ]);

    $this->actingAs($direction)
        ->delete(route('admin.doctorants.paiements.destroy', [$doctorant->id, $paiement->id]))
        ->assertRedirect();

    expect(Paiement::count())->toBe(0);
});
