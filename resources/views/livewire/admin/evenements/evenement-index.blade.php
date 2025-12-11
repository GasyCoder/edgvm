<div class="max-w-10xl mx-auto px-4 py-6">
    {{-- En-tête --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Événements</h1>
            <p class="mt-1 text-sm text-gray-500">
                Gérez les événements (soutenances, séminaires, conférences…). Vous pouvez filtrer,
                publier, archiver ou supprimer.
            </p>
        </div>

        <a href="{{ route('admin.evenements.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-ed-secondary focus:outline-none focus:ring-2 focus:ring-ed-primary/60 focus:ring-offset-1">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4"/>
            </svg>
            <span>Nouvel événement</span>
        </a>
    </div>

    {{-- Messages flash --}}
    @if(session()->has('success'))
        <div class="mb-4 rounded-xl border border-green-100 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div class="mb-4 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    {{-- Filtres --}}
    <div class="mb-5 rounded-2xl border border-gray-100 bg-white px-4 py-4 shadow-sm">
        <div class="grid grid-cols-1 gap-3 md:grid-cols-4">
            <div class="space-y-1">
                <label class="text-xs font-medium text-gray-500">Recherche</label>
                <input
                    wire:model.debounce.300ms="search"
                    type="text"
                    placeholder="Recherche par titre..."
                    class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm
                           placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-ed-primary/60 focus:border-ed-primary"
                />
            </div>

            <div class="space-y-1">
                <label class="text-xs font-medium text-gray-500">Type</label>
                <select
                    wire:model="filterType"
                    class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white
                           focus:outline-none focus:ring-2 focus:ring-ed-primary/60 focus:border-ed-primary"
                >
                    <option value="">Tous les types</option>
                    <option value="soutenance">Soutenance</option>
                    <option value="seminaire">Séminaire</option>
                    <option value="conference">Conférence</option>
                    <option value="atelier">Atelier</option>
                    <option value="autre">Autre</option>
                </select>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-medium text-gray-500">Publication</label>
                <select
                    wire:model="filterPublie"
                    class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white
                           focus:outline-none focus:ring-2 focus:ring-ed-primary/60 focus:border-ed-primary"
                >
                    <option value="">Tous</option>
                    <option value="publie">Publié</option>
                    <option value="non">Non publié</option>
                </select>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-medium text-gray-500">Éléments par page</label>
                <select
                    wire:model="perPage"
                    class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white
                           focus:outline-none focus:ring-2 focus:ring-ed-primary/60 focus:border-ed-primary"
                >
                    <option value="15">15 / page</option>
                    <option value="30">30 / page</option>
                    <option value="50">50 / page</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto rounded-2xl border border-gray-100 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titre</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Période</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lieu</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Important</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Publié</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($evenements as $e)
                    <tr class="{{ $e->est_archive ? 'bg-gray-50' : '' }}">
                        {{-- Titre + date courte --}}
                        <td class="px-4 py-3 align-top">
                            <div class="flex flex-col gap-0.5">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-gray-900">
                                        {{ $e->titre }}
                                    </span>
                                    @if($e->est_important)
                                        <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-0.5 text-[10px] font-semibold text-red-600">
                                            Important
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $e->date_fr }}
                                </div>
                            </div>
                        </td>

                        {{-- Période --}}
                        <td class="px-4 py-3 align-top text-sm text-gray-700">
                            {{ $e->periode_aff }}
                        </td>

                        {{-- Type --}}
                        <td class="px-4 py-3 align-top">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] {{ $e->type_classe }}">
                                {{ $e->type_texte }}
                            </span>
                        </td>

                        {{-- Lieu --}}
                        <td class="px-4 py-3 align-top text-sm text-gray-700">
                            {{ $e->lieu ?: '—' }}
                        </td>

                        {{-- Important (Oui/Non) --}}
                        <td class="px-4 py-3 align-top text-center">
                            @if($e->est_important)
                                <span class="text-xs font-semibold text-red-600">Oui</span>
                            @else
                                <span class="text-xs text-gray-400">Non</span>
                            @endif
                        </td>

                        {{-- Statut : À venir / Terminé / Archivé --}}
                        <td class="px-4 py-3 align-top text-center">
                            @if($e->est_archive)
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-[11px] font-medium text-gray-700">
                                    Archivé
                                </span>
                            @elseif($e->est_termine)
                                <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-0.5 text-[11px] font-medium text-amber-700">
                                    Terminé
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-[11px] font-medium text-emerald-700">
                                    À venir
                                </span>
                            @endif
                        </td>

                    {{-- Publié / Brouillon avec toggle --}}
                    <td class="px-4 py-3 align-top text-center">
                        <div class="flex flex-col items-center gap-1">

                            <button
                                @if($e->est_archive) disabled @endif
                                wire:click="togglePublication({{ $e->id }})"
                                wire:loading.attr="disabled"
                                wire:target="togglePublication({{ $e->id }})"
                                class="relative inline-flex h-5 w-10 items-center rounded-full transition
                                    {{ $e->est_archive ? 'bg-gray-300 opacity-50 cursor-not-allowed' : ($e->est_publie ? 'bg-emerald-500 cursor-pointer' : 'bg-gray-300 cursor-pointer') }}"
                                title="@if($e->est_archive)
                                            Archivé (publication désactivée)
                                    @elseif($e->est_publie)
                                            Publié (cliquer pour mettre en brouillon)
                                    @else
                                            Brouillon (cliquer pour publier)
                                    @endif"
                            >
                                <span class="sr-only">Basculer publication</span>

                                {{-- Knob --}}
                                <span
                                    class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition
                                        {{ $e->est_publie ? 'translate-x-5' : 'translate-x-1' }}">
                                </span>

                                {{-- Petit loader à côté pendant la bascule --}}
                                <span
                                    wire:loading
                                    wire:target="togglePublication({{ $e->id }})"
                                    class="absolute -right-5"
                                >
                                    <svg class="h-3 w-3 animate-spin" viewBox="0 0 24 24" fill="none">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="3"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8v3a5 5 0 00-5 5H4z"></path>
                                    </svg>
                                </span>
                            </button>

                            <div class="text-[11px] text-gray-500">
                                @if($e->est_archive)
                                    Archivé
                                @elseif($e->est_publie)
                                    Publié
                                @else
                                    Brouillon
                                @endif
                            </div>
                        </div>
                    </td>


                    {{-- Actions --}}
                    <td class="px-4 py-3 align-top">
                        <div class="flex items-center justify-end gap-1.5">

                            {{-- Voir --}}
                            <a href="{{ route('evenements.show', $e) }}"
                            target="_blank"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-gray-200 bg-white
                                    text-gray-600 hover:bg-gray-50 hover:text-gray-900"
                            title="Voir l’événement">
                                <span class="sr-only">Voir</span>
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    <circle cx="12" cy="12" r="3" stroke-width="1.8" stroke="currentColor"/>
                                </svg>
                            </a>

                            {{-- Modifier --}}
                            <a href="{{ route('admin.evenements.edit', $e) }}"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-ed-yellow
                                    text-gray-900 hover:bg-yellow-400"
                            title="Modifier l’événement">
                                <span class="sr-only">Modifier</span>
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M16.862 4.487l1.651-1.651a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M19.5 7.125L16.875 4.5"/>
                                </svg>
                            </a>

                            {{-- Archiver / Restaurer --}}
                            @if($e->est_archive)
                                {{-- Restaurer --}}
                                <button
                                    onclick="return confirm('Restaurer cet événement depuis les archives ?')"
                                    wire:click="restaurer({{ $e->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="restaurer({{ $e->id }})"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-gray-300 bg-white
                                        text-gray-700 hover:bg-gray-50"
                                    title="Restaurer depuis les archives"
                                >
                                    <span class="sr-only">Restaurer</span>

                                    {{-- Loader pendant la restauration --}}
                                    <span wire:loading wire:target="restaurer({{ $e->id }})">
                                        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                        </svg>
                                    </span>

                                    {{-- Icône RESTAURER (flèche retour) --}}
                                    <span wire:loading.remove wire:target="restaurer({{ $e->id }})">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9 15L3 9L9 3"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M3 9H13C16.3137 9 19 11.6863 19 15C19 18.3137 16.3137 21 13 21H11"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </button>
                            @elseif($e->est_termine)
                                {{-- Archiver --}}
                                <button
                                    onclick="return confirm('Archiver cet événement ? Il ne sera plus affiché sur le site.')"
                                    wire:click="archiver({{ $e->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="archiver({{ $e->id }})"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-gray-900
                                        text-white hover:bg-gray-800"
                                    title="Archiver l’événement"
                                >
                                    <span class="sr-only">Archiver</span>

                                    {{-- Loader pendant l’archivage --}}
                                    <span wire:loading wire:target="archiver({{ $e->id }})">
                                        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                        </svg>
                                    </span>

                                    {{-- Icône ARCHIVE --}}
                                    <span wire:loading.remove wire:target="archiver({{ $e->id }})">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4 7H20"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M5 7L6 5H18L19 7"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M6 7V18C6 18.5523 6.44772 19 7 19H17C17.5523 19 18 18.5523 18 18V7"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M10 11H14"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </button>
                            @endif


                            {{-- Supprimer --}}
                            <button
                                onclick="return confirm('Voulez-vous vraiment supprimer cet événement ? Cette action est irréversible.')"
                                wire:click="deleteConfirm({{ $e->id }})"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-red-500
                                    text-white hover:bg-red-600"
                                title="Supprimer l’événement"
                            >
                                <span class="sr-only">Supprimer</span>
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M6 7h12M10 11v6M14 11v6"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M9 7l1-2h4l1 2M7 7l1 12h8l1-12"/>
                                </svg>
                            </button>
                        </div>
                    </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-sm text-gray-500">
                            Aucun événement trouvé.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="border-t border-gray-100 px-4 py-3">
            {{ $evenements->links() }}
        </div>
    </div>

    {{-- Confirmation suppression (conserve ton système existant) --}}
    <script>
        window.addEventListener('confirm-delete', event => {
            if (confirm(event.detail.message)) {
                Livewire.emit('delete', event.detail.id);
            }
        });
    </script>
</div>
