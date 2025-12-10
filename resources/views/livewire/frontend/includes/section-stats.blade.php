<!-- Stats Section - version épurée et pro -->
<section class="relative py-16 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- En-tête de section --}}
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-10">
            <div>
                <p class="text-xs font-semibold tracking-[0.18em] text-ed-primary uppercase">
                    Chiffres clés
                </p>
                <h2 class="mt-1 text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">
                    Une communauté doctorale dynamique
                </h2>
                <p class="mt-2 text-sm sm:text-base text-gray-600 max-w-xl">
                    L’École Doctorale EDGVM rassemble doctorants, encadrants et équipes d’accueil 
                    engagés dans la recherche et l’innovation.
                </p>
            </div>

            <div class="text-sm text-gray-500">
                <span class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-3 py-1">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    <span>Données indicatives mises à jour régulièrement</span>
                </span>
            </div>
        </div>

        {{-- Cartes de stats --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 md:gap-6">

            {{-- Card 1 --}}
            <div class="group bg-white rounded-2xl p-6 md:p-7 shadow-sm ring-1 ring-gray-100/80
                        hover:shadow-md hover:ring-ed-primary/25 hover:-translate-y-1
                        transition-all duration-300 flex flex-col">
                <div class="w-12 h-12 md:w-14 md:h-14 
                            bg-gradient-to-br from-ed-primary to-ed-secondary 
                            rounded-xl flex items-center justify-center mb-4 md:mb-5 
                            group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 md:w-7 md:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>

                <div class="flex items-baseline gap-2">
                    <h3 class="text-2xl md:text-2xl font-bold text-gray-900 tracking-tight">
                        {{ $stats['doctorants'] }}+
                    </h3>
                    <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                        Actifs
                    </span>
                </div>
                <p class="mt-1 text-sm text-gray-600">
                    Doctorants inscrits et suivis au sein de l’École Doctorale.
                </p>

                <div class="mt-5 h-0.5 w-12 bg-gradient-to-r from-ed-primary to-ed-secondary rounded-full"></div>
            </div>

            {{-- Card 2 --}}
            <div class="group bg-white rounded-2xl p-6 md:p-7 shadow-sm ring-1 ring-gray-100/80
                        hover:shadow-md hover:ring-ed-yellow/20 hover:-translate-y-1
                        transition-all duration-300 flex flex-col">
                <div class="w-12 h-12 md:w-14 md:h-14 
                            bg-gradient-to-br from-ed-yellow to-amber-500 
                            rounded-xl flex items-center justify-center mb-4 md:mb-5 
                            group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 md:w-7 md:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>

                <div class="flex items-baseline gap-2">
                    <h3 class="text-2xl md:text-2xl font-bold text-gray-900 tracking-tight">
                        {{ $stats['encadrants'] }}+
                    </h3>
                    <span class="text-xs font-medium text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full">
                        Encadrement
                    </span>
                </div>
                <p class="mt-1 text-sm text-gray-600">
                    Encadrants et co-encadrants mobilisés dans les projets de recherche.
                </p>

                <div class="mt-5 h-0.5 w-12 bg-gradient-to-r from-ed-yellow to-amber-500 rounded-full"></div>
            </div>

            {{-- Card 3 --}}
            <div class="group bg-white rounded-2xl p-6 md:p-7 shadow-sm ring-1 ring-gray-100/80
                        hover:shadow-md hover:ring-emerald-500/25 hover:-translate-y-1
                        transition-all duration-300 flex flex-col">
                <div class="w-12 h-12 md:w-14 md:h-14 
                            bg-gradient-to-br from-green-500 to-emerald-600 
                            rounded-xl flex items-center justify-center mb-4 md:mb-5 
                            group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 md:w-7 md:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>

                <div class="flex items-baseline gap-2">
                    <h3 class="text-2xl md:text-2xl font-bold text-gray-900 tracking-tight">
                        {{ $stats['theses_soutenues'] }}+
                    </h3>
                    <span class="text-xs font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                        Soutenances
                    </span>
                </div>
                <p class="mt-1 text-sm text-gray-600">
                    Thèses soutenues au sein de l’École Doctorale ces dernières années.
                </p>

                <div class="mt-5 h-0.5 w-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full"></div>
            </div>

            {{-- Card 4 --}}
            <div class="group bg-white rounded-2xl p-6 md:p-7 shadow-sm ring-1 ring-gray-100/80
                        hover:shadow-md hover:ring-indigo-500/25 hover:-translate-y-1
                        transition-all duration-300 flex flex-col">
                <div class="w-12 h-12 md:w-14 md:h-14 
                            bg-gradient-to-br from-ed-primary to-indigo-600 
                            rounded-xl flex items-center justify-center mb-4 md:mb-5 
                            group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 md:w-7 md:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>

                <div class="flex items-baseline gap-2">
                    <h3 class="text-2xl md:text-2xl font-bold text-gray-900 tracking-tight">
                        {{ $stats['equipes'] }}
                    </h3>
                    <span class="text-xs font-medium text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-full">
                        Structures
                    </span>
                </div>
                <p class="mt-1 text-sm text-gray-600">
                    Équipes d’accueil et laboratoires partenaires pour les travaux de thèse.
                </p>

                <div class="mt-5 h-0.5 w-12 bg-gradient-to-r from-ed-primary to-indigo-600 rounded-full"></div>
            </div>

        </div>
    </div>
</section>
