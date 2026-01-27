<div>
    <!-- Hero Section -->
    <section class="relative gradient-primary pt-24 md:pt-28 pb-16 md:pb-20 overflow-hidden">
        <div class="absolute inset-0 opacity-15 pointer-events-none">
            <div class="absolute -top-10 right-0 w-40 h-40 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 -left-10 w-40 h-40 bg-ed-yellow rounded-full blur-3xl"></div>
        </div>
        
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center text-white">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 mb-4">
                <span class="w-2 h-2 rounded-full bg-ed-yellow"></span>
                <span class="text-xs font-semibold tracking-[0.18em] uppercase">
                    Programme doctoral EDGVM
                </span>
            </div>
            <h1 class="text-3xl md:text-4xl font-black mb-3">
                🏛️ Équipes d’Accueil Doctorales
            </h1>
            <p class="text-base md:text-lg text-white/90 max-w-2xl mx-auto">
                Découvrez les EAD, leurs domaines d’expertise et les équipes qui encadrent les doctorants.
            </p>
        </div>
    </section>

    <!-- Contenu principal -->
    <section class="py-12 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Barre de recherche + filtres + mode de vue -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-5 lg:p-6 space-y-4">
                <!-- Search -->
                <div>
                    <div class="relative max-w-2xl">
                        <input type="text" 
                               wire:model.live.debounce.300ms="search"
                               placeholder="Rechercher une équipe (nom, domaine, description)..."
                               class="w-full pl-11 pr-4 py-3.5 text-sm sm:text-base border border-gray-200 rounded-xl 
                                      focus:ring-2 focus:ring-ed-primary focus:border-transparent shadow-sm bg-white">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Filtres + switch vue -->
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <!-- Filtres domaines -->
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                            Domaine :
                        </span>

                        <button
                            type="button"
                            wire:click="setDomaine('')"
                            class="px-3 py-1.5 rounded-full text-xs sm:text-sm font-semibold border 
                                   transition-all
                                   {{ $domaine === '' 
                                       ? 'bg-ed-primary text-white border-ed-primary shadow-sm' 
                                       : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                            Tous
                        </button>

                        @foreach($domaines as $dom)
                            <button
                                type="button"
                                wire:click="setDomaine('{{ $dom }}')"
                                class="px-3 py-1.5 rounded-full text-xs sm:text-sm font-semibold border 
                                       max-w-[180px] truncate transition-all
                                       {{ $domaine === $dom 
                                           ? 'bg-ed-secondary text-white border-ed-secondary shadow-sm' 
                                           : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                                {{ $dom }}
                            </button>
                        @endforeach
                    </div>

                    <!-- Switch vue list / grid -->
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500 uppercase tracking-wide hidden sm:inline">
                            Affichage :
                        </span>
                        <div class="inline-flex items-center rounded-full bg-gray-100 p-1">
                            <button type="button"
                                wire:click="setView('grid')"
                                class="inline-flex items-center justify-center w-9 h-9 rounded-full text-xs
                                       transition-all
                                       {{ $view === 'grid' 
                                            ? 'bg-white shadow-sm text-gray-900' 
                                            : 'text-gray-500 hover:text-gray-800' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 6h7V4H4v2zm9 0h7V4h-7v2zM4 13h7v-2H4v2zm9 0h7v-2h-7v2zM4 20h7v-2H4v2zm9 0h7v-2h-7v2z"/>
                                </svg>
                            </button>
                            <button type="button"
                                wire:click="setView('list')"
                                class="inline-flex items-center justify-center w-9 h-9 rounded-full text-xs
                                       transition-all
                                       {{ $view === 'list' 
                                            ? 'bg-white shadow-sm text-gray-900' 
                                            : 'text-gray-500 hover:text-gray-800' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 6h16M4 12h16M4 18h10"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des EAD -->
            @if($eads->isEmpty())
                <div class="text-center py-16">
                    <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <p class="text-lg text-gray-500 font-medium mb-2">Aucune EAD trouvée</p>
                    @if($search || $domaine)
                        <button wire:click="$set('search', ''); $set('domaine', '');" 
                                class="mt-3 px-5 py-2.5 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary 
                                       text-sm font-semibold shadow-sm">
                            Réinitialiser les filtres
                        </button>
                    @endif
                </div>
            @else

                {{-- Vue LISTE --}}
                @if($view === 'list')
                    <div class="space-y-4">
                        @foreach($eads as $ead)
                            <article class="group bg-white rounded-2xl shadow-sm hover:shadow-md border border-gray-100 
                                           hover:-translate-y-0.5 transition-all duration-300 p-5 md:p-6 
                                           flex flex-col md:flex-row gap-4">
                                
                                {{-- Colonne gauche : infos principales --}}
                                <div class="flex-1 flex gap-3">
                                    <div class="hidden sm:flex w-10 h-10 rounded-xl bg-blue-50 items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 6a2 2 0 012-2h4.5a1 1 0 01.7.29l2.5 2.5a1 1 0 00.7.29H18a2 2 0 012 2v9a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="text-base md:text-lg font-bold text-gray-900 mb-1 group-hover:text-ed-primary transition-colors line-clamp-2">
                                            {{ $ead->nom }}
                                        </h3>
                                        @if($ead->domaine)
                                            <p class="text-xs text-gray-500 mb-1 line-clamp-1">
                                                {{ $ead->domaine }}
                                            </p>
                                        @endif

                                        @if($ead->description)
                                            <p class="text-sm text-gray-600 mb-2 line-clamp-2 md:line-clamp-3">
                                                {{ $ead->description }}
                                            </p>
                                        @endif

                                        @if($ead->responsable)
                                            <div class="mt-1 flex items-center gap-2 text-xs text-gray-500">
                                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                </svg>
                                                <span>
                                                    Responsable :
                                                    <span class="font-semibold text-gray-800">
                                                        {{ optional($ead->responsable)->nom }} {{ optional($ead->responsable)->prenom }}
                                                    </span>
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Colonne droite : stats + lien --}}
                                <div class="md:w-64 border-t md:border-t-0 md:border-l border-gray-100 pt-3 md:pt-0 md:pl-4 flex flex-col gap-3 justify-between">
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="text-center p-2.5 bg-blue-50/80 rounded-lg">
                                            <p class="text-lg font-bold text-blue-700">
                                                {{ $ead->specialites->count() }}
                                            </p>
                                            <p class="text-[11px] text-gray-600 uppercase tracking-wide">Spécialités</p>
                                        </div>
                                        <div class="text-center p-2.5 bg-green-50/80 rounded-lg">
                                            <p class="text-lg font-bold text-green-700">
                                                {{ $ead->doctorats_count }}
                                            </p>
                                            <p class="text-[11px] text-gray-600 uppercase tracking-wide">Doctorants</p>
                                        </div>
                                    </div>

                                    <div class="flex justify-end md:justify-start">
                                        <a href="{{ route('ead.show', $ead) }}" 
                                           class="inline-flex items-center text-ed-primary font-semibold text-sm hover:text-ed-secondary transition-colors">
                                            <span>Voir l’équipe</span>
                                            <svg class="w-4 h-4 ml-2 translate-x-0 group-hover:translate-x-1 transition-transform" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                {{-- Vue GRID (cards) --}}
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                        @foreach($eads as $ead)
                            <article class="group bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 
                                           hover:-translate-y-1.5 transition-all duration-300 flex flex-col h-full">
                                
                                <div class="px-6 pt-5 pb-4 border-b border-gray-100">
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M4 6a2 2 0 012-2h4.5a1 1 0 01.7.29l2.5 2.5a1 1 0 00.7.29H18a2 2 0 012 2v9a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/>
                                                </svg>
                                            </div>
                                            <div class="min-w-0">
                                                <h3 class="text-base md:text-lg font-bold text-gray-900 group-hover:text-ed-primary 
                                                           transition-colors line-clamp-2">
                                                    {{ $ead->nom }}
                                                </h3>
                                                @if($ead->domaine)
                                                    <p class="text-xs text-gray-500 mt-0.5 line-clamp-1">
                                                        {{ $ead->domaine }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="p-6 flex-1 flex flex-col">
                                    @if($ead->description)
                                        <p class="text-sm text-gray-600 mb-4 line-clamp-3 leading-relaxed">
                                            {{ $ead->description }}
                                        </p>
                                    @endif
                                    
                                    @if($ead->responsable)
                                        <div class="flex items-center gap-2 mb-4 p-3 bg-gray-50 rounded-lg">
                                            <svg class="w-5 h-5 text-gray-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                            </svg>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs text-gray-500">Responsable de l’équipe</p>
                                                <p class="text-sm font-semibold text-gray-900 truncate">
                                                    {{ $ead->responsable->name }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    
                                    <div class="grid grid-cols-2 gap-3 mb-4">
                                        <div class="text-center p-3 bg-blue-50/70 rounded-lg">
                                            <p class="text-xl font-bold text-blue-700">
                                                {{ $ead->specialites->count() }}
                                            </p>
                                            <p class="text-xs text-gray-600">Spécialités</p>
                                        </div>
                                        <div class="text-center p-3 bg-green-50/70 rounded-lg">
                                            <p class="text-xl font-bold text-green-700">
                                                {{ $ead->doctorats_count }}
                                            </p>
                                            <p class="text-xs text-gray-600">Doctorants</p>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-auto pt-1">
                                        <a href="{{ route('ead.show', $ead) }}" 
                                           class="inline-flex items-center text-ed-primary font-semibold text-sm hover:text-ed-secondary transition-colors">
                                            <span>Découvrir l'équipe</span>
                                            <svg class="w-4 h-4 ml-2 translate-x-0 group-hover:translate-x-1 transition-transform" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </section>
</div>
