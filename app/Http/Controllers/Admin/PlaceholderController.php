<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PlaceholderController extends Controller
{
    public function index(Request $request, string $section): Response
    {
        return $this->render($section, 'index');
    }

    public function create(Request $request, string $section): Response
    {
        return $this->render($section, 'create');
    }

    public function edit(Request $request, string $section): Response
    {
        return $this->render($section, 'edit', $this->resourceId($request));
    }

    public function show(Request $request, string $section): Response
    {
        return $this->render($section, 'show', $this->resourceId($request));
    }

    private function render(string $section, string $action, ?string $id = null): Response
    {
        return Inertia::render('Admin/Placeholder', [
            'section' => $section,
            'action' => $action,
            'resourceId' => $id,
        ]);
    }

    private function resourceId(Request $request): ?string
    {
        $parameters = $request->route()?->parameters() ?? [];
        unset($parameters['section']);

        $values = array_values($parameters);

        return isset($values[0]) ? (string) $values[0] : null;
    }
}
