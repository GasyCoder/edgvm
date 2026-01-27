<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim($request->string('search')->toString());

        $categories = Category::when($search !== '', function ($q) use ($search) {
            $q->where('nom', 'like', '%'.$search.'%');
        })
            ->withCount('actualites')
            ->orderBy('nom')
            ->paginate(10)
            ->withQueryString()
            ->through(fn (Category $category) => [
                'id' => $category->id,
                'nom' => $category->nom,
                'description' => $category->description,
                'couleur' => $category->couleur,
                'visible' => $category->visible,
                'actualites_count' => $category->actualites_count ?? 0,
            ]);

        return Inertia::render('Admin/Categories/Index', [
            'filters' => [
                'search' => $search,
            ],
            'categories' => $categories,
        ]);
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        Category::create($request->validated());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categorie creee.');
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categorie mise a jour.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categorie supprimee.');
    }
}
