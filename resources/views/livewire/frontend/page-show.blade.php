<div>
    {{-- HERO --}}
    <section class="relative gradient-primary pt-24 md:pt-28 pb-16 md:pb-20 overflow-hidden">
        {{-- Décor de fond --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute inset-0 bg-[url('/images/pattern.svg')] opacity-10"></div>
            <div class="absolute -top-24 -right-10 w-80 h-80 md:w-96 md:h-96 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-10 w-80 h-80 md:w-96 md:h-96 bg-white/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumb --}}
            <nav class="mb-6 md:mb-8">
                <ol class="flex flex-wrap items-center gap-2 text-xs md:text-sm text-white/70">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-white transition">
                            Accueil
                        </a>
                    </li>
                    <li>
                        <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5l7 7-7 7"/>
                        </svg>
                    </li>
                    <li class="font-semibold text-white">
                        {{ $page->titre }}
                    </li>
                </ol>
            </nav>

            {{-- Titre + meta --}}
            <div class="max-w-3xl">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-xs md:text-sm text-white/80 mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                    Page de contenu de l’EDGVM
                </span>

                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4 leading-tight">
                    {{ $page->titre }}
                </h1>

                @if($page->updated_at)
                    <p class="text-white/80 text-xs md:text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Dernière mise à jour :
                        <span class="font-semibold">
                            {{ $page->updated_at->format('d/m/Y') }}
                        </span>
                    </p>
                @endif
            </div>
        </div>
    </section>

    @php
        $hasContent = filled($page->contenu) || $page->sections->isNotEmpty();
    @endphp

    {{-- CONTENU PRINCIPAL --}}
    <section class="relative bg-gradient-to-b from-gray-50 to-white pb-16 pt-6 md:pt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(!$hasContent)
                {{-- ÉTAT VIDE : aucune section / contenu --}}
                <div class="-mt-16 md:-mt-20">
                    <div class="bg-white rounded-2xl shadow-xl p-12 md:p-16 border border-gray-100 text-center">
                        <div class="max-w-md mx-auto">
                            <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5
                                             a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414
                                             a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">
                                Contenu en cours de rédaction
                            </h3>
                            <p class="text-gray-600 leading-relaxed">
                                Cette page sera bientôt enrichie avec des informations détaillées par l’équipe de l’École Doctorale.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                {{-- LAYOUT AVEC CONTENU & SIDEBAR --}}
                <div class="-mt-16 md:-mt-20">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {{-- Colonne principale --}}
                        <div class="lg:col-span-2 space-y-10">
                            {{-- Contenu principal --}}
                            @if($page->contenu)
                                <article class="bg-white rounded-2xl shadow-xl p-6 md:p-10 border border-gray-100">
                                    <header class="mb-6 md:mb-8">
                                        <h2 class="text-xl md:text-2xl font-semibold text-gray-900 flex items-center gap-2">
                                            <span class="w-1.5 h-7 bg-gradient-to-b from-blue-600 to-indigo-600 rounded-full"></span>
                                            Présentation
                                        </h2>
                                    </header>

                                    <div class="prose prose-indigo max-w-none text-justify leading-relaxed">
                                        {{-- {!! nl2br(e($page->contenu)) !!} --}}
                                        {!! $page->contenu !!}
                                    </div>
                                </article>
                            @endif

                            {{-- Sections --}}
                            @if($page->sections->isNotEmpty())
                                <div class="space-y-10">
                                    @foreach($page->sections as $index => $section)
                                        @if($section->image)
                                            {{-- Section avec image (layout alterné) --}}
                                            <section class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 hover:shadow-2xl transition-shadow duration-300">
                                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                                                    {{-- Image --}}
                                                    <div class="relative h-64 md:h-72 lg:h-full {{ $index % 2 === 0 ? '' : 'lg:order-2' }} overflow-hidden group">
                                                        <img
                                                            src="{{ $section->image->url }}"
                                                            alt="{{ $section->titre }}"
                                                            class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
                                                        >
                                                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-black/5 to-transparent pointer-events-none"></div>
                                                        <div class="absolute top-4 left-4 inline-flex items-center px-3 py-1 rounded-full bg-black/50 text-white text-xs font-medium backdrop-blur">
                                                            Section {{ $index + 1 }}
                                                        </div>
                                                    </div>

                                                    {{-- Contenu --}}
                                                    <div class="p-6 md:p-10 flex flex-col justify-center {{ $index % 2 === 0 ? '' : 'lg:order-1' }}">
                                                        @if($section->titre)
                                                            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4 md:mb-6 flex items-center gap-3">
                                                                <span class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white text-lg md:text-xl flex items-center justify-center shadow-md">
                                                                    {{ $index + 1 }}
                                                                </span>
                                                                <span>{{ $section->titre }}</span>
                                                            </h2>
                                                        @endif

                                                        @if($section->contenu)
                                                            <div class="prose prose-gray max-w-none text-justify leading-relaxed">
                                                                {!! nl2br(e($section->contenu)) !!}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </section>
                                        @else
                                            {{-- Section sans image --}}
                                            <section class="bg-white rounded-2xl shadow-xl p-6 md:p-10 border border-gray-100 hover:shadow-2xl transition-shadow duration-300">
                                                @if($section->titre)
                                                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4 md:mb-6 flex items-center gap-3">
                                                        <span class="flex items-center justify-center w-9 h-9 md:w-10 md:h-10 bg-gradient-to-br from-blue-600 to-indigo-600 text-white text-lg md:text-xl font-bold rounded-xl shadow-md">
                                                            {{ $index + 1 }}
                                                        </span>
                                                        {{ $section->titre }}
                                                    </h2>
                                                @endif

                                                @if($section->contenu)
                                                    <div class="prose prose-lg prose-gray max-w-none text-justify leading-relaxed">
                                                        {!! nl2br(e($section->contenu)) !!}
                                                    </div>
                                                @endif
                                            </section>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- SIDEBAR --}}
                        <aside class="lg:col-span-1 space-y-6">
                            {{-- 🔹 SI page = "a-propos" → carte Organigramme + PDF --}}
                            @if($page->slug === 'a-propos')
                                <div x-data="{ openOrganigramme: false }" class="relative">
                                    {{-- Carte cliquable --}}
                                    <button
                                        type="button"
                                        @click="openOrganigramme = true"
                                        class="group w-full rounded-2xl overflow-hidden shadow-xl border border-gray-100 bg-gradient-to-br from-green-600 to-blue-700 relative">
                                        {{-- Image de fond --}}
                                        <div class="absolute inset-0">
                                            <img
                                                src="{{ asset('images/organigramme-edgvm.png') }}" {{-- 🔁 image à placer dans /public/images --}}
                                                alt="Organigramme de l'École Doctorale EDGVM"
                                                class="w-full h-full object-cover opacity-20 group-hover:opacity-90
                                                    group-hover:scale-105 transition-all duration-500"
                                            >
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/30 to-transparent"></div>
                                        </div>

                                        {{-- Contenu overlay --}}
                                        <div class="relative z-10 p-6 md:p-7 flex flex-col gap-3 text-white">
                                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-black/40 backdrop-blur text-xs font-medium">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                                                Organigramme officiel EDGVM
                                            </div>

                                            <h3 class="text-lg md:text-xl font-semibold flex items-center gap-2">
                                                <svg class="w-5 h-5 md:w-6 md:h-6 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 20h12M6 4h12M6 4l2 16m8-16l-2 16"/>
                                                </svg>
                                                Organigramme de l'École Doctorale
                                            </h3>

                                            <p class="text-xs md:text-sm text-white/80 leading-relaxed">
                                                Visualisez la structure de la direction, des équipes d’accueil et des instances de l’EDGVM.
                                            </p>

                                            <div class="mt-2 flex items-center justify-between text-xs md:text-sm">
                                                <span class="inline-flex items-center gap-1 text-white/80">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M4 6h16M4 12h16M4 18h16"/>
                                                    </svg>
                                                    Cliquer pour agrandir
                                                </span>

                                                <a href="{{ asset('docs/organigramme-edgvm.pdf') }}"  {{-- 🔁 PDF à placer dans /public/docs --}}
                                                target="_blank"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/15
                                                        hover:bg-white/25 text-[11px] font-medium">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 5v14m0-14l-4 4m4-4l4 4M5 19h14"/>
                                                    </svg>
                                                    Version PDF
                                                </a>
                                            </div>
                                        </div>
                                    </button>

                                    {{-- Modal plein écran --}}
                                    <div
                                        x-show="openOrganigramme"
                                        x-transition.opacity
                                        x-cloak
                                        @keydown.window.escape="openOrganigramme = false"
                                        class="fixed inset-0 z-[999] flex items-center justify-center bg-black/70 backdrop-blur-sm"
                                    >
                                        <div class="relative max-w-5xl w-full mx-4">
                                            {{-- Header modal --}}
                                            <div class="flex items-center justify-between mb-3 text-white">
                                                <h4 class="text-sm md:text-base font-semibold">
                                                    Organigramme de l'École Doctorale EDGVM
                                                </h4>

                                                <div class="flex items-center gap-2">
                                                    {{-- Bouton PDF --}}
                                                    <a href="{{ asset('docs/organigramme-edgvm.pdf') }}"
                                                    target="_blank"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/90
                                                            text-gray-800 text-xs font-medium shadow">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M12 5v14m0-14l-4 4m4-4l4 4M5 19h14"/>
                                                        </svg>
                                                        Ouvrir en PDF
                                                    </a>

                                                    {{-- Bouton fermer --}}
                                                    <button
                                                        type="button"
                                                        @click="openOrganigramme = false"
                                                        class="inline-flex items-center justify-center
                                                            w-9 h-9 rounded-full bg-white/90 hover:bg-white shadow-md text-gray-700
                                                            focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>

                                            {{-- Contenu modal --}}
                                            <div
                                                class="bg-white rounded-2xl overflow-hidden shadow-2xl border border-white/10
                                                    max-h-[80vh] flex items-center justify-center"
                                                @click.away="openOrganigramme = false"
                                            >
                                                <img
                                                    src="{{ asset('images/organigramme-edgvm.png') }}"
                                                    alt="Organigramme de l'École Doctorale EDGVM"
                                                    class="w-full h-auto object-contain"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- 🔹 Call-to-action toujours affiché --}}
                            <div class="bg-gradient-to-br from-indigo-600 to-blue-600 rounded-2xl shadow-xl p-6 text-white">
                                <h3 class="text-sm font-semibold mb-2">
                                    Une question sur cette page ?
                                </h3>
                                <p class="text-xs text-white/80 mb-4 leading-relaxed">
                                    Pour toute précision concernant cette information, vous pouvez contacter le secrétariat de l’École Doctorale.
                                </p>
                                <a href="{{ route('contact') }}"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-semibold rounded-full bg-white text-indigo-700 hover:bg-indigo-50 transition">
                                    Contacter l’EDGVM
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </a>
                            </div>
                        </aside>

                    </div>
                </div>
            @endif
        </div>
    </section>
</div>
