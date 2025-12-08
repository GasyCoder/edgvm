<div class="space-y-6">
    {{-- Flash messages --}}
    @if (session('success'))
        <div class="rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Partenaires</h1>
            <p class="text-sm text-gray-500 mt-1">
                Gérez les partenaires affichés sur la page d’accueil (logos, liens, ordre d’affichage, visibilité).
            </p>
        </div>

        <a href="{{ route('admin.partenaires.create') }}"
           class="inline-flex items-center px-4 py-2 rounded-lg bg-ed-primary text-white text-sm font-semibold hover:bg-ed-secondary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4"/>
            </svg>
            Nouveau partenaire
        </a>
    </div>

    <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between gap-4">
            <div class="flex-1">
                <input type="text"
                       wire:model.debounce.400ms="search"
                       placeholder="Rechercher par nom..."
                       class="w-full rounded-lg border-gray-300 text-sm focus:border-ed-primary focus:ring-ed-primary">
            </div>
        </div>

        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left font-semibold text-gray-700">Logo</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-700">Nom</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-700">URL</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-700">Ordre</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-700">Visible</th>
                <th class="px-4 py-3 text-right font-semibold text-gray-700">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @forelse ($partenaires as $partenaire)
                <tr>
                    <td class="px-4 py-3">
                        @if($partenaire->logo_url)
                            <img src="{{ $partenaire->logo_url }}"
                                alt="{{ $partenaire->nom }}"
                                class="w-14 h-14 object-contain rounded-md border border-gray-100 bg-white">
                        @else
                            <div class="w-14 h-14 rounded-md border border-dashed border-gray-300 flex items-center justify-center text-[10px] text-gray-400">
                                Pas de logo
                            </div>
                        @endif
                    </td>

                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-900">{{ $partenaire->nom }}</div>
                        @if($partenaire->description)
                            <div class="text-xs text-gray-500 line-clamp-2">
                                {{ $partenaire->description }}
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-700">
                        @if($partenaire->url)
                            <a href="{{ $partenaire->url }}" target="_blank" class="text-ed-primary hover:underline break-all">
                                {{ $partenaire->url }}
                            </a>
                        @else
                            <span class="text-gray-400 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center text-gray-700">
                        {{ $partenaire->ordre }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <button
                            wire:click="toggleVisibility({{ $partenaire->id }})"
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                   {{ $partenaire->visible ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $partenaire->visible ? 'Affiché' : 'Masqué' }}
                        </button>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.partenaires.edit', $partenaire) }}"
                               class="px-3 py-1 rounded-lg border border-gray-200 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                Éditer
                            </a>

                            <button
                                wire:click="delete({{ $partenaire->id }})"
                                onclick="return confirm('Supprimer ce partenaire ?')"
                                class="px-3 py-1 rounded-lg border border-red-200 text-xs font-medium text-red-600 hover:bg-red-50">
                                Supprimer
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                        Aucun partenaire enregistré pour le moment.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div class="px-4 py-3 border-t border-gray-100">
            {{ $partenaires->links() }}
        </div>
    </div>
</div>