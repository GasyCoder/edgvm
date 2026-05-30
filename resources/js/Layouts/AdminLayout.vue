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

const userInitials = computed(() => {
    const name = auth.value?.user?.name || '';

    return name.split(' ').filter(Boolean).slice(0, 2).map((p) => p.charAt(0).toUpperCase()).join('') || 'ED';
});

// Mobile sidebar state
const sidebarOpen = ref(false);
const closeSidebar = () => {
    sidebarOpen.value = false;
};
watch(currentRoute, () => closeSidebar());

const openCommunications = ref(
    ['admin.evenements', 'admin.annonces', 'admin.newsletter']
        .some((prefix) => currentRoute.value.startsWith(prefix)),
);

const openContenus = ref(
    ['admin.actualites', 'admin.categories', 'admin.tags', 'admin.sliders', 'admin.slides', 'admin.media', 'admin.message-directions', 'admin.partenaires']
        .some((prefix) => currentRoute.value.startsWith(prefix)),
);

const openAcademique = ref(
    ['admin.ead', 'admin.specialites', 'admin.encadrants', 'admin.theses', 'admin.jurys']
        .some((prefix) => currentRoute.value.startsWith(prefix)),
);

// Classes partagées
const navBase = 'group relative mx-3 flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-colors duration-150';
const navActive = 'bg-white/10 font-semibold text-white';
const navIdle = 'text-white/70 hover:bg-white/5 hover:text-white';
const navItem = (active) => [navBase, active ? navActive : navIdle];

const subBase = 'group relative flex items-center gap-2.5 rounded-lg py-2 pl-3 pr-3 text-[13px] transition-colors duration-150';
const subActive = 'bg-white/10 font-medium text-white';
const subIdle = 'text-white/55 hover:bg-white/5 hover:text-white';
const subItem = (active) => [subBase, active ? subActive : subIdle];
</script>

