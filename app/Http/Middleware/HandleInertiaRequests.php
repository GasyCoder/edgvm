<?php

namespace App\Http\Middleware;

use App\Models\Menu;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Get menu items by slug.
     */
    protected function getMenuItems(string $slug): array
    {
        if (! Schema::hasTable('menus') || ! Schema::hasTable('menu_items')) {
            return [];
        }

        $menu = Menu::with([
            'items' => function ($q) {
                $q->visible()
                    ->whereNull('parent_id')
                    ->orderBy('ordre');
            },
            'items.page',
        ])
            ->where('slug', $slug)
            ->first();

        if (! $menu) {
            return [];
        }

        return $menu->items->map(function ($item) {
            return [
                'id' => $item->id,
                'label' => $item->label,
                'url' => $item->resolved_url,
                'icone' => $item->icone,
            ];
        })->toArray();
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $appSettings = null;
        if (Schema::hasTable('settings')) {
            $appSettings = Setting::main();
        }

        return array_merge(parent::share($request), [
            'menuAProposItems' => fn () => $this->getMenuItems('a-propos'),
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'role' => $request->user()->role ?? null,
                ] : null,
            ],
            'appSettings' => $appSettings ? [
                'site_name' => $appSettings->site_name,
                'seo_title' => $appSettings->seo_title,
                'meta_description' => $appSettings->meta_description,
                'meta_keywords' => $appSettings->meta_keywords,
                'site_email' => $appSettings->site_email,
                'site_phone' => $appSettings->site_phone,
                'site_address' => $appSettings->site_address,
                'facebook_url' => $appSettings->facebook_url,
                'twitter_url' => $appSettings->twitter_url,
                'linkedin_url' => $appSettings->linkedin_url,
                'youtube_url' => $appSettings->youtube_url,
                'instagram_url' => $appSettings->instagram_url,
                'logo_path' => $appSettings->logo_path,
                'logo_url' => $appSettings->logo_path ? asset('storage/'.$appSettings->logo_path) : null,
                'favicon_path' => $appSettings->favicon_path,
                'favicon_url' => $appSettings->favicon_path ? asset('storage/'.$appSettings->favicon_path) : null,
                'maintenance_mode' => $appSettings->maintenance_mode,
            ] : null,
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'warning' => fn () => $request->session()->get('warning'),
                'info' => fn () => $request->session()->get('info'),
            ],
            'currentUrl' => $request->url(),
            'currentRouteName' => $request->route()?->getName(),
        ]);
    }
}
