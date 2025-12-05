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
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900">Actualités</h2>
                </div>
                <a href="{{ route('actualites') }}" class="hidden md:inline-flex items-center gap-2 text-ed-primary font-bold hover:text-ed-secondary transition-colors group">
                    <span>Voir plus</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($actualites as $actualite)
                <article class="group bg-white rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 hover:-translate-y-2">
                    <div class="relative h-56 overflow-hidden">
                        <img src="{{ $actualite->image ?? 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=800&q=80' }}" 
                             alt="{{ $actualite->titre }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                        
                        <!-- Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1.5 bg-ed-yellow text-gray-900 text-xs font-bold rounded-full shadow-lg">
                                {{ $actualite->date_publication->format('d M Y') }}
                            </span>
                        </div>
                        
                        <!-- Icône -->
                        <div class="absolute top-4 right-4">
                            <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center border border-white/30">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z"/>
                                    <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-ed-primary transition-colors line-clamp-2">
                            {{ $actualite->titre }}
                        </h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed">
                            {{ Str::limit(strip_tags($actualite->contenu), 100) }}
                        </p>
                        <a href="{{ route('actualites.show', $actualite->id) }}" 
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
        </div>
    </section>