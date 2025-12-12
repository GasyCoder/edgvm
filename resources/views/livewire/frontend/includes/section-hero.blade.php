@if($slider && $slider->slides->count() > 0)
<section
    class="relative mt-20 text-white overflow-hidden"
    x-data="slider()"
>
    {{-- SEO H1 (OK) --}}
    <header class="sr-only">
        <h1>École Doctorale Génie du Vivant et Modélisation (EDGVM) – Université de Mahajanga</h1>
    </header>

    {{-- Fond dynamique par slide (votre logique) --}}
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
        <div class="absolute inset-0 bg-gradient-to-b from-black/45 via-black/25 to-black/35"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(255,255,255,0.10),transparent_55%)]"></div>
        <div class="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-white/25 to-transparent"></div>

        {{-- Overlays subtils (pas “opacity direct” forte) --}}
        <div class="absolute inset-0 bg-gradient-to-b from-black/25 via-black/10 to-black/25"></div>

        {{-- Légère “lumière” latérale (effet premium) --}}
        <div class="pointer-events-none absolute -top-24 -right-24 w-[520px] h-[520px] rounded-full bg-ed-yellow/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-28 -left-28 w-[520px] h-[520px] rounded-full bg-ed-primary/12 blur-3xl"></div>

        {{-- Grain très léger --}}
        <div class="absolute inset-0 opacity-[0.06] mix-blend-overlay
            bg-[radial-gradient(circle_at_1px_1px,rgba(255,255,255,0.9)_0.6px,transparent_0)]
            [background-size:12px_12px]"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center min-h-[360px] lg:min-h-[460px]">

            {{-- Colonne gauche : contenu texte (structure identique) --}}
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
                        {{-- Badge (plus premium, sans changer la logique) --}}
                        @if($slide->badge_texte)
                            <div class="inline-flex items-center gap-2 mb-1 w-fit
                                        px-3 py-1.5 rounded-full bg-white/10 ring-1 ring-white/15 backdrop-blur">
                                <span class="w-2 h-2 rounded-full bg-ed-yellow"></span>
                                <span class="text-[11px] font-semibold tracking-[0.18em] uppercase text-white/90">
                                    {{ $slide->badge_texte }}
                                </span>
                            </div>
                        @endif

                        {{-- Titre (meilleure typo, toujours H2 chez vous) --}}
                        <h2 class="text-2xl md:text-3xl lg:text-4xl font-extrabold tracking-tight
                                   leading-[1.12] md:leading-[1.12] drop-shadow-[0_6px_18px_rgba(0,0,0,0.28)]">
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
                        </h2>

                        {{-- Description (lisibilité améliorée) --}}
                        @if($slide->description)
                            <p class="text-sm md:text-base text-white/85 leading-relaxed max-w-xl">
                                {{ $slide->description }}
                            </p>
                        @endif

                        {{-- CTA (même structure, meilleur rendu) --}}
                        <div class="mt-4 flex flex-wrap gap-3">
                            @if($slide->lien_cta && $slide->texte_cta)
                                <a href="{{ $slide->lien_cta }}"
                                   class="inline-flex items-center px-6 py-3 rounded-xl
                                          bg-ed-yellow text-gray-900 text-sm font-semibold
                                          shadow-lg shadow-black/20 hover:brightness-[0.98] hover:-translate-y-[1px]
                                          transition-all duration-200
                                          focus:outline-none focus:ring-2 focus:ring-ed-yellow/60 focus:ring-offset-1 focus:ring-offset-transparent">
                                    <span>{{ $slide->texte_cta }}</span>
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                              d="M13 7l5 5m0 0-5 5m5-5H6"/>
                                    </svg>
                                </a>
                            @endif

                            @if($index === 0)
                                <a href="{{ route('pages.show', 'a-propos') }}"
                                   class="inline-flex items-center px-6 py-3 rounded-xl border border-white/30
                                          text-sm font-semibold text-white/90
                                          hover:bg-white/10 hover:border-white/40 hover:text-white
                                          transition-colors duration-200
                                          focus:outline-none focus:ring-2 focus:ring-white/50 focus:ring-offset-1 focus:ring-offset-transparent">
                                    <span>Découvrir l’École</span>
                                </a>
                            @endif
                        </div>

                        {{-- micro-infos (optionnel, discret) --}}
                        <div class="pt-2 flex flex-wrap gap-2 text-[11px] text-white/75">
                            <span class="inline-flex items-center gap-2 rounded-full bg-black/10 px-3 py-1 ring-1 ring-white/10">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                Université de Mahajanga
                            </span>
                            <span class="inline-flex items-center gap-2 rounded-full bg-black/10 px-3 py-1 ring-1 ring-white/10">
                                Recherche • Innovation • Excellence
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Colonne droite : image (structure identique) --}}
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
                        <div class="w-full max-w-xl bg-white/6 backdrop-blur-md rounded-3xl
                                    border border-white/15 shadow-2xl shadow-black/30 overflow-hidden">
                            <div class="aspect-[4/3] md:aspect-[5/3] lg:aspect-[4/3] relative">
                                @if($slide->image)
                                    <img src="{{ $slide->image->url }}"
                                         alt="{{ $slide->titre_complet }}"
                                         loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                         decoding="async"
                                         fetchpriority="{{ $index === 0 ? 'high' : 'auto' }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <img src="https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=1200&q=80&auto=format&fit=crop"
                                         alt="{{ $slide->titre_complet }}"
                                         class="w-full h-full object-cover">
                                @endif

                                {{-- voile très léger + vignette premium --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-black/25 via-transparent to-black/10"></div>
                            </div>

                            {{-- Bandeau d’infos (un peu plus clean) --}}
                            <div class="px-4 py-3 flex items-center justify-between text-[11px] sm:text-xs text-white/85 bg-black/10">
                                <span class="inline-flex items-center gap-2">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                    <span class="uppercase tracking-[0.14em] font-semibold">
                                        École Doctorale EDGVM
                                    </span>
                                </span>
                                <span class="hidden sm:inline text-white/75">
                                    Recherche – Innovation – Excellence
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Navigation & pagination (même endroit) --}}
                @if($slider->slides->count() > 1)
                    <div class="absolute -bottom-6 right-2 sm:right-4 flex items-center gap-3">
                        <button
                            @click="previousSlide()"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/85
                                   text-ed-primary shadow-md hover:bg-white transition-all duration-200
                                   focus:outline-none focus:ring-2 focus:ring-white/50"
                            aria-label="Slide précédent"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                      d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>

                        <button
                            @click="nextSlide()"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/85
                                   text-ed-primary shadow-md hover:bg-white transition-all duration-200
                                   focus:outline-none focus:ring-2 focus:ring-white/50"
                            aria-label="Slide suivant"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
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
                                        ? 'w-6 bg-white'
                                        : 'w-2 bg-white/35 hover:bg-white/60'"
                                    aria-label="Aller au slide {{ $slideIndex + 1 }}"
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
