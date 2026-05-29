<script setup>
import { computed, ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import Container from '@/Components/Frontend/UI/Container.vue';
import Pagination from '@/Components/Common/Pagination.vue';
import { cleanText } from '@/utils/text';

const props = defineProps({
    filters: Object,
    theses: Object,
});

const search = ref(props.filters?.search || '');
const viewMode = computed(() => props.filters?.viewMode || 'grid');
const statut = ref(props.filters?.statut || '');

const statutOptions = [
    { value: '', label: 'Toutes les thèses', dot: 'bg-gray-400' },
    { value: 'preparation', label: 'En préparation', dot: 'bg-amber-500' },
    { value: 'en_cours', label: 'En cours', dot: 'bg-ed-primary' },
    { value: 'soutenue', label: 'Soutenue', dot: 'bg-emerald-600' },
];

let searchTimeout = null;

const updateFilters = (values) => {
    router.get(route('theses.index'), {
        search: search.value,
        viewMode: viewMode.value,
        statut: statut.value,
        ...values,
    }, {
        preserveScroll: true,
        preserveState: true,
    });
};

watch(search, (value) => {
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }

    searchTimeout = setTimeout(() => {
        updateFilters({ search: value, page: 1 });
    }, 400);
});

const setViewMode = (mode) => {
    updateFilters({ viewMode: mode, page: 1 });
};

const setStatut = (value) => {
    statut.value = value || '';
    updateFilters({ statut: value || '', page: 1 });
};

defineOptions({
    layout: FrontendLayout,
});
</script>

