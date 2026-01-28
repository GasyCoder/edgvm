<script setup>
import { computed, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const currentRoute = computed(() => page.props.currentRouteName || '');
const auth = computed(() => page.props.auth);
const isActive = (name) => currentRoute.value === name || currentRoute.value.startsWith(`${name}.`);

const openCommunication = ref(
    [
        'admin.actualites',
        'admin.annonces',
        'admin.newsletter',
        'admin.categories',
        'admin.tags',
        'admin.sliders',
        'admin.slides',
        'admin.media',
        'admin.message-directions',
        'admin.partenaires',
        'admin.evenements',
    ].some((prefix) => currentRoute.value.startsWith(prefix))
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
    <div class="min-h-screen bg-slate-100 font-poppins text-slate-900">
        <div class="flex min-h-screen">
            <aside
                class="relative w-64 flex-shrink-0 overflow-hidden bg-gradient-to-b from-slate-900 via-slate-900 to-emerald-950 text-white shadow-2xl"
            >
                <div class="pointer-events-none absolute inset-0">
                    <div class="absolute -top-10 -right-10 h-40 w-40 rounded-full bg-emerald-500/10 blur-3xl"></div>
                    <div class="absolute -bottom-10 -left-10 h-48 w-48 rounded-full bg-emerald-400/15 blur-3xl"></div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 h-64 w-64 rounded-full bg-emerald-600/5 blur-3xl"></div>
                </div>

                <div class="relative flex h-full flex-col">
                    <div class="border-b border-emerald-500/20 px-6 pt-6 pb-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-2xl border border-emerald-500/30 bg-gradient-to-br from-emerald-500 to-emerald-600 text-sm font-bold tracking-tight shadow-lg shadow-emerald-500/20"
                            >
                                ED
                            </div>
                            <div class="flex-1">
                                <h1 class="text-base font-semibold leading-tight text-white">EDGVM Admin</h1>
                                <p class="text-[11px] text-emerald-300/70">Panneau administration</p>
                            </div>
                        </div>

                        <div
                            class="mt-3 inline-flex items-center gap-1.5 rounded-full bg-emerald-500/15 px-3 py-1 text-[10px] font-medium text-emerald-300"
                        >
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>Environnement securise</span>
                        </div>
                    </div>

                    <nav class="relative flex-1 overflow-y-auto pt-4 pb-5">
                        <Link
                            :href="route('admin.dashboard')"
                            class="flex items-center gap-3 px-6 py-3 text-sm transition-all duration-150 hover:bg-emerald-500/10 hover:pl-7"
                            :class="isActive('admin.dashboard') ? 'bg-emerald-500/15 border-r-4 border-emerald-400 font-semibold text-emerald-300' : 'text-slate-300'"
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

                        <div class="mt-4 px-6">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-500">
                                Communication & contenus
                            </p>
                        </div>

                        <div class="mt-1">
                            <button
                                type="button"
                                class="flex w-full items-center justify-between px-6 py-3 text-left text-sm text-slate-300 transition-all duration-150 hover:bg-emerald-500/10"
                                :class="openCommunication ? 'bg-emerald-500/15 border-r-4 border-emerald-400 font-semibold text-emerald-300' : ''"
                                @click="openCommunication = !openCommunication"
                            >
                                <span class="flex items-center gap-3">
                                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M8 10h8M8 14h5M5 20l2-4h10a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2h1"
                                        />
                                    </svg>
                                    <span>Contenus & communication</span>
                                </span>
                                <svg
                                    class="h-4 w-4 flex-shrink-0 transition-transform duration-200"
                                    :class="openCommunication ? 'rotate-180' : ''"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div v-show="openCommunication" class="mt-2 space-y-1 pl-10 pr-4 text-sm">
                                <Link
                                    :href="route('admin.message-directions.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-emerald-500/10 hover:pl-4"
                                    :class="isActive('admin.message-directions') ? 'bg-emerald-500/15 text-emerald-300 font-semibold' : 'text-slate-400'"
                                >
                                    <span>Mot direction</span>
                                </Link>
                                <Link
                                    :href="route('admin.actualites.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-emerald-500/10 hover:pl-4"
                                    :class="isActive('admin.actualites') ? 'bg-emerald-500/15 text-emerald-300 font-semibold' : 'text-slate-400'"
                                >
                                    <span>Actualites</span>
                                </Link>
                                <Link
                                    :href="route('admin.evenements.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-emerald-500/10 hover:pl-4"
                                    :class="isActive('admin.evenements') ? 'bg-emerald-500/15 text-emerald-300 font-semibold' : 'text-slate-400'"
                                >
                                    <span>Evenements</span>
                                </Link>
                                <Link
                                    :href="route('admin.annonces.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-emerald-500/10 hover:pl-4"
                                    :class="isActive('admin.annonces') ? 'bg-emerald-500/15 text-emerald-300 font-semibold' : 'text-slate-400'"
                                >
                                    <span>Annonces</span>
                                </Link>
                                <Link
                                    :href="route('admin.newsletter.subscribers')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-emerald-500/10 hover:pl-4"
                                    :class="isActive('admin.newsletter') ? 'bg-emerald-500/15 text-emerald-300 font-semibold' : 'text-slate-400'"
                                >
                                    <span>Newsletter</span>
                                </Link>
                                <Link
                                    :href="route('admin.categories.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-emerald-500/10 hover:pl-4"
                                    :class="isActive('admin.categories') ? 'bg-emerald-500/15 text-emerald-300 font-semibold' : 'text-slate-400'"
                                >
                                    <span>Categories</span>
                                </Link>
                                <Link
                                    :href="route('admin.tags.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-emerald-500/10 hover:pl-4"
                                    :class="isActive('admin.tags') ? 'bg-emerald-500/15 text-emerald-300 font-semibold' : 'text-slate-400'"
                                >
                                    <span>Tags</span>
                                </Link>
                                <Link
                                    :href="route('admin.sliders.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-emerald-500/10 hover:pl-4"
                                    :class="isActive('admin.sliders') || isActive('admin.slides') ? 'bg-emerald-500/15 text-emerald-300 font-semibold' : 'text-slate-400'"
                                >
                                    <span>Sliders</span>
                                </Link>
                                <Link
                                    :href="route('admin.media.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-emerald-500/10 hover:pl-4"
                                    :class="isActive('admin.media') ? 'bg-emerald-500/15 text-emerald-300 font-semibold' : 'text-slate-400'"
                                >
                                    <span>Media</span>
                                </Link>
                                <Link
                                    :href="route('admin.partenaires.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-emerald-500/10 hover:pl-4"
                                    :class="isActive('admin.partenaires') ? 'bg-emerald-500/15 text-emerald-300 font-semibold' : 'text-slate-400'"
                                >
                                    <span>Partenaires</span>
                                </Link>
                            </div>
                        </div>

                        <div class="mt-6 px-6">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-500">
                                Recherche & formations
                            </p>
                        </div>

                        <div class="mt-1">
                            <button
                                type="button"
                                class="flex w-full items-center justify-between px-6 py-3 text-left text-sm text-slate-300 transition-all duration-150 hover:bg-emerald-500/10"
                                :class="openAcademique ? 'bg-emerald-500/15 border-r-4 border-emerald-400 font-semibold text-emerald-300' : ''"
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

                            <div v-show="openAcademique" class="mt-2 space-y-1 pl-10 pr-4 text-sm">
                                <Link
                                    :href="route('admin.ead.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-emerald-500/10 hover:pl-4"
                                    :class="isActive('admin.ead') ? 'bg-emerald-500/15 text-emerald-300 font-semibold' : 'text-slate-400'"
                                >
                                    <span>EAD</span>
                                </Link>
                                <Link
                                    :href="route('admin.specialites.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-emerald-500/10 hover:pl-4"
                                    :class="isActive('admin.specialites') ? 'bg-emerald-500/15 text-emerald-300 font-semibold' : 'text-slate-400'"
                                >
                                    <span>Specialites</span>
                                </Link>
                                <Link
                                    :href="route('admin.doctorants.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-emerald-500/10 hover:pl-4"
                                    :class="isActive('admin.doctorants') ? 'bg-emerald-500/15 text-emerald-300 font-semibold' : 'text-slate-400'"
                                >
                                    <span>Doctorants</span>
                                </Link>
                                <Link
                                    :href="route('admin.encadrants.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-emerald-500/10 hover:pl-4"
                                    :class="isActive('admin.encadrants') ? 'bg-emerald-500/15 text-emerald-300 font-semibold' : 'text-slate-400'"
                                >
                                    <span>Encadrants</span>
                                </Link>
                                <Link
                                    :href="route('admin.theses.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-emerald-500/10 hover:pl-4"
                                    :class="isActive('admin.theses') ? 'bg-emerald-500/15 text-emerald-300 font-semibold' : 'text-slate-400'"
                                >
                                    <span>Theses</span>
                                </Link>
                                <Link
                                    :href="route('admin.jurys.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-emerald-500/10 hover:pl-4"
                                    :class="isActive('admin.jurys') ? 'bg-emerald-500/15 text-emerald-300 font-semibold' : 'text-slate-400'"
                                >
                                    <span>Jurys</span>
                                </Link>
                            </div>
                        </div>

                        <div class="mt-6 px-6">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-500">
                                Configuration
                            </p>
                        </div>

                        <Link
                            :href="route('admin.settings')"
                            class="mt-1 flex items-center gap-3 px-6 py-3 text-sm transition-all duration-150 hover:bg-emerald-500/10 hover:pl-7"
                            :class="isActive('admin.settings') ? 'bg-emerald-500/15 border-r-4 border-emerald-400 font-semibold text-emerald-300' : 'text-slate-300'"
                        >
                            <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Parametres</span>
                        </Link>

                        <Link
                            :href="route('admin.pages.index')"
                            class="flex items-center gap-3 px-6 py-3 text-sm transition-all duration-150 hover:bg-emerald-500/10 hover:pl-7"
                            :class="isActive('admin.pages') ? 'bg-emerald-500/15 border-r-4 border-emerald-400 font-semibold text-emerald-300' : 'text-slate-300'"
                        >
                            <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Pages</span>
                        </Link>
                    </nav>
                </div>
            </aside>

            <div class="flex flex-1 flex-col">
                <header class="border-b border-slate-200 bg-white px-6 py-4 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <slot name="header">
                            <h1 class="text-lg font-semibold text-slate-900">Administration</h1>
                        </slot>
                        <div class="flex items-center gap-2">
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
                                :href="route('admin.settings')"
                                class="group relative inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:border-ed-primary/40 hover:bg-ed-primary/5 hover:text-ed-primary"
                                title="Parametres"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </Link>
                            <span
                                v-if="auth?.user"
                                class="hidden items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-700 md:inline-flex"
                            >
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                {{ auth.user.name }}
                            </span>
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
                <main class="flex-1 px-6 py-6">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>
