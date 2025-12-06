<div>
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-ed-primary via-ed-secondary to-ed-primary py-20 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 right-10 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 left-10 w-64 h-64 bg-ed-yellow rounded-full blur-3xl"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 mt-12">
            <div class="text-center text-white">
                <h1 class="text-4xl md:text-6xl font-black mb-4">📰 Actualités</h1>
                <p class="text-xl text-white/90 max-w-2xl mx-auto">
                    Restez informé des dernières nouvelles de l'EDGVM
                </p>
            </div>
        </div>
    </section>

    <!-- Barre de filtres actifs + toggle vue -->
    <section class="bg-gray-50 py-4 border-b sticky top-0 z-40 backdrop-blur-lg bg-gray-50/95">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
                    <span class="text-sm text-gray-500">{{ $actualites->total() }} actualité(s)</span>
                    @endif
                </div>
                
                <!-- Toggle vue grille/liste -->
                <div class="flex items-center gap-2 bg-white rounded-lg p-1 shadow">
                    <button wire:click="setView('grid')"
                            class="p-2 rounded transition {{ $view === 'grid' ? 'bg-ed-primary text-white' : 'text-gray-600 hover:bg-gray-100' }}"
                            title="Vue grille">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </button>
                    <button wire:click="setView('list')"
                            class="p-2 rounded transition {{ $view === 'list' ? 'bg-ed-primary text-white' : 'text-gray-600 hover:bg-gray-100' }}"
                            title="Vue liste">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Contenu principal -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                
                <!-- Sidebar (identique) -->
                <aside class="lg:col-span-1 space-y-6">
                   <!-- Articles les plus lus -->
                    {{-- @if($plusLus->isNotEmpty())
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border-2 border-blue-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <span>📊</span> Les plus lus
                        </h3>
                        <div class="space-y-4">
                            @foreach($plusLus as $index => $actu)
                            <a href="{{ route('actualites.show', $actu) }}" 
                            class="block group">
                                <div class="flex gap-3">
                                    <!-- Numéro -->
                                    <div class="flex-shrink-0 w-8 h-8 bg-ed-primary text-white rounded-full flex items-center justify-center font-bold text-sm">
                                        {{ $index + 1 }}
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-bold text-gray-900 group-hover:text-ed-primary line-clamp-2 mb-1">
                                            {{ $actu->titre }}
                                        </h4>
                                        <div class="flex items-center gap-3 text-xs text-gray-500">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                {{ $actu->vues_formatted }}
                                            </span>
                                            <span>{{ $actu->date_publication->format('d M') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif --}}
                    
                    <!-- Recherche -->
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">🔍 Rechercher</h3>
                        <input type="text" 
                               wire:model.live.debounce.300ms="search"
                               placeholder="Mot-clé..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                    </div>

                    <!-- Catégories -->
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">📁 Catégories</h3>
                        <ul class="space-y-2">
                            @foreach($categories as $cat)
                            <li>
                                <button wire:click="filterByCategory('{{ $cat->slug }}')"
                                        class="w-full text-left px-3 py-2 rounded-lg transition group hover:bg-white
                                               {{ $category === $cat->slug ? 'bg-white shadow-sm ring-2 ring-ed-primary' : '' }}">
                                    <div class="flex items-center justify-between">
                                        <span class="flex items-center gap-2">
                                            <span class="w-3 h-3 rounded-full" style="background-color: {{ $cat->couleur }}"></span>
                                            <span class="text-sm font-medium {{ $category === $cat->slug ? 'text-ed-primary' : 'text-gray-700' }}">
                                                {{ $cat->nom }}
                                            </span>
                                        </span>
                                        <span class="text-xs text-gray-500">{{ $cat->actualites_count }}</span>
                                    </div>
                                </button>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Tags -->
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">#️⃣ Tags</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($tags as $t)
                            <button wire:click="filterByTag('{{ $t->slug }}')"
                                    class="px-3 py-1.5 text-xs font-semibold rounded-full transition
                                           {{ $tag === $t->slug 
                                              ? 'bg-purple-600 text-white' 
                                              : 'bg-white text-gray-700 hover:bg-purple-50 border border-gray-300' }}">
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
                        <svg class="mx-auto h-24 w-24 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-xl text-gray-500 font-medium">Aucune actualité trouvée</p>
                        @if($category || $tag || $search)
                        <button wire:click="clearFilters" 
                                class="mt-4 px-6 py-2 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary">
                            Afficher toutes les actualités
                        </button>
                        @endif
                    </div>
                    @else
                    
                    <!-- VUE GRILLE -->
                    @if($view === 'grid')
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($actualites as $actualite)
                        @php
                            $readingTime = $this->calculateReadingTime($actualite->contenu);
                        @endphp
                        <article class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 hover:-translate-y-1">
                            
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $readingTime }} min
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Contenu -->
                            <div class="p-5">
                                <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-ed-primary transition-colors line-clamp-2 min-h-[3.5rem]">
                                    {{ Str::limit(strip_tags($actualite->titre), 40) }}
                                </h3>
                                
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed">
                                    {{ Str::limit(strip_tags($actualite->contenu), 100) }}
                                </p>
                                
                                <!-- Meta info -->
                                <div class="flex items-center justify-between mb-4 text-xs text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $actualite->date_publication->format('d M Y') }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </article>
                        @endforeach
                    </div>
                    @endif

                    <!-- VUE LISTE -->
                    @if($view === 'list')
                    <div class="space-y-6">
                        @foreach($actualites as $actualite)
                        @php
                            $readingTime = $this->calculateReadingTime($actualite->contenu);
                        @endphp
                        <article class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100">
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
                                        <h3 class="text-2xl font-bold text-gray-900 group-hover:text-ed-primary transition-colors line-clamp-2">
                                            {{ $actualite->titre }}
                                        </h3>
                                        
                                        <!-- Temps de lecture -->
                                        <div class="flex-shrink-0 px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-bold rounded-full flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $readingTime }} min
                                        </div>
                                    </div>
                                    
                                    <!-- Meta -->
                                    <div class="flex items-center gap-4 mb-4 text-sm text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $actualite->date_publication->format('d M Y') }}
                                        </span>
                                        @if($actualite->auteur)
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            {{ $actualite->auteur->name }}
                                        </span>
                                        @endif
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                        @endforeach
                    </div>
                    @endif

                    <!-- Pagination -->
                    <div class="mt-12">
                        {{ $actualites->links() }}
                    </div>
                    
                    @endif
                </main>
            </div>
        </div>
    </section>
</div>