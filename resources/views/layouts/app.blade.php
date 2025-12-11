<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin' }} - EDGVM Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/ggl24otsg6ii4amtaziztgfu6y1fv23npxoqemawhqdwvmnd/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>

    @livewireStyles
</head>
<body class="bg-slate-100 font-poppins antialiased">

    <div class="flex min-h-screen">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-gradient-to-b from-ed-primary via-ed-primary/95 to-ed-secondary text-white flex-shrink-0 shadow-2xl relative overflow-hidden">
            {{-- Effet décoratif en arrière-plan --}}
            <div class="pointer-events-none absolute inset-0 opacity-20">
                <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/20 blur-3xl"></div>
                <div class="absolute bottom-0 -left-10 w-48 h-48 rounded-full bg-ed-yellow/30 blur-3xl"></div>
            </div>

            <div class="relative flex flex-col h-full">
                {{-- Header sidebar --}}
                <div class="px-6 pt-6 pb-4 border-b border-white/10">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-2xl bg-white/15 border border-white/20 shadow-md text-sm font-semibold tracking-tight">
                            ED
                        </div>
                        <div class="flex-1">
                            <h1 class="text-base font-semibold leading-tight">EDGVM Admin</h1>
                            <p class="text-[11px] text-white/70">Panneau d’administration</p>
                        </div>
                    </div>

                    <div class="mt-3 inline-flex items-center gap-1 rounded-full bg-black/15 px-3 py-1 text-[10px] font-medium text-white/80">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                        <span>Environnement sécurisé</span>
                    </div>
                </div>

                {{-- NAVIGATION --}}
                <nav class="relative flex-1 overflow-y-auto pt-4 pb-5 scrollbar-thin scrollbar-thumb-white/20 scrollbar-track-transparent"
                     x-data="{
                        openCommunication: {{ 
                            request()->routeIs('admin.actualites.*') 
                            || request()->routeIs('admin.newsletter.*')
                            || request()->routeIs('admin.categories.*')
                            || request()->routeIs('admin.tags.*')
                            || request()->routeIs('admin.sliders.*')
                            || request()->routeIs('admin.slides.*')
                            || request()->routeIs('admin.media.*')
                            || request()->routeIs('admin.message-directions.*')
                            || request()->routeIs('admin.partenaires.*')
                            || request()->routeIs('admin.evenements.*')
                            ? 'true' : 'false' 
                        }}
                     }">

                    {{-- DASHBOARD --}}
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-6 py-3 text-sm transition-all duration-150
                              hover:bg-white/10 hover:pl-7
                              {{ request()->routeIs('admin.dashboard') ? 'bg-white/15 border-r-4 border-ed-yellow font-semibold shadow-sm' : 'text-white/80' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    {{-- LABEL : Communication --}}
                    <div class="mt-4 px-6">
                        <p class="text-[10px] uppercase tracking-[0.18em] text-white/50 font-semibold">
                            Communication & contenus
                        </p>
                    </div>

                    {{-- DROPDOWN : COMMUNICATION & CONTENUS --}}
                    <div class="mt-1">
                        <button type="button"
                                @click="openCommunication = !openCommunication"
                                class="w-full flex items-center justify-between px-6 py-3 text-sm
                                       hover:bg-white/10 transition-all duration-150 text-left
                                       {{ (request()->routeIs('admin.actualites.*') 
                                            || request()->routeIs('admin.newsletter.*')
                                            || request()->routeIs('admin.categories.*')
                                            || request()->routeIs('admin.tags.*')
                                            || request()->routeIs('admin.sliders.*')
                                            || request()->routeIs('admin.slides.*')
                                            || request()->routeIs('admin.media.*')
                                            || request()->routeIs('admin.message-directions.*')
                                            || request()->routeIs('admin.partenaires.*')
                                            || request()->routeIs('admin.evenements.*'))
                                            ? 'bg-white/15 border-r-4 border-ed-yellow font-semibold shadow-sm' : 'text-white/80' }}">
                            <span class="flex items-center gap-3">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 10h8M8 14h5M5 20l2-4h10a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2h1" />
                                </svg>
                                <span>Contenus & communication</span>
                            </span>

                            <svg class="w-4 h-4 flex-shrink-0 transform transition-transform duration-200"
                                 :class="openCommunication ? 'rotate-180' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="openCommunication"
                             x-collapse
                             x-cloak
                             class="mt-2 space-y-1 text-sm pl-10 pr-4">

                            {{-- Mot de la Directrice --}}
                            <a href="{{ route('admin.message-directions.index') }}"
                               class="flex items-center gap-2 px-3 py-2 rounded-md transition-all duration-150
                                      hover:bg-white/10 hover:pl-4
                                      {{ request()->routeIs('admin.message-directions.*') ? 'bg-white/15 text-ed-yellow font-semibold shadow-sm' : 'text-white/80' }}">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 12a4 4 0 100-8 4 4 0 000 8z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M6 20v-1c0-2.667 2.667-4 6-4s6 1.333 6 4v1" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M18 8h2m0 0v3m0-3l-2 2" />
                                </svg>
                                <span>Mot de la Directrice</span>
                            </a>

                            {{-- Actualités --}}
                            <a href="{{ route('admin.actualites.index') }}"
                               class="flex items-center gap-2 px-3 py-2 rounded-md transition-all duration-150
                                      hover:bg-white/10 hover:pl-4
                                      {{ request()->routeIs('admin.actualites.*') ? 'bg-white/15 text-ed-yellow font-semibold shadow-sm' : 'text-white/80' }}">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                                <span>Actualités</span>
                            </a>

                            {{-- Événements --}}
                            <a href="{{ route('admin.evenements.index') }}"
                               class="flex items-center gap-2 px-3 py-2 rounded-md transition-all duration-150
                                      hover:bg-white/10 hover:pl-4
                                      {{ request()->routeIs('admin.evenements.*') ? 'bg-white/15 text-ed-yellow font-semibold shadow-sm' : 'text-white/80' }}">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Événements</span>
                            </a>

                            {{-- Newsletter --}}
                            <a href="{{ route('admin.newsletter.subscribers') }}"
                               class="flex items-center gap-2 px-3 py-2 rounded-md transition-all duration-150
                                      hover:bg-white/10 hover:pl-4
                                      {{ request()->routeIs('admin.newsletter.*') ? 'bg-white/15 text-ed-yellow font-semibold shadow-sm' : 'text-white/80' }}">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span>Newsletter</span>
                            </a>

                            {{-- Catégories --}}
                            <a href="{{ route('admin.categories.index') }}"
                               class="flex items-center gap-2 px-3 py-2 rounded-md transition-all duration-150
                                      hover:bg-white/10 hover:pl-4
                                      {{ request()->routeIs('admin.categories.*') ? 'bg-white/15 text-ed-yellow font-semibold shadow-sm' : 'text-white/80' }}">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <span>Catégories</span>
                            </a>

                            {{-- Tags --}}
                            <a href="{{ route('admin.tags.index') }}"
                               class="flex items-center gap-2 px-3 py-2 rounded-md transition-all duration-150
                                      hover:bg-white/10 hover:pl-4
                                      {{ request()->routeIs('admin.tags.*') ? 'bg-white/15 text-ed-yellow font-semibold shadow-sm' : 'text-white/80' }}">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                </svg>
                                <span>Tags</span>
                            </a>

                            {{-- Sliders & Slides --}}
                            <a href="{{ route('admin.sliders.index') }}"
                               class="flex items-center gap-2 px-3 py-2 rounded-md transition-all duration-150
                                      hover:bg-white/10 hover:pl-4
                                      {{ request()->routeIs('admin.sliders.*') || request()->routeIs('admin.slides.*') ? 'bg-white/15 text-ed-yellow font-semibold shadow-sm' : 'text-white/80' }}">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Sliders & Slides</span>
                            </a>

                            {{-- Médiathèque --}}
                            <a href="{{ route('admin.media.index') }}"
                               class="flex items-center gap-2 px-3 py-2 rounded-md transition-all duration-150
                                      hover:bg-white/10 hover:pl-4
                                      {{ request()->routeIs('admin.media.*') ? 'bg-white/15 text-ed-yellow font-semibold shadow-sm' : 'text-white/80' }}">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                                </svg>
                                <span>Médiathèque</span>
                            </a>

                            {{-- Partenaires --}}
                            <a href="{{ route('admin.partenaires.index') }}"
                               class="flex items-center gap-2 px-3 py-2 rounded-md transition-all duration-150
                                      hover:bg-white/10 hover:pl-4
                                      {{ request()->routeIs('admin.partenaires.*') ? 'bg-white/15 text-ed-yellow font-semibold shadow-sm' : 'text-white/80' }}">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M7 8a3 3 0 116 0 3 3 0 01-6 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 18a4 4 0 014-4h2a4 4 0 014 4v1H3v-1z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 9a3 3 0 110 6" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 21h-4v-1a4 4 0 014-4h0" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M10 11l3 1.5M13 9.5L10 8" />
                                </svg>
                                <span>Partenaires</span>
                            </a>
                        </div>
                    </div>

                    {{-- LABEL : Structures académiques --}}
                    <div class="mt-5 px-6">
                        <p class="text-[10px] uppercase tracking-[0.18em] text-white/50 font-semibold">
                            Structures académiques
                        </p>
                    </div>

                    {{-- Pages --}}
                    <a href="{{ route('admin.pages.index') }}" 
                       class="mt-1 flex items-center gap-3 px-6 py-3 text-sm transition-all duration-150
                              hover:bg-white/10 hover:pl-7
                              {{ request()->routeIs('admin.pages*') ? 'bg-white/15 border-r-4 border-ed-yellow font-semibold shadow-sm' : 'text-white/80' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <span>Pages</span>
                    </a>

                    {{-- EAD --}}
                    <a href="{{ route('admin.ead.index') }}" 
                       class="flex items-center gap-3 px-6 py-3 text-sm transition-all duration-150
                              hover:bg-white/10 hover:pl-7
                              {{ request()->routeIs('admin.ead*') ? 'bg-white/15 border-r-4 border-ed-yellow font-semibold shadow-sm' : 'text-white/80' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>EAD</span>
                    </a>

                    {{-- Spécialités --}}
                    <a href="{{ route('admin.specialites.index') }}" 
                       class="flex items-center gap-3 px-6 py-3 text-sm transition-all duration-150
                              hover:bg-white/10 hover:pl-7
                              {{ request()->routeIs('admin.specialites*') ? 'bg-white/15 border-r-4 border-ed-yellow font-semibold shadow-sm' : 'text-white/80' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span>Spécialités</span>
                    </a>

                    {{-- Doctorants --}}
                    <a href="{{ route('admin.doctorants.index') }}" 
                       class="flex items-center gap-3 px-6 py-3 text-sm transition-all duration-150
                              hover:bg-white/10 hover:pl-7
                              {{ request()->routeIs('admin.doctorants*') ? 'bg-white/15 border-r-4 border-ed-yellow font-semibold shadow-sm' : 'text-white/80' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>Doctorants</span>
                    </a>

                    {{-- Encadrants --}}
                    <a href="{{ route('admin.encadrants.index') }}" 
                       class="flex items-center gap-3 px-6 py-3 text-sm transition-all duration-150
                              hover:bg-white/10 hover:pl-7
                              {{ request()->routeIs('admin.encadrants*') ? 'bg-white/15 border-r-4 border-ed-yellow font-semibold shadow-sm' : 'text-white/80' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span>Encadrants</span>
                    </a>

                    {{-- Thèses --}}
                    <a href="{{ route('admin.theses.index') }}" 
                       class="flex items-center gap-3 px-6 py-3 text-sm transition-all duration-150
                              hover:bg-white/10 hover:pl-7
                              {{ request()->routeIs('admin.theses*') ? 'bg-white/15 border-r-4 border-ed-yellow font-semibold shadow-sm' : 'text-white/80' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span>Thèses</span>
                    </a>

                    {{-- Paramètres --}}
                    <a href="{{ route('admin.settings') }}" 
                    class="flex items-center gap-3 px-6 py-3 text-sm transition-all duration-150
                            hover:bg-white/10 hover:pl-7
                            {{ request()->routeIs('admin.settings*') 
                                    ? 'bg-white/15 border-r-4 border-ed-yellow font-semibold shadow-sm' 
                                    : 'text-white/80' }}">
                        <svg class="w-5 h-5 flex-shrink-0" 
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none" 
                            viewBox="0 0 24 24" 
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 
                                    3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 
                                    0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 
                                    1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 
                                    0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 
                                    2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Paramètres</span>
                    </a>

                    {{-- Séparateur --}}
                    <div class="border-t border-white/10 my-4 mx-6"></div>

                    {{-- Voir le site --}}
                    <a href="{{ route('home') }}" target="_blank"
                       class="flex items-center gap-3 px-6 py-3 text-sm text-white/80 hover:text-white
                              hover:bg-white/10 hover:pl-7 transition-all duration-150">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        <span>Voir le site</span>
                    </a>

                    {{-- Déconnexion --}}
                    <form method="POST" action="{{ route('logout') }}" class="mt-1 px-6">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-3 w-full text-left mt-1
                                       bg-red-600/95 text-white text-sm
                                       hover:bg-red-700 active:bg-red-800
                                       focus:outline-none focus:ring-2 focus:ring-red-400/70
                                       rounded-lg px-4 py-2.5 font-semibold transition-all duration-150 shadow-md">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Déconnexion</span>
                        </button>
                    </form>
                </nav>
            </div>
        </aside>

        <!-- MAIN -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- TOP BAR -->
            <header class="bg-white/90 backdrop-blur border-b border-slate-200 shadow-sm">
                <div class="px-6 py-3 md:py-4">
                    @if(isset($header))
                        {{ $header }}
                    @else
                        <div class="flex items-center justify-between gap-4">
                            <div class="min-w-0">
                                <h2 class="text-lg md:text-xl font-semibold text-slate-900 truncate">
                                    {{ $title ?? 'Dashboard' }}
                                </h2>
                                @isset($subtitle)
                                    <p class="text-xs md:text-sm text-slate-500 mt-0.5">
                                        {{ $subtitle }}
                                    </p>
                                @endisset
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="hidden md:flex flex-col items-end">
                                    <span class="text-sm font-medium text-slate-800">
                                        {{ auth()->user()->name }}
                                    </span>
                                    <span class="text-[11px] text-slate-500">
                                        Espace administration EDGVM
                                    </span>
                                </div>
                                <div class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-gradient-to-br from-ed-primary to-ed-secondary
                                            flex items-center justify-center text-white font-semibold shadow-md">
                                    {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </header>

            <!-- PAGE CONTENT -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gradient-to-br from-slate-100 via-slate-50 to-slate-100 p-4 md:p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