<template>
    <Head title="Thèses" />

    <!-- Hero -->
    <section class="mt-16 bg-ed-teal-dark py-12 text-white sm:mt-20 sm:py-16">
        <Container>
            <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-ed-yellow">Répertoire scientifique</p>
                    <h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Thèses de l'École Doctorale</h1>
                    <p class="mt-3 max-w-2xl text-sm leading-relaxed text-white/85 sm:text-base">
                        Consultez les travaux de recherche des doctorants de l'EDGVM.
                    </p>

                    <nav class="mt-5 flex items-center gap-2 text-sm text-white/70" aria-label="Fil d'Ariane">
                        <Link :href="route('home')" class="transition-colors hover:text-ed-yellow">Accueil</Link>
                        <span aria-hidden="true">/</span>
                        <span class="font-semibold text-white">Thèses</span>
                    </nav>
                </div>

                <div class="rounded-lg border border-white/15 bg-white/5 px-5 py-3">
                    <p class="text-2xl font-semibold tabular-nums">{{ theses?.meta?.total || 0 }}</p>
                    <p class="text-xs text-white/70">Thèses référencées</p>
                </div>
            </div>
        </Container>
    </section>

    <section class="bg-gray-50 py-10 md:py-14">
        <Container>
            <!-- Toolbar -->
            <div class="flex flex-col gap-4 rounded-xl border border-gray-200 bg-white p-4 sm:p-5 lg:flex-row lg:items-end lg:justify-between">
                <div class="w-full lg:max-w-xl">
                    <label for="search-these" class="mb-1.5 block text-xs font-semibold text-gray-600">Rechercher une thèse</label>
                    <div class="relative">
                        <svg class="pointer-events-none absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z" />
                        </svg>
                        <input
                            id="search-these"
                            v-model="search"
                            type="text"
                            placeholder="Sujet, doctorant, mots-clés…"
                            class="w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-10 pr-3 text-sm placeholder:text-gray-400 focus:border-transparent focus:ring-2 focus:ring-ed-primary/40"
                        >
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-xs font-semibold text-gray-600">Affichage</span>
                    <div class="inline-flex rounded-lg border border-gray-300 p-0.5">
                        <button type="button" class="inline-flex h-9 items-center gap-1.5 rounded-md px-3 text-sm transition" :class="viewMode === 'grid' ? 'bg-ed-primary text-white' : 'text-gray-500 hover:text-gray-800'" @click="setViewMode('grid')">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h7V4H4v2zm9 0h7V4h-7v2zM4 13h7v-2H4v2zm9 0h7v-2h-7v2zM4 20h7v-2H4v2zm9 0h7v-2h-7v2z" /></svg>
                            <span class="hidden sm:inline">Grille</span>
                        </button>
                        <button type="button" class="inline-flex h-9 items-center gap-1.5 rounded-md px-3 text-sm transition" :class="viewMode === 'list' ? 'bg-ed-primary text-white' : 'text-gray-500 hover:text-gray-800'" @click="setViewMode('list')">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                            <span class="hidden sm:inline">Liste</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-4">
                <!-- Résultats -->
                <div class="order-2 lg:order-1 lg:col-span-3">
                    <div v-if="theses?.data?.length">
                        <!-- Grille -->
                        <div v-if="viewMode === 'grid'" class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                            <Link
                                v-for="these in theses.data"
                                :key="these.id"
                                :href="route('theses.show', these.uuid)"
                                class="group flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white transition hover:border-ed-primary/40 hover:shadow-sm"
                            >
                                <div class="flex items-start justify-between gap-3 px-5 pt-4">
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <span v-if="these.specialite" class="rounded-full bg-ed-primary/10 px-2 py-0.5 text-[11px] font-medium text-ed-primary">{{ these.specialite.nom || 'Spécialité' }}</span>
                                        <span v-if="these.ead" class="rounded-full bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-700">{{ (these.ead.sigle || these.ead.nom || 'EAD').slice(0, 8) }}</span>
                                    </div>
                                    <span class="inline-flex flex-none items-center rounded-full border px-2.5 py-1 text-[11px] font-medium" :class="these.statut_badge_classes">{{ these.statut_label }}</span>
                                </div>

                                <div class="flex flex-1 flex-col gap-3 px-5 py-3">
                                    <h2 class="line-clamp-3 text-sm font-semibold text-gray-900 transition-colors group-hover:text-ed-primary">{{ these.sujet_these }}</h2>

                                    <div v-if="these.doctorant" class="flex items-center gap-2 text-xs text-gray-600">
                                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-gray-100 text-[11px] font-semibold text-gray-700">{{ these.doctorant.initials }}</span>
                                        <span class="font-medium text-gray-800">{{ these.doctorant.name }}</span>
                                    </div>

                                    <p v-if="these.resume" class="line-clamp-3 text-xs leading-relaxed text-gray-600">{{ cleanText(these.resume) }}</p>
                                    <p v-else class="text-xs italic text-gray-400">Résumé non renseigné.</p>
                                </div>

                                <div class="mt-auto flex items-center justify-between border-t border-gray-100 bg-gray-50/60 px-5 py-3 text-[11px] text-gray-500">
                                    <span v-if="these.date_debut" class="inline-flex items-center gap-1.5">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        Début {{ these.date_debut }}
                                    </span>
                                    <span v-else></span>
                                    <span class="inline-flex items-center gap-1 font-semibold text-ed-primary">
                                        Voir la fiche
                                        <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5 motion-reduce:transform-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                    </span>
                                </div>
                            </Link>
                        </div>

                        <!-- Liste -->
                        <div v-else class="space-y-4">
                            <Link
                                v-for="these in theses.data"
                                :key="these.id"
                                :href="route('theses.show', these.uuid)"
                                class="group flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white transition hover:border-ed-primary/40 hover:shadow-sm md:flex-row"
                            >
                                <div class="flex flex-col justify-between gap-3 border-b border-gray-100 bg-gray-50/70 p-4 md:w-56 md:border-b-0 md:border-r">
                                    <span class="inline-flex w-fit items-center rounded-full border px-2.5 py-1 text-[11px] font-medium" :class="these.statut_badge_classes">{{ these.statut_label }}</span>
                                    <div class="space-y-1 text-[11px] text-gray-500">
                                        <p v-if="these.date_debut">Début : {{ these.date_debut }}</p>
                                        <p v-if="these.date_prevue_fin">Fin prévue : {{ these.date_prevue_fin }}</p>
                                        <p v-if="these.date_publication">Soutenue : {{ these.date_publication }}</p>
                                    </div>
                                </div>

                                <div class="flex-1 p-5">
                                    <div class="mb-3 flex flex-wrap items-center gap-1.5">
                                        <span v-if="these.specialite" class="rounded-full bg-ed-primary/10 px-2 py-0.5 text-[11px] font-medium text-ed-primary">{{ these.specialite.nom || 'Spécialité' }}</span>
                                        <span v-if="these.ead" class="rounded-full bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-700">{{ these.ead.sigle || these.ead.nom || 'EAD' }}</span>
                                    </div>

                                    <h2 class="mb-2 line-clamp-2 text-base font-semibold text-gray-900 transition-colors group-hover:text-ed-primary md:text-lg">{{ these.sujet_these }}</h2>

                                    <p v-if="these.resume" class="mb-3 line-clamp-3 text-xs leading-relaxed text-gray-600">{{ cleanText(these.resume) }}</p>

                                    <div v-if="these.doctorant" class="flex items-center gap-2 text-xs text-gray-600">
                                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-gray-100 text-[11px] font-semibold text-gray-700">{{ these.doctorant.initials }}</span>
                                        <span class="font-medium text-gray-800">{{ these.doctorant.name }}</span>
                                    </div>
                                </div>
                            </Link>
                        </div>

                        <div class="mt-8">
                            <Pagination v-if="theses?.links" :links="theses.links" />
                        </div>
                    </div>

                    <div v-else class="rounded-xl border border-dashed border-gray-300 bg-white p-12 text-center">
                        <p class="text-sm font-medium text-gray-600">Aucune thèse trouvée.</p>
                        <p class="mt-1 text-xs text-gray-500">Modifiez vos critères de recherche.</p>
                    </div>
                </div>

                <!-- Filtre statut -->
                <aside class="order-1 lg:order-2 lg:col-span-1">
                    <div class="rounded-xl border border-gray-200 bg-white p-5">
                        <h3 class="mb-3 text-sm font-semibold text-gray-900">Filtrer par statut</h3>
                        <div class="space-y-2">
                            <button
                                v-for="item in statutOptions"
                                :key="item.value"
                                type="button"
                                class="flex w-full items-center gap-2.5 rounded-lg border px-3 py-2 text-left text-sm transition"
                                :class="statut === item.value ? 'border-ed-primary/40 bg-ed-primary/5 font-semibold text-ed-primary' : 'border-gray-200 text-gray-700 hover:bg-gray-50'"
                                @click="setStatut(item.value)"
                            >
                                <span class="h-2 w-2 flex-none rounded-full" :class="item.dot"></span>
                                <span>{{ item.label }}</span>
                            </button>
                        </div>
                    </div>
                </aside>
            </div>
        </Container>
    </section>
</template>
