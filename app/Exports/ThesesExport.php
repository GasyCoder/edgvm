<?php

namespace App\Exports;

use App\Models\These;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ThesesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return These::with(['doctorant.user', 'encadrants.user', 'ead'])->get();
    }

    /**
     * En-têtes du fichier Excel
     */
    public function headings(): array
    {
        return [
            'Matricule doctorant',
            'Nom doctorant',
            'Email doctorant',
            'Sujet de thèse',
            'Directeur',
            'Email directeur',
            'Co-directeur',
            'Email co-directeur',
            'EAD',
            'Statut',
            'Date début',
            'Date fin prévue',
            'Date soutenance',
        ];
    }

    /**
     * Mapping des données
     */
    public function map($these): array
    {
        $directeur = $these->encadrants()->wherePivot('role', 'directeur')->first();
        $codirecteur = $these->encadrants()->wherePivot('role', 'codirecteur')->first();

        return [
            $these->doctorant->matricule ?? '',
            $these->doctorant->user?->name ?? 'Pas de compte',
            $these->doctorant->user?->email ?? 'N/A',
            $these->sujet_these ?? '',
            $directeur?->user?->name ?? '',
            $directeur?->user?->email ?? '',
            $codirecteur?->user?->name ?? '',
            $codirecteur?->user?->email ?? '',
            $these->ead?->nom ?? '',
            $these->statut ?? '',
            $these->date_debut?->format('Y-m-d') ?? '',
            $these->date_fin_prevue?->format('Y-m-d') ?? '',
            $these->date_soutenance?->format('Y-m-d') ?? '',
        ];
    }

    /**
     * Styles pour le fichier Excel
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style pour l'en-tête
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'], // Couleur ed-primary
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    /**
     * Largeur des colonnes
     */
    public function columnWidths(): array
    {
        return [
            'A' => 20, // Matricule doctorant
            'B' => 30, // Nom doctorant
            'C' => 35, // Email doctorant
            'D' => 50, // Sujet de thèse
            'E' => 30, // Directeur
            'F' => 35, // Email directeur
            'G' => 30, // Co-directeur
            'H' => 35, // Email co-directeur
            'I' => 25, // EAD
            'J' => 15, // Statut
            'K' => 15, // Date début
            'L' => 15, // Date fin prévue
            'M' => 15, // Date soutenance
        ];
    }
}