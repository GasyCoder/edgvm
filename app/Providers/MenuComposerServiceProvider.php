<?php

namespace App\Providers;

use App\Models\Menu;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MenuComposerServiceProvider extends ServiceProvider
{
    protected function getMenuItems(string $slug)
    {
        $menu = Menu::with([
                'items' => function ($q) {
                    $q->visible()
                      ->whereNull('parent_id')
                      ->orderBy('ordre');
                },
                'items.page', // pour éviter le N+1
            ])
            ->where('slug', $slug)
            ->first();

        return $menu?->items ?? collect();
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('menuAProposItems', $this->getMenuItems('a-propos'));
        });
    }

    public function register(): void
    {
        //
    }
}
