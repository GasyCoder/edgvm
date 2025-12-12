<section
    id="actualites-agenda"
    aria-labelledby="news-agenda-heading"
    class="relative overflow-hidden py-16 md:py-20 bg-gradient-to-b from-gray-50 via-white to-gray-50"
>
    {{-- Décorations --}}
    <div class="pointer-events-none absolute -top-24 right-[-6rem] h-72 w-72 rounded-full bg-ed-primary/7 blur-3xl"></div>
    <div class="pointer-events-none absolute bottom-[-5rem] left-[-6rem] h-72 w-72 rounded-full bg-ed-yellow/8 blur-3xl"></div>
    <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>

    <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <header class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between mb-10 md:mb-12">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 rounded-full border border-ed-primary/20 bg-ed-primary/8 px-3 py-1.5">
                    <span class="h-2 w-2 rounded-full bg-ed-primary"></span>
                    <span class="text-[11px] font-semibold tracking-[0.16em] uppercase text-ed-primary">
                        Dernières informations
                    </span>
                </div>

                <h2 id="news-agenda-heading" class="mt-3 text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">
                    Actualités &amp; agenda des événements
                </h2>

                <p class="mt-2 text-sm sm:text-[15px] leading-relaxed text-gray-600">
                    Suivez la vie de l’École Doctorale : annonces, séminaires, soutenances, conférences et activités scientifiques majeures.
                </p>
            </div>

            <div class="flex flex-wrap gap-2.5">
                <a
                    href="{{ route('actualites.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-[12px] font-semibold text-gray-700
                           shadow-sm transition hover:-translate-y-[1px] hover:border-ed-primary/50 hover:text-ed-primary hover:shadow-md
                           focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ed-primary/50"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0-4-4m4 4-4 4"/>
                    </svg>
                    <span>Toutes les actualités</span>
                </a>

                <a
                    href="{{ route('evenements.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2.5 text-[12px] font-semibold text-white
                           shadow-md transition hover:-translate-y-[1px] hover:bg-ed-secondary hover:shadow-lg
                           focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ed-primary/50"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Agenda complet</span>
                </a>
            </div>
        </header>

        {{-- Layout --}}
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-start">
            {{-- Actualités --}}
            <div class="lg:col-span-8 space-y-5">
                @if($actualites->isEmpty())
                    <div class="rounded-2xl border border-dashed border-gray-200 bg-white p-10 text-center shadow-sm">
                        <svg class="mx-auto mb-3 h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm font-medium text-gray-600">
                            Aucune actualité publiée pour le moment.
                        </p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($actualites as $actualite)
                            @php($pub = $actualite->date_publication)
                            <article
                                class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm
                                       transition hover:-translate-y-[2px] hover:border-ed-primary/35 hover:shadow-md"
                            >
                                <div class="flex flex-col gap-4 p-4 sm:flex-row sm:items-stretch">
                                    {{-- Media --}}
                                    <div class="relative w-full overflow-hidden rounded-xl sm:w-40 md:w-44 h-44 sm:h-auto">
                                        @if($actualite->image)
                                            <img
                                                src="{{ $actualite->image->url }}"
                                                alt="{{ $actualite->titre }}"
                                                class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                                loading="lazy"
                                                decoding="async"
                                            >
                                        @else
                                            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-ed-primary to-ed-secondary">
                                                <svg class="h-9 w-9 text-white/70" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                    <path d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z"/>
                                                    <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"/>
                                                </svg>
                                            </div>
                                        @endif

                                        {{-- Overlay subtle --}}
                                        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/15 via-transparent to-transparent"></div>

                                        {{-- Badge Important --}}
                                        @if($actualite->est_important)
                                            <div class="absolute left-2 top-2">
                                                <span class="inline-flex items-center rounded-full bg-red-600 px-2 py-0.5 text-[10px] font-semibold text-white shadow">
                                                    Important
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Content --}}
                                    <div class="flex min-w-0 flex-1 flex-col">
                                        {{-- Meta row --}}
                                        <div class="flex flex-wrap items-center gap-2 text-[11px] text-gray-500">
                                            <span class="inline-flex items-center gap-1 rounded-full bg-gray-50 px-2 py-0.5 border border-gray-100">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 01-2 2z"/>
                                                </svg>
                                                <time datetime="{{ $pub?->format('Y-m-d') }}">
                                                    {{ $pub?->format('d M Y') ?? '—' }}
                                                </time>
                                            </span>

                                            @if($actualite->category)
                                                <span
                                                    class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold text-gray-800 border"
                                                    style="background-color: {{ $actualite->category->couleur }}22; border-color: {{ $actualite->category->couleur }}33"
                                                >
                                                    {{ $actualite->category->nom }}
                                                </span>
                                            @endif

                                            <span class="inline-flex items-center gap-1 rounded-full bg-gray-50 px-2 py-0.5 border border-gray-100">
                                                {{-- Eye icon fiable (solid) --}}
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" class="text-ed-primary" aria-hidden="true">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7C3.732 7.943 7.523 5 12 5zm0 11a4 4 0 100-8 4 4 0 000 8z"/>
                                                    <path d="M12 10a2 2 0 100 4 2 2 0 000-4z"/>
                                                </svg>
                                                <span class="font-semibold text-gray-700">{{ $actualite->vues_formatted }}</span>
                                            </span>
                                        </div>

                                        {{-- Title --}}
                                        <h3 class="mt-2 text-sm md:text-[15px] font-semibold leading-snug line-clamp-2">
                                            <a
                                                href="{{ route('actualites.show', $actualite) }}"
                                                class="text-gray-900 transition-colors group-hover:text-ed-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ed-primary/40 rounded"
                                            >
                                                {{ $actualite->titre }}
                                            </a>
                                        </h3>

                                        {{-- Excerpt --}}
                                        <p class="mt-1.5 text-[13px] leading-relaxed text-gray-600 line-clamp-2">
                                            {!! Str::limit(strip_tags($actualite->contenu), 140) !!}
                                        </p>

                                        {{-- Tags --}}
                                        @if($actualite->tags->isNotEmpty())
                                            <div class="mt-2 flex flex-wrap gap-1.5">
                                                @foreach($actualite->tags->take(3) as $tag)
                                                    <span class="rounded-lg bg-gray-100 px-2 py-0.5 text-[10px] font-medium text-gray-700">
                                                        #{{ $tag->nom }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif

                                        {{-- CTA row --}}
                                        <div class="mt-auto pt-3 flex items-center justify-between">
                                            <span class="text-[11px] text-gray-400">
                                                Mise à jour régulière
                                            </span>

                                            <a
                                                href="{{ route('actualites.show', $actualite) }}"
                                                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-3 py-2 text-[12px] font-semibold text-ed-primary
                                                       shadow-sm transition hover:border-ed-primary/50 hover:shadow-md
                                                       focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ed-primary/40"
                                            >
                                                <span>Lire</span>
                                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-ed-primary/10">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                {{-- Subtle left accent on hover --}}
                                <div class="pointer-events-none absolute inset-y-0 left-0 w-1 bg-ed-primary/0 group-hover:bg-ed-primary/40 transition"></div>
                            </article>
                        @endforeach
                    </div>
                @endif

                {{-- CTA mobile --}}
                <div class="md:hidden pt-2 text-center">
                    <a
                        href="{{ route('actualites.index') }}"
                        class="inline-flex items-center gap-2 rounded-full bg-ed-primary px-5 py-2.5 text-xs font-semibold text-white shadow-md transition hover:bg-ed-secondary"
                    >
                        <span>Voir toutes les actualités</span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Agenda --}}
            <aside class="lg:col-span-4">
                <div class="lg:sticky lg:top-24">
                    <div class="rounded-2xl border border-gray-100 bg-white/95 p-4 md:p-5 shadow-lg backdrop-blur">
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div>
                                <h3 class="text-sm md:text-base font-bold text-gray-900 flex items-center gap-2">
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-ed-primary/10 text-ed-primary">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 01-2 2z"/>
                                        </svg>
                                    </span>
                                    <span>Agenda à venir</span>
                                </h3>
                                <p class="mt-1 text-[11px] text-gray-500">
                                    Séminaires, soutenances, conférences et ateliers.
                                </p>
                            </div>
                        </div>

                        @if(isset($evenementsFuturs) && $evenementsFuturs->count() > 0)
                            @php($hasVisibleEvents = false)

                            <div class="space-y-3">
                                @foreach($evenementsFuturs as $evenement)
                                    @if($evenement->est_archive || ! $evenement->est_publie)
                                        @continue
                                    @endif

                                    @php($hasVisibleEvents = true)

                                    <div class="group rounded-2xl border border-gray-100 p-3 transition hover:border-ed-primary/40 hover:bg-ed-primary/3">
                                        <div class="flex gap-3">
                                            {{-- Date --}}
                                            <div class="flex flex-col items-center">
                                                <div class="h-12 w-12 rounded-2xl bg-ed-primary/10 text-ed-primary flex flex-col items-center justify-center">
                                                    <span class="text-sm font-extrabold leading-none">{{ $evenement->jour }}</span>
                                                    <span class="text-[10px] uppercase tracking-wide">{{ $evenement->mois }}</span>
                                                </div>
                                            </div>

                                            {{-- Content --}}
                                            <div class="min-w-0 flex-1">
                                                <a href="{{ route('evenements.show', $evenement) }}" class="focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ed-primary/40 rounded">
                                                <div class="flex items-start justify-between gap-2">
                                                    <h4 class="text-[13px] font-semibold text-gray-900 line-clamp-2 group-hover:text-ed-primary">
                                                        {{ $evenement->titre }}
                                                    </h4>

                                                    @if($evenement->est_important)
                                                        <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-0.5 text-[10px] font-semibold text-red-600">
                                                            Important
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="mt-1 flex flex-wrap items-center gap-1.5">
                                                    <span class="rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $evenement->type_classe }}">
                                                        {{ $evenement->type_texte }}
                                                    </span>

                                                    @if($evenement->heure_debut_aff)
                                                        <span class="inline-flex items-center gap-1 text-[10px] text-gray-500">
                                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            {{ $evenement->heure_debut_aff }}
                                                        </span>
                                                    @endif

                                                    @if($evenement->lieu)
                                                        <span class="inline-flex items-center gap-1 text-[10px] text-gray-500">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                                      d="M17.657 16.657L13.414 20.9a1 1 0 0 1-1.414 0L6.343 16.657a8 8 0 1 1 11.314 0z"/>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                                      d="M15 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                                            </svg>
                                                            {{ Str::limit($evenement->lieu, 26) }}
                                                        </span>
                                                    @endif
                                                </div>
                                                </a>
                                                {{-- Actions --}}
                                                <div class="mt-2 flex items-center justify-between">
                                                    <a
                                                        href="{{ route('evenements.show', $evenement) }}"
                                                        class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-ed-primary hover:text-ed-secondary"
                                                    >
                                                        <span>Détails</span>
                                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                        </svg>
                                                    </a>

                                                    @if($evenement->lien_inscription)
                                                        <a
                                                            href="{{ $evenement->lien_inscription }}"
                                                            target="_blank"
                                                            rel="noopener noreferrer"
                                                            class="text-[11px] font-semibold text-emerald-600 hover:text-emerald-700 underline"
                                                        >
                                                            S’inscrire
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if(! $hasVisibleEvents)
                                <div class="py-6 text-center text-[12px] text-gray-500">
                                    Aucun événement à venir pour le moment.
                                </div>
                            @endif
                        @else
                            <div class="py-6 text-center text-[12px] text-gray-500">
                                Aucun événement à venir pour le moment.
                            </div>
                        @endif

                        <div class="mt-4 text-center">
                            <a
                                href="{{ route('evenements.index') }}"
                                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-[12px] font-semibold text-gray-700
                                       shadow-sm transition hover:border-ed-primary/50 hover:text-ed-primary hover:shadow-md"
                            >
                                <span>Voir tous les événements</span>
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.1" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
