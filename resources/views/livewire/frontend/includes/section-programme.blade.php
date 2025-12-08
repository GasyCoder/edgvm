<!-- Équipes d'Accueil Doctorales -->
<section class="py-20 bg-white/50 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- En-tête section --}}
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-blue-50 rounded-full mb-4 border border-blue-100">
                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-700">
                    Équipes d'accueil
                </span>
            </div>
            <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-3">
                Équipes d’Accueil Doctorales
            </h2>
            <p class="text-base md:text-lg text-slate-600 max-w-3xl mx-auto">
                Des équipes d'accueil structurées pour les travaux doctoraux.
            </p>
        </div>

        {{-- Grille : 4 équipes sur la home --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-7">
            @foreach($eads as $ead)
                <article class="group relative flex flex-col h-full rounded-2xl border border-slate-200 
                               bg-white/80 backdrop-blur-sm shadow-sm hover:shadow-xl hover:-translate-y-1.5 
                               transition-all duration-300">
                    
                    <div class="h-1 w-full bg-gradient-to-r from-blue-500 via-indigo-500 to-sky-400"></div>

                    <div class="p-5 sm:p-6 flex-1 flex flex-col">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 6a2 2 0 012-2h4.5a1 1 0 01.7.29l2.5 2.5a1 1 0 00.7.29H18a2 2 0 012 2v9a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/>
                                    </svg>
                                </div>
                                @if(!empty($ead->domaine))
                                    <span class="hidden sm:inline-flex px-2.5 py-1 rounded-full bg-purple-50 text-purple-700 text-xs font-semibold">
                                        {{ $ead->domaine }}
                                    </span>
                                @endif
                            </div>

                            @if(!empty($ead->domaine))
                                <span class="sm:hidden inline-flex px-2 py-1 rounded-full bg-purple-50 text-purple-700 text-[11px] font-semibold">
                                    {{ Str::limit($ead->domaine, 18) }}
                                </span>
                            @endif
                        </div>

                        <h3 class="text-lg font-bold text-slate-900 mb-3 group-hover:text-blue-600 transition-colors line-clamp-2">
                            {{ $ead->nom }}
                        </h3>

                        <p class="text-xs text-slate-500 mb-4 min-h-[2rem]">
                            Équipe d’accueil doctorale impliquée dans l’encadrement des recherches.
                        </p>

                        <div class="grid grid-cols-2 gap-3 mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[11px] uppercase tracking-wide text-slate-500">Spécialités</p>
                                    <p class="text-sm font-semibold text-slate-900">
                                        {{ $ead->specialites_count }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 4.5a3.5 3.5 0 110 7 3.5 3.5 0 010-7zM6 20a6 6 0 1112 0H6z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[11px] uppercase tracking-wide text-slate-500">Doctorants</p>
                                    <p class="text-sm font-semibold text-slate-900">
                                        {{ $ead->theses_count }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-auto pt-1">
                            <a href="{{ route('ead.show', $ead->slug) }}"
                               class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                                <span>Voir l’équipe</span>
                                <svg class="w-4 h-4 ml-1 translate-x-0 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        {{-- Bouton pour voir toutes les EAD --}}
        <div class="text-center mt-10">
            <a href="{{ route('ead.index') }}"
               class="inline-flex items-center px-7 py-3 bg-blue-600 text-white rounded-xl 
                      hover:bg-blue-700 transition-all duration-300 font-semibold shadow-md hover:shadow-lg">
                <span>Voir toutes les équipes</span>
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>
