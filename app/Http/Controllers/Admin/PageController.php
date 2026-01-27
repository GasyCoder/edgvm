<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePageRequest;
use App\Http\Requests\StorePageSectionRequest;
use App\Http\Requests\UpdatePageRequest;
use App\Http\Requests\UpdatePageSectionRequest;
use App\Models\Media;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim($request->string('search')->toString());

        $pages = Page::with(['auteur', 'sections'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('titre', 'like', '%'.$search.'%')
                        ->orWhere('slug', 'like', '%'.$search.'%');
                });
            })
            ->orderBy('ordre')
            ->paginate(10)
            ->withQueryString()
            ->through(fn (Page $page) => [
                'id' => $page->id,
                'titre' => $page->titre,
                'slug' => $page->slug,
                'visible' => $page->visible,
                'ordre' => $page->ordre,
                'sections_count' => $page->sections->count(),
                'auteur' => $page->auteur ? [
                    'name' => $page->auteur->name,
                ] : null,
            ]);

        return Inertia::render('Admin/Pages/Index', [
            'pages' => $pages,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Pages/Create', [
            'menus' => $this->menus(),
        ]);
    }

    public function store(StorePageRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $attachToMenu = (bool) ($data['attach_to_menu'] ?? false);
        $menuId = $data['menu_id'] ?? null;
        $menuLabel = $data['menu_label'] ?? null;

        unset($data['attach_to_menu'], $data['menu_id'], $data['menu_label']);

        $page = Page::create([
            ...$data,
            'auteur_id' => Auth::id(),
        ]);

        if ($attachToMenu && $menuId) {
            $nextOrdre = (MenuItem::where('menu_id', $menuId)
                ->whereNull('parent_id')
                ->max('ordre') ?? 0) + 1;

            MenuItem::create([
                'menu_id' => $menuId,
                'label' => $menuLabel ?: $page->titre,
                'page_id' => $page->id,
                'parent_id' => null,
                'ordre' => $nextOrdre,
                'visible' => true,
                'icone' => null,
            ]);
        }

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page creee avec succes.');
    }

    public function edit(Page $page): Response
    {
        $page->load(['sections.image']);

        $menuItem = MenuItem::where('page_id', $page->id)
            ->whereNull('parent_id')
            ->first();

        return Inertia::render('Admin/Pages/Edit', [
            'page' => [
                'id' => $page->id,
                'titre' => $page->titre,
                'slug' => $page->slug,
                'contenu' => $page->contenu,
                'visible' => $page->visible,
                'ordre' => $page->ordre,
                'sections' => $page->sections->map(fn (PageSection $section) => [
                    'id' => $section->id,
                    'titre' => $section->titre,
                    'contenu' => $section->contenu,
                    'image_id' => $section->image_id,
                    'image_url' => $section->image?->url,
                    'ordre' => $section->ordre,
                ])->toArray(),
            ],
            'menus' => $this->menus(),
            'menuState' => [
                'attach_to_menu' => (bool) $menuItem,
                'menu_id' => $menuItem?->menu_id,
                'menu_label' => $menuItem?->label,
                'menu_item_id' => $menuItem?->id,
            ],
            'images' => Media::where('type', 'image')
                ->orderByDesc('created_at')
                ->get()
                ->map(fn (Media $media) => [
                    'id' => $media->id,
                    'nom' => $media->display_name,
                    'url' => $media->url,
                ])
                ->toArray(),
        ]);
    }

    public function update(UpdatePageRequest $request, Page $page): RedirectResponse
    {
        $data = $request->validated();
        $attachToMenu = (bool) ($data['attach_to_menu'] ?? false);
        $menuId = $data['menu_id'] ?? null;
        $menuLabel = $data['menu_label'] ?? null;

        unset($data['attach_to_menu'], $data['menu_id'], $data['menu_label']);

        $page->update($data);

        if ($attachToMenu && $menuId) {
            $menuItem = MenuItem::where('page_id', $page->id)
                ->whereNull('parent_id')
                ->first();

            if ($menuItem) {
                $menuItem->update([
                    'menu_id' => $menuId,
                    'label' => $menuLabel ?: $page->titre,
                    'page_id' => $page->id,
                    'visible' => true,
                ]);
            } else {
                $nextOrdre = (MenuItem::where('menu_id', $menuId)
                    ->whereNull('parent_id')
                    ->max('ordre') ?? 0) + 1;

                $menuItem = MenuItem::create([
                    'menu_id' => $menuId,
                    'label' => $menuLabel ?: $page->titre,
                    'page_id' => $page->id,
                    'parent_id' => null,
                    'ordre' => $nextOrdre,
                    'visible' => true,
                    'icone' => null,
                ]);
            }
        } else {
            MenuItem::where('page_id', $page->id)->delete();
        }

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page mise a jour avec succes.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        MenuItem::where('page_id', $page->id)->delete();
        $page->sections()->delete();
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page supprimee avec succes.');
    }

    public function toggleVisibility(Page $page): RedirectResponse
    {
        $page->update(['visible' => ! $page->visible]);

        MenuItem::where('page_id', $page->id)
            ->update(['visible' => $page->visible]);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Visibilite mise a jour.');
    }

    public function storeSection(StorePageSectionRequest $request, Page $page): RedirectResponse
    {
        $data = $request->validated();

        $page->sections()->create([
            'titre' => $data['titre'] ?? null,
            'contenu' => $data['contenu'] ?? null,
            'image_id' => $data['image_id'] ?? null,
            'ordre' => $data['ordre'] ?? 0,
        ]);

        return redirect()->route('admin.pages.edit', $page)
            ->with('success', 'Section ajoutee avec succes.');
    }

    public function updateSection(UpdatePageSectionRequest $request, Page $page, PageSection $section): RedirectResponse
    {
        if ($section->page_id !== $page->id) {
            abort(404);
        }

        $data = $request->validated();

        $section->update([
            'titre' => $data['titre'] ?? null,
            'contenu' => $data['contenu'] ?? null,
            'image_id' => $data['image_id'] ?? null,
            'ordre' => $data['ordre'] ?? 0,
        ]);

        return redirect()->route('admin.pages.edit', $page)
            ->with('success', 'Section mise a jour avec succes.');
    }

    public function destroySection(Page $page, PageSection $section): RedirectResponse
    {
        if ($section->page_id !== $page->id) {
            abort(404);
        }

        $section->delete();

        return redirect()->route('admin.pages.edit', $page)
            ->with('success', 'Section supprimee avec succes.');
    }

    private function menus(): array
    {
        return Menu::orderBy('nom')
            ->get()
            ->map(fn (Menu $menu) => [
                'id' => $menu->id,
                'nom' => $menu->nom,
                'slug' => $menu->slug,
            ])
            ->toArray();
    }
}
