<!-- Équipes d'Accueil Doctorales -->
<section
    id="ead"
    aria-labelledby="ead-heading"
    class="py-14 md:py-16 bg-gradient-to-b from-gray-50 via-white to-gray-50 relative overflow-hidden"
>
    {{-- Décorations de fond discrètes --}}
    <div class="pointer-events-none absolute -top-16 right-[-3rem] w-64 h-64 bg-ed-primary/8 rounded-full blur-3xl"></div>
    <div class="pointer-events-none absolute bottom-[-4rem] left-[-3rem] w-64 h-64 bg-ed-yellow/10 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- En-tête section --}}
        <header class="text-center mb-10 md:mb-12">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-ed-primary/10 rounded-full mb-3 border border-ed-primary/20">
                <span class="w-2 h-2 rounded-full bg-ed-primary"></span>
                <span class="text-[11px] font-semibold uppercase tracking-[0.18em] text-ed-primary">
                    Équipes d'accueil
                </span>
            </div>

            <h2 id="ead-heading" class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 mb-2 leading-snug">
                Équipes d’Accueil Doctorales
            </h2>

            <p class="text-sm md:text-base text-slate-600 max-w-3xl mx-auto">
                Des équipes structurées pour accompagner les doctorants dans leurs travaux de recherche, encadrement et collaborations.
            </p>
        </header>

        {{-- Grille : 4 équipes sur la home --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 lg:gap-6">
            @foreach($eads as $ead)
                <article
                    class="group relative h-full rounded-2xl bg-white/85 backdrop-blur
                           border border-slate-100 ring-1 ring-black/5
                           shadow-sm hover:shadow-xl hover:-translate-y-1
                           transition-all duration-300 overflow-hidden"
                >
                    {{-- Accent bar (plus fine + propre) --}}
                    <div class="h-[3px] w-full bg-gradient-to-r from-ed-primary via-ed-secondary to-ed-yellow"></div>

                    {{-- Glow au hover (senior) --}}
                    <div class="pointer-events-none absolute -inset-16 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="absolute inset-0 bg-gradient-to-r from-ed-primary/12 via-transparent to-ed-yellow/12 blur-2xl"></div>
                    </div>

                    <div class="relative p-4 sm:p-5 flex flex-col h-full">

                        {{-- Header card --}}
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="flex items-center gap-3 min-w-0">
                                {{-- Icon container (uniforme) --}}
                                <div class="w-10 h-10 rounded-xl bg-ed-primary/10 ring-1 ring-ed-primary/10 flex items-center justify-center flex-none">
                                    <svg class="w-5 h-5 text-ed-primary" xmlns="http://www.w3.org/2000/svg" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M4 6a2 2 0 012-2h4.5a1 1 0 01.7.29l2.5 2.5a1 1 0 00.7.29H18a2 2 0 012 2v9a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/>
                                    </svg>
                                </div>

                                {{-- Domaine (desktop) --}}
                                @if(!empty($ead->domaine))
                                    <span
                                        class="hidden sm:inline-flex max-w-[10rem] truncate
                                               px-2.5 py-1 rounded-full
                                               bg-ed-yellow/10 text-amber-800
                                               ring-1 ring-amber-200/60
                                               text-[11px] font-semibold"
                                        title="{{ $ead->domaine }}"
                                    >
                                        {{ $ead->domaine }}
                                    </span>
                                @endif
                            </div>

                            {{-- Domaine (mobile) --}}
                            @if(!empty($ead->domaine))
                                <span
                                    class="sm:hidden inline-flex max-w-[9.5rem] truncate
                                           px-2 py-1 rounded-full
                                           bg-ed-yellow/10 text-amber-800
                                           ring-1 ring-amber-200/60
                                           text-[10px] font-semibold"
                                    title="{{ $ead->domaine }}"
                                >
                                    {{ $ead->domaine }}
                                </span>
                            @endif
                        </div>

                        {{-- Nom --}}
                        <h3 class="text-sm md:text-[15px] font-bold text-slate-900 mb-2 leading-snug line-clamp-2">
                            <a
                                href="{{ route('ead.show', $ead->slug) }}"
                                class="hover:text-ed-primary transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-ed-primary/40 rounded-md"
                            >
                                {{ $ead->nom }}
                            </a>
                        </h3>

                        {{-- Description courte --}}
                        <p class="text-[12px] text-slate-500 mb-4 leading-relaxed min-h-[2.5rem]">
                            Équipe d’accueil doctorale impliquée dans l’encadrement, le suivi et la valorisation des recherches.
                        </p>

                        {{-- Stats --}}
                        <div class="mt-auto">
                            <div class="grid grid-cols-2 gap-3 mb-3 pb-3 border-t border-slate-100 pt-3">
                                {{-- Spécialités --}}
                                <div class="flex items-center gap-2">
                                    <div class="w-9 h-9 rounded-xl bg-ed-primary/8 ring-1 ring-ed-primary/10 flex items-center justify-center flex-none">
                                        <svg class="w-4.5 h-4.5 text-ed-primary" xmlns="http://www.w3.org/2000/svg" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[10px] uppercase tracking-wide text-slate-500">Spécialités</p>
                                        <p class="text-sm font-semibold text-slate-900">
                                            {{ $ead->specialites_count }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Doctorants --}}
                                <div class="flex items-center gap-2">
                                    <div class="w-9 h-9 rounded-xl bg-emerald-50 ring-1 ring-emerald-200/40 flex items-center justify-center flex-none">
                                        <svg class="w-4.5 h-4.5 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M12 4.5a3.5 3.5 0 110 7 3.5 3.5 0 010-7zM6 20a6 6 0 1112 0H6z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[10px] uppercase tracking-wide text-slate-500">Doctorants</p>
                                        <p class="text-sm font-semibold text-slate-900">
                                            {{ $ead->theses_count }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- CTA --}}
                            <a
                                href="{{ route('ead.show', $ead->slug) }}"
                                class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-ed-primary
                                       hover:text-ed-secondary transition-colors
                                       focus:outline-none focus-visible:ring-2 focus-visible:ring-ed-primary/40 rounded-md"
                            >
                                <span>Voir l’équipe</span>
                                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" xmlns="http://www.w3.org/2000/svg"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                    </div>
                </article>
            @endforeach
        </div>

        {{-- Bouton global --}}
        <div class="text-center mt-8">
            <a href="{{ route('ead.index') }}"
               class="inline-flex items-center px-6 py-2.5 rounded-xl
                      bg-ed-primary text-white text-sm font-semibold
                      shadow-md hover:shadow-lg hover:bg-ed-secondary
                      transition-all duration-300
                      focus:outline-none focus-visible:ring-2 focus-visible:ring-ed-primary/40"
            >
                <span>Voir toutes les équipes</span>
                <svg class="w-4 h-4 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

    </div>
</section>
