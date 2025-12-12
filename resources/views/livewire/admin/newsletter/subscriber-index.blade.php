<div class="space-y-6">

    {{-- Flash --}}
    @if (session()->has('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Abonnés Newsletter</h1>
            <p class="text-sm text-slate-500">Gérer les abonnés (doctorants / encadrants), et importer/exporter via Excel.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            {{-- Export --}}
            <button wire:click="exportExcel"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 shadow-sm hover:bg-slate-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 10v9m0 0l3-3m-3 3l-3-3M7 10V6a2 2 0 012-2h6a2 2 0 012 2v4"/>
                </svg>
                Export Excel
            </button>

            {{-- Import --}}
            <div class="flex items-center gap-2">
                <label class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 shadow-sm hover:bg-slate-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 14V5m0 0l3 3m-3-3L9 8m-5 8v3a2 2 0 002 2h12a2 2 0 002-2v-3"/>
                    </svg>
                    <span>Choisir fichier</span>
                    <input type="file" wire:model="importFile" class="hidden" accept=".xlsx,.xls,.csv">
                </label>

                {{-- Pendant l'upload (téléversement) --}}
                <div wire:loading wire:target="importFile" class="text-sm text-slate-500 inline-flex items-center gap-2">
                    <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    Téléversement...
                </div>

                {{-- Après sélection (upload terminé) => afficher Importer --}}
                @if($importFile)
                    <div class="hidden sm:flex items-center gap-2 text-xs text-slate-500">
                        <span class="max-w-[220px] truncate">{{ $importFile->getClientOriginalName() }}</span>
                    </div>

                    <button wire:click="importExcel"
                            wire:loading.attr="disabled"
                            wire:target="importExcel"
                            class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-ed-secondary disabled:opacity-60 disabled:cursor-not-allowed">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 16l2 2m0 0l2-2m-2 2V10a6 6 0 10-12 0v8m4 0h4"/>
                        </svg>

                        <span wire:loading.remove wire:target="importExcel">Importer</span>

                        <span wire:loading wire:target="importExcel" class="inline-flex items-center gap-2">
                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            Import...
                        </span>
                    </button>

                    <button type="button"
                            wire:click="clearImportFile"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Annuler
                    </button>
                @endif
            </div>

            @error('importFile')
                <div class="text-sm text-rose-600">{{ $message }}</div>
            @enderror


            {{-- Create --}}
            <button wire:click="create"
                    class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-black">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4v16m8-8H4"/>
                </svg>
                Nouvel abonné
            </button>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-5">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold text-slate-500">Total</p>
            <p class="mt-1 text-2xl font-bold text-slate-900">{{ $stats['total'] }}</p>
        </div>
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
            <p class="text-xs font-semibold text-emerald-700">Actifs</p>
            <p class="mt-1 text-2xl font-bold text-emerald-800">{{ $stats['actifs'] }}</p>
        </div>
        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4">
            <p class="text-xs font-semibold text-rose-700">Inactifs</p>
            <p class="mt-1 text-2xl font-bold text-rose-800">{{ $stats['inactifs'] }}</p>
        </div>
        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4">
            <p class="text-xs font-semibold text-blue-700">Doctorants</p>
            <p class="mt-1 text-2xl font-bold text-blue-800">{{ $stats['doctorants'] }}</p>
        </div>
        <div class="rounded-2xl border border-violet-200 bg-violet-50 p-4">
            <p class="text-xs font-semibold text-violet-700">Encadrants</p>
            <p class="mt-1 text-2xl font-bold text-violet-800">{{ $stats['encadrants'] }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="grid grid-cols-1 gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm md:grid-cols-12">
        <div class="md:col-span-6">
            <div class="relative">
                <span class="pointer-events-none absolute left-3 top-2.5 text-slate-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.5 10.5a7.5 7.5 0 0013.15 6.15z"/>
                    </svg>
                </span>
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       class="w-full rounded-xl border-slate-200 pl-10 focus:border-ed-primary focus:ring-ed-primary/20"
                       placeholder="Rechercher par email ou nom...">
            </div>
        </div>

        <div class="md:col-span-3">
            <select wire:model.live="filterType"
                    class="w-full rounded-xl border-slate-200 focus:border-ed-primary focus:ring-ed-primary/20">
                <option value="all">Tous types</option>
                <option value="doctorant">Doctorants</option>
                <option value="encadrant">Encadrants</option>
                <option value="autre">Autres</option>
            </select>
        </div>

        <div class="md:col-span-3">
            <select wire:model.live="filterActif"
                    class="w-full rounded-xl border-slate-200 focus:border-ed-primary focus:ring-ed-primary/20">
                <option value="all">Tous statuts</option>
                <option value="actif">Actifs</option>
                <option value="inactif">Inactifs</option>
            </select>
        </div>

        @error('importFile')
            <div class="md:col-span-12 text-sm text-rose-600">{{ $message }}</div>
        @enderror
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50">
                <tr class="text-left text-slate-600">
                    <th class="px-4 py-3 font-semibold">Email</th>
                    <th class="px-4 py-3 font-semibold">Nom</th>
                    <th class="px-4 py-3 font-semibold">Type</th>
                    <th class="px-4 py-3 font-semibold">Abonné le</th>
                    <th class="px-4 py-3 font-semibold">Statut</th>
                    <th class="px-4 py-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($subscribers as $subscriber)
                    <tr class="text-slate-700 hover:bg-slate-50/60">
                        <td class="px-4 py-3">
                            <div class="font-semibold text-slate-900">{{ $subscriber->email }}</div>
                        </td>

                        <td class="px-4 py-3">
                            <div class="text-slate-700">{{ $subscriber->nom ?? '—' }}</div>
                        </td>

                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold
                                @if($subscriber->type === 'doctorant') bg-blue-50 text-blue-700
                                @elseif($subscriber->type === 'encadrant') bg-violet-50 text-violet-700
                                @else bg-slate-100 text-slate-700
                                @endif">
                                {{ ucfirst($subscriber->type) }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-slate-600">
                            {{ optional($subscriber->abonne_le ?? $subscriber->created_at)->format('d/m/Y') }}
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <button wire:click="toggleActif({{ $subscriber->id }})"
                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors
                                            {{ $subscriber->actif ? 'bg-emerald-600' : 'bg-rose-600' }}">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform
                                        {{ $subscriber->actif ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>

                                <span class="text-xs font-semibold {{ $subscriber->actif ? 'text-emerald-700' : 'text-rose-700' }}">
                                    {{ $subscriber->actif ? 'Actif' : 'Inactif' }}
                                </span>
                            </div>

                            @if(!$subscriber->actif && $subscriber->desabonne_le)
                                <div class="mt-1 text-xs text-rose-700">
                                    Désabonné le {{ $subscriber->desabonne_le->format('d/m/Y') }}
                                </div>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="edit({{ $subscriber->id }})"
                                        class="inline-flex items-center justify-center rounded-lg border border-slate-200 p-2 text-slate-700 hover:bg-slate-50"
                                        title="Modifier">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                <button wire:click="delete({{ $subscriber->id }})"
                                        onclick="return confirm('Supprimer cet abonné ?')"
                                        class="inline-flex items-center justify-center rounded-lg border border-rose-200 bg-rose-50 p-2 text-rose-700 hover:bg-rose-100"
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
                        <td colspan="6" class="px-4 py-12 text-center text-slate-500">
                            Aucun abonné trouvé.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $subscribers->links() }}
    </div>

    {{-- Modal Create/Edit --}}
    @if($editing)
        <div class="fixed inset-0 z-50">
            <div class="absolute inset-0 bg-slate-900/40" wire:click="$set('editing', false)"></div>

            <div class="relative mx-auto mt-20 w-full max-w-lg px-4">
                <div class="rounded-2xl bg-white shadow-2xl overflow-hidden">
                    <div class="bg-gradient-to-r from-ed-primary to-ed-secondary px-6 py-4">
                        <h3 class="text-base font-semibold text-white">
                            {{ $subscriberId ? 'Modifier l’abonné' : 'Nouvel abonné' }}
                        </h3>
                    </div>

                    <form wire:submit.prevent="save" class="p-6 space-y-4">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Email *</label>
                            <input type="email" wire:model="email"
                                   class="w-full rounded-xl border-slate-200 focus:border-ed-primary focus:ring-ed-primary/20"
                                   placeholder="exemple@email.com">
                            @error('email') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Nom (optionnel)</label>
                            <input type="text" wire:model="nom"
                                   class="w-full rounded-xl border-slate-200 focus:border-ed-primary focus:ring-ed-primary/20"
                                   placeholder="Prénom Nom">
                            @error('nom') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Type *</label>
                            <select wire:model="type"
                                    class="w-full rounded-xl border-slate-200 focus:border-ed-primary focus:ring-ed-primary/20">
                                <option value="doctorant">Doctorant</option>
                                <option value="encadrant">Encadrant</option>
                                <option value="autre">Autre</option>
                            </select>
                            @error('type') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="pt-1">
                            <label class="flex items-center gap-3">
                                <input type="checkbox" wire:model="actif"
                                       class="h-5 w-5 rounded border-slate-300 text-ed-primary focus:ring-ed-primary/30">
                                <span class="text-sm font-medium text-slate-700">Abonnement actif</span>
                            </label>
                            <p class="mt-1 text-xs text-slate-500 ml-8">
                                Les inactifs ne recevront pas les notifications.
                            </p>
                        </div>

                        @if($subscriberId && $subscriberCreatedAt)
                            <div class="border-t border-slate-100 pt-3 text-xs text-slate-500">
                                Inscrit le : <span class="font-semibold">{{ $subscriberCreatedAt->format('d/m/Y à H:i') }}</span>
                            </div>
                        @endif

                        <div class="flex justify-end gap-2 pt-4">
                            <button type="button" wire:click="$set('editing', false)"
                                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Annuler
                            </button>
                            <button type="submit"
                                    class="rounded-xl bg-ed-primary px-5 py-2 text-sm font-semibold text-white hover:bg-ed-secondary">
                                {{ $subscriberId ? 'Mettre à jour' : 'Créer' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
