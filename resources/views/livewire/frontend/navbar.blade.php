<div>
<header>
    <nav
        x-data="{ open: false, openSubmenu: null, scrolled: false }"
        @scroll.window="scrolled = (window.pageYOffset > 20)"
        :class="scrolled ? 'bg-white shadow-lg' : 'bg-white/95 backdrop-blur-sm'"
        class="fixed w-full top-0 z-50 transition-all duration-300"
        aria-label="Navigation principale"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">

                {{-- Logo + identité (pas de H1 ici) --}}
                <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="Aller à l’accueil">
                    <img
                        src="{{ $appSettings->logo_path ? asset('storage/'.$appSettings->logo_path) : asset('images/logo-edgvm.jpg') }}"
                        alt="Logo {{ $appSettings->site_name ?? 'EDGVM' }}"
                        class="h-14 w-auto"
                        loading="eager"
                        decoding="async"
                    >
                    <div class="leading-tight">
                        <span class="block text-xl font-bold text-ed-primary">EDGVM</span>
                        <span class="block text-xs text-ed-gray">École Doctorale Génie du Vivant et Modélisation</span>
                    </div>
                </a>

                {{-- Desktop --}}
                <div class="hidden lg:flex items-center">
                    <ul class="flex items-center gap-1" role="list">
                        <li>
                            <a
                                href="{{ route('home') }}"
                                class="px-4 py-2 text-ed-gray hover:text-ed-primary font-medium transition rounded-lg hover:bg-teal-50"
                                @if(request()->routeIs('home')) aria-current="page" @endif
                            >Accueil</a>
                        </li>

                        {{-- À propos dropdown --}}
                        <li class="relative" x-data="{ openDd: false }" @mouseenter="openDd = true" @mouseleave="openDd = false">
                            <button
                                type="button"
                                class="px-4 py-2 text-ed-gray hover:text-ed-primary font-medium transition rounded-lg hover:bg-teal-50 flex items-center gap-1"
                                aria-haspopup="true"
                                :aria-expanded="openDd.toString()"
                            >
                                À propos
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div
                                x-cloak
                                x-show="openDd"
                                x-transition
                                class="absolute left-0 mt-1 w-64 bg-white rounded-lg shadow-xl py-2 z-50"
                                role="menu"
                            >
                                @forelse($menuAProposItems as $item)
                                    <a href="{{ $item->resolved_url }}" class="block px-4 py-2 text-ed-gray hover:bg-teal-50 hover:text-ed-primary transition" role="menuitem">
                                        {{ $item->label }}
                                    </a>
                                @empty
                                    <div class="px-4 py-2 text-xs text-gray-400">Aucune page configurée pour ce menu.</div>
                                @endforelse
                            </div>
                        </li>

                        {{-- Recherches dropdown --}}
                        <li class="relative" x-data="{ openDd: false }" @mouseenter="openDd = true" @mouseleave="openDd = false">
                            <button type="button" class="px-4 py-2 text-ed-gray hover:text-ed-primary font-medium transition rounded-lg hover:bg-teal-50 flex items-center gap-1"
                                    aria-haspopup="true" :aria-expanded="openDd.toString()">
                                Recherches
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-cloak x-show="openDd" x-transition class="absolute left-0 mt-1 w-64 bg-white rounded-lg shadow-xl py-2 z-50" role="menu">
                                <a href="{{ route('doctorants.index') }}" class="block px-4 py-2 text-ed-gray hover:bg-teal-50 hover:text-ed-primary transition" role="menuitem">Nos doctorants</a>
                                <a href="{{ route('ead.index') }}" class="block px-4 py-2 text-ed-gray hover:bg-teal-50 hover:text-ed-primary transition" role="menuitem">Équipes d'accueil</a>
                                <a href="{{ route('programmes.index') }}" class="block px-4 py-2 text-ed-gray hover:bg-teal-50 hover:text-ed-primary transition" role="menuitem">Programmes de recherche</a>
                                <a href="{{ route('theses.index') }}" class="block px-4 py-2 text-ed-gray hover:bg-teal-50 hover:text-ed-primary transition" role="menuitem">Banque des thèses</a>
                            </div>
                        </li>

                        <li>
                            <a href="{{ route('actualites.index') }}"
                               class="px-4 py-2 text-ed-gray hover:text-ed-primary font-medium transition rounded-lg hover:bg-teal-50"
                               @if(request()->routeIs('actualites.*')) aria-current="page" @endif
                            >Actualités</a>
                        </li>

                        <li>
                            <a href="{{ route('contact') }}"
                               class="px-4 py-2 text-ed-gray hover:text-ed-primary font-medium transition rounded-lg hover:bg-teal-50"
                               @if(request()->routeIs('contact')) aria-current="page" @endif
                            >Contact</a>
                        </li>
                    </ul>
                </div>

                {{-- Auth (desktop) --}}
                <div class="hidden lg:flex items-center">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-6 py-2.5 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary transition font-medium shadow-md">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2.5 bg-ed-yellow text-white rounded-lg hover:bg-ed-yellow-dark transition font-medium shadow-md">
                            Membres
                        </a>
                    @endauth
                </div>

                {{-- Mobile toggle --}}
                <button
                    type="button"
                    @click="open = !open"
                    class="lg:hidden text-ed-gray hover:text-ed-primary transition"
                    aria-label="Ouvrir le menu"
                    :aria-expanded="open.toString()"
                    aria-controls="mobile-menu"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div
            id="mobile-menu"
            x-cloak
            x-show="open"
            @keydown.escape.window="open = false; openSubmenu = null"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform -translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-2"
            class="lg:hidden bg-white border-t shadow-lg">
            <div class="px-4 pt-2 pb-4 space-y-1 max-h-[calc(100vh-5rem)] overflow-y-auto">
                {{-- Accueil --}}
                <a
                    href="{{ route('home') }}"
                    class="block px-4 py-3 text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg font-medium transition"
                    @if(request()->routeIs('home')) aria-current="page" @endif
                    @click="open = false; openSubmenu = null"
                >
                    Accueil
                </a>

                {{-- À propos (accordéon) --}}
                <div class="space-y-1">
                    <button
                        type="button"
                        @click="openSubmenu = (openSubmenu === 'apropos' ? null : 'apropos')"
                        class="w-full flex items-center justify-between px-4 py-3 text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg font-medium transition"
                        aria-controls="submenu-apropos"
                        :aria-expanded="(openSubmenu === 'apropos').toString()">
                        <span>À propos</span>
                        <svg
                            class="w-4 h-4 transition-transform"
                            :class="openSubmenu === 'apropos' ? 'rotate-180' : ''"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div
                        id="submenu-apropos"
                        x-cloak
                        x-show="openSubmenu === 'apropos'"
                        x-collapse
                        class="ml-2 pl-2 border-l border-gray-100 space-y-1"
                        role="list">
                        @forelse($menuAProposItems as $item)
                            <a
                                href="{{ $item->resolved_url }}"
                                class="block px-4 py-2 text-sm text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg transition"
                                @click="open = false; openSubmenu = null">
                                {{ $item->label }}
                            </a>
                        @empty
                            <div class="px-4 py-2 text-xs text-gray-400">
                                Aucune page configurée pour ce menu.
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Recherches (accordéon) --}}
                <div class="space-y-1">
                    <button
                        type="button"
                        @click="openSubmenu = (openSubmenu === 'recherches' ? null : 'recherches')"
                        class="w-full flex items-center justify-between px-4 py-3 text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg font-medium transition"
                        aria-controls="submenu-recherches"
                        :aria-expanded="(openSubmenu === 'recherches').toString()">
                        <span>Recherches</span>
                        <svg
                            class="w-4 h-4 transition-transform"
                            :class="openSubmenu === 'recherches' ? 'rotate-180' : ''"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div
                        id="submenu-recherches"
                        x-cloak
                        x-show="openSubmenu === 'recherches'"
                        x-collapse
                        class="ml-2 pl-2 border-l border-gray-100 space-y-1"
                        role="list">
                        <a
                            href="{{ route('doctorants.index') }}"
                            class="block px-4 py-2 text-sm text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg transition"
                            @if(request()->routeIs('doctorants.*')) aria-current="page" @endif
                            @click="open = false; openSubmenu = null">
                            Nos doctorants
                        </a>

                        <a
                            href="{{ route('ead.index') }}"
                            class="block px-4 py-2 text-sm text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg transition"
                            @if(request()->routeIs('ead.*')) aria-current="page" @endif
                            @click="open = false; openSubmenu = null">
                            Équipes d'accueil
                        </a>

                        <a
                            href="{{ route('programmes.index') }}"
                            class="block px-4 py-2 text-sm text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg transition"
                            @if(request()->routeIs('programmes.*')) aria-current="page" @endif
                            @click="open = false; openSubmenu = null">
                            Programmes de recherche
                        </a>

                        <a
                            href="{{ route('theses.index') }}"
                            class="block px-4 py-2 text-sm text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg transition"
                            @if(request()->routeIs('theses.*')) aria-current="page" @endif
                            @click="open = false; openSubmenu = null">
                            Banque des thèses
                        </a>
                    </div>
                </div>

                {{-- Actualités --}}
                <a
                    href="{{ route('actualites.index') }}"
                    class="block px-4 py-3 text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg font-medium transition"
                    @if(request()->routeIs('actualites.*')) aria-current="page" @endif
                    @click="open = false; openSubmenu = null">
                    Actualités
                </a>

                {{-- Contact --}}
                <a
                    href="{{ route('contact') }}"
                    class="block px-4 py-3 text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg font-medium transition"
                    @if(request()->routeIs('contact')) aria-current="page" @endif
                    @click="open = false; openSubmenu = null">
                    Contact
                </a>

                {{-- Auth --}}
                <div class="pt-2">
                    @auth
                        <a
                            href="{{ route('dashboard') }}"
                            class="block px-4 py-3 bg-ed-primary text-white rounded-lg text-center font-medium shadow-md hover:bg-ed-secondary transition"
                            @click="open = false; openSubmenu = null">
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="block px-4 py-3 bg-ed-yellow text-white rounded-lg text-center font-medium shadow-md hover:bg-ed-yellow-dark transition"
                            @click="open = false; openSubmenu = null">
                            Membres
                        </a>
                    @endauth
                </div>
            </div>
        </div>

    </nav>
</header>
</div>
