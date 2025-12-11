<div>
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-ed-primary via-ed-secondary to-ed-primary
                    pt-24 pb-10 sm:pt-28 sm:pb-10 overflow-hidden">
        {{-- Décors doux --}}
        <div class="absolute inset-0 opacity-15 pointer-events-none">
            <div class="absolute -top-10 right-0 w-40 h-40 sm:w-56 sm:h-56 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 -left-10 w-32 h-32 sm:w-44 sm:h-44 bg-ed-yellow rounded-full blur-3xl"></div>
        </div>

        {{-- Légère transition vers le contenu en dessous --}}
        <div class="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-black/10 to-transparent pointer-events-none"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col items-center text-center text-white gap-4">
                {{-- Petit badge / sur-titre --}}
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 backdrop-blur">
                    <span class="w-2 h-2 rounded-full bg-ed-yellow"></span>
                    <span class="text-xs font-semibold uppercase tracking-[0.16em]">
                        Espace actualités EDGVM
                    </span>
                </div>

                {{-- Titre principal --}}
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-black leading-tight">
                    📰 Actualités
                </h1>

                {{-- Sous-titre --}}
                <p class="text-base sm:text-lg md:text-xl text-white/90 max-w-2xl">
                    Restez informé des dernières nouvelles, événements et annonces de l’École Doctorale EDGVM.
                </p>

                {{-- Fil d’Ariane --}}
                <div class="flex items-center gap-2 text-sm text-white/80 mt-1">
                    <a href="{{ route('home') }}" class="hover:text-ed-yellow transition-colors">
                        Accueil
                    </a>
                    <span class="opacity-60">/</span>
                    <span class="font-semibold">Actualités</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Barre de filtres actifs + toggle vue -->
    <section class="bg-gray-50/95 backdrop-blur border-b sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex items-center justify-between gap-4">
                <!-- Filtres actifs -->
                <div class="flex items-center gap-2 flex-wrap flex-1">
                    @if($category || $tag || $search)
                        <span class="text-sm text-gray-600 font-medium">Filtres :</span>
                        
                        @if($category)
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-ed-primary text-white text-sm rounded-full">
                                {{ $categories->where('slug', $category)->first()?->nom }}
                                <button wire:click="$set('category', '')" class="ml-1 hover:text-ed-yellow">×</button>
                            </span>
                        @endif
                        
                        @if($tag)
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-purple-600 text-white text-sm rounded-full">
                                {{ $tags->where('slug', $tag)->first()?->nom }}
                                <button wire:click="$set('tag', '')" class="ml-1 hover:text-purple-200">×</button>
                            </span>
                        @endif
                        
                        @if($search)
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-600 text-white text-sm rounded-full">
                                "{{ $search }}"
                                <button wire:click="$set('search', '')" class="ml-1 hover:text-gray-200">×</button>
                            </span>
                        @endif
                        
                        <button wire:click="clearFilters" class="text-sm text-red-600 hover:text-red-800 font-medium ml-2">
                            Tout effacer
                        </button>
                    @else
                        <span class="text-sm text-gray-500">
                            {{ $actualites->total() }} actualité(s) publiées
                        </span>
                    @endif
                </div>
                
                <!-- Toggle vue grille/liste -->
                <div class="flex items-center gap-2 bg-white rounded-full px-1.5 py-1 shadow-sm border border-gray-200">
                    <button wire:click="setView('grid')"
                            class="p-1.5 rounded-full transition {{ $view === 'grid' ? 'bg-ed-primary text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}"
                            title="Vue grille">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </button>
                    <button wire:click="setView('list')"
                            class="p-1.5 rounded-full transition {{ $view === 'list' ? 'bg-ed-primary text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}"
                            title="Vue liste">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Contenu principal -->
    <section class="py-14 bg-gradient-to-b from-gray-50 via-white to-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                
                <!-- Sidebar -->
                <aside class="lg:col-span-1 space-y-6">

                    {{-- Recherche --}}
                    <div class="bg-white/90 backdrop-blur rounded-2xl p-5 border border-gray-200 shadow-sm">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <span class="inline-flex w-5 h-5 items-center justify-center rounded-full bg-ed-primary/10 text-ed-primary">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>
                                </svg>
                            </span>
                            <span>Rechercher</span>
                        </h3>
                        <div class="space-y-2">
                            <input
                                type="text" 
                                wire:model.live.debounce.300ms="search"
                                placeholder="Mot-clé, auteur, thématique..."
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm
                                       focus:ring-2 focus:ring-ed-primary focus:border-transparent"
                            >
                            <p class="text-[11px] text-gray-400">
                                Tapez au moins 2 caractères pour filtrer les actualités.
                            </p>
                        </div>
                    </div>

                    {{-- Catégories --}}
                    <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <span class="inline-flex w-5 h-5 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 6h16M4 10h10M4 14h16M4 18h10"/>
                                </svg>
                            </span>
                            <span>Catégories</span>
                        </h3>
                        <ul class="space-y-1.5">
                            @foreach($categories as $cat)
                                <li>
                                    <button
                                        wire:click="filterByCategory('{{ $cat->slug }}')"
                                        class="w-full text-left px-3 py-2 rounded-lg transition group
                                               {{ $category === $cat->slug
                                                  ? 'bg-ed-primary/5 border border-ed-primary/60 shadow-sm'
                                                  : 'bg-gray-50 hover:bg-gray-100 border border-transparent' }}"
                                    >
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-2">
                                                <span class="w-2.5 h-2.5 rounded-full"
                                                      style="background-color: {{ $cat->couleur }}"></span>
                                                <span class="text-xs font-medium
                                                    {{ $category === $cat->slug ? 'text-ed-primary' : 'text-gray-700' }}">
                                                    {{ $cat->nom }}
                                                </span>
                                            </span>
                                            <span class="text-[11px] text-gray-500">
                                                {{ $cat->actualites_count }}
                                            </span>
                                        </div>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Bloc Agenda Évènements à venir --}}
                    <div class="bg-gradient-to-br from-ed-primary via-ed-secondary to-amber-500 rounded-2xl shadow-xl text-white p-5">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7
                                             a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-bold">
                                    Agenda EDGVM
                                </h3>
                                <p class="text-[11px] text-white/80 leading-snug">
                                    Soutenances, séminaires, conférences et ateliers à venir.
                                </p>
                            </div>
                        </div>

                        <ul class="text-xs text-white/85 space-y-1.5 mb-4">
                            <li class="flex items-start gap-1.5">
                                <span class="mt-1 w-1.5 h-1.5 rounded-full bg-emerald-300"></span>
                                <span>Consultez l’agenda complet des événements de l’École Doctorale.</span>
                            </li>
                            <li class="flex items-start gap-1.5">
                                <span class="mt-1 w-1.5 h-1.5 rounded-full bg-amber-200"></span>
                                <span>Préparez vos participations aux prochaines sessions scientifiques.</span>
                            </li>
                        </ul>

                        <a href="{{ route('evenements.index') }}"
                           class="inline-flex items-center justify-center w-full px-4 py-2.5
                                  bg-white text-ed-primary rounded-xl text-xs font-bold shadow-md
                                  hover:bg-ed-yellow hover:text-white transition-colors">
                            <span>Voir les événements à venir</span>
                            <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                      d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                    {{-- Tags --}}
                    <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <span class="inline-flex w-5 h-5 items-center justify-center rounded-full bg-purple-100 text-purple-700">
                                #
                            </span>
                            <span>Tags</span>
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($tags as $t)
                                <button
                                    wire:click="filterByTag('{{ $t->slug }}')"
                                    class="px-3 py-1.5 text-xs font-semibold rounded-full transition
                                           {{ $tag === $t->slug
                                              ? 'bg-purple-600 text-white shadow'
                                              : 'bg-gray-100 text-gray-700 hover:bg-purple-50 border border-gray-200' }}"
                                >
                                    {{ $t->nom }} ({{ $t->actualites_count }})
                                </button>
                            @endforeach
                        </div>
                    </div>
                </aside>

                <!-- Liste des actualités -->
                <main class="lg:col-span-3">
                    @if($actualites->isEmpty())
                        <div class="text-center py-20">
                            <svg class="mx-auto h-20 w-20 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5
                                         a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414
                                         a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-lg text-gray-600 font-semibold">Aucune actualité trouvée</p>
                            @if($category || $tag || $search)
                                <button
                                    wire:click="clearFilters" 
                                    class="mt-4 px-6 py-2.5 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary text-sm font-semibold shadow-sm">
                                    Afficher toutes les actualités
                                </button>
                            @endif
                        </div>
                    @else

                        {{-- VUE GRILLE --}}
                        @if($view === 'grid')
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                @foreach($actualites as $actualite)
                                    @php
                                        $readingTime = $this->calculateReadingTime($actualite->contenu);
                                    @endphp
                                    <article class="group bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-400 overflow-hidden border border-gray-100 hover:-translate-y-1">
                                        
                                        <!-- Image -->
                                        <div class="relative h-48 overflow-hidden">
                                            @if($actualite->image)
                                                <img src="{{ $actualite->image->url }}" 
                                                     alt="{{ $actualite->titre }}" 
                                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-ed-primary to-ed-secondary"></div>
                                            @endif
                                            
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                                            
                                            <!-- Badge Important -->
                                            @if($actualite->est_important)
                                                <div class="absolute top-3 left-3">
                                                    <span class="px-3 py-1 bg-red-600 text-white text-xs font-bold rounded-full shadow-lg flex items-center gap-1">
                                                        <span>🔥</span> Important
                                                    </span>
                                                </div>
                                            @endif
                                            
                                            <!-- Catégorie -->
                                            @if($actualite->category)
                                                <div class="absolute bottom-3 left-3">
                                                    <span class="px-3 py-1 text-white text-xs font-bold rounded-full shadow-lg backdrop-blur-sm"
                                                          style="background-color: {{ $actualite->category->couleur }}80">
                                                        {{ $actualite->category->nom }}
                                                    </span>
                                                </div>
                                            @endif
                                            
                                            <!-- Temps de lecture -->
                                            <div class="absolute top-3 right-3">
                                                <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-gray-900 text-xs font-bold rounded-full shadow-lg flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ $readingTime }} min
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- Contenu -->
                                        <div class="p-5">
                                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 min-h-[3.5rem]">
                                                <a href="{{ route('actualites.show', $actualite) }}"
                                                   class="block group-hover:text-ed-primary transition-colors">
                                                    {{ Str::limit(strip_tags($actualite->titre), 60) }}
                                                </a>
                                            </h3>
                                            <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed">
                                                {{ Str::limit(strip_tags($actualite->contenu), 110) }}
                                            </p>
                                            <!-- Meta info -->
                                            <div class="flex items-center justify-between mb-4 text-xs text-gray-500">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7
                                                                 a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ $actualite->date_publication->format('d M Y') }}
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M2.458 12C3.732 7.943 7.523 5 12 5
                                                                 c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7
                                                                 -4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    {{ $actualite->vues_formatted }}
                                                </span>
                                            </div>
                                                                            
                                            <!-- Tags -->
                                            @if($actualite->tags->isNotEmpty())
                                                <div class="flex flex-wrap gap-1 mb-4">
                                                    @foreach($actualite->tags->take(3) as $t)
                                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded">
                                                            #{{ $t->nom }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                            
                                            <!-- Lien -->
                                            <a href="{{ route('actualites.show', $actualite) }}" 
                                               class="group/link inline-flex items-center text-ed-primary font-bold text-sm hover:text-ed-secondary transition-colors">
                                                <span>Lire la suite</span>
                                                <svg class="w-4 h-4 ml-2 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                          d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        @endif

                        {{-- VUE LISTE --}}
                        @if($view === 'list')
                            <div class="space-y-6">
                                @foreach($actualites as $actualite)
                                    @php
                                        $readingTime = $this->calculateReadingTime($actualite->contenu);
                                    @endphp
                                    <article class="group bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-400 overflow-hidden border border-gray-100">
                                        <div class="flex flex-col md:flex-row">
                                            
                                            <!-- Image -->
                                            <div class="relative md:w-80 h-64 md:h-auto overflow-hidden flex-shrink-0">
                                                @if($actualite->image)
                                                    <img src="{{ $actualite->image->url }}" 
                                                         alt="{{ $actualite->titre }}" 
                                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                                @else
                                                    <div class="w-full h-full bg-gradient-to-br from-ed-primary to-ed-secondary"></div>
                                                @endif
                                                
                                                <div class="absolute inset-0 bg-gradient-to-t md:bg-gradient-to-r from-black/60 via-black/20 to-transparent"></div>
                                                
                                                <!-- Badges sur l'image -->
                                                @if($actualite->est_important)
                                                    <div class="absolute top-4 left-4">
                                                        <span class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-full shadow-lg flex items-center gap-1">
                                                            <span>🔥</span> Important
                                                        </span>
                                                    </div>
                                                @endif
                                                
                                                @if($actualite->category)
                                                    <div class="absolute bottom-4 left-4">
                                                        <span class="px-3 py-1.5 text-white text-xs font-bold rounded-full shadow-lg backdrop-blur-sm"
                                                              style="background-color: {{ $actualite->category->couleur }}80">
                                                            {{ $actualite->category->nom }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Contenu -->
                                            <div class="flex-1 p-6 md:p-8">
                                                <div class="flex items-start justify-between gap-4 mb-4">
                                                    <h3 class="text-2xl font-bold text-gray-900 line-clamp-2">
                                                        <a href="{{ route('actualites.show', $actualite) }}"
                                                           class="block group-hover:text-ed-primary transition-colors">
                                                            {{ $actualite->titre }}
                                                        </a>
                                                    </h3>
                                                    <!-- Temps de lecture -->
                                                    <div class="flex-shrink-0 px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-bold rounded-full flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        {{ $readingTime }} min
                                                    </div>
                                                </div>
                                                
                                                <!-- Meta -->
                                                <div class="flex flex-wrap items-center gap-4 mb-4 text-sm text-gray-500">
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7
                                                                     a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                        {{ $actualite->date_publication->format('d M Y') }}
                                                    </span>
                                                    @if($actualite->auteur)
                                                        <span class="flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                            </svg>
                                                            {{ $actualite->auteur->name }}
                                                        </span>
                                                    @endif
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5
                                                                     c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7
                                                                     -4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                        {{ $actualite->vues_formatted }} vues
                                                    </span>
                                                </div>
                                                
                                                <p class="text-gray-600 mb-6 line-clamp-3 leading-relaxed">
                                                    {{ Str::limit(strip_tags($actualite->contenu), 250) }}
                                                </p>
                                                
                                                <!-- Tags -->
                                                @if($actualite->tags->isNotEmpty())
                                                    <div class="flex flex-wrap gap-2 mb-6">
                                                        @foreach($actualite->tags->take(5) as $t)
                                                            <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">
                                                                #{{ $t->nom }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                
                                                <!-- Lien -->
                                                <a href="{{ route('actualites.show', $actualite) }}" 
                                                   class="group/link inline-flex items-center text-ed-primary font-bold hover:text-ed-secondary transition-colors">
                                                    <span>Lire l'article complet</span>
                                                    <svg class="w-5 h-5 ml-2 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                              d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        @endif

                        <!-- Pagination -->
                        <div class="mt-10">
                            {{ $actualites->links() }}
                        </div>
                    
                    @endif
                </main>
            </div>
        </div>
    </section>
</div>
