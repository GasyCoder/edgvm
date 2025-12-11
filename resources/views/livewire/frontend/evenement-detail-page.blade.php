<div>
    @php
        // Image de couverture
        $coverMedia = $evenement->image_id
            ? \App\Models\Media::find($evenement->image_id)
            : null;
        $coverUrl = $coverMedia?->url;

        // Document PDF associé
        $docMedia = $evenement->document_id
            ? \App\Models\Media::find($evenement->document_id)
            : null;
    @endphp

    {{-- HERO AVEC IMAGE SI DISPONIBLE --}}
    <section class="relative pt-24 md:pt-28 pb-10 overflow-hidden">
        @if($coverUrl)
            {{-- Background = image de l’événement --}}
            <div class="absolute inset-0">
                <img
                    src="{{ $coverUrl }}"
                    alt="Image de l'événement"
                    class="w-full h-full object-cover"
                >
                <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/75 to-black/80"></div>
            </div>
        @else
            {{-- Background par défaut (gradient EDGVM) --}}
            <div class="absolute inset-0 opacity-15 pointer-events-none">
                <div class="absolute -top-10 right-0 w-40 h-40 bg-white rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 -left-10 w-40 h-40 bg-ed-yellow rounded-full blur-3xl"></div>
            </div>
            <div class="absolute inset-0 bg-gradient-to-b from-ed-primary via-ed-secondary to-black/80 opacity-80"></div>
        @endif

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Ligne retour + statut --}}
            <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                <a href="{{ route('evenements.index') }}"
                   class="inline-flex items-center gap-1.5 text-xs sm:text-sm text-white/80 hover:text-white">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span>Retour à l’agenda</span>
                </a>

                <div class="inline-flex items-center gap-1.5 rounded-full bg-black/35 text-white px-3 py-1.5 text-[11px] font-medium">
                    @if($evenement->est_termine)
                        <span class="inline-flex w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        <span>Événement terminé</span>
                    @else
                        <span class="inline-flex w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        <span>Événement à venir</span>
                    @endif
                </div>
            </div>

            {{-- Contenu hero --}}
            <div class="flex flex-col md:flex-row items-start gap-6 text-white">
                {{-- Bloc date --}}
                <div class="flex md:flex-col items-center md:items-stretch gap-3">
                    <div class="flex flex-col items-center justify-center rounded-2xl bg-black/25 backdrop-blur w-20 h-20 border border-white/20 shadow-lg shadow-black/40">
                        <span class="text-2xl font-black leading-none">
                            {{ $evenement->jour }}
                        </span>
                        <span class="text-[11px] uppercase tracking-wide">
                            {{ $evenement->mois }}
                        </span>
                    </div>
                </div>

                {{-- Bloc texte principal --}}
                <div class="flex-1 min-w-0 space-y-3">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/20 text-[11px] font-semibold tracking-[0.18em] uppercase">
                        <span class="w-2 h-2 rounded-full bg-ed-yellow"></span>
                        <span>Agenda EDGVM</span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-black leading-tight">
                        {{ $evenement->titre }}
                    </h1>

                    {{-- Méta : période + heure + lieu (une seule fois) --}}
                    <div class="space-y-1.5 text-sm text-white/90">
                        <div class="flex flex-wrap items-center gap-3">
                            {{-- Période --}}
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 01-2 2z"/>
                                </svg>
                                <span>{{ $evenement->periode_aff }}</span>
                            </span>

                            {{-- Heure de début --}}
                            @if($evenement->heure_debut_aff ?? false)
                                <span class="inline-flex items-center gap-1.5 text-white/85">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>{{ $evenement->heure_debut_aff }}</span>
                                </span>
                            @endif
                        </div>

                        {{-- Lieu --}}
                        @if($evenement->lieu)
                            <div class="inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M17.657 16.657L13.414 20.9a1 1 0 0 1-1.414 0L6.343 16.657a8 8 0 1 1 11.314 0z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M15 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"
                                    />
                                </svg>
                                <span>{{ $evenement->lieu }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Type + important --}}
                <div class="flex flex-col items-end gap-2">
                    <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-semibold {{ $evenement->type_classe }}">
                        {{ $evenement->type_texte }}
                    </span>

                    @if($evenement->est_important)
                        <span class="inline-flex items-center gap-1 rounded-full bg-red-50/95 px-2.5 py-1 text-[11px] font-semibold text-red-700 shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 9v3.5m0 4h.01M12 5a7 7 0 100 14 7 7 0 000-14z"/>
                            </svg>
                            Important
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- CONTENU + SIDEBAR NEWSLETTER --}}
    <section class="py-10 md:py-14 bg-gradient-to-b from-gray-50 via-white to-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Colonne principale : description + image + document + lieu --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 md:p-6 lg:p-7 space-y-6">

                        {{-- Description --}}
                        <div>
                            <h2 class="text-sm font-semibold text-gray-800 mb-2">
                                Description
                            </h2>

                            @if($evenement->description)
                                <div class="text-sm text-gray-700 leading-relaxed space-y-3">
                                    {!! nl2br(e($evenement->description)) !!}
                                </div>
                            @else
                                <p class="text-sm text-gray-500">
                                    Aucune description détaillée n’a été fournie pour cet événement.
                                </p>
                            @endif
                        </div>

                        {{-- VISUEL ÉVÉNEMENT (dans la colonne principale) --}}
                        @if($coverUrl)
                            <div class="pt-2">
                                <h3 class="text-sm font-semibold text-gray-800 mb-2">
                                    Visuel de l’événement
                                </h3>

                                <div class="relative w-full rounded-2xl overflow-hidden border border-gray-200 bg-gray-50 shadow-sm">
                                    <img
                                        src="{{ $coverUrl }}"
                                        alt="Visuel de l'événement"
                                        class="w-full h-64 md:h-72 object-cover"
                                    >
                                </div>
                            </div>
                        @endif

                        {{-- Document associé (PDF) --}}
                        @if($docMedia && $docMedia->url)
                            <div class="pt-2 border-t border-gray-100">
                                <h3 class="text-sm font-semibold text-gray-800 mb-2 flex items-center gap-2">
                                    <span class="inline-flex w-7 h-7 items-center justify-center rounded-full bg-emerald-50 text-emerald-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 3h4a2 2 0 012 2v4M15 3l-6 6M15 3v4a2 2 0 01-2 2h-4M5 21h14a2 2 0 002-2V7a2 2 0 00-.586-1.414l-3-3A2 2 0 0014 2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </span>
                                    <span>Document associé</span>
                                </h3>

                                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 rounded-xl border border-emerald-100 bg-emerald-50/60 px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-white text-emerald-700 border border-emerald-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M15 12H9m0 0l3-3m-3 3l3 3m7-6.5V17a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h5.5"/>
                                            </svg>
                                        </div>
                                        <div class="flex flex-col text-xs">
                                            <span class="font-semibold text-emerald-900 truncate max-w-[220px]">
                                                {{ $docMedia->display_name ?? $docMedia->nom_original }}
                                            </span>
                                            <span class="text-[11px] text-emerald-700">
                                                {{ strtoupper($docMedia->mime_type ?? 'PDF') }}
                                                @if($docMedia->created_at)
                                                    • ajouté le {{ $docMedia->created_at->format('d/m/Y') }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    <a href="{{ $docMedia->url }}"
                                       target="_blank"
                                       class="inline-flex items-center gap-1.5 rounded-full bg-emerald-600 px-3 py-1.5 text-[11px] font-semibold text-white hover:bg-emerald-700 shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"/>
                                        </svg>
                                        <span>Télécharger le PDF</span>
                                    </a>
                                </div>
                            </div>
                        @endif

                        {{-- Lieu + placeholder carte --}}
                        @if($evenement->lieu)
                            <div class="pt-2">
                                <h3 class="text-sm font-semibold text-gray-800 mb-2">
                                    Lieu & accès
                                </h3>
                                <div class="w-full h-56 rounded-2xl border border-dashed border-gray-200 bg-gray-50 flex items-center justify-center px-4">
                                    <div class="text-center text-xs sm:text-sm text-gray-500">
                                        <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-700 mb-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1 1 0 0 1-1.414 0L6.343 16.657a8 8 0 1 1 11.314 0z"
                                                />
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M15 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"
                                                />
                                            </svg>
                                        </div>
                                        <p>Zone pour intégrer ultérieurement une carte (Google Maps / Leaflet).</p>
                                        <p class="mt-1 font-medium text-gray-700">
                                            {{ $evenement->lieu }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Actions --}}
                        <div class="pt-4 border-t border-gray-100 flex flex-wrap items-center gap-3">
                            @if($evenement->lien_inscription && ! $evenement->est_termine)
                                <a href="{{ $evenement->lien_inscription }}"
                                   target="_blank"
                                   class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white
                                          shadow-sm hover:bg-ed-secondary focus:outline-none focus:ring-2 focus:ring-ed-primary/60 focus:ring-offset-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 4v16m8-8H4"/>
                                    </svg>
                                    <span>S’inscrire à l’événement</span>
                                </a>
                            @elseif($evenement->est_termine)
                                <div class="inline-flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Événement terminé — inscriptions closes</span>
                                </div>
                            @endif

                            <a href="{{ route('evenements.index') }}"
                               class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5
                                      text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none
                                      focus:ring-2 focus:ring-ed-primary/40 focus:ring-offset-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 19l-7-7 7-7"/>
                                </svg>
                                <span>Retour à l’agenda</span>
                            </a>
                        </div>

                    </div>
                </div>

                {{-- SIDEBAR : NEWSLETTER --}}
                <aside class="space-y-5">
                    <div class="bg-gradient-to-br from-ed-primary via-ed-secondary to-yellow-600 rounded-2xl shadow-xl overflow-hidden">
                        <div class="p-6 text-white">
                            <div class="text-center mb-4">
                                <div class="w-14 h-14 bg-white/20 backdrop-blur-lg rounded-xl flex items-center justify-center mx-auto mb-3 border border-white/30">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold mb-1">Newsletter</h3>
                                <p class="text-white/80 text-xs">Recevez nos actualités et événements</p>
                            </div>

                            {{-- Formulaire Livewire newsletter (adapter si nécessaire au composant) --}}
                            <form wire:submit.prevent="subscribe" class="space-y-2.5">
                                <input
                                    type="email"
                                    wire:model.defer="newsletterEmail"
                                    placeholder="Votre adresse email"
                                    class="w-full px-3 py-2.5 text-sm rounded-lg bg-white/20 backdrop-blur-md border border-white/30
                                           text-white placeholder-white/70 focus:ring-2 focus:ring-white focus:outline-none transition"
                                    required
                                >

                                <input
                                    type="text"
                                    wire:model.defer="newsletterNom"
                                    placeholder="Nom (optionnel)"
                                    class="w-full px-3 py-2.5 text-sm rounded-lg bg-white/20 backdrop-blur-md border border-white/30
                                           text-white placeholder-white/70 focus:ring-2 focus:ring-white focus:outline-none transition"
                                >

                                <button
                                    type="submit"
                                    class="w-full px-4 py-2.5 bg-white text-ed-primary rounded-lg font-bold hover:bg-ed-yellow
                                           transition shadow-lg text-sm"
                                >
                                    S'abonner
                                </button>
                            </form>

                            @if (session()->has('newsletter_success'))
                                <div class="mt-3 p-3 bg-green-500/30 backdrop-blur-md border border-green-300/40 rounded-lg text-white text-xs">
                                    ✅ {{ session('newsletter_success') }}
                                </div>
                            @endif

                            @error('newsletterEmail')
                                <div class="mt-3 p-3 bg-red-500/30 backdrop-blur-md border border-red-300/40 rounded-lg text-white text-xs">
                                    ❌ {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Bloc info complémentaire --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 text-xs text-gray-600">
                        <p class="font-semibold text-gray-800 mb-1">
                            Besoin d’informations complémentaires ?
                        </p>
                        <p>
                            Vous pouvez contacter le secrétariat de l’École Doctorale pour toute question
                            relative à cet événement (programme, inscriptions, modalités, etc.).
                        </p>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</div>
