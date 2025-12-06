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
        return Doctorant::with(['directeur.user', 'codirecteur.user', 'ead'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Matricule',
            'Nom',
            'Prénom',
            'Email',
            'Niveau',
            'Sujet de thèse',
            'Directeur de thèse',
            'Co-directeur',
            'EAD',
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
            $doctorant->nom,
            $doctorant->prenom,
            $doctorant->email,
            $doctorant->niveau,
            $doctorant->sujet_these,
            $doctorant->directeur?->user->name ?? '',
            $doctorant->codirecteur?->user->name ?? '',
            $doctorant->ead?->nom ?? '',
            $doctorant->date_naissance?->format('d/m/Y') ?? '',
            $doctorant->lieu_naissance ?? '',
            $doctorant->phone ?? '',
            $doctorant->adresse ?? '',
            $doctorant->date_inscription->format('d/m/Y'),
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