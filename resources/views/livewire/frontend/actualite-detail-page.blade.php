<div>
    <!-- Hero Image -->
    <section class="relative h-[50vh] min-h-[300px] overflow-hidden">
        @if($actualite->image)
        <img src="{{ $actualite->image->url }}" 
             alt="{{ $actualite->titre }}" 
             class="w-full h-full object-cover">
        @else
        <div class="w-full h-full bg-gradient-to-br from-ed-primary to-ed-secondary"></div>
        @endif
        
        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
        
        <!-- Contenu Hero -->
        <div class="absolute inset-0 flex items-end">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 w-full">
                
                <!-- Breadcrumb -->
                <nav class="mb-8">
                    <ol class="flex items-center gap-2 text-sm text-white/80">
                        <li><a href="{{ route('home') }}" class="hover:text-white">Accueil</a></li>
                        <li>•</li>
                        <li><a href="{{ route('actualites') }}" class="hover:text-white">Actualités</a></li>
                        @if($actualite->category)
                        <li>•</li>
                        <li class="text-white font-semibold">{{ $actualite->category->nom }}</li>
                        @endif
                    </ol>
                </nav>
                
                <!-- Badges -->
                <div class="flex flex-wrap gap-3 mb-6">
                    @if($actualite->est_important)
                    <span class="px-4 py-2 bg-red-600 text-white text-sm font-bold rounded-full shadow-lg flex items-center gap-2">
                        <span>🔥</span> Important
                    </span>
                    @endif
                    
                    @if($actualite->category)
                    <span class="px-4 py-2 text-white text-sm font-bold rounded-full shadow-lg backdrop-blur-md border border-white/30"
                          style="background-color: {{ $actualite->category->couleur }}80">
                        {{ $actualite->category->nom }}
                    </span>
                    @endif
                    
                    <span class="px-4 py-2 bg-white/20 backdrop-blur-md text-white text-sm font-bold rounded-full border border-white/30">
                        {{ $actualite->date_publication->format('d F Y') }}
                    </span>
                </div>
                
                <!-- Titre -->
                <h1 class="text-3xl md:text-5xl font-black text-white mb-4 leading-tight">
                    {{ $actualite->titre }}
                </h1>
                
                <!-- Auteur -->
                @if($actualite->auteur)
                <div class="flex items-center gap-6 text-white/90">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center border border-white/30">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium">Par {{ $actualite->auteur->name }}</p>
                            <p class="text-xs text-white/70">{{ $actualite->date_publication->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    
                    <!-- Vues ← AJOUTÉ -->
                    <div class="flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-md rounded-full border border-white/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span class="text-sm font-bold">{{ $actualite->vues_formatted }} vues</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Contenu principal -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                
                <!-- Article -->
                <article class="lg:col-span-2">
                    <div class="bg-white rounded-3xl shadow-xl p-8 md:p-12">
                        
                        <!-- Contenu -->
                        <div class="prose prose-lg max-w-none">
                            {!! $actualite->contenu !!}
                        </div>
                        
                        <!-- Tags -->
                        @if($actualite->tags->isNotEmpty())
                        <div class="mt-12 pt-8 border-t border-gray-200">
                            <h3 class="text-sm font-bold text-gray-500 uppercase mb-4">Tags</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($actualite->tags as $tag)
                                <a href="{{ route('actualites') }}?tag={{ $tag->slug }}" 
                                   class="px-4 py-2 bg-purple-100 text-purple-700 rounded-full text-sm font-semibold hover:bg-purple-200 transition">
                                    #{{ $tag->nom }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <!-- Partage social -->
                        <div class="mt-12 pt-8 border-t border-gray-200">
                            <h3 class="text-sm font-bold text-gray-500 uppercase mb-4">Partager</h3>
                            <div class="flex gap-3">
                                <!-- Facebook -->
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                                   target="_blank"
                                   class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </a>
                                
                                <!-- Twitter -->
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($actualite->titre) }}" 
                                   target="_blank"
                                   class="w-10 h-10 bg-sky-500 text-white rounded-full flex items-center justify-center hover:bg-sky-600 transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                </a>
                                
                                <!-- LinkedIn -->
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($actualite->titre) }}" 
                                   target="_blank"
                                   class="w-10 h-10 bg-blue-700 text-white rounded-full flex items-center justify-center hover:bg-blue-800 transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                </a>
                                
                                <!-- WhatsApp -->
                                <a href="https://wa.me/?text={{ urlencode($actualite->titre . ' - ' . url()->current()) }}" 
                                   target="_blank"
                                   class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center hover:bg-green-600 transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Navigation Précédent/Suivant -->
                        <div class="mt-12 pt-8 border-t border-gray-200">
                            <div class="flex justify-between items-center gap-4">
                                @if($precedent)
                                <a href="{{ route('actualites.show', $precedent) }}" 
                                class="flex items-center gap-2 text-ed-primary font-bold hover:text-ed-secondary transition group">
                                    <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    <span>Article précédent</span>
                                </a>
                                @else
                                <div></div>
                                @endif
                                
                                @if($suivant)
                                <a href="{{ route('actualites.show', $suivant) }}" 
                                class="flex items-center gap-2 text-ed-primary font-bold hover:text-ed-secondary transition group">
                                    <span>Article suivant</span>
                                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Sidebar -->
                <aside class="space-y-8">
                    
                    <!-- Newsletter -->
                    <div class="bg-gradient-to-br from-ed-primary to-ed-secondary rounded-3xl shadow-xl p-8 text-white">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center mx-auto mb-4 border border-white/30">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-black mb-2">Newsletter</h3>
                            <p class="text-white/90 text-sm">Recevez nos actualités par email</p>
                        </div>
                        
                        <form wire:submit="subscribe" class="space-y-3">
                            <input type="email" 
                                   wire:model="newsletterEmail"
                                   placeholder="Votre email"
                                   class="w-full px-4 py-3 rounded-xl bg-white/20 backdrop-blur-md border border-white/30 text-white placeholder-white/60 focus:ring-2 focus:ring-white/50"
                                   required>
                            
                            <input type="text" 
                                   wire:model="newsletterNom"
                                   placeholder="Votre nom (optionnel)"
                                   class="w-full px-4 py-3 rounded-xl bg-white/20 backdrop-blur-md border border-white/30 text-white placeholder-white/60 focus:ring-2 focus:ring-white/50">
                            
                            <select wire:model="newsletterType"
                                    class="w-full px-4 py-3 rounded-xl bg-white/20 backdrop-blur-md border border-white/30 text-white focus:ring-2 focus:ring-white/50">
                                <option value="autre">Visiteur</option>
                                <option value="doctorant">Doctorant</option>
                                <option value="encadrant">Encadrant</option>
                            </select>
                            
                            <button type="submit" 
                                    class="w-full px-6 py-3 bg-white text-ed-primary rounded-xl font-bold hover:bg-ed-yellow hover:text-gray-900 transition shadow-lg">
                                S'abonner
                            </button>
                        </form>
                        
                        @if (session()->has('newsletter_success'))
                        <div class="mt-4 p-3 bg-green-500/20 backdrop-blur-md border border-green-300/30 rounded-xl text-white text-sm">
                            {{ session('newsletter_success') }}
                        </div>
                        @endif
                        
                        @error('newsletterEmail')
                        <div class="mt-4 p-3 bg-red-500/20 backdrop-blur-md border border-red-300/30 rounded-xl text-white text-sm">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Articles similaires -->
                    @if($similaires->isNotEmpty())
                    <div class="bg-white rounded-3xl shadow-xl p-8">
                        <h3 class="text-2xl font-black text-gray-900 mb-6">Articles similaires</h3>
                        
                        <div class="space-y-6">
                            @foreach($similaires as $similaire)
                            <a href="{{ route('actualites.show', $similaire) }}" 
                               class="block group">
                                <div class="flex gap-4">
                                    @if($similaire->image)
                                    <img src="{{ $similaire->image->url }}" 
                                         class="w-20 h-20 rounded-xl object-cover flex-shrink-0 group-hover:scale-105 transition-transform"
                                         alt="{{ $similaire->titre }}">
                                    @else
                                    <div class="w-20 h-20 rounded-xl bg-gradient-to-br from-ed-primary to-ed-secondary flex-shrink-0"></div>
                                    @endif
                                    
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-bold text-gray-900 group-hover:text-ed-primary line-clamp-2 mb-1">
                                            {{ $similaire->titre }}
                                        </h4>
                                        <p class="text-xs text-gray-500">
                                            {{ $similaire->date_publication->format('d M Y') }}
                                        </p>
                                        @if($similaire->category)
                                        <span class="inline-block mt-1 px-2 py-0.5 text-xs rounded-full text-white"
                                              style="background-color: {{ $similaire->category->couleur }}">
                                            {{ $similaire->category->nom }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                        
                        <a href="{{ route('actualites') }}" 
                           class="mt-6 block text-center px-6 py-3 bg-gray-100 text-gray-900 rounded-xl font-bold hover:bg-gray-200 transition">
                            Voir toutes les actualités
                        </a>
                    </div>
                    @endif
                </aside>
            </div>
        </div>
    </section>
</div>