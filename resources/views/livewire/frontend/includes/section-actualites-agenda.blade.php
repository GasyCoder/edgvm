<section class="py-16 md:py-20 bg-gradient-to-b from-gray-50 via-white to-gray-50 relative overflow-hidden">
    {{-- Décorations de fond discrètes --}}
    <div class="pointer-events-none absolute -top-20 right-[-4rem] w-64 h-64 bg-ed-primary/5 rounded-full blur-3xl"></div>
    <div class="pointer-events-none absolute bottom-[-3rem] left-[-4rem] w-64 h-64 bg-ed-yellow/6 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- En-tête --}}
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-ed-primary/8 rounded-full mb-3 border border-ed-primary/15">
                    <span class="w-2 h-2 bg-ed-primary rounded-full"></span>
                    <span class="text-ed-primary font-semibold text-[11px] tracking-[0.16em] uppercase">
                        Dernières informations
                    </span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 leading-snug">
                    Actualités &amp; agenda des événements
                </h2>
                <p class="mt-2 text-sm sm:text-[15px] text-gray-600 max-w-2xl">
                    Suivez la vie de l’École Doctorale : annonces, séminaires, soutenances, conférences 
                    et activités scientifiques majeures.
                </p>
            </div>

            <div class="flex flex-wrap gap-2.5 justify-start md:justify-end">
                <a href="{{ route('actualites.index') }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-gray-200 bg-white 
                          hover:border-ed-primary/60 hover:text-ed-primary text-[11px] font-semibold shadow-sm hover:shadow-md transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 11H5m14 0-4-4m4 4-4 4"/>
                    </svg>
                    <span>Toutes les actualités</span>
                </a>

                <a href="{{ route('evenements.index') }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-ed-primary text-white 
                          hover:bg-ed-secondary text-[11px] font-semibold shadow-md hover:shadow-lg transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Agenda complet</span>
                </a>
            </div>
        </div>

        {{-- Grille principale : Actualités + Agenda --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            {{-- Colonne Actualités (2/3) --}}
            <div class="lg:col-span-2 space-y-5">
                @if($actualites->isEmpty())
                    <div class="text-center py-12 bg-white rounded-2xl shadow-sm border border-dashed border-gray-200">
                        <svg class="mx-auto h-10 w-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm text-gray-500 font-medium">
                            Aucune actualité publiée pour le moment.
                        </p>
                    </div>
                @else
                    {{-- LISTE d’actualités --}}
                    <div class="space-y-4">
                        @foreach($actualites as $actualite)
                            <article class="group bg-white rounded-2xl shadow-sm border border-gray-100 
                                        hover:border-ed-primary/40 hover:shadow-md hover:-translate-y-[2px]
                                        transition-all duration-200">
                                <div class="flex flex-col sm:flex-row gap-4 p-4">
                                    {{-- Image à gauche --}}
                                    <div class="relative w-full sm:w-32 md:w-36 h-40 sm:h-24 rounded-xl overflow-hidden">
                                        @if($actualite->image)
                                            <img src="{{ $actualite->image->url }}"
                                                 alt="{{ $actualite->titre }}"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-ed-primary to-ed-secondary flex items-center justify-center">
                                                <svg class="w-8 h-8 text-white/60" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z"/>
                                                    <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"/>
                                                </svg>
                                            </div>
                                        @endif

                                        {{-- Badge Important --}}
                                        @if($actualite->est_important)
                                            <div class="absolute top-2 left-2">
                                                <span class="px-2 py-0.5 bg-red-600 text-white text-[10px] font-semibold rounded-full shadow-md">
                                                    Important
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Texte à droite --}}
                                    <div class="flex-1 min-w-0 flex flex-col">
                                        {{-- Ligne méta --}}
                                        <div class="flex flex-wrap items-center gap-2 text-[11px] text-gray-500 mb-1">
                                            <span class="inline-flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 01-2 2z"/>
                                                </svg>
                                                {{ optional($actualite->date_publication)->format('d M Y') ?? '—' }}
                                            </span>

                                            @if($actualite->category)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold text-gray-800"
                                                      style="background-color: {{ $actualite->category->couleur }}22">
                                                    {{ $actualite->category->nom }}
                                                </span>
                                            @endif

                                            <span class="inline-flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M15 12a3 3 0 11-6 0 3 3 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                {{ $actualite->vues_formatted }}
                                            </span>
                                        </div>

                                        {{-- Titre --}}
                                        <h3 class="text-sm md:text-[15px] font-semibold mb-1.5 line-clamp-2">
                                            <a href="{{ route('actualites.show', $actualite) }}"
                                               class="text-gray-900 group-hover:text-ed-primary transition-colors">
                                                {{ $actualite->titre }}
                                            </a>
                                        </h3>

                                        {{-- Extrait --}}
                                        <p class="text-[13px] text-gray-600 line-clamp-2 leading-relaxed mb-1.5">
                                            {!! Str::limit(strip_tags($actualite->contenu), 120) !!}
                                        </p>

                                        {{-- Tags --}}
                                        @if($actualite->tags->isNotEmpty())
                                            <div class="flex flex-wrap gap-1 mb-2">
                                                @foreach($actualite->tags->take(3) as $tag)
                                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-[10px] rounded">
                                                        #{{ $tag->nom }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif

                                        {{-- Lien "Lire la suite" aligné à droite --}}
                                        <div class="mt-auto pt-2 flex justify-end">
                                            <a href="{{ route('actualites.show', $actualite) }}"
                                               class="inline-flex items-center gap-1.5 text-ed-primary font-semibold text-[12px] 
                                                      hover:text-ed-secondary">
                                                <span>Lire la suite</span>
                                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full border border-ed-primary/40 group-hover:border-ed-secondary/60">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.1" d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif

                {{-- Bouton mobile actualités --}}
                <div class="md:hidden text-center mt-4">
                    <a href="{{ route('actualites.index') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-ed-primary text-white rounded-full text-xs font-semibold hover:bg-ed-secondary transition-colors shadow-md">
                        <span>Voir toutes les actualités</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Colonne Agenda (liste verticale) --}}
            <aside class="lg:col-span-1">
                <div class="bg-white/95 rounded-2xl shadow-lg border border-gray-100 p-4 md:p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h3 class="text-sm md:text-base font-bold text-gray-900 flex items-center gap-2">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-ed-primary/10 text-ed-primary">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 01-2 2z"/>
                                    </svg>
                                </span>
                                <span>Agenda à venir</span>
                            </h3>
                            <p class="text-[11px] text-gray-500 mt-0.5">
                                Événements futurs : séminaires, soutenances, conférences et ateliers.
                            </p>
                        </div>
                    </div>

                    @if(isset($evenementsFuturs) && $evenementsFuturs->count() > 0)
                        @php($hasVisibleEvents = false)

                        <div class="space-y-2.5">
                            @foreach($evenementsFuturs as $evenement)
                                {{-- Sécurité front : ne pas afficher les événements archivés ou non publiés --}}
                                @if($evenement->est_archive || ! $evenement->est_publie)
                                    @continue
                                @endif

                                @php($hasVisibleEvents = true)

                                <div class="group flex gap-3 p-2.5 rounded-2xl border border-gray-100 hover:border-ed-primary/60 hover:bg-ed-primary/3 transition-all duration-300">
                                    {{-- Date --}}
                                    <div class="flex flex-col items-center mt-0.5">
                                        <div class="w-11 h-11 rounded-xl bg-ed-primary/10 flex flex-col items-center justify-center text-ed-primary">
                                            <span class="text-sm font-extrabold leading-none">
                                                {{ $evenement->jour }}
                                            </span>
                                            <span class="text-[10px] uppercase tracking-wide">
                                                {{ $evenement->mois }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Contenu --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-2">
                                            <h4 class="text-[13px] font-semibold text-gray-900 line-clamp-2 group-hover:text-ed-primary">
                                                {{ $evenement->titre }}
                                            </h4>

                                            @if($evenement->est_important)
                                                <span class="ml-1 inline-flex items-center rounded-full bg-red-50 px-2 py-0.5 text-[10px] font-semibold text-red-600">
                                                    Important
                                                </span>
                                            @endif
                                        </div>

                                        <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $evenement->type_classe }}">
                                                {{ $evenement->type_texte }}
                                            </span>

                                            @if($evenement->heure_debut_aff)
                                                <span class="inline-flex items-center gap-1 text-[10px] text-gray-500">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ $evenement->heure_debut_aff }}
                                                </span>
                                            @endif

                                            @if($evenement->lieu)
                                                <span class="inline-flex items-center gap-1 text-[10px] text-gray-500">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        {{-- Contour du pin --}}
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="1.8"
                                                            d="M17.657 16.657L13.414 20.9a1 1 0 0 1-1.414 0L6.343 16.657a8 8 0 1 1 11.314 0z"
                                                        />
                                                        {{-- Rond au centre --}}
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="1.8"
                                                            d="M15 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"
                                                        />
                                                    </svg>
                                                    {{ Str::limit($evenement->lieu, 26) }}
                                                </span>
                                            @endif
                                        </div>

                                        @if($evenement->description)
                                            <p class="mt-1 text-[10px] text-gray-500 line-clamp-2">
                                                {{ Str::limit(strip_tags($evenement->description), 80) }}
                                            </p>
                                        @endif

                                        <div class="mt-1.5 flex items-center justify-between">
                                            <a href="{{ route('evenements.show', $evenement) }}"
                                               class="inline-flex items-center text-[11px] font-semibold text-ed-primary hover:text-ed-secondary">
                                                <span>Détails</span>
                                                <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>

                                            @if($evenement->lien_inscription)
                                                <a href="{{ $evenement->lien_inscription }}" target="_blank"
                                                   class="text-[11px] font-semibold text-emerald-600 hover:text-emerald-700 underline">
                                                    S’inscrire
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if(! $hasVisibleEvents)
                            <div class="py-5 text-center text-[12px] text-gray-500">
                                Aucun événement à venir pour le moment.
                            </div>
                        @endif
                    @else
                        <div class="py-5 text-center text-[12px] text-gray-500">
                            Aucun événement à venir pour le moment.
                        </div>
                    @endif

                    {{-- Bouton bas de carte --}}
                    <div class="mt-3 text-center">
                        <a href="{{ route('evenements.index') }}"
                           class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-ed-primary hover:text-ed-secondary">
                            <span>Voir tous les événements</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.1"
                                      d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
