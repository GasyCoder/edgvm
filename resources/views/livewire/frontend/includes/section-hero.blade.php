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
