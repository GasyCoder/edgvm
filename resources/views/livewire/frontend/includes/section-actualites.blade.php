<!-- Actualités - DESIGN MODERNE -->
<section class="py-24 bg-gradient-to-b from-gray-50 to-white relative overflow-hidden">
    <!-- Décorations -->
    <div class="absolute top-10 right-10 w-64 h-64 bg-ed-primary/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-10 left-10 w-48 h-48 bg-ed-yellow/5 rounded-full blur-3xl"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex justify-between items-end mb-12">
            <div>
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-ed-primary/10 rounded-full mb-4">
                    <div class="w-2 h-2 bg-ed-primary rounded-full"></div>
                    <span class="text-ed-primary font-semibold text-sm">Dernières nouvelles</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-black text-gray-900">Actualités</h2>
            </div>
            <a href="{{ route('actualites.index') }}" class="hidden md:inline-flex items-center gap-2 text-ed-primary font-bold hover:text-ed-secondary transition-colors group">
                <span>Voir plus</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>

        @if($actualites->isEmpty())
        <div class="text-center py-20 bg-white rounded-3xl shadow-lg">
            <svg class="mx-auto h-20 w-20 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-xl text-gray-500 font-medium">Aucune actualité pour le moment</p>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($actualites as $actualite)
            <article class="group bg-white rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 hover:-translate-y-2">
                
                <!-- Image -->
                <div class="relative h-56 overflow-hidden">
                    {{-- Image --}}
                    @if($actualite->image)
                        <img src="{{ $actualite->image->url }}" 
                            alt="{{ $actualite->titre }}" 
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-ed-primary to-ed-secondary flex items-center justify-center">
                            <svg class="w-20 h-20 text-white/30" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z"/>
                                <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"/>
                            </svg>
                        </div>
                    @endif

                    {{-- Overlay assombri --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent z-10"></div>

                    {{-- Badge Important --}}
                    @if($actualite->est_important)
                        <div class="absolute top-4 left-4 z-20">
                            <span class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-full shadow-lg flex items-center gap-1 animate-pulse">
                                <span>🔥</span> Important
                            </span>
                        </div>
                    @endif

                    {{-- Catégorie --}}
                    @if($actualite->category)
                        <div class="absolute bottom-4 left-4 z-20">
                            <span class="px-3 py-1.5 text-white text-xs font-bold rounded-full shadow-lg backdrop-blur-md border border-white/30"
                                style="background-color: {{ $actualite->category->couleur }}80">
                                {{ $actualite->category->nom }}
                            </span>
                        </div>
                    @endif

                    {{-- 📅 Date + 👁 Vues : bien par-dessus l’overlay --}}
                    <div class="absolute bottom-4 right-4 z-20 flex flex-col items-end gap-1 text-xs text-white">
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 01-2 2z"/>
                            </svg>
                            {{ optional($actualite->date_publication)->format('d M Y') ?? '—' }}
                        </span>

                        <span class="inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{ $actualite->vues_formatted }}
                        </span>
                    </div>
                </div>

                
                <!-- Contenu -->
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-3 line-clamp-2">
                        <a href="{{ route('actualites.show', $actualite) }}"
                        class="text-gray-900 group-hover:text-ed-primary transition-colors">
                            {{ $actualite->titre }}
                        </a>
                    </h3>
                    
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed">
                        {{ Str::limit(strip_tags($actualite->contenu), 100) }}
                    </p>
                    
                    <!-- Tags -->
                    @if($actualite->tags->isNotEmpty())
                    <div class="flex flex-wrap gap-1 mb-4">
                        @foreach($actualite->tags->take(3) as $tag)
                        <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded">
                            #{{ $tag->nom }}
                        </span>
                        @endforeach
                    </div>
                    @endif
                    
                    <!-- Lien -->
                    <a href="{{ route('actualites.show', $actualite) }}" 
                       class="group/link inline-flex items-center text-ed-primary font-bold text-sm hover:text-ed-secondary transition-colors">
                        <span>Lire l'article</span>
                        <svg class="w-4 h-4 ml-2 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </article>
            @endforeach
        </div>
        @endif

        <!-- Bouton mobile "Voir plus" -->
        <div class="md:hidden text-center mt-8">
            <a href="{{ route('actualites.index') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-ed-primary text-white rounded-full font-bold hover:bg-ed-secondary transition-colors shadow-lg">
                <span>Voir toutes les actualités</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>