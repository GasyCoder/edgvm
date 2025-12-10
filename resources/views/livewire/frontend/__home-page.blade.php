<div>
{{-- section hero --}}
@if($slider && $slider->slides->count() > 0)
<section class="relative h-[73vh] mt-20 overflow-hidden" x-data="slider()">
    <!-- Slides Container -->
    <div class="relative h-full">
        @foreach($slider->slides as $index => $slide)
        <!-- Slide {{ $index + 1 }} -->
        <div x-show="currentSlide === {{ $index }}" 
             x-transition:enter="transition ease-out duration-1000"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-1000"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 w-full h-full">
            
            <!-- Background gradient -->
            <div class="absolute inset-0 bg-gradient-to-br {{ $slide->couleur_fond }}"></div>
            
            <!-- Image de fond -->
            @if($slide->image)
            <img src="{{ $slide->image->url }}" 
                 class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-20" 
                 alt="{{ $slide->titre_complet }}">
            @else
            <img src="https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=1920&q=80" 
                 class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-20" 
                 alt="{{ $slide->titre_complet }}">
            @endif
            
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
            
            <div class="relative h-full flex items-center z-10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                    <div class="max-w-4xl">
                        <!-- Badge - Plus d'espace en bas -->
                        @if($slide->badge_texte)
                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-md rounded-full border border-white/20 mb-8">
                            @if($slide->badge_icon === 'university')
                                <div class="w-2 h-2 bg-ed-yellow rounded-full animate-pulse"></div>
                            @elseif($slide->badge_icon === 'research')
                                <svg class="w-4 h-4 text-ed-yellow" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                </svg>
                            @elseif($slide->badge_icon === 'students')
                                <svg class="w-4 h-4 text-ed-yellow" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                </svg>
                            @endif
                            <span class="text-white text-sm font-medium">{{ $slide->badge_texte }}</span>
                        </div>
                        @endif
                        
                        <!-- Titre - AVEC PLUS D'ESPACE -->
                        <h1 class="text-3xl md:text-5xl font-black mb-10 mt-4 leading-[1.3] md:leading-[1.4] text-white">
                            @if($slide->titre_ligne1)
                                {{ $slide->titre_ligne1 }} 
                            @endif
                            <span class="text-ed-yellow">{{ $slide->titre_highlight }}</span>
                            @if($slide->titre_ligne2)
                                <br class="hidden md:block"><!-- Responsive break -->
                                {{ $slide->titre_ligne2 }}
                            @endif
                        </h1>
                        <!-- Description -->
                        @if($slide->description)
                        <p class="text-xl md:text-2xl mb-10 text-white/90 font-light max-w-2xl">
                            {{ $slide->description }}
                        </p>
                        @endif
                        
                        <!-- CTA Buttons -->
                        <div class="flex flex-wrap gap-4">
                            @if($slide->lien_cta && $slide->texte_cta)
                            <a href="{{ $slide->lien_cta }}" class="group px-8 py-4 bg-ed-yellow text-gray-900 rounded-xl hover:bg-ed-yellow-dark transition-all duration-300 font-bold shadow-2xl hover:shadow-ed-yellow/50 hover:scale-[1.02] inline-flex items-center">
                                <span>{{ $slide->texte_cta }}</span>
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                            @endif
                            
                            @if($index === 0)
                            <a href="{{ route('pages.show', 'a-propos') }}" class="px-8 py-4 bg-white/10 backdrop-blur-md text-white border-2 border-white/30 rounded-xl hover:bg-white hover:text-ed-primary transition-all duration-300 font-bold hover:scale-[1.02]">
                                Découvrir l'école
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Navigation moderne -->
    @if($slider->slides->count() > 1)
    <button @click="previousSlide()" 
            class="absolute left-6 md:left-10 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white hover:bg-white hover:text-ed-primary transition-all duration-300 flex items-center justify-center group">
        <svg class="w-6 h-6 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
        </svg>
    </button>
    
    <button @click="nextSlide()" 
            class="absolute right-6 md:right-10 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white hover:bg-white hover:text-ed-primary transition-all duration-300 flex items-center justify-center group">
        <svg class="w-6 h-6 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
        </svg>
    </button>

    <!-- Pagination moderne -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex gap-2">
        @foreach($slider->slides as $slideIndex => $slide)
        <button @click="goToSlide({{ $slideIndex }})"
                :class="currentSlide === {{ $slideIndex }} ? 'w-10 bg-white' : 'w-2 bg-white/40 hover:bg-white/60'"
                class="h-2 rounded-full transition-all duration-300">
        </button>
        @endforeach
    </div>
    @endif
