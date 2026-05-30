<?php

namespace App\Enums;

enum StatutAnnee: string
{
    case EnCours = 'en_cours';
    case Admis = 'admis';
    case Ajourne = 'ajourne';
    case Valide = 'valide';
    case Abandon = 'abandon';

    public function label(): string
    {
        return match ($this) {
            self::EnCours => 'En cours',
            self::Admis => 'Admis',
            self::Ajourne => 'Ajourné',
            self::Valide => 'Validé',
            self::Abandon => 'Abandon',
        };
    }
}
