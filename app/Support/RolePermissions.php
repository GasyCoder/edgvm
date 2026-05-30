<?php

namespace App\Support;

use App\Enums\UserRole;

class RolePermissions
{
    /**
     * Toutes les capacités (abilities) au niveau module/zone.
     *
     * @var array<int, string>
     */
    public const ABILITIES = [
        'contenu.access',          // Site web public & contenus
        'gestion.access',          // Gestion académique
        'finances.access',         // Finances / paiements
        'communications.access',   // Communications internes ciblées
        'rapports.access',         // Rapports administratifs/académiques/financiers
        'systeme.access',          // Utilisateurs, rôles, paramètres
        'records.delete',          // Suppression définitive
    ];

    /**
     * Carte rôle → capacités. '*' = toutes.
     *
     * @var array<string, array<int, string>>
     */
    public const MAP = [
        UserRole::SuperAdmin->value => ['*'],
        UserRole::Direction->value => [
            'contenu.access', 'gestion.access', 'finances.access',
            'communications.access', 'rapports.access', 'records.delete',
        ],
        UserRole::Secretariat->value => [
            'gestion.access', 'finances.access', 'communications.access',
        ],
        UserRole::Communication->value => [
            'contenu.access',
            'communications.access',
        ],
        UserRole::Encadrant->value => [],
        UserRole::Doctorant->value => [],
    ];

    /**
     * @return array<int, string>
     */
    public static function for(?string $role): array
    {
        $abilities = self::MAP[$role] ?? [];

        if (in_array('*', $abilities, true)) {
            return self::ABILITIES;
        }

        return $abilities;
    }

    public static function allows(?string $role, string $ability): bool
    {
        if ($role === UserRole::SuperAdmin->value) {
            return true;
        }

        return in_array($ability, self::for($role), true);
    }
}
