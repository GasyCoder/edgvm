<div class="space-y-6">

    {{-- Flash --}}
    @if (session()->has('success'))
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-emerald-800">
            <div class="flex items-start gap-3">
                <div class="mt-0.5 h-8 w-8 rounded-xl bg-emerald-600/10 flex items-center justify-center">
                    <svg class="h-4 w-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-sm font-medium leading-6">
                    {{ session('success') }}
                </div>
            </div>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div class="min-w-0">
            <h1 class="text-xl md:text-2xl font-semibold text-slate-900 tracking-tight">
                Actualités
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Gérez la visibilité, les contenus et la publication des actualités.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.actualites.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white
                      hover:bg-ed-secondary transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                          d="M12 4v16m8-8H4"/>
                </svg>
                <span>Nouvelle actualité</span>
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="rounded-2xl bg-white border border-slate-100 p-4">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500">Total</p>
                <div class="h-9 w-9 rounded-xl bg-slate-900/5 flex items-center justify-center">
                    <svg class="h-4 w-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </div>
            </div>
            <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $stats['total'] }}</p>
        </div>

        <div class="rounded-2xl bg-white border border-slate-100 p-4">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500">Visibles</p>
                <div class="h-9 w-9 rounded-xl bg-emerald-600/10 flex items-center justify-center">
                    <svg class="h-4 w-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4"/>
                    </svg>
                </div>
            </div>
            <p class="mt-2 text-2xl font-semibold text-emerald-700">{{ $stats['visibles'] }}</p>
        </div>

        <div class="rounded-2xl bg-white border border-slate-100 p-4">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500">Cachées</p>
                <div class="h-9 w-9 rounded-xl bg-slate-500/10 flex items-center justify-center">
                    <svg class="h-4 w-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10 0-1.04.158-2.043.451-2.986M6.228 6.228A9.97 9.97 0 0112 5c5.523 0 10 4.477 10 10 0 1.58-.366 3.074-1.017 4.402M3 3l18 18"/>
                    </svg>
                </div>
            </div>
            <p class="mt-2 text-2xl font-semibold text-slate-700">{{ $stats['cachees'] }}</p>
        </div>
    </div>

    {{-- Toolbar (sans bouton créer) --}}
    <div class="rounded-2xl bg-white border border-slate-100 p-4">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            <div class="md:col-span-4">
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z"/>
                        </svg>
                    </span>

                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           placeholder="Rechercher une actualité..."
                           class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-slate-200
                                  focus:border-ed-primary focus:ring-ed-primary/20">
                </div>
            </div>

            <div class="md:col-span-2">
                <select wire:model.live="filterVisible"
                        class="w-full px-3 py-2.5 rounded-xl border border-slate-200
                               focus:border-ed-primary focus:ring-ed-primary/20">
                    <option value="all">Toutes</option>
                    <option value="visible">Visibles</option>
                    <option value="hidden">Cachées</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50">
                    <tr class="text-left text-slate-600">
                        <th class="px-5 py-3 font-semibold">Image</th>
                        <th class="px-5 py-3 font-semibold">Titre</th>
                        <th class="px-5 py-3 font-semibold">Catégorie</th>
                        <th class="px-5 py-3 font-semibold">Auteur</th>
                        <th class="px-5 py-3 font-semibold">Date</th>
                        <th class="px-5 py-3 font-semibold">Visible</th>
                        <th class="px-5 py-3 font-semibold text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($actualites as $actualite)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-5 py-4">
                                <div class="h-12 w-12 rounded-xl overflow-hidden bg-slate-100 border border-slate-200">
                                    @if($actualite->image)
                                        <img src="{{ $actualite->image->url }}"
                                             alt="{{ $actualite->titre }}"
                                             class="h-full w-full object-cover">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center">
                                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <td class="px-5 py-4">
                                <div class="max-w-xl">
                                    <div class="font-semibold text-slate-900 leading-5 line-clamp-2">
                                        {{ $actualite->titre }}
                                    </div>
                                    <div class="mt-1 text-xs text-slate-500 line-clamp-2">
                                        {{ Str::limit(strip_tags($actualite->contenu), 90) }}
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-4">
                                @if($actualite->category)
                                    <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-semibold text-slate-800 bg-slate-100">
                                        <span class="h-2 w-2 rounded-full"
                                              style="background-color: {{ $actualite->category->couleur }}"></span>
                                        {{ $actualite->category->nom }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400">Aucune</span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $actualite->auteur?->name ?? 'Non défini' }}
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $actualite->date_publication?->format('d/m/Y') ?? '—' }}
                            </td>

                            <td class="px-5 py-4">
                                <button wire:click="toggleVisibility({{ $actualite->id }})"
                                        type="button"
                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors
                                               {{ $actualite->visible ? 'bg-ed-primary' : 'bg-slate-200' }}"
                                        aria-label="Basculer visibilité">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform
                                                 {{ $actualite->visible ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.actualites.edit', $actualite) }}"
                                       class="inline-flex items-center justify-center h-9 w-9 rounded-xl
                                              border border-slate-200 bg-white text-slate-700
                                              hover:border-ed-primary/40 hover:text-ed-primary transition"
                                       title="Modifier">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    <button wire:click="confirmDelete({{ $actualite->id }})"
                                            type="button"
                                            class="inline-flex items-center justify-center h-9 w-9 rounded-xl
                                                   border border-slate-200 bg-white text-slate-700
                                                   hover:border-red-300 hover:text-red-600 transition"
                                            title="Supprimer">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                Aucune actualité trouvée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div>
        {{ $actualites->links() }}
    </div>

    {{-- Modal suppression --}}
    @if($confirmingDeletion)
        <div class="fixed inset-0 z-50">
            <div class="absolute inset-0 bg-slate-900/35 backdrop-blur-[1px]"
                 wire:click="$set('confirmingDeletion', false)"></div>

            <div class="relative flex min-h-screen items-center justify-center px-4">
                <div class="w-full max-w-lg rounded-2xl bg-white border border-slate-100 p-6">
                    <div class="flex items-start gap-3">
                        <div class="h-10 w-10 rounded-2xl bg-red-600/10 flex items-center justify-center">
                            <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 9v4m0 4h.01M10.29 3.86l-8.02 13.9A2 2 0 004 21h16a2 2 0 001.73-3.24l-8.02-13.9a2 2 0 00-3.42 0z"/>
                            </svg>
                        </div>

                        <div class="flex-1">
                            <h3 class="text-base font-semibold text-slate-900">Confirmer la suppression</h3>
                            <p class="mt-1 text-sm text-slate-600">
                                Êtes-vous sûr de vouloir supprimer cette actualité ? Cette action est irréversible.
                            </p>

                            <div class="mt-6 flex justify-end gap-2">
                                <button wire:click="$set('confirmingDeletion', false)"
                                        type="button"
                                        class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold
                                               border border-slate-200 bg-white text-slate-800 hover:bg-slate-50 transition">
                                    Annuler
                                </button>

                                <button wire:click="deleteActualite"
                                        type="button"
                                        class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold
                                               bg-red-600 text-white hover:bg-red-700 transition">
                                    Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
