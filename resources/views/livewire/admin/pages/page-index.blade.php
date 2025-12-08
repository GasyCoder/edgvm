<div class="p-6 lg:p-8">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 10.414V18a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/>
                </svg>
                Pages
            </h1>
            <p class="mt-1 text-sm text-gray-600">
                Gérer les pages statiques publiques du site EDGVM.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <div class="hidden sm:flex items-center gap-2 text-xs text-gray-500 bg-gray-50 border border-gray-200 rounded-full px-3 py-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span>
                    {{ $this->pages->total() ?? $this->pages->count() }} page(s) au total
                </span>
            </div>

            <a href="{{ route('admin.pages.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4v16m8-8H4"/>
                </svg>
                Nouvelle page
            </a>
        </div>
    </div>

    {{-- Message succès --}}
    @if (session()->has('success'))
        <div class="mb-6 flex items-start gap-3 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m5 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Barre de recherche / filtre --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="w-full md:max-w-md">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>
                    </svg>
                </span>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Rechercher une page par titre, slug, auteur..."
                    class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm
                           focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            <p class="mt-1 text-xs text-gray-400">
                Tapez au moins 2 caractères pour filtrer la liste.
            </p>
        </div>

        {{-- Légende visibilité --}}
        <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
            <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Visible
            </div>
            <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-gray-100 text-gray-700 border border-gray-200">
                <span class="w-2 h-2 rounded-full bg-gray-400"></span> Masquée
            </div>
        </div>
    </div>

    {{-- Table des pages --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50/80">
                <tr>
                    <th class="px-4 md:px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                        Ordre
                    </th>
                    <th class="px-4 md:px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                        Titre
                    </th>
                    <th class="px-4 md:px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                        Slug / URL
                    </th>
                    <th class="px-4 md:px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                        Sections
                    </th>
                    <th class="px-4 md:px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                        Visibilité
                    </th>
                    <th class="px-4 md:px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                        Auteur
                    </th>
                    <th class="px-4 md:px-6 py-3 text-right text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($this->pages as $page)
                    <tr class="hover:bg-gray-50/60">
                        {{-- Ordre --}}
                        <td class="px-4 md:px-6 py-3 whitespace-nowrap text-sm text-gray-700">
                            <div class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full bg-gray-100 text-[11px] text-gray-700">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 10h16M4 14h10"/>
                                </svg>
                                {{ $page->ordre }}
                            </div>
                        </td>

                        {{-- Titre --}}
                        <td class="px-4 md:px-6 py-3 whitespace-nowrap max-w-xs">
                            <div class="flex flex-col">
                                <div class="text-sm font-semibold text-gray-900 truncate">
                                    {{ $page->titre }}
                                </div>
                                <div class="mt-0.5 text-[11px] text-gray-400">
                                    ID #{{ $page->id }}
                                    @if($page->updated_at)
                                        • maj {{ $page->updated_at->format('d/m/Y') }}
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Slug / URL --}}
                        <td class="px-4 md:px-6 py-3 whitespace-nowrap">
                            <div class="flex flex-col gap-1">
                                <div class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full bg-gray-100 text-[11px] font-mono text-gray-700">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M10.5 6H5.75A2.75 2.75 0 003 8.75v9.5A2.75 2.75 0 005.75 21h9.5A2.75 2.75 0 0018 18.25V13.5"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 15h4m-4-3h1m4.25-9H19a2 2 0 012 2v5.75M11 6l8 8"/>
                                    </svg>
                                    {{ $page->slug }}
                                </div>
                                <a href="{{ route('pages.show', $page->slug) }}"
                                   target="_blank"
                                   class="text-[11px] text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center gap-1">
                                    <span class="truncate max-w-[180px] md:max-w-[260px]">
                                        {{ route('pages.show', $page->slug) }}
                                    </span>
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M10 6h8m0 0v8m0-8L9 15"/>
                                    </svg>
                                </a>
                            </div>
                        </td>

                        {{-- Sections --}}
                        <td class="px-4 md:px-6 py-3 whitespace-nowrap">
                            <div class="inline-flex items-center gap-1.5 text-xs text-gray-700">
                                <div class="w-7 h-7 rounded-full bg-indigo-50 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 7h12M8 12h12M8 17h12M4 7h.01M4 12h.01M4 17h.01"/>
                                    </svg>
                                </div>
                                <span class="font-medium">
                                    {{ $page->sections->count() }} section(s)
                                </span>
                            </div>
                        </td>

                        {{-- Visibilité (toggle) --}}
                        <td class="px-4 md:px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    wire:click="toggleVisibility({{ $page->id }})"
                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition
                                           {{ $page->visible ? 'bg-emerald-500' : 'bg-gray-300' }}"
                                >
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow
                                                 transition {{ $page->visible ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                                <span class="text-xs {{ $page->visible ? 'text-emerald-700' : 'text-gray-500' }}">
                                    {{ $page->visible ? 'Visible' : 'Masquée' }}
                                </span>
                            </div>
                        </td>

                        {{-- Auteur --}}
                        <td class="px-4 md:px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center text-[11px] font-semibold text-gray-700">
                                    {{ strtoupper(substr($page->auteur->name ?? 'N', 0, 1)) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-800">
                                        {{ $page->auteur->name ?? 'Non défini' }}
                                    </span>
                                    @if($page->auteur)
                                        <span class="text-[11px] text-gray-400">
                                            Auteur
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Actions --}}
                        <td class="px-4 md:px-6 py-3 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                {{-- Prévisualiser (front) --}}
                                <a href="{{ route('pages.show', $page->slug) }}"
                                   target="_blank"
                                   class="p-1.5 rounded-full text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition"
                                   title="Voir la page publique">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 3h6m0 0v6m0-6L10 14"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M5 5v14a2 2 0 002 2h12"/>
                                    </svg>
                                </a>

                                {{-- Éditer --}}
                                <a href="{{ route('admin.pages.edit', $page) }}"
                                   class="p-1.5 rounded-full text-blue-600 hover:text-blue-800 hover:bg-blue-50 transition"
                                   title="Éditer la page">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                                                 m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9
                                                 v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                {{-- Supprimer --}}
                                <button
                                    type="button"
                                    wire:click="confirmDelete({{ $page->id }})"
                                    class="p-1.5 rounded-full text-red-600 hover:text-red-700 hover:bg-red-50 transition"
                                    title="Supprimer la page">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                                                 a2 2 0 01-1.995-1.858L5 7m5 4v6
                                                 m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3
                                                 m-9 0h10"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12">
                            <div class="flex flex-col items-center justify-center text-center text-gray-500">
                                <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 13h6m-6-4h6m5 12H4a2 2 0 01-2-2V5
                                                 a2 2 0 012-2h7l2 2h7a2 2 0 012 2v10
                                                 a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium">
                                    Aucune page trouvée.
                                </p>
                                <p class="mt-1 text-xs text-gray-400">
                                    Modifiez votre recherche ou créez une nouvelle page.
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $this->pages->links() }}
    </div>

    {{-- Modal de confirmation suppression --}}
    @if($confirmingPageDeletion)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
             wire:click="$set('confirmingPageDeletion', false)">
            <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-xl"
                 wire:click.stop>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v4m0 4h.01M4.93 4.93l14.14 14.14M5 19h14a2 2 0 002-2V7
                                     a2 2 0 00-2-2h-5l-1-2H11L10 5H5
                                     a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">
                            Confirmer la suppression
                        </h3>
                        <p class="text-xs text-gray-500">
                            Cette action est irréversible. La page ne sera plus accessible sur le site.
                        </p>
                    </div>
                </div>

                <div class="mt-2 mb-6 text-sm text-gray-600">
                    Êtes-vous sûr de vouloir supprimer cette page ?
                </div>

                <div class="flex justify-end gap-3">
                    <button
                        type="button"
                        wire:click="$set('confirmingPageDeletion', false)"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm transition">
                        Annuler
                    </button>
                    <button
                        type="button"
                        wire:click="deletePage"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm transition">
                        Supprimer définitivement
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
