<x-app-layout>
    {{-- HEADER DU DASHBOARD --}}
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="font-semibold text-lg md:text-xl text-slate-900 leading-tight">
                    Dashboard administrateur
                </h2>
                <p class="text-xs md:text-sm text-slate-500 mt-0.5">
                    Vue synthétique des indicateurs de l’EDGVM.
                </p>
            </div>

            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full
                         text-[11px] font-semibold bg-emerald-50 text-emerald-700
                         border border-emerald-100">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Administrateur</span>
            </span>
        </div>
    </x-slot>

    <div class="max-w-10xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-6">
        {{-- CARTES STATISTIQUES PRINCIPALES --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            {{-- Total utilisateurs --}}
            <div class="bg-gradient-to-br from-ed-primary to-ed-secondary rounded-2xl shadow-lg p-5 text-white
                        transform hover:-translate-y-1 hover:shadow-xl transition duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-white/80 uppercase tracking-[0.12em]">
                            Total utilisateurs
                        </p>
                        <h3 class="text-3xl font-bold mt-2">
                            {{ $stats['total_users'] ?? 0 }}
                        </h3>
                    </div>
                    <div class="bg-white/20 rounded-2xl p-3 backdrop-blur-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Doctorants --}}
            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl shadow-lg p-5 text-white
                        transform hover:-translate-y-1 hover:shadow-xl transition duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-emerald-100 uppercase tracking-[0.12em]">
                            Doctorants
                        </p>
                        <h3 class="text-3xl font-bold mt-2">
                            {{ $stats['total_doctorants'] ?? 0 }}
                        </h3>
                    </div>
                    <div class="bg-white/25 rounded-2xl p-3 backdrop-blur-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Encadrants --}}
            <div class="bg-gradient-to-br from-sky-500 to-sky-600 rounded-2xl shadow-lg p-5 text-white
                        transform hover:-translate-y-1 hover:shadow-xl transition duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-sky-100 uppercase tracking-[0.12em]">
                            Encadrants
                        </p>
                        <h3 class="text-3xl font-bold mt-2">
                            {{ $stats['total_encadrants'] ?? 0 }}
                        </h3>
                    </div>
                    <div class="bg-white/25 rounded-2xl p-3 backdrop-blur-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Abonnés newsletter --}}
            <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl shadow-lg p-5 text-white
                        transform hover:-translate-y-1 hover:shadow-xl transition duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-amber-100 uppercase tracking-[0.12em]">
                            Abonnés newsletter
                        </p>
                        <h3 class="text-3xl font-bold mt-2">
                            {{ $stats['total_subscribers'] ?? 0 }}
                        </h3>
                    </div>
                    <div class="bg-white/25 rounded-2xl p-3 backdrop-blur-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- DEUX COLONNES : ACTIONS RAPIDES / STATISTIQUES THÈSES --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Actions rapides --}}
            <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-6">
                <h3 class="text-sm font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <span class="inline-flex w-8 h-8 items-center justify-center rounded-xl bg-ed-primary/10 text-ed-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4v16m8-8H4"/>
                        </svg>
                    </span>
                    <span>Actions rapides</span>
                </h3>

                <div class="space-y-3">
                    {{-- Exemple 1 : Ajouter un doctorant --}}
                    <a href="#"
                       class="flex items-center p-4 bg-slate-50 hover:bg-slate-100 rounded-xl transition duration-150">
                        <div class="bg-ed-primary/10 rounded-xl p-3 mr-4">
                            <svg class="w-5 h-5 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900 text-sm">Ajouter un doctorant</p>
                            <p class="text-xs text-slate-500 mt-0.5">Créer rapidement un nouveau profil.</p>
                        </div>
                    </a>

                    {{-- Exemple 2 : Publier une actualité --}}
                    <a href="#"
                       class="flex items-center p-4 bg-slate-50 hover:bg-slate-100 rounded-xl transition duration-150">
                        <div class="bg-amber-100 rounded-xl p-3 mr-4">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 20H5a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v1m2 12a2 2 0 01-2-2V8m2 12a2 2 0 002-2v-6a2 2 0 00-2-2h-2M9 7h4M9 11h4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900 text-sm">Publier une actualité</p>
                            <p class="text-xs text-slate-500 mt-0.5">Mettre en avant une information clé.</p>
                        </div>
                    </a>

                    {{-- Exemple 3 : Planifier un événement --}}
                    <a href="#"
                       class="flex items-center p-4 bg-slate-50 hover:bg-slate-100 rounded-xl transition duration-150">
                        <div class="bg-emerald-100 rounded-xl p-3 mr-4">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2v-8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900 text-sm">Planifier un événement</p>
                            <p class="text-xs text-slate-500 mt-0.5">Créer séminaire, soutenance ou atelier.</p>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Statistiques Thèses --}}
            <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-6">
                <h3 class="text-sm font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <span class="inline-flex w-8 h-8 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </span>
                    <span>Statistiques thèses</span>
                </h3>

                @php
                    $thesesEnCours = $stats['theses_en_cours'] ?? 0;
                    $thesesSoutenues = $stats['theses_soutenues'] ?? 0;
                    $totalTheses = $thesesEnCours + $thesesSoutenues;
                    $ratioEnCours = $totalTheses > 0 ? ($thesesEnCours / $totalTheses) * 100 : 0;
                    $ratioSoutenues = $totalTheses > 0 ? ($thesesSoutenues / $totalTheses) * 100 : 0;
                @endphp

                <div class="space-y-4 text-sm">
                    {{-- Thèses en cours --}}
                    <div>
                        <div class="flex justify-between mb-1.5">
                            <span class="text-slate-600">Thèses en cours</span>
                            <span class="font-semibold text-slate-800">{{ $thesesEnCours }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2">
                            <div class="bg-orange-500 h-2 rounded-full"
                                 style="width: {{ $ratioEnCours }}%"></div>
                        </div>
                    </div>

                    {{-- Thèses soutenues --}}
                    <div>
                        <div class="flex justify-between mb-1.5">
                            <span class="text-slate-600">Thèses soutenues</span>
                            <span class="font-semibold text-slate-800">{{ $thesesSoutenues }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2">
                            <div class="bg-emerald-500 h-2 rounded-full"
                                 style="width: {{ $ratioSoutenues }}%"></div>
                        </div>
                    </div>

                    <div class="mt-5 p-4 bg-gradient-to-r from-indigo-50 to-sky-50 rounded-xl border border-indigo-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-slate-500">Total des thèses</p>
                                <p class="text-2xl font-bold text-slate-900 mt-1">
                                    {{ $totalTheses }}
                                </p>
                            </div>
                            <svg class="w-12 h-12 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
