<?php

namespace App\Imports;

use App\Models\NewsletterSubscriber;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class NewsletterSubscribersImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $now = now();

        // 1) Normaliser emails du fichier
        $emails = $rows->pluck('email')
            ->filter()
            ->map(fn ($e) => strtolower(trim((string) $e)))
            ->filter(fn ($e) => $e !== '')
            ->unique()
            ->values();

        if ($emails->isEmpty()) {
            return;
        }

        // 2) Récupérer tokens existants pour NE PAS les écraser
        $existingTokens = NewsletterSubscriber::whereIn('email', $emails)
            ->pluck('token', 'email'); // ['email' => 'token']

        // 3) Construire dataset pour upsert
        $data = $rows->map(function ($row) use ($existingTokens, $now) {
            $email = strtolower(trim((string) ($row['email'] ?? '')));
            if ($email === '') {
                return null;
            }

            $type = strtolower(trim((string) ($row['type'] ?? 'autre')));
            if (!in_array($type, ['doctorant', 'encadrant', 'autre'], true)) {
                $type = 'autre';
            }

            $actifRaw = $row['actif'] ?? 1;
            $actif = (string) $actifRaw;
            // accepte: 1/0, true/false, oui/non
            $actif = in_array(strtolower($actif), ['1','true','oui','yes'], true);

            $abonneLe = $this->parseExcelDate($row['abonne_le'] ?? null) ?? $now;
            $desabonneLe = $this->parseExcelDate($row['desabonne_le'] ?? null);

            return [
                'email'        => $email,
                'nom'          => !empty($row['nom']) ? trim((string) $row['nom']) : null,
                'type'         => $type,
                'actif'        => $actif,
                'abonne_le'    => $abonneLe,
                'desabonne_le' => $desabonneLe,
                // token: garder ancien si existe, sinon générer
                'token'        => $existingTokens[$email] ?? Str::random(32),
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        })->filter()->values()->all();

        // 4) UPSERT
        // IMPORTANT: on n’update pas "token" et "created_at"
        NewsletterSubscriber::upsert(
            $data,
            ['email'],
            ['nom', 'type', 'actif', 'abonne_le', 'desabonne_le', 'updated_at']
        );
    }

    private function parseExcelDate($value): ?Carbon
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Excel numeric date
        if (is_numeric($value)) {
            try {
                return Carbon::instance(ExcelDate::excelToDateTimeObject($value));
            } catch (\Throwable) {
                return null;
            }
        }

        // string date
        try {
            return Carbon::parse((string) $value);
        } catch (\Throwable) {
            return null;
        }
    }
}
