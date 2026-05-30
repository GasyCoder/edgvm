<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'super_admin';
    case Direction = 'direction';
    case Secretariat = 'secretariat';
    case Communication = 'communication';
    case Encadrant = 'encadrant';
    case Doctorant = 'doctorant';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super administrateur',
            self::Direction => 'Direction',
            self::Secretariat => 'Secrétariat',
            self::Communication => 'Responsable communication',
            self::Encadrant => 'Encadrant',
            self::Doctorant => 'Doctorant',
        };
    }

    /**
     * Rôles ayant accès au backend (espace de gestion).
     *
     * @return array<int, string>
     */
    public static function backendRoles(): array
    {
        return [
            self::SuperAdmin->value,
            self::Direction->value,
            self::Secretariat->value,
            self::Communication->value,
        ];
    }
}
