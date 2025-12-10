@if($slider && $slider->slides->count() > 0)
<section 
    class="relative mt-20 text-white overflow-hidden"
    x-data="slider()"
>
    {{-- Fond dynamique simple par slide (couleur_fond) --}}
    <div class="absolute inset-0 -z-10">
        @foreach($slider->slides as $index => $slide)
            <div 
                x-show="currentSlide === {{ $index }}"
                class="absolute inset-0 bg-gradient-to-br {{ $slide->couleur_fond }}"
                x-transition:enter="transition-opacity duration-500"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity duration-500"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            ></div>
        @endforeach
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center min-h-[360px] lg:min-h-[460px]">

            {{-- Colonne gauche : contenu texte du slide --}}
            <div class="relative flex flex-col justify-center min-h-[240px] pr-2 sm:pr-4 lg:pr-10">
                @foreach($slider->slides as $index => $slide)
                    <div 
                        x-show="currentSlide === {{ $index }}"
                        x-transition:enter="transition ease-out duration-700"
                        x-transition:enter-start="opacity-0 translate-x-full"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition ease-in duration-500"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 -translate-x-full"
                        class="absolute inset-0 flex flex-col justify-center space-y-4"
                    >
                        {{-- Badge simple --}}
                        @if($slide->badge_texte)
                            <div class="inline-flex items-center gap-2 mb-1">
                                <span class="w-2 h-2 rounded-full bg-ed-yellow"></span>
                                <span class="text-xs font-semibold tracking-wide uppercase">
                                    {{ $slide->badge_texte }}
                                </span>
                            </div>
                        @endif

                        {{-- Titre : plus large, pas limité, pas de block --}}
                        <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold leading-tight md:leading-tight mb-3">
                            @if($slide->titre_ligne1)
                                <span>{{ $slide->titre_ligne1 }} </span>
                            @endif

                            @if($slide->titre_highlight)
                                <span class="text-ed-yellow">
                                    {{ $slide->titre_highlight }}
                                </span>
                            @endif

                            @if($slide->titre_ligne2)
                                <span> {{ $slide->titre_ligne2 }}</span>
                            @endif
                        </h1>

                        {{-- Description : plus large aussi, pas de max-w --}}
                        @if($slide->description)
                            <p class="text-sm md:text-base text-white/85 leading-relaxed">
                                {{ $slide->description }}
                            </p>
                        @endif

                        {{-- CTA --}}
                        <div class="mt-4 flex flex-wrap gap-3">
                            @if($slide->lien_cta && $slide->texte_cta)
                                <a href="{{ $slide->lien_cta }}"
                                   class="inline-flex items-center px-6 py-3 rounded-xl 
                                          bg-ed-yellow text-gray-900 text-sm font-semibold
                                          shadow-lg hover:bg-amber-400 transition-colors duration-200">
                                    <span>{{ $slide->texte_cta }}</span>
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                              d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </a>
                            @endif

                            @if($index === 0)
                                <a href="{{ route('pages.show', 'a-propos') }}"
                                   class="inline-flex items-center px-6 py-3 rounded-xl border border-white/30
                                          text-sm font-semibold text-white/90 hover:bg-white/10 
                                          hover:text-white transition-colors duration-200">
                                    <span>Découvrir l’École</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Colonne droite : image du slide, arrondie + shadow + blur + opacity --}}
            <div class="relative min-h-[260px] sm:min-h-[320px] lg:min-h-[420px] flex items-center justify-center">
                @foreach($slider->slides as $index => $slide)
                    <div 
                        x-show="currentSlide === {{ $index }}"
                        x-transition:enter="transition ease-out duration-700"
                        x-transition:enter-start="opacity-0 translate-x-full"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition ease-in duration-500"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 -translate-x-full"
                        class="absolute inset-0 flex items-center justify-center"
                    >
                        <div class="w-full max-w-xl bg-white/5 backdrop-blur-md rounded-3xl border border-white/15 shadow-2xl overflow-hidden">
                            <div class="aspect-[4/3] md:aspect-[5/3] lg:aspect-[4/3] relative">
                                @if($slide->image)
                                    <img src="{{ $slide->image->url }}"
                                         alt="{{ $slide->titre_complet }}"
                                         class="w-full h-full object-cover opacity-90">
                                @else
                                    <img src="https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=1200&q=80&auto=format&fit=crop"
                                         alt="{{ $slide->titre_complet }}"
                                         class="w-full h-full object-cover opacity-90">
                                @endif
                                {{-- léger voile pour l’effet soft --}}
                                <div class="absolute inset-0 bg-black/15 mix-blend-multiply"></div>
                            </div>

                            {{-- Bandeau d’infos en bas (optionnel) --}}
                            <div class="px-4 py-3 flex items-center justify-between text-[11px] sm:text-xs text-white/85 bg-black/15">
                                <span class="inline-flex items-center gap-2">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                    <span class="uppercase tracking-[0.12em] font-medium">
                                        École Doctorale EDGVM
                                    </span>
                                </span>
                                <span class="hidden sm:inline">
                                    Recherche – Innovation – Excellence
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Navigation & pagination en bas à droite --}}
                @if($slider->slides->count() > 1)
                    <div class="absolute -bottom-6 right-2 sm:right-4 flex items-center gap-3">
                        <button 
                            @click="previousSlide()"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/80 
                                   text-ed-primary shadow-md hover:bg-white transition-all duration-200"
                            aria-label="Slide précédent"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                      d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>

                        <button 
                            @click="nextSlide()"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/80 
                                   text-ed-primary shadow-md hover:bg-white transition-all duration-200"
                            aria-label="Slide suivant"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                      d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>

                        <div class="flex gap-1.5">
                            @foreach($slider->slides as $slideIndex => $slide)
                                <button 
                                    @click="goToSlide({{ $slideIndex }})"
                                    class="h-1.5 rounded-full transition-all duration-200"
                                    :class="currentSlide === {{ $slideIndex }} 
                                        ? 'w-4 bg-white' 
                                        : 'w-2 bg-white/40 hover:bg-white/70'"
                                ></button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</section>
@endif
