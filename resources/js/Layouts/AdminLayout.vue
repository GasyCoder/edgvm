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
                class="relative w-64 flex-shrink-0 overflow-hidden bg-gradient-to-b from-ed-primary via-ed-primary/95 to-ed-secondary text-white shadow-2xl"
            >
                <div class="pointer-events-none absolute inset-0 opacity-20">
                    <div class="absolute -top-10 -right-10 h-40 w-40 rounded-full bg-white/20 blur-3xl"></div>
                    <div class="absolute -bottom-10 -left-10 h-48 w-48 rounded-full bg-ed-yellow/30 blur-3xl"></div>
                </div>

                <div class="relative flex h-full flex-col">
                    <div class="border-b border-white/10 px-6 pt-6 pb-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-2xl border border-white/20 bg-white/15 text-sm font-semibold tracking-tight shadow-md"
                            >
                                ED
                            </div>
                            <div class="flex-1">
                                <h1 class="text-base font-semibold leading-tight">EDGVM Admin</h1>
                                <p class="text-[11px] text-white/70">Panneau administration</p>
                            </div>
                        </div>

                        <div
                            class="mt-3 inline-flex items-center gap-1 rounded-full bg-black/15 px-3 py-1 text-[10px] font-medium text-white/80"
                        >
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-300"></span>
                            <span>Environnement securise</span>
                        </div>
                    </div>

                    <nav class="relative flex-1 overflow-y-auto pt-4 pb-5">
                        <Link
                            :href="route('admin.dashboard')"
                            class="flex items-center gap-3 px-6 py-3 text-sm transition-all duration-150 hover:bg-white/10 hover:pl-7"
                            :class="isActive('admin.dashboard') ? 'bg-white/15 border-r-4 border-ed-yellow font-semibold shadow-sm' : 'text-white/80'"
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
                            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-white/50">
                                Communication & contenus
                            </p>
                        </div>

                        <div class="mt-1">
                            <button
                                type="button"
                                class="flex w-full items-center justify-between px-6 py-3 text-left text-sm text-white/80 transition-all duration-150 hover:bg-white/10"
                                :class="openCommunication ? 'bg-white/15 border-r-4 border-ed-yellow font-semibold shadow-sm' : ''"
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
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/10 hover:pl-4"
                                    :class="isActive('admin.message-directions') ? 'bg-white/15 text-ed-yellow font-semibold shadow-sm' : 'text-white/80'"
                                >
                                    <span>Mot direction</span>
                                </Link>
                                <Link
                                    :href="route('admin.actualites.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/10 hover:pl-4"
                                    :class="isActive('admin.actualites') ? 'bg-white/15 text-ed-yellow font-semibold shadow-sm' : 'text-white/80'"
                                >
                                    <span>Actualites</span>
                                </Link>
                                <Link
                                    :href="route('admin.evenements.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/10 hover:pl-4"
                                    :class="isActive('admin.evenements') ? 'bg-white/15 text-ed-yellow font-semibold shadow-sm' : 'text-white/80'"
                                >
                                    <span>Evenements</span>
                                </Link>
                                <Link
                                    :href="route('admin.annonces.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/10 hover:pl-4"
                                    :class="isActive('admin.annonces') ? 'bg-white/15 text-ed-yellow font-semibold shadow-sm' : 'text-white/80'"
                                >
                                    <span>Annonces</span>
                                </Link>
                                <Link
                                    :href="route('admin.newsletter.subscribers')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/10 hover:pl-4"
                                    :class="isActive('admin.newsletter') ? 'bg-white/15 text-ed-yellow font-semibold shadow-sm' : 'text-white/80'"
                                >
                                    <span>Newsletter</span>
                                </Link>
                                <Link
                                    :href="route('admin.categories.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/10 hover:pl-4"
                                    :class="isActive('admin.categories') ? 'bg-white/15 text-ed-yellow font-semibold shadow-sm' : 'text-white/80'"
                                >
                                    <span>Categories</span>
                                </Link>
                                <Link
                                    :href="route('admin.tags.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/10 hover:pl-4"
                                    :class="isActive('admin.tags') ? 'bg-white/15 text-ed-yellow font-semibold shadow-sm' : 'text-white/80'"
                                >
                                    <span>Tags</span>
                                </Link>
                                <Link
                                    :href="route('admin.sliders.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/10 hover:pl-4"
                                    :class="isActive('admin.sliders') || isActive('admin.slides') ? 'bg-white/15 text-ed-yellow font-semibold shadow-sm' : 'text-white/80'"
                                >
                                    <span>Sliders</span>
                                </Link>
                                <Link
                                    :href="route('admin.media.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/10 hover:pl-4"
                                    :class="isActive('admin.media') ? 'bg-white/15 text-ed-yellow font-semibold shadow-sm' : 'text-white/80'"
                                >
                                    <span>Media</span>
                                </Link>
                                <Link
                                    :href="route('admin.partenaires.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/10 hover:pl-4"
                                    :class="isActive('admin.partenaires') ? 'bg-white/15 text-ed-yellow font-semibold shadow-sm' : 'text-white/80'"
                                >
                                    <span>Partenaires</span>
                                </Link>
                            </div>
                        </div>

                        <div class="mt-6 px-6">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-white/50">
                                Recherche & formations
                            </p>
                        </div>

                        <div class="mt-1">
                            <button
                                type="button"
                                class="flex w-full items-center justify-between px-6 py-3 text-left text-sm text-white/80 transition-all duration-150 hover:bg-white/10"
                                :class="openAcademique ? 'bg-white/15 border-r-4 border-ed-yellow font-semibold shadow-sm' : ''"
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
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/10 hover:pl-4"
                                    :class="isActive('admin.ead') ? 'bg-white/15 text-ed-yellow font-semibold shadow-sm' : 'text-white/80'"
                                >
                                    <span>EAD</span>
                                </Link>
                                <Link
                                    :href="route('admin.specialites.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/10 hover:pl-4"
                                    :class="isActive('admin.specialites') ? 'bg-white/15 text-ed-yellow font-semibold shadow-sm' : 'text-white/80'"
                                >
                                    <span>Specialites</span>
                                </Link>
                                <Link
                                    :href="route('admin.doctorants.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/10 hover:pl-4"
                                    :class="isActive('admin.doctorants') ? 'bg-white/15 text-ed-yellow font-semibold shadow-sm' : 'text-white/80'"
                                >
                                    <span>Doctorants</span>
                                </Link>
                                <Link
                                    :href="route('admin.encadrants.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/10 hover:pl-4"
                                    :class="isActive('admin.encadrants') ? 'bg-white/15 text-ed-yellow font-semibold shadow-sm' : 'text-white/80'"
                                >
                                    <span>Encadrants</span>
                                </Link>
                                <Link
                                    :href="route('admin.theses.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/10 hover:pl-4"
                                    :class="isActive('admin.theses') ? 'bg-white/15 text-ed-yellow font-semibold shadow-sm' : 'text-white/80'"
                                >
                                    <span>Theses</span>
                                </Link>
                                <Link
                                    :href="route('admin.jurys.index')"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 transition-all duration-150 hover:bg-white/10 hover:pl-4"
                                    :class="isActive('admin.jurys') ? 'bg-white/15 text-ed-yellow font-semibold shadow-sm' : 'text-white/80'"
                                >
                                    <span>Jurys</span>
                                </Link>
                            </div>
                        </div>
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
                                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:border-ed-primary/40 hover:text-ed-primary"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m4-8v8m5-6v6a2 2 0 01-2 2H7a2 2 0 01-2-2v-6" />
                                </svg>
                                Site public
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
                                class="inline-flex items-center gap-2 rounded-xl border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
                                </svg>
                                Deconnexion
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
