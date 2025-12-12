@props(['messageDirection', 'stats'])

@if($messageDirection)
<section class="py-16 md:py-20 bg-white relative overflow-hidden">
    {{-- Décorations de fond (subtiles, “senior”) --}}
    <div class="pointer-events-none absolute -top-28 right-[-6rem] w-80 h-80 bg-ed-primary/5 rounded-full blur-3xl"></div>
    <div class="pointer-events-none absolute bottom-[-6rem] left-[-7rem] w-96 h-96 bg-ed-yellow/5 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- Header --}}
        <header class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10">
            <div>
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-ed-primary/10 border border-ed-primary/15">
                    <span class="w-2 h-2 bg-ed-primary rounded-full"></span>
                    <span class="text-ed-primary font-semibold text-xs tracking-[0.16em] uppercase">Direction</span>
                </div>

                <h2 class="mt-3 text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">
                    Mot de la Directrice
                </h2>

                <p class="mt-2 text-sm sm:text-[15px] text-gray-600 max-w-2xl">
                    Une vision, des priorités et des engagements au service des doctorants et de la recherche.
                </p>
            </div>

            <div class="hidden md:flex items-center gap-2 text-xs text-gray-500">
                <span class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-3 py-1">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    <span>Message institutionnel</span>
                </span>
            </div>
        </header>

        {{-- Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">

            {{-- PHOTO (centrée verticalement) --}}
            <div class="lg:col-span-5 flex items-center">
                <figure class="relative w-full">
                    <div class="relative rounded-3xl overflow-hidden bg-white shadow-xl ring-1 ring-black/5">
                        <img
                            src="{{ $messageDirection->photo_path ? asset('storage/'.$messageDirection->photo_path) : 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=1200&q=80' }}"
                            alt="{{ $messageDirection->nom }}"
                            class="w-full h-80 md:h-[420px] lg:h-[520px] object-cover object-top"
                            loading="lazy"
                            decoding="async"
                        >

                        {{-- Overlay photo (lisible mais léger) --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/15 to-transparent"></div>

                        {{-- Signature --}}
                        <figcaption class="absolute bottom-0 left-0 right-0 p-5 md:p-6 text-white">
                            <p class="text-base md:text-lg font-semibold leading-tight">
                                {{ $messageDirection->nom }}
                            </p>

                            @if($messageDirection->fonction || $messageDirection->institution)
                                <p class="text-xs md:text-sm text-white/85 mt-1">
                                    {{ $messageDirection->fonction }}
                                    @if($messageDirection->institution) — {{ $messageDirection->institution }} @endif
                                </p>
                            @endif
                        </figcaption>
                    </div>

                    {{-- Cadre décalé (effet premium discret) --}}
                    <div class="hidden md:block absolute -z-10 -bottom-4 -left-4 w-full h-full rounded-3xl border border-gray-200/70"></div>
                </figure>
            </div>

            {{-- TEXTE --}}
            <div class="lg:col-span-7 flex">
                <div class="relative w-full">

                    {{-- Accent latéral “editorial” --}}
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 rounded-full bg-gradient-to-b from-ed-primary/70 via-ed-primary/25 to-ed-yellow/35"></div>

                    <div class="ml-5 bg-white rounded-xl border-gray-50 ring-1 ring-gray-100/70 p-6 md:p-8">

                        {{-- Citation --}}
                        @if($messageDirection->citation)
                            <p class="text-xl md:text-lg text-gray-900 leading-relaxed">
                                “{{ $messageDirection->citation }}”
                            </p>
                            <div class="mt-4 h-px w-16 bg-gray-200"></div>
                        @endif

                        {{-- Message --}}
                        @if($messageDirection->message_intro)
                            <div class="mt-4 text-sm md:text-base text-gray-700 leading-relaxed">
                                {!! nl2br(e($messageDirection->message_intro)) !!}
                            </div>
                        @endif

                        {{-- Contacts (propres, sans “chip” trop flashy) --}}
                        <div class="mt-6 flex flex-wrap gap-2">
                            @if($messageDirection->email)
                                <span class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-gray-50 px-3 py-1 text-xs text-gray-700">
                                    <svg class="w-4 h-4 text-ed-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span>{{ $messageDirection->email }}</span>
                                </span>
                            @endif

                            @if($messageDirection->telephone)
                                <span class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-gray-50 px-3 py-1 text-xs text-gray-700">
                                    <svg class="w-4 h-4 text-ed-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <span>{{ $messageDirection->telephone }}</span>
                                </span>
                            @endif
                        </div>

                        {{-- Footer “signature” --}}
                        <div class="mt-7 flex items-center gap-3">
                            <div class="h-1 w-16 rounded-full bg-gradient-to-r from-ed-primary to-ed-yellow"></div>
                            <span class="text-xs text-gray-500">EDGVM — Université de Mahajanga</span>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endif
