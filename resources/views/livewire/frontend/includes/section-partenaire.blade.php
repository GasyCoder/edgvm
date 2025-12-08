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