<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const appSettings = computed(() => page.props.appSettings);
const menuAProposItems = computed(() => page.props.menuAProposItems || []);
const auth = computed(() => page.props.auth);
const currentRouteName = computed(() => page.props.currentRouteName);

const mobileMenuOpen = ref(false);
const openSubmenu = ref(null);
const scrolled = ref(false);

// Desktop dropdown states
const aproposOpen = ref(false);
const recherchesOpen = ref(false);

const handleScroll = () => {
    scrolled.value = window.pageYOffset > 20;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
    handleScroll();
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

const closeMobileMenu = () => {
    mobileMenuOpen.value = false;
    openSubmenu.value = null;
};

const toggleSubmenu = (name) => {
    openSubmenu.value = openSubmenu.value === name ? null : name;
};

const isRoute = (routeName) => {
    return currentRouteName.value?.startsWith(routeName);
};

const logoUrl = computed(() => {
    return appSettings.value?.logo_url || '/images/logo-edgvm.jpg';
});
</script>

<template>
    <header>
        <nav
            :class="scrolled ? 'bg-white shadow-lg' : 'bg-white/95 backdrop-blur-sm'"
            class="fixed w-full top-0 z-50 transition-all duration-300"
            aria-label="Navigation principale"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo -->
                    <Link :href="route('home')" class="flex items-center gap-3" aria-label="Aller a l'accueil">
                        <img
                            :src="logoUrl"
                            :alt="'Logo ' + (appSettings?.site_name || 'EDGVM')"
                            class="h-14 w-auto"
                            loading="eager"
                            decoding="async"
                        >
                        <div class="leading-tight">
                            <span class="block text-xl font-bold text-ed-primary">EDGVM</span>
                            <span class="block text-xs text-ed-gray">Ecole Doctorale Genie du Vivant et Modelisation</span>
                        </div>
                    </Link>

                    <!-- Desktop Navigation -->
                    <div class="hidden lg:flex items-center">
                        <ul class="flex items-center gap-1" role="list">
                            <li>
                                <Link
                                    :href="route('home')"
                                    class="px-4 py-2 text-ed-gray hover:text-ed-primary font-medium transition rounded-lg hover:bg-teal-50"
                                    :aria-current="isRoute('home') ? 'page' : undefined"
                                >
                                    Accueil
                                </Link>
                            </li>

                            <!-- A propos dropdown -->
                            <li
                                class="relative"
                                @mouseenter="aproposOpen = true"
                                @mouseleave="aproposOpen = false"
                            >
                                <button
                                    type="button"
                                    class="px-4 py-2 text-ed-gray hover:text-ed-primary font-medium transition rounded-lg hover:bg-teal-50 flex items-center gap-1"
                                    aria-haspopup="true"
                                    :aria-expanded="aproposOpen.toString()"
                                >
                                    A propos
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <Transition
                                    enter-active-class="transition ease-out duration-100"
                                    enter-from-class="transform opacity-0 scale-95"
                                    enter-to-class="transform opacity-100 scale-100"
                                    leave-active-class="transition ease-in duration-75"
                                    leave-from-class="transform opacity-100 scale-100"
                                    leave-to-class="transform opacity-0 scale-95"
                                >
                                    <div
                                        v-show="aproposOpen"
                                        class="absolute left-0 mt-1 w-64 bg-white rounded-lg shadow-xl py-2 z-50"
                                        role="menu"
                                    >
                                        <template v-if="menuAProposItems.length > 0">
                                            <Link
                                                v-for="item in menuAProposItems"
                                                :key="item.id"
                                                :href="item.url"
                                                class="block px-4 py-2 text-ed-gray hover:bg-teal-50 hover:text-ed-primary transition"
                                                role="menuitem"
                                            >
                                                {{ item.label }}
                                            </Link>
                                        </template>
                                        <div v-else class="px-4 py-2 text-xs text-gray-400">
                                            Aucune page configuree pour ce menu.
                                        </div>
                                    </div>
                                </Transition>
                            </li>

                            <!-- Recherches dropdown -->
                            <li
                                class="relative"
                                @mouseenter="recherchesOpen = true"
                                @mouseleave="recherchesOpen = false"
                            >
                                <button
                                    type="button"
                                    class="px-4 py-2 text-ed-gray hover:text-ed-primary font-medium transition rounded-lg hover:bg-teal-50 flex items-center gap-1"
                                    aria-haspopup="true"
                                    :aria-expanded="recherchesOpen.toString()"
                                >
                                    Recherches
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <Transition
                                    enter-active-class="transition ease-out duration-100"
                                    enter-from-class="transform opacity-0 scale-95"
                                    enter-to-class="transform opacity-100 scale-100"
                                    leave-active-class="transition ease-in duration-75"
                                    leave-from-class="transform opacity-100 scale-100"
                                    leave-to-class="transform opacity-0 scale-95"
                                >
                                    <div
                                        v-show="recherchesOpen"
                                        class="absolute left-0 mt-1 w-64 bg-white rounded-lg shadow-xl py-2 z-50"
                                        role="menu"
                                    >
                                        <Link :href="route('doctorants.index')" class="block px-4 py-2 text-ed-gray hover:bg-teal-50 hover:text-ed-primary transition" role="menuitem">Nos doctorants</Link>
                                        <Link :href="route('ead.index')" class="block px-4 py-2 text-ed-gray hover:bg-teal-50 hover:text-ed-primary transition" role="menuitem">Equipes d'accueil</Link>
                                        <Link :href="route('programmes.index')" class="block px-4 py-2 text-ed-gray hover:bg-teal-50 hover:text-ed-primary transition" role="menuitem">Programmes de recherche</Link>
                                        <Link :href="route('theses.index')" class="block px-4 py-2 text-ed-gray hover:bg-teal-50 hover:text-ed-primary transition" role="menuitem">Banque des theses</Link>
                                    </div>
                                </Transition>
                            </li>

                            <li>
                                <Link
                                    :href="route('actualites.index')"
                                    class="px-4 py-2 text-ed-gray hover:text-ed-primary font-medium transition rounded-lg hover:bg-teal-50"
                                    :aria-current="isRoute('actualites') ? 'page' : undefined"
                                >
                                    Actualites
                                </Link>
                            </li>

                            <li>
                                <Link
                                    :href="route('contact')"
                                    class="px-4 py-2 text-ed-gray hover:text-ed-primary font-medium transition rounded-lg hover:bg-teal-50"
                                    :aria-current="isRoute('contact') ? 'page' : undefined"
                                >
                                    Contact
                                </Link>
                            </li>
                        </ul>
                    </div>

                    <!-- Auth (desktop) -->
                    <div class="hidden lg:flex items-center gap-2">
                        <Link
                            v-if="auth?.user"
                            :href="route('dashboard')"
                            class="group relative inline-flex items-center justify-center h-10 w-10 rounded-xl bg-ed-primary text-white shadow-md transition hover:bg-ed-secondary hover:scale-105"
                            title="Dashboard"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m4-8v8m5-6v6a2 2 0 01-2 2H7a2 2 0 01-2-2v-6" />
                            </svg>
                            <span class="absolute -bottom-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap pointer-events-none">
                                Dashboard
                            </span>
                        </Link>
                        <Link
                            v-if="auth?.user"
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="group relative inline-flex items-center justify-center h-10 w-10 rounded-xl border border-slate-200 text-slate-600 transition hover:border-red-300 hover:bg-red-50 hover:text-red-600 hover:scale-105"
                            title="Deconnexion"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
                            </svg>
                            <span class="absolute -bottom-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap pointer-events-none">
                                Deconnexion
                            </span>
                        </Link>
                        <Link
                            v-else
                            :href="route('login')"
                            class="group relative inline-flex items-center justify-center h-10 w-10 rounded-xl bg-ed-yellow text-white shadow-md transition hover:bg-ed-yellow-dark hover:scale-105"
                            title="Espace membres"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="absolute -bottom-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap pointer-events-none">
                                Espace membres
                            </span>
                        </Link>
                    </div>

                    <!-- Mobile toggle -->
                    <button
                        type="button"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="lg:hidden text-ed-gray hover:text-ed-primary transition"
                        aria-label="Ouvrir le menu"
                        :aria-expanded="mobileMenuOpen.toString()"
                        aria-controls="mobile-menu"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <Transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0 transform -translate-y-2"
                enter-to-class="opacity-100 transform translate-y-0"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100 transform translate-y-0"
                leave-to-class="opacity-0 transform -translate-y-2"
            >
                <div
                    v-show="mobileMenuOpen"
                    id="mobile-menu"
                    class="lg:hidden bg-white border-t shadow-lg"
                >
                    <div class="px-4 pt-2 pb-4 space-y-1 max-h-[calc(100vh-5rem)] overflow-y-auto">
                        <!-- Accueil -->
                        <Link
                            :href="route('home')"
                            class="block px-4 py-3 text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg font-medium transition"
                            :aria-current="isRoute('home') ? 'page' : undefined"
                            @click="closeMobileMenu"
                        >
                            Accueil
                        </Link>

                        <!-- A propos (accordion) -->
                        <div class="space-y-1">
                            <button
                                type="button"
                                @click="toggleSubmenu('apropos')"
                                class="w-full flex items-center justify-between px-4 py-3 text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg font-medium transition"
                                aria-controls="submenu-apropos"
                                :aria-expanded="(openSubmenu === 'apropos').toString()"
                            >
                                <span>A propos</span>
                                <svg
                                    class="w-4 h-4 transition-transform"
                                    :class="openSubmenu === 'apropos' ? 'rotate-180' : ''"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <Transition
                                enter-active-class="transition ease-out duration-200"
                                enter-from-class="opacity-0 max-h-0"
                                enter-to-class="opacity-100 max-h-96"
                                leave-active-class="transition ease-in duration-150"
                                leave-from-class="opacity-100 max-h-96"
                                leave-to-class="opacity-0 max-h-0"
                            >
                                <div
                                    v-show="openSubmenu === 'apropos'"
                                    id="submenu-apropos"
                                    class="ml-2 pl-2 border-l border-gray-100 space-y-1 overflow-hidden"
                                    role="list"
                                >
                                    <template v-if="menuAProposItems.length > 0">
                                        <Link
                                            v-for="item in menuAProposItems"
                                            :key="item.id"
                                            :href="item.url"
                                            class="block px-4 py-2 text-sm text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg transition"
                                            @click="closeMobileMenu"
                                        >
                                            {{ item.label }}
                                        </Link>
                                    </template>
                                    <div v-else class="px-4 py-2 text-xs text-gray-400">
                                        Aucune page configuree pour ce menu.
                                    </div>
                                </div>
                            </Transition>
                        </div>

                        <!-- Recherches (accordion) -->
                        <div class="space-y-1">
                            <button
                                type="button"
                                @click="toggleSubmenu('recherches')"
                                class="w-full flex items-center justify-between px-4 py-3 text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg font-medium transition"
                                aria-controls="submenu-recherches"
                                :aria-expanded="(openSubmenu === 'recherches').toString()"
                            >
                                <span>Recherches</span>
                                <svg
                                    class="w-4 h-4 transition-transform"
                                    :class="openSubmenu === 'recherches' ? 'rotate-180' : ''"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <Transition
                                enter-active-class="transition ease-out duration-200"
                                enter-from-class="opacity-0 max-h-0"
                                enter-to-class="opacity-100 max-h-96"
                                leave-active-class="transition ease-in duration-150"
                                leave-from-class="opacity-100 max-h-96"
                                leave-to-class="opacity-0 max-h-0"
                            >
                                <div
                                    v-show="openSubmenu === 'recherches'"
                                    id="submenu-recherches"
                                    class="ml-2 pl-2 border-l border-gray-100 space-y-1 overflow-hidden"
                                    role="list"
                                >
                                    <Link :href="route('doctorants.index')" class="block px-4 py-2 text-sm text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg transition" @click="closeMobileMenu">Nos doctorants</Link>
                                    <Link :href="route('ead.index')" class="block px-4 py-2 text-sm text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg transition" @click="closeMobileMenu">Equipes d'accueil</Link>
                                    <Link :href="route('programmes.index')" class="block px-4 py-2 text-sm text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg transition" @click="closeMobileMenu">Programmes de recherche</Link>
                                    <Link :href="route('theses.index')" class="block px-4 py-2 text-sm text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg transition" @click="closeMobileMenu">Banque des theses</Link>
                                </div>
                            </Transition>
                        </div>

                        <!-- Actualites -->
                        <Link
                            :href="route('actualites.index')"
                            class="block px-4 py-3 text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg font-medium transition"
                            :aria-current="isRoute('actualites') ? 'page' : undefined"
                            @click="closeMobileMenu"
                        >
                            Actualites
                        </Link>

                        <!-- Contact -->
                        <Link
                            :href="route('contact')"
                            class="block px-4 py-3 text-ed-gray hover:text-ed-primary hover:bg-teal-50 rounded-lg font-medium transition"
                            :aria-current="isRoute('contact') ? 'page' : undefined"
                            @click="closeMobileMenu"
                        >
                            Contact
                        </Link>

                        <!-- Auth -->
                        <div class="pt-2 flex items-center justify-center gap-3">
                            <Link
                                v-if="auth?.user"
                                :href="route('dashboard')"
                                class="inline-flex items-center justify-center h-12 w-12 bg-ed-primary text-white rounded-xl shadow-md hover:bg-ed-secondary transition"
                                title="Dashboard"
                                @click="closeMobileMenu"
                            >
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m4-8v8m5-6v6a2 2 0 01-2 2H7a2 2 0 01-2-2v-6" />
                                </svg>
                            </Link>
                            <Link
                                v-if="auth?.user"
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="inline-flex items-center justify-center h-12 w-12 rounded-xl border border-red-200 text-red-600 transition hover:bg-red-50"
                                title="Deconnexion"
                                @click="closeMobileMenu"
                            >
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
                                </svg>
                            </Link>
                            <Link
                                v-else
                                :href="route('login')"
                                class="inline-flex items-center justify-center h-12 w-12 bg-ed-yellow text-white rounded-xl shadow-md hover:bg-ed-yellow-dark transition"
                                title="Espace membres"
                                @click="closeMobileMenu"
                            >
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </Link>
                        </div>
                    </div>
                </div>
            </Transition>
        </nav>
    </header>
</template>
