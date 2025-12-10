<!-- Équipes d'Accueil Doctorales -->
<section class="py-14 md:py-16 bg-gradient-to-b from-gray-50 via-white to-gray-50 relative overflow-hidden">
    {{-- Décorations de fond discrètes --}}
    <div class="pointer-events-none absolute -top-16 right-[-3rem] w-64 h-64 bg-ed-primary/8 rounded-full blur-3xl"></div>
    <div class="pointer-events-none absolute bottom-[-4rem] left-[-3rem] w-64 h-64 bg-ed-yellow/10 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- En-tête section --}}
        <div class="text-center mb-10 md:mb-12">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-ed-primary/10 rounded-full mb-3 border border-ed-primary/20">
                <span class="w-2 h-2 rounded-full bg-ed-primary"></span>
                <span class="text-[11px] font-semibold uppercase tracking-[0.18em] text-ed-primary">
                    Équipes d'accueil
                </span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 mb-2 leading-snug">
                Équipes d’Accueil Doctorales
            </h2>
            <p class="text-sm md:text-base text-slate-600 max-w-3xl mx-auto">
                Des équipes d’accueil structurées pour accompagner les doctorants dans leurs travaux de recherche.
            </p>
        </div>

        {{-- Grille : 4 équipes sur la home --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 lg:gap-6">
            @foreach($eads as $ead)
                <article
                    class="group relative flex flex-col h-full rounded-2xl border border-slate-100 
                           bg-white/80 backdrop-blur-sm shadow-sm hover:shadow-xl hover:-translate-y-1 
                           transition-all duration-300"
                >
                    {{-- Barre d’accent en haut (vert → jaune) --}}
                    <div class="h-1 w-full bg-gradient-to-r from-ed-primary via-ed-secondary to-ed-yellow"></div>

                    <div class="p-4 sm:p-5 flex-1 flex flex-col">
                        {{-- En-tête carte --}}
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-ed-primary/10 flex items-center justify-center">
                                    <svg class="w-4.5 h-4.5 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 6a2 2 0 012-2h4.5a1 1 0 01.7.29l2.5 2.5a1 1 0 00.7.29H18a2 2 0 012 2v9a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/>
                                    </svg>
                                </div>

                                @if(!empty($ead->domaine))
                                    <span class="hidden sm:inline-flex px-2.5 py-1 rounded-full bg-ed-yellow/10 text-ed-yellow/90 text-[11px] font-semibold">
                                        {{ $ead->domaine }}
                                    </span>
                                @endif
                            </div>

                            @if(!empty($ead->domaine))
                                <span class="sm:hidden inline-flex px-2 py-1 rounded-full bg-ed-yellow/10 text-ed-yellow/90 text-[10px] font-semibold">
                                    {{ Str::limit($ead->domaine, 18) }}
                                </span>
                            @endif
                        </div>

                        {{-- Nom de l’EAD --}}
                        <h3 class="text-sm md:text-base font-bold text-slate-900 mb-2 group-hover:text-ed-primary transition-colors line-clamp-2">
                            {{ $ead->nom }}
                        </h3>

                        <p class="text-[12px] text-slate-500 mb-4 min-h-[2rem]">
                            Équipe d’accueil doctorale impliquée dans l’encadrement et le suivi des recherches.
                        </p>

                        {{-- Stats rapides --}}
                        <div class="grid grid-cols-2 gap-3 mb-3 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-ed-primary/8 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] uppercase tracking-wide text-slate-500">Spécialités</p>
                                    <p class="text-sm font-semibold text-slate-900">
                                        {{ $ead->specialites_count }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50/70 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 4.5a3.5 3.5 0 110 7 3.5 3.5 0 010-7zM6 20a6 6 0 1112 0H6z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] uppercase tracking-wide text-slate-500">Doctorants</p>
                                    <p class="text-sm font-semibold text-slate-900">
                                        {{ $ead->theses_count }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Lien --}}
                        <div class="mt-auto pt-1">
                            <a href="{{ route('ead.show', $ead->slug) }}"
                               class="inline-flex items-center text-[13px] font-semibold text-ed-primary hover:text-ed-secondary transition-colors">
                                <span>Voir l’équipe</span>
                                <svg class="w-4 h-4 ml-1 translate-x-0 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.1" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        {{-- Bouton pour voir toutes les EAD --}}
        <div class="text-center mt-8">
            <a href="{{ route('ead.index') }}"
               class="inline-flex items-center px-6 py-2.5 bg-ed-primary text-white rounded-xl 
                      hover:bg-ed-secondary transition-all duration-300 text-sm font-semibold shadow-md hover:shadow-lg">
                <span>Voir toutes les équipes</span>
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>
