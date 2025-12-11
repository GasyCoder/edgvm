<div class="max-w-4xl mx-auto px-4 py-6 lg:py-8">
    {{-- En-tête --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">
                Créer un événement
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Renseignez les informations principales de l’événement. Les champs marqués d’un
                <span class="text-red-500 font-semibold">*</span> sont obligatoires.
            </p>
        </div>

        <a href="{{ route('admin.evenements.index') }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Retour à la liste</span>
        </a>
    </div>

    {{-- Résumé des erreurs --}}
    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-100 bg-red-50 p-4 text-sm text-red-700">
            <div class="font-semibold mb-1">Veuillez corriger les erreurs suivantes :</div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulaire --}}
    <form wire:submit.prevent="submit"
          class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6 lg:p-8 space-y-8">

        {{-- Bloc 1 : Infos principales --}}
        <div class="space-y-4">
            <h2 class="text-base font-semibold text-gray-900">
                Informations générales
            </h2>
            <div class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Titre <span class="text-red-500">*</span>
                    </label>
                    <input
                        wire:model.defer="titre"
                        type="text"
                        class="block w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm
                               focus:outline-none focus:ring-2 focus:ring-ed-primary/60 focus:border-ed-primary
                               placeholder-gray-400"
                        placeholder="Ex. : Soutenance de thèse, Séminaire EDGVM..."
                    />
                    @error('titre')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Type <span class="text-red-500">*</span>
                    </label>
                    <select
                        wire:model.defer="type"
                        class="block w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm
                               bg-white focus:outline-none focus:ring-2 focus:ring-ed-primary/60 focus:border-ed-primary"
                    >
                        <option value="soutenance">Soutenance</option>
                        <option value="seminaire">Séminaire</option>
                        <option value="conference">Conférence</option>
                        <option value="atelier">Atelier</option>
                        <option value="autre">Autre</option>
                    </select>
                    @error('type')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    Description
                </label>
                <textarea
                    wire:model.defer="description"
                    rows="4"
                    class="block w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm
                           focus:outline-none focus:ring-2 focus:ring-ed-primary/60 focus:border-ed-primary
                           placeholder-gray-400"
                    placeholder="Quelques lignes pour décrire le contexte, les intervenants, le public cible..."
                ></textarea>
                @error('description')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Bloc 2 : Dates, heures et lieu --}}
        <div class="space-y-4">
            <h2 class="text-base font-semibold text-gray-900">
                Date, heure et lieu
            </h2>

            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Date de début <span class="text-red-500">*</span>
                    </label>
                    <input
                        wire:model.defer="date_debut"
                        type="date"
                        class="block w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm
                               focus:outline-none focus:ring-2 focus:ring-ed-primary/60 focus:border-ed-primary"
                    />
                    @error('date_debut')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Heure de début
                    </label>
                    <input
                        wire:model.defer="heure_debut"
                        type="time"
                        class="block w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm
                               focus:outline-none focus:ring-2 focus:ring-ed-primary/60 focus:border-ed-primary"
                    />
                    @error('heure_debut')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Date de fin
                    </label>
                    <input
                        wire:model.defer="date_fin"
                        type="date"
                        class="block w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm
                               focus:outline-none focus:ring-2 focus:ring-ed-primary/60 focus:border-ed-primary"
                    />
                    @error('date_fin')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-400">
                        Laissez vide si l’événement se déroule sur une seule journée.
                    </p>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Heure de fin
                    </label>
                    <input
                        wire:model.defer="heure_fin"
                        type="time"
                        class="block w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm
                               focus:outline-none focus:ring-2 focus:ring-ed-primary/60 focus:border-ed-primary"
                    />
                    @error('heure_fin')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    Lieu
                </label>
                <input
                    wire:model.defer="lieu"
                    type="text"
                    class="block w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm
                           focus:outline-none focus:ring-2 focus:ring-ed-primary/60 focus:border-ed-primary
                           placeholder-gray-400"
                    placeholder="Ex. : Amphithéâtre A, Salle de conférence, En ligne..."
                />
                @error('lieu')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Bloc 3 : Médias associés --}}
        <div class="space-y-4">
            <h2 class="text-base font-semibold text-gray-900">
                Médias associés
            </h2>

            <div class="grid gap-6 md:grid-cols-2">
                {{-- Image de couverture --}}
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Image de couverture (fond principal)
                    </label>
                    <div class="border border-dashed border-gray-300 rounded-xl p-3 bg-gray-50">
                        <input
                            type="file"
                            wire:model="cover_image"
                            accept="image/*"
                            class="block w-full text-xs text-gray-500
                                   file:mr-3 file:py-1.5 file:px-3
                                   file:rounded-lg file:border-0
                                   file:text-xs file:font-semibold
                                   file:bg-ed-primary/10 file:text-ed-primary
                                   hover:file:bg-ed-primary/20"
                        >
                        <p class="mt-1 text-[11px] text-gray-400">
                            Formats acceptés : JPG, PNG, WebP. Taille max : 2 Mo.
                        </p>

                        @error('cover_image')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror

                        @if($cover_image)
                            <div class="mt-3">
                                <p class="text-xs text-gray-500 mb-1">Aperçu :</p>
                                <img src="{{ $cover_image->temporaryUrl() }}"
                                     alt="Aperçu"
                                     class="w-full h-32 object-cover rounded-lg border border-gray-200">
                            </div>
                        @endif
                    </div>
                </div>

