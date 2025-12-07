<?php

namespace App\Exports;

use App\Models\Encadrant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EncadrantsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Encadrant::with(['user'])->get();
    }

    /**
     * En-têtes du fichier Excel
     */
    public function headings(): array
    {
        return [
            'Nom complet',
            'Email',
            'Grade',
            'Spécialité',
            'Téléphone',
            'Bureau',
            'A un compte',
        ];
    }

    /**
     * Mapping des données
     */
    public function map($encadrant): array
    {
        return [
            $encadrant->user?->name ?? 'Pas de compte',
            $encadrant->user?->email ?? 'N/A',
            $encadrant->grade ?? '',
            $encadrant->specialite ?? '',
            $encadrant->phone ?? '',
            $encadrant->bureau ?? '',
            $encadrant->user ? 'Oui' : 'Non',
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
            'A' => 30, // Nom complet
            'B' => 35, // Email
            'C' => 25, // Grade
            'D' => 30, // Spécialité
            'E' => 20, // Téléphone
            'F' => 25, // Bureau
            'G' => 15, // A un compte
        ];
    }
}