</section>
@endif

{{-- section stats --}}
<section class="py-20 bg-gradient-to-b from-gray-50 to-white relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 1 -->
            <div class="group bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 border border-gray-100 hover:-translate-y-1">
                <div class="w-16 h-16 bg-gradient-to-br from-ed-primary to-ed-secondary rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-3xl font-black text-gray-900 mb-2">{{ $stats['doctorants'] }}+</h3>
                <p class="text-gray-600 font-medium">Doctorants actifs</p>
                <div class="mt-4 h-1 w-12 bg-gradient-to-r from-ed-primary to-ed-secondary rounded-full"></div>
            </div>

            <!-- Card 2 -->
            <div class="group bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 border border-gray-100 hover:-translate-y-1">
                <div class="w-16 h-16 bg-gradient-to-br from-ed-yellow to-yellow-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-3xl font-black text-gray-900 mb-2">{{ $stats['encadrants'] }}+</h3>
                <p class="text-gray-600 font-medium">Encadrants experts</p>
                <div class="mt-4 h-1 w-12 bg-gradient-to-r from-ed-yellow to-yellow-500 rounded-full"></div>
            </div>

            <!-- Card 3 -->
            <div class="group bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 border border-gray-100 hover:-translate-y-1">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
                <h3 class="text-3xl font-black text-gray-900 mb-2">{{ $stats['theses_soutenues'] }}+</h3>
                <p class="text-gray-600 font-medium">Thèses soutenues</p>
                <div class="mt-4 h-1 w-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full"></div>
            </div>

            <!-- Card 4 -->
            <div class="group bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 border border-gray-100 hover:-translate-y-1">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-3xl font-black text-gray-900 mb-2">{{ $stats['equipes'] }}</h3>
                <p class="text-gray-600 font-medium">Equipes d'accueil</p>
                <div class="mt-4 h-1 w-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full"></div>
            </div>
        </div>
    </div>
</section>