{{-- Document PDF depuis la médiathèque avec recherche --}}
<div class="space-y-2">
    <label class="block text-sm font-medium text-gray-700">
        Document PDF associé (médiathèque)
    </label>

    {{-- Résumé du document sélectionné --}}
    @if($this->selectedPdf)
        <div class="flex items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2">
            <div class="flex items-center gap-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100">
                    <svg class="h-4 w-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 11V7m0 4v4m0-4h4m-4 0H8m-2 8h12a2 2 0 002-2V7a2 2 0 00-.586-1.414l-3-3A2 2 0 0014 2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs font-semibold text-emerald-900 truncate max-w-[220px]">
                        {{ $this->selectedPdf->display_name }}
                    </span>
                    <span class="text-[11px] text-emerald-700">
                        {{ strtoupper($this->selectedPdf->mime_type) }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ $this->selectedPdf->url }}"
                   target="_blank"
                   class="inline-flex items-center rounded-full bg-white px-2 py-1 text-[11px] font-medium text-emerald-700 hover:bg-emerald-50 border border-emerald-200">
                    Voir
                </a>
                <button
                    type="button"
                    wire:click="clearPdf"
                    class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-1 text-[11px] font-medium text-emerald-800 hover:bg-emerald-200">
                    Retirer
                </button>
            </div>
        </div>
    @endif

    {{-- Zone de recherche --}}
    <label class="block text-xs font-medium text-gray-600 mt-2">
        Rechercher dans la banque des fichiers
    </label>

    <div class="relative">
        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>
            </svg>
        </span>
        <input
            type="text"
            wire:model.live.debounce.300ms="pdfSearch"
            placeholder="Nom du fichier, mot-clé..."
            class="block w-full rounded-xl border border-gray-300 pl-9 pr-3 py-2 text-sm
                   focus:outline-none focus:ring-2 focus:ring-ed-primary/60 focus:border-ed-primary
                   placeholder-gray-400"
        />
    </div>

    @php
        $hasSearch = strlen(trim($pdfSearch ?? '')) >= 2;
    @endphp

    <p class="mt-1 text-[11px] text-gray-400">
        @if($hasSearch)
            Résultats filtrés (max. 20). Modifiez le mot-clé pour affiner.
        @else
            Tapez au moins 2 caractères pour afficher la liste des documents.
        @endif
    </p>

    {{-- Liste scrollable des PDF : uniquement si recherche suffisante --}}
    @if($hasSearch)
        <div class="mt-2 max-h-60 overflow-y-auto rounded-xl border border-gray-200 bg-gray-50/60">
            @forelse($this->pdfMedias as $media)
                <button
                    type="button"
                    wire:click="selectPdf({{ $media->id }})"
                    class="flex w-full items-center justify-between px-3 py-2 text-left text-xs
                           hover:bg-white transition
                           {{ $document_media_id == $media->id ? 'bg-ed-primary/5 border-l-4 border-ed-primary/70' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg
                                    {{ $document_media_id == $media->id ? 'bg-ed-primary text-white' : 'bg-white text-gray-500 border border-gray-200' }}">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 3h4a2 2 0 012 2v4M15 3l-6 6M15 3v4a2 2 0 01-2 2h-4"/>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-medium text-gray-800 truncate max-w-[220px]">
                                {{ $media->display_name }}
                            </span>
                            <span class="text-[11px] text-gray-500">
                                {{ strtoupper($media->mime_type) }} • {{ $media->created_at?->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>

                    @if($document_media_id == $media->id)
                        <span class="text-[11px] font-semibold text-ed-primary">
                            Sélectionné
                        </span>
                    @endif
                </button>
            @empty
                <p class="px-3 py-2 text-[11px] text-gray-500 italic">
                    Aucun document ne correspond à la recherche.
                </p>
            @endforelse
        </div>
    @endif

    @error('document_media_id')
        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
    @enderror

    <p class="text-xs text-gray-400">
        Les fichiers listés proviennent de la banque des fichiers (type « document »).
    </p>
</div>



            </div>
        </div>

        {{-- Bloc 4 : Paramètres d’affichage + Newsletter --}}
        <div class="space-y-4">
            <h2 class="text-base font-semibold text-gray-900">
                Paramètres d’affichage
            </h2>

            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Lien d’inscription
                    </label>
                    <input
                        wire:model.defer="lien_inscription"
                        type="url"
                        class="block w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm
                               focus:outline-none focus:ring-2 focus:ring-ed-primary/60 focus:border-ed-primary
                               placeholder-gray-400"
                        placeholder="https://..."
                    />
                    @error('lien_inscription')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-400">
                        Facultatif. Laissez vide si aucune inscription en ligne n’est requise.
                    </p>
                </div>

                <div class="space-y-3">
                    <div class="flex flex-wrap items-center gap-4">
                        <label class="inline-flex items-center gap-2">
                            <input
                                type="checkbox"
                                wire:model="est_important"
                                class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500"
                            />
                            <span class="text-sm text-gray-700">
                                Marquer comme <span class="font-semibold">important</span>
                            </span>
                        </label>

                        <label class="inline-flex items-center gap-2">
                            <input
                                type="checkbox"
                                wire:model="est_publie"
                                class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                            />
                            <span class="text-sm text-gray-700">
                                <span class="font-semibold">Publié</span> sur le site
                            </span>
                        </label>
                    </div>

                    <p class="text-xs text-gray-400">
                        Si “Publié” est décoché, l’événement sera enregistré mais non visible côté site.
                    </p>
                </div>
            </div>

            {{-- Sous-bloc : Notification newsletter --}}
            <div class="mt-4 pt-4 border-t border-gray-100 space-y-3">
                <h3 class="text-sm font-semibold text-gray-900">
                    Notification newsletter
                </h3>
                <p class="text-xs text-gray-500">
                    Choisissez à qui envoyer l’annonce de cet événement. Aucun envoi si aucune case n’est cochée.
                </p>

                <div class="space-y-2">
                    <label class="inline-flex items-center gap-2">
                        <input
                            type="checkbox"
                            wire:model="notify_all"
                            class="h-4 w-4 rounded border-gray-300 text-ed-primary focus:ring-ed-primary"
                        />
                        <span class="text-sm text-gray-800">
                            Tous les abonnés actifs
                        </span>
                    </label>

                    <div class="pl-6 space-y-1">
                        <label class="inline-flex items-center gap-2">
                            <input
                                type="checkbox"
                                wire:model="notify_encadrants"
                                class="h-4 w-4 rounded border-gray-300 text-ed-primary focus:ring-ed-primary"
                                @if($notify_all) disabled @endif
                            />
                            <span class="text-sm text-gray-700">
                                Encadrants
                            </span>
                        </label>

                        <label class="inline-flex items-center gap-2">
                            <input
                                type="checkbox"
                                wire:model="notify_doctorants"
                                class="h-4 w-4 rounded border-gray-300 text-ed-primary focus:ring-ed-primary"
                                @if($notify_all) disabled @endif
                            />
                            <span class="text-sm text-gray-700">
                                Doctorants
                            </span>
                        </label>
                    </div>

                    <p class="text-[11px] text-gray-400">
                        Si “Tous les abonnés” est coché, les filtres Encadrants / Doctorants seront ignorés.
                    </p>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.evenements.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white
                      px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Annuler
            </a>

            <button
                type="submit"
                wire:target="submit"
                wire:loading.attr="disabled"
                wire:loading.class="cursor-not-allowed opacity-70"
                class="inline-flex items-center justify-center gap-2 rounded-xl
                       bg-ed-primary px-5 py-2.5 text-sm font-semibold text-white
                       shadow-sm hover:bg-ed-secondary focus:outline-none
                       focus:ring-2 focus:ring-ed-primary/60 focus:ring-offset-1"
            >
                <span wire:loading wire:target="submit" class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <span>Création en cours...</span>
                </span>

                <span wire:loading.remove wire:target="submit" class="inline-flex items-center gap-2">
                    <span>Créer l’événement</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </span>
            </button>
        </div>
    </form>
</div>