<template>
    <div class="h-screen overflow-hidden bg-gray-50 font-body text-slate-900 [&_h1]:font-heading [&_h2]:font-heading [&_h3]:font-heading">
        <!-- Mobile overlay -->
        <Transition
            enter-active-class="transition-opacity ease-out duration-300" enter-from-class="opacity-0" enter-to-class="opacity-100"
            leave-active-class="transition-opacity ease-in duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0"
        >
            <div v-if="sidebarOpen" class="fixed inset-0 z-40 bg-black/50 md:hidden" @click="closeSidebar"></div>
        </Transition>

        <div class="flex h-full">
            <!-- Sidebar -->
            <aside
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                class="fixed inset-y-0 left-0 z-50 flex h-screen w-[270px] shrink-0 flex-col bg-ed-teal-dark text-white shadow-2xl transition-transform duration-300 ease-in-out md:static md:h-full md:translate-x-0"
            >
                <!-- Branding -->
                <div class="flex items-center gap-3 px-5 pb-5 pt-6">
                    <div class="flex h-11 w-11 flex-none items-center justify-center rounded-2xl bg-gradient-to-br from-white/20 to-white/5 text-base font-bold tracking-tight ring-1 ring-white/15">
                        ED
                    </div>
                    <div class="min-w-0 flex-1">
                        <h1 class="text-base font-semibold leading-tight text-white">EDGVM</h1>
                        <p class="text-[11px] text-white/45">Espace de gestion</p>
                    </div>
                </div>

                <div class="mx-3 mb-2 h-px bg-white/10"></div>

                <!-- Navigation -->
                <nav class="flex-1 space-y-1 overflow-y-auto overflow-x-hidden py-2">
                    <Link :href="route('admin.dashboard')" :class="navItem(isActive('admin.dashboard'))">
                        <span v-if="isActive('admin.dashboard')" class="absolute -left-3 inset-y-1.5 w-1 rounded-r-full bg-ed-yellow"></span>
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Tableau de bord</span>
                    </Link>

                    <Link v-if="can('gestion.access')" :href="route('admin.doctorants.index')" :class="navItem(isActive('admin.doctorants'))">
                        <span v-if="isActive('admin.doctorants')" class="absolute -left-3 inset-y-1.5 w-1 rounded-r-full bg-ed-yellow"></span>
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a3 3 0 10-3-3" />
                        </svg>
                        <span>Doctorants</span>
                    </Link>

                    <!-- Communications -->
                    <template v-if="can('communications.access')">
                        <p class="px-6 pb-1 pt-5 text-[10px] font-semibold uppercase tracking-[0.18em] text-white/35">Communications</p>
                        <button type="button" :class="navItem(openCommunications)" @click="openCommunications = !openCommunications">
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                            <span class="flex-1 text-left">Communications</span>
                            <svg class="h-4 w-4 shrink-0 transition-transform duration-200" :class="openCommunications && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div v-show="openCommunications" class="ml-7 mr-3 space-y-0.5 border-l border-white/10 pl-3">
                            <Link :href="route('admin.evenements.index')" :class="subItem(isActive('admin.evenements'))">
                                <span class="h-1.5 w-1.5 flex-none rounded-full" :class="isActive('admin.evenements') ? 'bg-ed-yellow' : 'bg-white/25'"></span>
                                <span>Événements</span>
                            </Link>
                            <Link :href="route('admin.annonces.index')" :class="subItem(isActive('admin.annonces'))">
                                <span class="h-1.5 w-1.5 flex-none rounded-full" :class="isActive('admin.annonces') ? 'bg-ed-yellow' : 'bg-white/25'"></span>
                                <span>Annonces</span>
                            </Link>
                            <Link :href="route('admin.newsletter.subscribers')" :class="subItem(isActive('admin.newsletter'))">
                                <span class="h-1.5 w-1.5 flex-none rounded-full" :class="isActive('admin.newsletter') ? 'bg-ed-yellow' : 'bg-white/25'"></span>
                                <span>E-mails / Newsletter</span>
                            </Link>
                        </div>
                    </template>

                    <!-- Contenus du site -->
                    <template v-if="can('contenu.access')">
                        <p class="px-6 pb-1 pt-5 text-[10px] font-semibold uppercase tracking-[0.18em] text-white/35">Contenus du site</p>
                        <button type="button" :class="navItem(openContenus)" @click="openContenus = !openContenus">
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="flex-1 text-left">Contenus du site</span>
                            <svg class="h-4 w-4 shrink-0 transition-transform duration-200" :class="openContenus && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div v-show="openContenus" class="ml-7 mr-3 space-y-0.5 border-l border-white/10 pl-3">
                            <Link :href="route('admin.message-directions.index')" :class="subItem(isActive('admin.message-directions'))">
                                <span class="h-1.5 w-1.5 flex-none rounded-full" :class="isActive('admin.message-directions') ? 'bg-ed-yellow' : 'bg-white/25'"></span><span>Mot de la direction</span>
                            </Link>
                            <Link :href="route('admin.actualites.index')" :class="subItem(isActive('admin.actualites'))">
                                <span class="h-1.5 w-1.5 flex-none rounded-full" :class="isActive('admin.actualites') ? 'bg-ed-yellow' : 'bg-white/25'"></span><span>Actualités</span>
                            </Link>
                            <Link :href="route('admin.categories.index')" :class="subItem(isActive('admin.categories'))">
                                <span class="h-1.5 w-1.5 flex-none rounded-full" :class="isActive('admin.categories') ? 'bg-ed-yellow' : 'bg-white/25'"></span><span>Catégories</span>
                            </Link>
                            <Link :href="route('admin.tags.index')" :class="subItem(isActive('admin.tags'))">
                                <span class="h-1.5 w-1.5 flex-none rounded-full" :class="isActive('admin.tags') ? 'bg-ed-yellow' : 'bg-white/25'"></span><span>Tags</span>
                            </Link>
                            <Link :href="route('admin.sliders.index')" :class="subItem(isActive('admin.sliders') || isActive('admin.slides'))">
                                <span class="h-1.5 w-1.5 flex-none rounded-full" :class="(isActive('admin.sliders') || isActive('admin.slides')) ? 'bg-ed-yellow' : 'bg-white/25'"></span><span>Sliders</span>
                            </Link>
                            <Link :href="route('admin.media.index')" :class="subItem(isActive('admin.media'))">
                                <span class="h-1.5 w-1.5 flex-none rounded-full" :class="isActive('admin.media') ? 'bg-ed-yellow' : 'bg-white/25'"></span><span>Médias</span>
                            </Link>
                            <Link :href="route('admin.partenaires.index')" :class="subItem(isActive('admin.partenaires'))">
                                <span class="h-1.5 w-1.5 flex-none rounded-full" :class="isActive('admin.partenaires') ? 'bg-ed-yellow' : 'bg-white/25'"></span><span>Partenaires</span>
                            </Link>
                        </div>
                    </template>

                    <!-- Recherche & formations -->
                    <template v-if="can('gestion.access')">
                        <p class="px-6 pb-1 pt-5 text-[10px] font-semibold uppercase tracking-[0.18em] text-white/35">Recherche &amp; formations</p>
                        <button type="button" :class="navItem(openAcademique)" @click="openAcademique = !openAcademique">
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6l7 4-7 4-7-4 7-4zm0 8l7 4-7 4-7-4 7-4z" />
                            </svg>
                            <span class="flex-1 text-left">Recherche</span>
                            <svg class="h-4 w-4 shrink-0 transition-transform duration-200" :class="openAcademique && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div v-show="openAcademique" class="ml-7 mr-3 space-y-0.5 border-l border-white/10 pl-3">
                            <Link :href="route('admin.ead.index')" :class="subItem(isActive('admin.ead'))">
                                <span class="h-1.5 w-1.5 flex-none rounded-full" :class="isActive('admin.ead') ? 'bg-ed-yellow' : 'bg-white/25'"></span><span>Équipes d'accueil</span>
                            </Link>
                            <Link :href="route('admin.specialites.index')" :class="subItem(isActive('admin.specialites'))">
                                <span class="h-1.5 w-1.5 flex-none rounded-full" :class="isActive('admin.specialites') ? 'bg-ed-yellow' : 'bg-white/25'"></span><span>Spécialités</span>
                            </Link>
                            <Link :href="route('admin.encadrants.index')" :class="subItem(isActive('admin.encadrants'))">
                                <span class="h-1.5 w-1.5 flex-none rounded-full" :class="isActive('admin.encadrants') ? 'bg-ed-yellow' : 'bg-white/25'"></span><span>Encadrants</span>
                            </Link>
                            <Link :href="route('admin.theses.index')" :class="subItem(isActive('admin.theses'))">
                                <span class="h-1.5 w-1.5 flex-none rounded-full" :class="isActive('admin.theses') ? 'bg-ed-yellow' : 'bg-white/25'"></span><span>Thèses</span>
                            </Link>
                            <Link :href="route('admin.jurys.index')" :class="subItem(isActive('admin.jurys'))">
                                <span class="h-1.5 w-1.5 flex-none rounded-full" :class="isActive('admin.jurys') ? 'bg-ed-yellow' : 'bg-white/25'"></span><span>Jurys</span>
                            </Link>
                        </div>
                    </template>

                    <!-- Configuration -->
                    <template v-if="can('systeme.access') || can('contenu.access')">
                        <p class="px-6 pb-1 pt-5 text-[10px] font-semibold uppercase tracking-[0.18em] text-white/35">Configuration</p>
                        <Link v-if="can('systeme.access')" :href="route('admin.settings')" :class="navItem(isActive('admin.settings'))">
                            <span v-if="isActive('admin.settings')" class="absolute -left-3 inset-y-1.5 w-1 rounded-r-full bg-ed-yellow"></span>
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Paramètres</span>
                        </Link>
                        <Link v-if="can('contenu.access')" :href="route('admin.pages.index')" :class="navItem(isActive('admin.pages'))">
                            <span v-if="isActive('admin.pages')" class="absolute -left-3 inset-y-1.5 w-1 rounded-r-full bg-ed-yellow"></span>
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Pages</span>
                        </Link>
                    </template>
                </nav>

                <!-- Carte utilisateur -->
                <div v-if="auth?.user" class="border-t border-white/10 p-3">
                    <div class="flex items-center gap-3 rounded-xl bg-white/5 px-3 py-2.5">
                        <span class="flex h-9 w-9 flex-none items-center justify-center rounded-full bg-ed-yellow/20 text-xs font-bold text-ed-yellow">{{ userInitials }}</span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold leading-tight text-white">{{ auth.user.name }}</p>
                            <p class="truncate text-[11px] text-white/50">{{ roleLabel }}</p>
                        </div>
                        <Link :href="route('logout')" method="post" as="button" class="flex h-8 w-8 flex-none items-center justify-center rounded-lg text-white/60 transition hover:bg-red-500/20 hover:text-red-300" title="Déconnexion">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
                            </svg>
                        </Link>
                    </div>
                </div>
            </aside>

            <div class="flex min-w-0 flex-1 flex-col overflow-hidden">
                <header class="z-30 border-b border-slate-200 bg-white px-3 py-3 shadow-sm md:px-6 md:py-4">
                    <div class="flex items-center justify-between gap-2 md:gap-4">
                        <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:bg-slate-50 md:hidden" @click="sidebarOpen = !sidebarOpen" aria-label="Ouvrir le menu">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        </button>

                        <slot name="header">
                            <h1 class="text-base font-semibold text-slate-900 md:text-lg">Administration</h1>
                        </slot>
                        <div class="flex items-center gap-1 md:gap-2">
                            <Link :href="route('home')" target="_blank" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:border-ed-primary/40 hover:bg-ed-primary/5 hover:text-ed-primary" title="Voir le site">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                            </Link>
                            <Link v-if="can('systeme.access')" :href="route('admin.settings')" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:border-ed-primary/40 hover:bg-ed-primary/5 hover:text-ed-primary" title="Paramètres">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </Link>
                            <div v-if="auth?.user" class="mr-1 hidden flex-col items-end leading-tight md:flex">
                                <span class="text-sm font-semibold text-slate-900">{{ auth.user.name }}</span>
                                <span class="text-[11px] text-slate-500">{{ roleLabel }}</span>
                            </div>
                            <Link v-if="auth?.user" :href="route('logout')" method="post" as="button" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:border-red-300 hover:bg-red-50 hover:text-red-600" title="Déconnexion">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" /></svg>
                            </Link>
                        </div>
                    </div>
                </header>
                <main class="flex-1 overflow-y-auto px-3 py-4 md:px-6 md:py-6">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>
