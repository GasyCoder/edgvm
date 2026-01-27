<?php

namespace App\Exports;

use App\Models\Encadrant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EncadrantsExport implements FromCollection, WithColumnWidths, WithHeadings, WithMapping, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Encadrant::query()->get();
    }

    /**
     * En-têtes du fichier Excel
     */
    public function headings(): array
    {
        return [
            'Nom',
            'Prenoms',
            'Email',
            'Genre',
            'Grade',
            'Spécialité',
            'Téléphone',
            'Bureau',
        ];
    }

    /**
     * Mapping des données
     */
    public function map($encadrant): array
    {
        return [
            $encadrant->nom ?? '',
            $encadrant->prenoms ?? '',
            $encadrant->email ?? '',
            $encadrant->genre ?? '',
            $encadrant->grade ?? '',
            $encadrant->specialite ?? '',
            $encadrant->phone ?? '',
            $encadrant->bureau ?? '',
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
            'A' => 20, // Nom
            'B' => 25, // Prenoms
            'C' => 35, // Email
            'D' => 12, // Genre
            'E' => 25, // Grade
            'F' => 30, // Spécialité
            'G' => 20, // Téléphone
            'H' => 25, // Bureau
        ];
    }
}