{{-- section about --}}
<section class="py-24 bg-white relative overflow-hidden">
    <!-- Motifs décoratifs -->
    <div class="absolute top-0 right-0 w-[40rem] h-[40rem] bg-gradient-to-br from-ed-primary/5 to-transparent rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-[40rem] h-[40rem] bg-gradient-to-tr from-ed-yellow/5 to-transparent rounded-full blur-3xl"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-ed-primary/10 rounded-full mb-6">
                    <div class="w-2 h-2 bg-ed-primary rounded-full"></div>
                    <span class="text-ed-primary font-semibold text-sm">À propos</span>
                </div>
                
                <h2 class="text-2xl md:text-3xl font-black text-gray-900 mb-6 leading-tight">
                    École Doctorale<br>
                    <span class="text-ed-primary">Génie du Vivant</span> et Modélisation
                </h2>
                
                <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                    L'EDGVM est un centre d'excellence en recherche qui forme les docteurs de demain dans les domaines du génie du vivant, de la modélisation mathématique et de l'innovation technologique.
                </p>
                
                <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                    Notre mission est de développer des compétences de haut niveau en recherche scientifique et de contribuer à l'avancement des connaissances.
                </p>
                
                <a href="{{ route('pages.show', 'a-propos') }}" class="group inline-flex items-center px-8 py-4 bg-ed-primary text-white rounded-xl hover:bg-ed-secondary transition-all duration-300 font-bold shadow-lg hover:shadow-xl hover:scale-[1.02]">
                    <span>Découvrir l'école</span>
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>

            <!-- Vidéo YouTube avec design moderne -->
            <div class="relative">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl group">
                    <!-- Container vidéo responsive 16:9 -->
                    <div class="relative pb-[56.25%] h-0">
                        <iframe 
                            class="absolute top-0 left-0 w-full h-full"
                            src="https://www.youtube.com/embed/ePyaHxQ8jpM?controls=1&modestbranding=1&rel=0" 
                            title="Présentation EDGVM" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                        </iframe>
                    </div>
                    <!-- Overlay décoratif (apparaît au hover) -->
                    <div class="absolute inset-0 bg-gradient-to-t from-ed-primary/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                </div>
                
                <!-- Badge "Vidéo de présentation" -->
                <div class="absolute -top-4 -left-4 z-10">
                    <div class="bg-ed-yellow text-gray-900 px-4 py-2 rounded-xl font-bold text-sm shadow-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                        </svg>
                        <span>Vidéo présentation</span>
                    </div>
                </div>
                
                <!-- Éléments décoratifs -->
                <div class="absolute -bottom-6 -right-6 w-72 h-72 bg-ed-yellow/20 rounded-full blur-3xl -z-10"></div>
                <div class="absolute -top-6 -left-6 w-48 h-48 bg-ed-primary/20 rounded-full blur-2xl -z-10"></div>
                
                <!-- Stats flottants -->
                <div class="absolute -bottom-6 left-8 bg-white rounded-2xl shadow-2xl p-4 z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-ed-primary/10 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">109+</p>
                            <p class="text-xs text-gray-600">Doctorants</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- section message direction --}}
