<div>
<nav x-data="{ open: false, openSubmenu: null, scrolled: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 20)" 
     :class="scrolled ? 'bg-white shadow-lg' : 'bg-white/95 backdrop-blur-sm'"
     class="fixed w-full top-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center space-x-3">
                <img src="/images/logo-edgvm.jpg" alt="EDGVM Logo" class="h-14 w-auto">
                <div>
                    <h1 class="text-xl font-bold text-ed-primary leading-tight">EDGVM</h1>
                    <p class="text-xs text-ed-gray leading-tight">Génie du Vivant et Modélisation</p>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center space-x-1">
                <a href="{{ route('home') }}" class="px-4 py-2 text-ed-gray hover:text-ed-primary font-medium transition rounded-lg hover:bg-teal-50">
                    Accueil
                </a>
                
                <!-- À propos Dropdown -->
                <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                    <button class="px-4 py-2 text-ed-gray hover:text-ed-primary font-medium transition rounded-lg hover:bg-teal-50 flex items-center gap-1">
                        À propos
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" 
                         x-transition
                         class="absolute left-0 mt-1 w-64 bg-white rounded-lg shadow-xl py-2 z-50">
                        <a href="{{ route('about') }}" class="block px-4 py-2 text-ed-gray hover:bg-teal-50 hover:text-ed-primary transition">
                            Présentation
                        </a>
                        <a href="{{ route('organisation') }}" class="block px-4 py-2 text-ed-gray hover:bg-teal-50 hover:text-ed-primary transition">
                            Organisation
                        </a>
                        <a href="{{ route('conseil') }}" class="block px-4 py-2 text-ed-gray hover:bg-teal-50 hover:text-ed-primary transition">
                            Conseil de l'École Doctorale
                        </a>
                        <a href="{{ route('comite-suivi') }}" class="block px-4 py-2 text-ed-gray hover:bg-teal-50 hover:text-ed-primary transition">
                            Comité de suivi de thèse
                        </a>
                        <a href="{{ route('reglement') }}" class="block px-4 py-2 text-ed-gray hover:bg-teal-50 hover:text-ed-primary transition">
                            Règlement intérieur
                        </a>
                    </div>
                </div>

                <a href="{{ route('programmes') }}" class="px-4 py-2 text-ed-gray hover:text-ed-primary font-medium transition rounded-lg hover:bg-teal-50">
                    Recherches
                </a>
                
                <!-- Doctorants Dropdown -->
                <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                    <button class="px-4 py-2 text-ed-gray hover:text-ed-primary font-medium transition rounded-lg hover:bg-teal-50 flex items-center gap-1">
                        Doctorants
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" 
                         x-transition
                         class="absolute left-0 mt-1 w-64 bg-white rounded-lg shadow-xl py-2 z-50">
                        <a href="{{ route('doctorants') }}" class="block px-4 py-2 text-ed-gray hover:bg-teal-50 hover:text-ed-primary transition">
                            Nos doctorants
                        </a>
                        <a href="{{ route('fiche-numerique') }}" class="block px-4 py-2 text-ed-gray hover:bg-teal-50 hover:text-ed-primary transition">
                            Fiche numérique des doctorants
                        </a>
                        <a href="{{ route('theses-soutenues') }}" class="block px-4 py-2 text-ed-gray hover:bg-teal-50 hover:text-ed-primary transition">
                            Thèses soutenues
                        </a>
                    </div>
                </div>
                
                <a href="{{ route('actualites') }}" class="px-4 py-2 text-ed-gray hover:text-ed-primary font-medium transition rounded-lg hover:bg-teal-50">
                    Actualités
                </a>
                <a href="{{ route('contact') }}" class="px-4 py-2 text-ed-gray hover:text-ed-primary font-medium transition rounded-lg hover:bg-teal-50">
                    Contact
                </a>
            </div>

            <!-- Auth Button -->
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

            <!-- Mobile menu button -->
            <button @click="open = !open" class="lg:hidden text-ed-gray hover:text-ed-primary transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="lg:hidden bg-white border-t shadow-lg">
        <div class="px-4 pt-2 pb-4 space-y-1 max-h-[calc(100vh-5rem)] overflow-y-auto">
            <a href="{{ route('home') }}" class="block px-4 py-3 text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg font-medium transition">
                Accueil
            </a>
            
            <!-- À propos Mobile -->
            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg font-medium transition">
                    <span>À propos</span>
                    <svg class="w-4 h-4 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-collapse class="ml-4 space-y-1">
                    <a href="{{ route('about') }}" class="block px-4 py-2 text-sm text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg transition">
                        Présentation
                    </a>
                    <a href="{{ route('organisation') }}" class="block px-4 py-2 text-sm text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg transition">
                        Organisation
                    </a>
                    <a href="{{ route('conseil') }}" class="block px-4 py-2 text-sm text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg transition">
                        Conseil de l'École Doctorale
                    </a>
                    <a href="{{ route('comite-suivi') }}" class="block px-4 py-2 text-sm text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg transition">
                        Comité de suivi de thèse
                    </a>
                    <a href="{{ route('reglement') }}" class="block px-4 py-2 text-sm text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg transition">
                        Règlement intérieur
                    </a>
                </div>
            </div>

            <a href="{{ route('programmes') }}" class="block px-4 py-3 text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg font-medium transition">
                Recherches
            </a>
            
            <!-- Doctorants Mobile -->
            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg font-medium transition">
                    <span>Doctorants</span>
                    <svg class="w-4 h-4 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-collapse class="ml-4 space-y-1">
                    <a href="{{ route('doctorants') }}" class="block px-4 py-2 text-sm text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg transition">
                        Nos doctorants
                    </a>
                    <a href="{{ route('fiche-numerique') }}" class="block px-4 py-2 text-sm text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg transition">
                        Fiche numérique des doctorants
                    </a>
                    <a href="{{ route('theses-soutenues') }}" class="block px-4 py-2 text-sm text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg transition">
                        Thèses soutenues
                    </a>
                </div>
            </div>
            
            <a href="{{ route('actualites') }}" class="block px-4 py-3 text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg font-medium transition">
                Actualités
            </a>
            <a href="{{ route('contact') }}" class="block px-4 py-3 text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg font-medium transition">
                Contact
            </a>
            
            @auth
                <a href="{{ route('dashboard') }}" class="block px-4 py-3 bg-ed-primary text-white rounded-lg text-center font-medium shadow-md">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="block px-4 py-3 bg-ed-yellow text-white rounded-lg text-center font-medium shadow-md">
                    Membres
                </a>
            @endauth
        </div>
    </div>
</nav>
</div>
