<?php

namespace App\Exports;

use App\Models\Doctorant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DoctorantsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Doctorant::with([
                'user',
            ])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Matricule',
            'Nom complet',
            'Email',
            'Niveau',
            'Date de naissance',
            'Lieu de naissance',
            'Téléphone',
            'Adresse',
            'Date d\'inscription',
            'Statut',
            'A un compte',
        ];
    }

    public function map($doctorant): array
    {
        return [
            $doctorant->matricule,
            $doctorant->user?->name ?? 'Pas de compte',
            $doctorant->user?->email ?? 'N/A',
            $doctorant->niveau,
            $doctorant->date_naissance?->format('d/m/Y') ?? '',
            $doctorant->lieu_naissance ?? '',
            $doctorant->phone ?? '',
            $doctorant->adresse ?? '',
            $doctorant->date_inscription
                ? $doctorant->date_inscription->format('d/m/Y')
                : '',
            ucfirst($doctorant->statut),
            $doctorant->user ? 'Oui' : 'Non',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
