<?php

namespace App\Exports;

use App\Models\Doctorant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DoctorantsExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Doctorant::with([
            'eadDirect',
        ])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Matricule',
            'Nom',
            'Prenoms',
            'Genre',
            'Email',
            'Equipe d\'accueil',
            'Niveau',
            'Date de naissance',
            'Lieu de naissance',
            'Téléphone',
            'Adresse',
            'Date d\'inscription',
            'Statut',
        ];
    }

    public function map($doctorant): array
    {
        return [
            $doctorant->matricule,
            $doctorant->nom ?? '',
            $doctorant->prenoms ?? '',
            ucfirst($doctorant->genre ?? ''),
            $doctorant->email ?? '',
            $doctorant->eadDirect?->nom ?? '',
            $doctorant->niveau,
            $doctorant->date_naissance?->format('d/m/Y') ?? '',
            $doctorant->lieu_naissance ?? '',
            $doctorant->phone ?? '',
            $doctorant->adresse ?? '',
            $doctorant->date_inscription
                ? $doctorant->date_inscription->format('d/m/Y')
                : '',
            ucfirst($doctorant->statut),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