@props(['messageDirection', 'stats'])
@if($messageDirection)
    <section class="py-24 bg-gradient-to-b from-gray-50 to-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-ed-primary/10 rounded-full mb-6">
                    <div class="w-2 h-2 bg-ed-primary rounded-full"></div>
                    <span class="text-ed-primary font-semibold text-sm">Direction</span>
                </div>
                <h2 class="text-4xl md:text-4xl font-black text-gray-900">
                    Mot de la Directrice
                </h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Photo -->
                <div class="relative">
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl">
                        <img 
                            src="{{ $messageDirection->photo_path 
                                    ? asset('storage/'.$messageDirection->photo_path) 
                                    : 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=800&q=80' }}"
                            alt="{{ $messageDirection->nom }}" 
                            class="w-full h-[550px] object-cover">
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-ed-primary/90 via-ed-primary/40 to-transparent"></div>
                        
                        <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                            <h3 class="text-3xl font-black mb-2">{{ $messageDirection->nom }}</h3>
                            @if($messageDirection->fonction)
                                <p class="text-lg text-white/90 mb-1">{{ $messageDirection->fonction }}</p>
                            @endif
                            @if($messageDirection->institution)
                                <p class="text-white/80 text-sm mb-6">{{ $messageDirection->institution }}</p>
                            @endif
                            
                            <div class="flex flex-wrap gap-3">
                                @if($messageDirection->telephone)
                                    <div class="flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-md rounded-lg border border-white/20">
                                        <svg class="w-4 h-4 text-ed-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        <span class="text-sm">{{ $messageDirection->telephone }}</span>
                                    </div>
                                @endif

                                @if($messageDirection->email)
                                    <div class="flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-md rounded-lg border border-white/20">
                                        <svg class="w-4 h-4 text-ed-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-sm">{{ $messageDirection->email }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="absolute -bottom-6 -right-6 w-72 h-72 bg-ed-yellow/20 rounded-full blur-3xl -z-10"></div>
                </div>

                <!-- Message -->
                <div class="space-y-6">
                    <div class="relative bg-white rounded-3xl p-8 md:p-10 shadow-xl border border-gray-100">
                        <!-- Quote -->
                        <svg class="absolute -top-4 -left-4 w-12 h-12 text-ed-yellow/20" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                        </svg>
                        
                        @if($messageDirection->citation)
                            <p class="text-lg text-gray-700 leading-relaxed mb-6 italic">
                                "{{ $messageDirection->citation }}"
                            </p>
                        @endif
                        
                        @if($messageDirection->message_intro)
                            <p class="text-base text-gray-600 leading-relaxed mb-6">
                                {!! nl2br(e($messageDirection->message_intro)) !!}
                            </p>
                        @endif
                        
                        <div class="flex items-center gap-3 pt-6 border-t border-gray-100">
                            <div class="flex-grow">
                                <p class="font-bold text-ed-primary text-lg">{{ $messageDirection->nom }}</p>
                                @if($messageDirection->fonction)
                                    <p class="text-gray-600 text-sm">{{ $messageDirection->fonction }}</p>
                                @endif
                            </div>
                            <div class="w-14 h-1 bg-ed-yellow rounded-full"></div>
                        </div>
                    </div>

                    <!-- Chiffres -->
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-white rounded-xl shadow-lg p-5 text-center border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                            <p class="text-3xl font-black text-ed-primary mb-1">{{ $stats['doctorants'] }}</p>
                            <p class="text-xs text-gray-600 font-medium">Doctorants</p>
                        </div>
                        <div class="bg-white rounded-xl shadow-lg p-5 text-center border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                            <p class="text-3xl font-black text-ed-primary mb-1">{{ $stats['equipes'] }}</p>
                            <p class="text-xs text-gray-600 font-medium">Équipes</p>
                        </div>
                        <div class="bg-white rounded-xl shadow-lg p-5 text-center border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                            <p class="text-3xl font-black text-ed-primary mb-1">{{ $stats['theses_soutenues'] }}</p>
                            <p class="text-xs text-gray-600 font-medium">Thèses</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

{{-- section programmes --}}
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

{{-- Section actualités  --}}
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

{{-- section CTA --}}
<section class="relative py-20 bg-gradient-to-br from-ed-primary via-ed-secondary to-teal-700 overflow-hidden">
    <!-- Motifs animés -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-96 h-96 bg-ed-yellow rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
    </div>
    
    <!-- Grille -->
    <div class="absolute inset-0 opacity-5">
        <div class="h-full w-full" style="background-image: linear-gradient(white 1px, transparent 1px), linear-gradient(90deg, white 1px, transparent 1px); background-size: 50px 50px;"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-md rounded-full border border-white/20 mb-8">
                <svg class="w-4 h-4 text-ed-yellow" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                </svg>
                <span class="text-white text-sm font-semibold">Rejoignez-nous</span>
            </div>

            <h2 class="text-4xl md:text-5xl font-black text-white mb-6 leading-tight">
                Prêt à commencer votre<br class="hidden md:block">parcours doctoral ?
            </h2>
            
            <p class="text-xl text-white/90 mb-10 max-w-3xl mx-auto">
                Rejoignez l'EDGVM et contribuez à l'avancement de la science
            </p>

            <!-- Stats inline -->
            <div class="flex flex-wrap justify-center gap-8 mb-10">
                <div class="text-center">
                    <p class="text-4xl font-black text-white mb-1">109</p>
                    <p class="text-white/80 text-sm font-medium">Doctorants actifs</p>
                </div>
                <div class="w-px h-16 bg-white/20"></div>
                <div class="text-center">
                    <p class="text-4xl font-black text-white mb-1">8</p>
                    <p class="text-white/80 text-sm font-medium">Équipes d'accueil</p>
                </div>
                <div class="w-px h-16 bg-white/20"></div>
                <div class="text-center">
                    <p class="text-4xl font-black text-white mb-1">23</p>
                    <p class="text-white/80 text-sm font-medium">Thèses soutenues</p>
                </div>
            </div>

            <!-- Boutons -->
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('register') }}" class="group px-8 py-4 bg-ed-yellow text-gray-900 rounded-xl hover:bg-ed-yellow-dark transition-all duration-300 font-bold shadow-2xl hover:shadow-ed-yellow/50 hover:scale-[1.02] inline-flex items-center">
                    <span>Candidater maintenant</span>
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
                <a href="{{ route('contact') }}" class="px-8 py-4 bg-white text-ed-primary rounded-xl hover:bg-white/90 transition-all duration-300 font-bold shadow-xl hover:scale-[1.02] inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span>Nous contacter</span>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Section partenaires --}}
