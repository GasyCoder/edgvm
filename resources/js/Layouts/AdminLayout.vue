<script setup>
import { computed, ref, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const currentRoute = computed(() => page.props.currentRouteName || '');
const auth = computed(() => page.props.auth);
const isActive = (name) => currentRoute.value === name || currentRoute.value.startsWith(`${name}.`);
const can = (ability) => Boolean(auth.value?.can?.[ability]);

const roleLabels = {
    super_admin: 'Super administrateur',
    direction: 'Direction',
    secretariat: 'Secrétariat',
    communication: 'Communication',
    encadrant: 'Encadrant',
    doctorant: 'Doctorant',
};
const roleLabel = computed(() => roleLabels[auth.value?.user?.role] || 'Membre');

// Mobile sidebar state
const sidebarOpen = ref(false);

const closeSidebar = () => {
    sidebarOpen.value = false;
};

// Close sidebar on route change
watch(currentRoute, () => {
    closeSidebar();
});

const openCommunications = ref(
    ['admin.evenements', 'admin.annonces', 'admin.newsletter']
        .some((prefix) => currentRoute.value.startsWith(prefix))
);

const openContenus = ref(
    ['admin.actualites', 'admin.categories', 'admin.tags', 'admin.sliders', 'admin.slides', 'admin.media', 'admin.message-directions', 'admin.partenaires']
        .some((prefix) => currentRoute.value.startsWith(prefix))
);

const openAcademique = ref(
    [
        'admin.ead',
        'admin.specialites',
        'admin.doctorants',
        'admin.encadrants',
        'admin.theses',
        'admin.jurys',
    ].some((prefix) => currentRoute.value.startsWith(prefix))
);
</script>

<template>
    <div class="min-h-screen bg-gray-50 font-poppins text-slate-900">
        <!-- Mobile sidebar overlay -->
        <Transition
            enter-active-class="transition-opacity ease-out duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity ease-in duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="sidebarOpen"
                class="fixed inset-0 z-40 bg-black/50 md:hidden"
                @click="closeSidebar"
            ></div>
        </Transition>

        <div class="flex min-h-screen">
            <!-- Sidebar -->
            <aside
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                class="fixed inset-y-0 left-0 z-50 w-64 flex-shrink-0 overflow-hidden bg-ed-teal-dark text-white shadow-xl transition-transform duration-300 ease-in-out md:relative md:translate-x-0"
            >
                <div class="relative flex h-full flex-col">
                    <div class="border-b border-white/10 px-6 pb-5 pt-6">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 flex-none items-center justify-center rounded-xl border border-white/15 bg-white/10 text-sm font-bold tracking-tight">
                                ED
                            </div>
                            <div class="min-w-0 flex-1">
                                <h1 class="text-base font-semibold leading-tight text-white">EDGVM</h1>
                                <p class="text-[11px] text-white/50">Espace de gestion</p>
                            </div>
                        </div>

                        <div class="mt-4 rounded-lg border border-white/10 bg-white/5 px-3 py-2">
                            <p class="text-[10px] uppercase tracking-wider text-white/40">Connecté en tant que</p>
                            <p class="truncate text-sm font-semibold text-white">{{ roleLabel }}</p>
                        </div>
                    </div>

                    <nav class="relative flex-1 overflow-y-auto pt-4 pb-5">
                        <Link
                            :href="route('admin.dashboard')"
                            class="flex items-center gap-3 px-6 py-3 text-sm transition-all duration-150 hover:bg-white/5 hover:pl-7"
                            :class="isActive('admin.dashboard') ? 'bg-white/10 border-r-2 border-ed-yellow font-semibold text-white' : 'text-white/75'"
                        >
                            <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                />
                            </svg>
                            <span>Dashboard</span>
                        </Link>

                        <!-- Communications (secrétariat / direction / communication) -->
                        <div v-if="can('communications.access')" class="mt-4 px-6">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-white/40">
                                Communications
                            </p>
                        </div>

                        <div v-if="can('communications.access')" class="mt-1">
                            <button
                                type="button"
                                class="flex w-full items-center justify-between px-6 py-3 text-left text-sm text-white/75 transition-all duration-150 hover:bg-white/5"
                                :class="openCommunications ? 'bg-white/10 border-r-2 border-ed-yellow font-semibold text-white' : ''"
                                @click="openCommunications = !openCommunications"
                            >
                                <span class="flex items-center gap-3">
                                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                    </svg>
                                    <span>Communications</span>
                                </span>
                                <svg class="h-4 w-4 flex-shrink-0 transition-transform duration-200" :class="openCommunications ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div v-show="openCommunications" class="mt-2 space-y-1 pl-4 pr-4 text-sm md:pl-10">
                                <Link :href="route('admin.evenements.index')" class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/5 hover:pl-4" :class="isActive('admin.evenements') ? 'bg-white/10 text-white font-medium' : 'text-white/60'">
                                    <span>Événements</span>
                                </Link>
                                <Link :href="route('admin.annonces.index')" class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/5 hover:pl-4" :class="isActive('admin.annonces') ? 'bg-white/10 text-white font-medium' : 'text-white/60'">
                                    <span>Annonces</span>
                                </Link>
                                <Link :href="route('admin.newsletter.subscribers')" class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/5 hover:pl-4" :class="isActive('admin.newsletter') ? 'bg-white/10 text-white font-medium' : 'text-white/60'">
                                    <span>E-mails / Newsletter</span>
                                </Link>
                            </div>
                        </div>

                        <!-- Contenus du site (communication / direction) -->
                        <div v-if="can('contenu.access')" class="mt-6 px-6">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-white/40">
                                Contenus du site
                            </p>
                        </div>

                        <div v-if="can('contenu.access')" class="mt-1">
                            <button
                                type="button"
                                class="flex w-full items-center justify-between px-6 py-3 text-left text-sm text-white/75 transition-all duration-150 hover:bg-white/5"
                                :class="openContenus ? 'bg-white/10 border-r-2 border-ed-yellow font-semibold text-white' : ''"
                                @click="openContenus = !openContenus"
                            >
                                <span class="flex items-center gap-3">
                                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span>Contenus du site</span>
                                </span>
                                <svg class="h-4 w-4 flex-shrink-0 transition-transform duration-200" :class="openContenus ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div v-show="openContenus" class="mt-2 space-y-1 pl-4 pr-4 text-sm md:pl-10">
                                <Link :href="route('admin.message-directions.index')" class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/5 hover:pl-4" :class="isActive('admin.message-directions') ? 'bg-white/10 text-white font-medium' : 'text-white/60'">
                                    <span>Mot de la direction</span>
                                </Link>
                                <Link :href="route('admin.actualites.index')" class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/5 hover:pl-4" :class="isActive('admin.actualites') ? 'bg-white/10 text-white font-medium' : 'text-white/60'">
                                    <span>Actualités</span>
                                </Link>
                                <Link :href="route('admin.categories.index')" class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/5 hover:pl-4" :class="isActive('admin.categories') ? 'bg-white/10 text-white font-medium' : 'text-white/60'">
                                    <span>Catégories</span>
                                </Link>
                                <Link :href="route('admin.tags.index')" class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/5 hover:pl-4" :class="isActive('admin.tags') ? 'bg-white/10 text-white font-medium' : 'text-white/60'">
                                    <span>Tags</span>
                                </Link>
                                <Link :href="route('admin.sliders.index')" class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/5 hover:pl-4" :class="isActive('admin.sliders') || isActive('admin.slides') ? 'bg-white/10 text-white font-medium' : 'text-white/60'">
                                    <span>Sliders</span>
                                </Link>
                                <Link :href="route('admin.media.index')" class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/5 hover:pl-4" :class="isActive('admin.media') ? 'bg-white/10 text-white font-medium' : 'text-white/60'">
                                    <span>Médias</span>
                                </Link>
                                <Link :href="route('admin.partenaires.index')" class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/5 hover:pl-4" :class="isActive('admin.partenaires') ? 'bg-white/10 text-white font-medium' : 'text-white/60'">
                                    <span>Partenaires</span>
                                </Link>
                            </div>
                        </div>

                        <div v-if="can('gestion.access')" class="mt-6 px-6">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-white/40">
                                Recherche & formations
                            </p>
                        </div>

                        <div v-if="can('gestion.access')" class="mt-1">
                            <button
                                type="button"
                                class="flex w-full items-center justify-between px-6 py-3 text-left text-sm text-slate-300 transition-all duration-150 hover:bg-white/5"
                                :class="openAcademique ? 'bg-white/10 border-r-2 border-ed-yellow font-semibold text-white' : ''"
                                @click="openAcademique = !openAcademique"
                            >
                                <span class="flex items-center gap-3">
                                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 6l7 4-7 4-7-4 7-4zm0 8l7 4-7 4-7-4 7-4z"
                                        />
                                    </svg>
                                    <span>Recherche & formations</span>
                                </span>
                                <svg
                                    class="h-4 w-4 flex-shrink-0 transition-transform duration-200"
                                    :class="openAcademique ? 'rotate-180' : ''"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div v-show="openAcademique" class="mt-2 space-y-1 pl-4 pr-4 text-sm md:pl-10">
                                <Link
                                    :href="route('admin.ead.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/5 hover:pl-4"
                                    :class="isActive('admin.ead') ? 'bg-white/10 text-white font-medium' : 'text-white/60'"
                                >
                                    <span>EAD</span>
                                </Link>
                                <Link
                                    :href="route('admin.specialites.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/5 hover:pl-4"
                                    :class="isActive('admin.specialites') ? 'bg-white/10 text-white font-medium' : 'text-white/60'"
                                >
                                    <span>Specialites</span>
                                </Link>
                                <Link
                                    :href="route('admin.doctorants.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/5 hover:pl-4"
                                    :class="isActive('admin.doctorants') ? 'bg-white/10 text-white font-medium' : 'text-white/60'"
                                >
                                    <span>Doctorants</span>
                                </Link>
                                <Link
                                    :href="route('admin.encadrants.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/5 hover:pl-4"
                                    :class="isActive('admin.encadrants') ? 'bg-white/10 text-white font-medium' : 'text-white/60'"
                                >
                                    <span>Encadrants</span>
                                </Link>
                                <Link
                                    :href="route('admin.theses.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/5 hover:pl-4"
                                    :class="isActive('admin.theses') ? 'bg-white/10 text-white font-medium' : 'text-white/60'"
                                >
                                    <span>Theses</span>
                                </Link>
                                <Link
                                    :href="route('admin.jurys.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/5 hover:pl-4"
                                    :class="isActive('admin.jurys') ? 'bg-white/10 text-white font-medium' : 'text-white/60'"
                                >
                                    <span>Jurys</span>
                                </Link>
                            </div>
                        </div>

                        <div v-if="can('systeme.access') || can('contenu.access')" class="mt-6 px-6">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-white/40">
                                Configuration
                            </p>
                        </div>

                        <Link
                            v-if="can('systeme.access')"
                            :href="route('admin.settings')"
                            class="mt-1 flex items-center gap-3 px-6 py-3 text-sm transition-all duration-150 hover:bg-white/5 hover:pl-7"
                            :class="isActive('admin.settings') ? 'bg-white/10 border-r-2 border-ed-yellow font-semibold text-white' : 'text-white/75'"
                        >
                            <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Parametres</span>
                        </Link>

                        <Link
                            v-if="can('contenu.access')"
                            :href="route('admin.pages.index')"
                            class="flex items-center gap-3 px-6 py-3 text-sm transition-all duration-150 hover:bg-white/5 hover:pl-7"
                            :class="isActive('admin.pages') ? 'bg-white/10 border-r-2 border-ed-yellow font-semibold text-white' : 'text-white/75'"
                        >
                            <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Pages</span>
                        </Link>
                    </nav>
                </div>
            </aside>

            <div class="flex flex-1 flex-col md:ml-0">
                <header class="border-b border-slate-200 bg-white px-3 py-3 shadow-sm md:px-6 md:py-4">
                    <div class="flex items-center justify-between gap-2 md:gap-4">
                        <!-- Mobile menu button -->
                        <button
                            type="button"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:bg-slate-50 md:hidden"
                            @click="sidebarOpen = !sidebarOpen"
                            aria-label="Ouvrir le menu"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <slot name="header">
                            <h1 class="text-base font-semibold text-slate-900 md:text-lg">Administration</h1>
                        </slot>
                        <div class="flex items-center gap-1 md:gap-2">
                            <Link
                                :href="route('home')"
                                target="_blank"
                                class="group relative inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:border-ed-primary/40 hover:bg-ed-primary/5 hover:text-ed-primary"
                                title="Voir le site"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </Link>
                            <Link
                                v-if="can('systeme.access')"
                                :href="route('admin.settings')"
                                class="group relative inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:border-ed-primary/40 hover:bg-ed-primary/5 hover:text-ed-primary"
                                title="Paramètres"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </Link>
                            <div v-if="auth?.user" class="mr-1 hidden flex-col items-end leading-tight md:flex">
                                <span class="text-sm font-semibold text-slate-900">{{ auth.user.name }}</span>
                                <span class="text-[11px] text-slate-500">{{ roleLabel }}</span>
                            </div>
                            <Link
                                v-if="auth?.user"
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="group relative inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:border-red-300 hover:bg-red-50 hover:text-red-600"
                                title="Deconnexion"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
                                </svg>
                            </Link>
                        </div>
                    </div>
                </header>
                <main class="flex-1 px-3 py-4 md:px-6 md:py-6">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>
