<div class="space-y-6">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Annonces académiques</h1>
            <p class="text-sm text-gray-500">Créer, publier et envoyer des annonces (doctorants / encadrants).</p>
        </div>

        <a href="{{ route('admin.annonces.create') }}"
           class="inline-flex items-center px-4 py-2 rounded-xl bg-ed-primary text-white text-sm font-semibold hover:bg-ed-secondary transition">
            + Nouvelle annonce
        </a>
    </div>

    @if (session('success'))
        <div class="rounded-xl bg-emerald-50 text-emerald-800 px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="rounded-xl bg-red-50 text-red-700 px-4 py-3 text-sm">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
        <div class="md:col-span-6">
            <input type="text" wire:model.live="search"
                   class="w-full rounded-xl border-gray-200 focus:border-ed-primary focus:ring-ed-primary/20"
                   placeholder="Rechercher par titre...">
        </div>

        <div class="md:col-span-3">
            <select wire:model.live="filterCible"
                    class="w-full rounded-xl border-gray-200 focus:border-ed-primary focus:ring-ed-primary/20">
                <option value="">Toutes cibles</option>
                <option value="doctorant">Doctorants</option>
                <option value="encadrant">Encadrants</option>
                <option value="both">Doctorants & Encadrants</option>
            </select>
        </div>

        <div class="md:col-span-3">
            <select wire:model.live="filterPublie"
                    class="w-full rounded-xl border-gray-200 focus:border-ed-primary focus:ring-ed-primary/20">
                <option value="">Tous statuts</option>
                <option value="1">Publié</option>
                <option value="0">Brouillon</option>
            </select>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-left text-gray-600">
                    <th class="px-4 py-3 font-semibold">Titre</th>
                    <th class="px-4 py-3 font-semibold">Cible</th>
                    <th class="px-4 py-3 font-semibold">Fichier</th>
                    <th class="px-4 py-3 font-semibold">Publication</th>
                    <th class="px-4 py-3 font-semibold">Email</th>
                    <th class="px-4 py-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($annonces as $annonce)
                    <tr class="text-gray-700">
                        <td class="px-4 py-3">
                            <div class="font-semibold text-gray-900">{{ $annonce->titre }}</div>
                            <div class="text-xs text-gray-500">
                                Créé le {{ $annonce->created_at->format('d/m/Y H:i') }}
                            </div>
                        </td>

                        <td class="px-4 py-3">
                            <span class="inline-flex px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-700">
                                {{ $annonce->cible }}
                            </span>
                        </td>

                        <td class="px-4 py-3">
                            @if($annonce->media_url)
                                <a href="{{ $annonce->media_url }}" target="_blank" class="text-ed-primary font-semibold hover:underline">
                                    {{ $annonce->media_name ?? 'Fichier' }}
                                </a>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            @if($annonce->est_publie)
                                <span class="inline-flex px-2 py-1 rounded-full text-xs bg-emerald-50 text-emerald-700">Publié</span>
                            @else
                                <span class="inline-flex px-2 py-1 rounded-full text-xs bg-amber-50 text-amber-700">Brouillon</span>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            <div class="text-xs text-gray-600">
                                Cible: <span class="font-semibold">{{ $annonce->email_cible ?? '—' }}</span>
                            </div>
                            <div class="text-xs text-gray-500">
                                Envoyé: {{ $annonce->email_envoye_at?->format('d/m/Y H:i') ?? '—' }}
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1.5">

                                {{-- Modifier --}}
                                <a href="{{ route('admin.annonces.edit', $annonce) }}"
                                title="Modifier"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg
                                        text-slate-600 hover:text-ed-primary hover:bg-slate-50 transition"
                                >
                                    <span class="sr-only">Modifier</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16.862 3.487a2.1 2.1 0 113.0 3.0L8.5 17.85 4 19l1.15-4.5L16.862 3.487z"/>
                                    </svg>
                                </a>

                                {{-- Publier / Dépublier --}}
                                <button type="button"
                                        wire:click="togglePublie({{ $annonce->id }})"
                                        title="{{ $annonce->est_publie ? 'Dépublier' : 'Publier' }}"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg transition
                                            {{ $annonce->est_publie
                                                    ? 'text-amber-700 hover:bg-amber-50'
                                                    : 'text-emerald-700 hover:bg-emerald-50' }}"
                                >
                                    <span class="sr-only">{{ $annonce->est_publie ? 'Dépublier' : 'Publier' }}</span>

                                    @if($annonce->est_publie)
                                        {{-- eye-slash --}}
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 3l18 18M10.477 10.49a3 3 0 004.243 4.243M9.88 5.09A10.94 10.94 0 0112 5c6.4 0 10 7 10 7a18.4 18.4 0 01-3.314 4.33M6.11 6.12C3.735 7.74 2 12 2 12a18.6 18.6 0 004.2 5.1A10.86 10.86 0 0012 19c1.26 0 2.46-.22 3.57-.62"/>
                                        </svg>
                                    @else
                                        {{-- eye --}}
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15a3 3 0 110-6 3 3 0 010 6z"/>
                                        </svg>
                                    @endif
                                </button>

                                {{-- Envoyer email --}}
                                <button type="button"
                                        wire:click="sendEmailNow({{ $annonce->id }})"
                                        title="Envoyer email"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg
                                            text-ed-primary hover:bg-ed-primary/10 transition"
                                >
                                    <span class="sr-only">Envoyer email</span>
                                    {{-- paper-airplane --}}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.5 12L3 9l18-7-7 18-3-7.5zm0 0l4.5-4.5"/>
                                    </svg>
                                </button>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-gray-500">
                            Aucune annonce trouvée.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $annonces->links() }}</div>
</div>