@if($partenaires->count() > 0)
    @php
        // On découpe les partenaires en "slides" de 5 logos max
        $slides = $partenaires->chunk(5);
    @endphp

    <section class="py-14 bg-gradient-to-b from-white via-slate-50 to-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- En-tête --}}
            <div class="text-center mb-10">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-ed-primary/10 rounded-full mb-3">
                    <div class="w-2 h-2 bg-ed-primary rounded-full"></div>
                    <span class="text-ed-primary font-semibold text-xs">Nos partenaires</span>
                </div>
                <h2 class="text-2xl md:text-3xl font-black text-gray-900 mb-2">
                    Ils nous font confiance
                </h2>
                <p class="text-sm md:text-base text-gray-600 max-w-2xl mx-auto">
                    Partenaires académiques, institutionnels et projets collaboratifs qui accompagnent l’EDGVM.
                </p>
            </div>

            {{-- CARROUSEL --}}
            <div 
                x-data="{
                    current: 0,
                    total: {{ $slides->count() }},
                    next() {
                        this.current = (this.current + 1) % this.total;
                    },
                    prev() {
                        this.current = this.current === 0 ? this.total - 1 : this.current - 1;
                    }
                }"
                class="relative"
            >
                {{-- Zone de slides --}}
                <div class="overflow-hidden">
                    <div 
                        class="flex transition-transform duration-500 ease-out"
                        :style="`transform: translateX(-${current * 100}%)`"
                    >
                        @foreach($slides as $slideIndex => $slide)
                            <div class="w-full shrink-0">
                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-x-8 gap-y-10 items-stretch mb-6">
                                    @foreach($slide as $partenaire)
                                        @php
                                            $logoUrl = $partenaire->logo_url ?? null;

                                            // Initiales si pas de logo
                                            $initials = '';
                                            if (! empty($partenaire->nom)) {
                                                $parts = preg_split('/\s+/', trim($partenaire->nom));
                                                if (is_array($parts)) {
                                                    $count = 0;
                                                    foreach ($parts as $part) {
                                                        if ($count >= 2) {
                                                            break;
                                                        }
                                                        $initials .= mb_substr($part, 0, 1);
                                                        $count++;
                                                    }
                                                }
                                            }
                                        @endphp

                                        @if($partenaire->url)
                                            {{-- Carte cliquable --}}
                                            <a href="{{ $partenaire->url }}" target="_blank" rel="noopener noreferrer"
                                               class="group flex flex-col items-center text-center gap-3">
                                                <div class="flex items-center justify-center h-12 sm:h-14">
                                                    @if($logoUrl)
                                                        <img src="{{ $logoUrl }}"
                                                             alt="{{ $partenaire->nom }}"
                                                             class="h-10 sm:h-12 w-auto object-contain opacity-80 group-hover:opacity-100 grayscale group-hover:grayscale-0 transition-all duration-300">
                                                    @else
                                                        <div class="h-12 w-12 rounded-full bg-ed-primary/10 flex items-center justify-center text-ed-primary font-bold text-sm">
                                                            {{ $initials ?: 'P' }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="max-w-[11rem]">
                                                    <p class="text-xs sm:text-sm font-semibold text-gray-700 line-clamp-2 group-hover:text-ed-primary transition-colors"
                                                       title="{{ $partenaire->nom }}">
                                                        {{ $partenaire->nom }}
                                                    </p>

                                                    @if($partenaire->description)
                                                        <p class="mt-1 text-[11px] text-gray-500 line-clamp-2">
                                                            {{ $partenaire->description }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </a>
                                        @else
                                            {{-- Carte non cliquable --}}
                                            <div class="group flex flex-col items-center text-center gap-3">
                                                <div class="flex items-center justify-center h-12 sm:h-14">
                                                    @if($logoUrl)
                                                        <img src="{{ $logoUrl }}"
                                                             alt="{{ $partenaire->nom }}"
                                                             class="h-10 sm:h-12 w-auto object-contain opacity-80 group-hover:opacity-100 grayscale group-hover:grayscale-0 transition-all duration-300">
                                                    @else
                                                        <div class="h-12 w-12 rounded-full bg-ed-primary/10 flex items-center justify-center text-ed-primary font-bold text-sm">
                                                            {{ $initials ?: 'P' }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="max-w-[11rem]">
                                                    <p class="text-xs sm:text-sm font-semibold text-gray-700 line-clamp-2 group-hover:text-ed-primary transition-colors"
                                                       title="{{ $partenaire->nom }}">
                                                        {{ $partenaire->nom }}
                                                    </p>

                                                    @if($partenaire->description)
                                                        <p class="mt-1 text-[11px] text-gray-500 line-clamp-2">
                                                            {{ $partenaire->description }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Boutons précédent / suivant --}}
                @if($slides->count() > 1)
                    <button
                        type="button"
                        @click="prev()"
                        class="hidden sm:flex absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 w-9 h-9 rounded-full bg-white shadow-md border border-gray-200 items-center justify-center text-gray-600 hover:bg-ed-primary hover:text-white transition"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                  d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>

                    <button
                        type="button"
                        @click="next()"
                        class="hidden sm:flex absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 w-9 h-9 rounded-full bg-white shadow-md border border-gray-200 items-center justify-center text-gray-600 hover:bg-ed-primary hover:text-white transition"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                  d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                @endif

                {{-- Petits indicateurs de pages --}}
                @if($slides->count() > 1)
                    <div class="flex items-center justify-center gap-2 mt-3">
                        @foreach(range(0, $slides->count() - 1) as $index)
                            <button
                                type="button"
                                @click="current = {{ $index }}"
                                class="h-2 rounded-full transition-all duration-300"
                                :class="current === {{ $index }} ? 'w-6 bg-ed-primary' : 'w-2 bg-gray-300'"
                            ></button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- CTA Devenir partenaire --}}
            <div class="text-center mt-6">
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-ed-primary/30 text-sm font-semibold text-ed-primary bg-ed-primary/5 hover:bg-ed-primary hover:text-white transition-all duration-300">
                    <span>Devenir partenaire</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                              d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>
