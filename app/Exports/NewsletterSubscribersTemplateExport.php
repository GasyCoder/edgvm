<?php 

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NewsletterSubscribersTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['email', 'nom', 'type', 'actif'];
    }

    public function array(): array
    {
        return [
            ['exemple1@domaine.com', 'Nom Exemple', 'doctorant', 1],
            ['exemple2@domaine.com', 'Nom Exemple 2', 'encadrant', 1],
        ];
    }
}
