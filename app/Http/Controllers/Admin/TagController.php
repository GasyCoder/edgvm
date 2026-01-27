<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TagController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim($request->string('search')->toString());

        $tags = Tag::when($search !== '', function ($q) use ($search) {
            $q->where('nom', 'like', '%'.$search.'%');
        })
            ->withCount('actualites')
            ->orderBy('nom')
            ->paginate(15)
            ->withQueryString()
            ->through(fn (Tag $tag) => [
                'id' => $tag->id,
                'nom' => $tag->nom,
                'slug' => $tag->slug,
                'actualites_count' => $tag->actualites_count ?? 0,
            ]);

        return Inertia::render('Admin/Tags/Index', [
            'filters' => [
                'search' => $search,
            ],
            'tags' => $tags,
        ]);
    }

    public function store(StoreTagRequest $request): RedirectResponse
    {
        Tag::create($request->validated());

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag cree.');
    }

    public function update(UpdateTagRequest $request, Tag $tag): RedirectResponse
    {
        $tag->update($request->validated());

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag mis a jour.');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->delete();

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag supprime.');
    }
}