@endif


{{-- Section Événements à venir --}}
@if(isset($evenementsFuturs) && $evenementsFuturs->count() > 0)
<section aria-labelledby="evenements-heading" class="relative py-12">
    <!-- Background decorations -->
    <div class="pointer-events-none absolute -left-16 top-0 w-72 h-72 rounded-full blur-3xl bg-gradient-to-br from-purple-400 to-indigo-600 opacity-20"></div>
    <div class="pointer-events-none absolute right-0 bottom-0 w-96 h-96 rounded-full blur-3xl bg-gradient-to-br from-indigo-500 to-purple-700 opacity-15"></div>

    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center justify-center p-2 rounded-full bg-purple-600 text-white">
                    <!-- Icon calendrier (Heroicons SVG calendrier) -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </span>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Prochains événements</span>
                    <h2 id="evenements-heading" class="text-3xl md:text-4xl font-extrabold text-black">Événements à venir</h2>
                </div>
            </div>

            <!-- Desktop button -->
            <div class="hidden md:block">
                <a href="{{ route('evenements.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-md shadow-md bg-white/80 backdrop-blur-sm text-sm font-medium border border-gray-200 hover:shadow-lg">
                    Voir tous les événements
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($evenementsFuturs as $evenement)
            <article class="relative bg-white/60 backdrop-blur-md rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transform hover:-translate-y-2 transition-all duration-300 group">
                <!-- Header with gradient and date -->
                <header class="p-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="text-center">
                            <div class="text-4xl md:text-5xl font-extrabold leading-none">{{ $evenement->jour }}</div>
                            <div class="text-sm uppercase tracking-wide">{{ $evenement->mois }}</div>
                        </div>
                        <div class="ml-3">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $evenement->type_classe }}">
                                {{ $evenement->type_texte }}
                            </span>
                            @if($evenement->est_important)
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-red-600 text-white text-xs font-semibold">Important</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="text-right text-xs">
                        <div class="font-medium">{{ $evenement->periode_aff }}</div>
                        @if($evenement->heure_debut)
                            <div class="mt-1 flex items-center gap-2 text-sm">
                                <!-- icone horloge -->
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ $evenement->heure_debut_aff ?? '' }}</span>
                            </div>
                        @endif
                        @if($evenement->lieu)
                            <div class="mt-1 flex items-center gap-2 text-sm">
                                <!-- icone localisation -->
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1 1 0 01-1.414 0L6.343 15.243a8 8 0 1111.314 1.414z"/>
                                </svg>
                                <span>{{ Str::limit($evenement->lieu, 30) }}</span>
                            </div>
                        @endif
                    </div>
                </header>

                <!-- Body -->
                <div class="p-4">
                    <h3 class="text-lg md:text-xl font-bold leading-tight mb-2 line-clamp-2">{{ $evenement->titre }}</h3>
                    <p class="text-sm text-gray-700 mb-4 line-clamp-2">{{ $evenement->description ? Str::limit(strip_tags($evenement->description), 120) : '' }}</p>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('evenements.show', $evenement) }}" class="inline-flex items-center gap-2 text-sm font-medium px-3 py-2 rounded-md bg-white border border-gray-200 hover:shadow">
                            En savoir plus
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>

                        @if($evenement->lien_inscription)
                            <a href="{{ $evenement->lien_inscription }}" target="_blank" class="text-sm underline">S'inscrire</a>
                        @endif
                    </div>
                </div>

                <!-- Hover progress bar -->
                <div class="absolute left-0 bottom-0 w-full h-1 bg-transparent">
                    <div class="h-1 bg-gradient-to-r from-purple-400 to-indigo-600 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-500"></div>
                </div>
            </article>
            @endforeach
        </div>

        <!-- Mobile button -->
        <div class="mt-6 md:hidden text-center">
            <a href="{{ route('evenements.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-md shadow-md bg-white/90 backdrop-blur-sm text-sm font-medium border border-gray-200 hover:shadow-lg">
                Voir tous les événements
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>
@endif


</div>

@push('scripts')
<script>
    function slider() {
        return {
            currentSlide: 0,
            slides: @json(range(0, $slider ? $slider->slides->count() - 1 : 0)),
            interval: null,
            
            init() {
                if (this.slides.length > 1) {
                    this.startAutoplay();
                }
            },
            
            nextSlide() {
                this.currentSlide = (this.currentSlide + 1) % this.slides.length;
                this.resetAutoplay();
            },
            
            previousSlide() {
                this.currentSlide = this.currentSlide === 0 ? this.slides.length - 1 : this.currentSlide - 1;
                this.resetAutoplay();
            },
            
            goToSlide(index) {
                this.currentSlide = index;
                this.resetAutoplay();
            },
            
            startAutoplay() {
                this.interval = setInterval(() => {
                    this.nextSlide();
                }, 6000);
            },
            
            resetAutoplay() {
                clearInterval(this.interval);
                this.startAutoplay();
            }
        }
    }
</script>
@endpush