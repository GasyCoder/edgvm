<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use Illuminate\Database\Seeder;

class MenuAProposSeeder extends Seeder
{
    public function run(): void
    {
        /**
         * 1. On s'assure que le menu "À propos" existe
         *    slug = "a-propos"
         */
        $menu = Menu::firstOrCreate(
            ['slug' => 'a-propos'],
            ['nom'  => 'À propos']
        );

        /**
         * 2. On s'assure que les pages existent
         *    (si tu les as déjà créées en admin, elles seront juste retrouvées)
         */
        $pagesDef = [
            'a-propos'            => "Présentation de l'École Doctorale",
            'organisation'        => "Organisation de l'École Doctorale",
            'conseil-scientifique'=> "Conseil de l'École Doctorale",
            'comite-suivi'        => "Comité de suivi de thèse",
            'reglement-interieur' => "Règlement intérieur",
        ];

        $pageIds = [];
        $ordre   = 1;

        foreach ($pagesDef as $slug => $titre) {
            $page = Page::firstOrCreate(
                ['slug' => $slug],
                [
                    'titre'    => $titre,
                    'contenu'  => "<p>Contenu à compléter pour « {$titre} ».</p>",
                    'visible'  => true,
                    'ordre'    => $ordre,
                    'auteur_id'=> null, // tu pourras l'éditer ensuite
                ]
            );

            $pageIds[$slug] = $page->id;
            $ordre++;
        }

        /**
         * 3. On crée les items du menu "À propos"
         *    → 1 item par page
         */
        $itemsDef = [
            [
                'slug'  => 'a-propos',
                'label' => 'Présentation',
            ],
            [
                'slug'  => 'organisation',
                'label' => 'Organisation',
            ],
            [
                'slug'  => 'conseil-scientifique',
                'label' => "Conseil de l'École Doctorale",
            ],
            [
                'slug'  => 'comite-suivi',
                'label' => 'Comité de suivi de thèse',
            ],
            [
                'slug'  => 'reglement-interieur',
                'label' => 'Règlement intérieur',
            ],
        ];

        $ordre = 1;

        foreach ($itemsDef as $def) {
            $pageId = $pageIds[$def['slug']] ?? null;

            if (! $pageId) {
                continue;
            }

            MenuItem::updateOrCreate(
                [
                    'menu_id' => $menu->id,
                    'page_id' => $pageId,
                ],
                [
                    'label'     => $def['label'],
                    'url'       => null,      // on passe par la page
                    'parent_id' => null,
                    'ordre'     => $ordre,
                    'visible'   => true,
                    'icone'     => null,
                ]
            );

            $ordre++;
        }
    }
}