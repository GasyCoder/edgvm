<script setup>
import { computed, ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import Container from '@/Components/Frontend/UI/Container.vue';
import Pagination from '@/Components/Common/Pagination.vue';

const props = defineProps({
    filters: Object,
    doctorants: Object,
    eads: Array,
    stats: Object,
});

const search = ref(props.filters?.search || '');
const filterStatut = ref(props.filters?.filterStatut || '');
const filterEad = ref(props.filters?.filterEad || '');
const viewMode = computed(() => props.filters?.viewMode || 'grid');

const hasActiveFilters = computed(() => Boolean(search.value || filterStatut.value || filterEad.value));

let searchTimeout = null;

const updateFilters = (values) => {
    router.get(route('doctorants.index'), {
        search: search.value,
        filterStatut: filterStatut.value,
        filterEad: filterEad.value,
        viewMode: viewMode.value,
        ...values,
    }, {
        preserveScroll: true,
        preserveState: true,
    });
};

const resetFilters = () => {
    search.value = '';
    filterStatut.value = '';
    filterEad.value = '';

    router.get(route('doctorants.index'), { viewMode: viewMode.value }, { preserveScroll: true, preserveState: true });
};

watch(search, (value) => {
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }

    searchTimeout = setTimeout(() => {
        updateFilters({ search: value, page: 1 });
    }, 300);
});

watch(filterStatut, (value) => {
    updateFilters({ filterStatut: value, page: 1 });
});

watch(filterEad, (value) => {
    updateFilters({ filterEad: value, page: 1 });
});

const setViewMode = (mode) => {
    updateFilters({ viewMode: mode, page: 1 });
};

defineOptions({
    layout: FrontendLayout,
});
</script>

<template>
    <Head title="Doctorants" />

    <!-- Hero -->
    <section class="mt-16 bg-ed-teal-dark py-12 text-white sm:mt-20 sm:py-16">
        <Container>
            <div class="flex flex-col gap-8 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-ed-yellow">École Doctorale EDGVM</p>
                    <h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Nos doctorants</h1>
                    <p class="mt-3 text-sm text-white/80">
                        Affichage de
                        <span class="font-semibold text-white">{{ doctorants?.meta?.from || 0 }}–{{ doctorants?.meta?.to || 0 }}</span>
                        sur
                        <span class="font-semibold text-white">{{ doctorants?.meta?.total || 0 }}</span>
                        doctorant(s).
                    </p>

                    <nav class="mt-5 flex items-center gap-2 text-sm text-white/70" aria-label="Fil d'Ariane">
                        <Link :href="route('home')" class="transition-colors hover:text-ed-yellow">Accueil</Link>
                        <span aria-hidden="true">/</span>
                        <span class="font-semibold text-white">Doctorants</span>
                    </nav>
                </div>

                <div class="flex gap-4">
                    <div class="rounded-lg border border-white/15 bg-white/5 px-5 py-3">
                        <p class="text-2xl font-semibold tabular-nums">{{ stats?.total || 0 }}</p>
                        <p class="text-xs text-white/70">Total inscrits</p>
                    </div>
                    <div class="rounded-lg border border-white/15 bg-white/5 px-5 py-3">
                        <p class="text-2xl font-semibold tabular-nums">{{ stats?.actifs || 0 }}</p>
                        <p class="text-xs text-white/70">Actifs</p>
                    </div>
                </div>
            </div>
        </Container>
    </section>

    <section class="bg-gray-50 py-10 md:py-14">
        <Container>
            <!-- Filtres -->
            <div class="rounded-xl border border-gray-200 bg-white p-4 sm:p-5">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end">
                    <div class="flex-1">
                        <label for="search-doctorant" class="mb-1.5 block text-xs font-semibold text-gray-600">Rechercher</label>
                        <div class="relative">
                            <input
                                id="search-doctorant"
                                v-model="search"
                                type="text"
                                placeholder="Nom, prénom ou matricule…"
                                class="w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-10 pr-4 text-sm placeholder:text-gray-400 focus:border-transparent focus:ring-2 focus:ring-ed-primary/40"
                            >
                            <svg class="pointer-events-none absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.2-5.2M10.5 18a7.5 7.5 0 110-15 7.5 7.5 0 010 15z" />
                            </svg>
                        </div>
                    </div>

                    <div>
                        <label for="filter-statut" class="mb-1.5 block text-xs font-semibold text-gray-600">Statut</label>
                        <select id="filter-statut" v-model="filterStatut" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-transparent focus:ring-2 focus:ring-ed-primary/40 lg:w-auto">
                            <option value="">Tous statuts</option>
                            <option value="en_cours">En cours</option>
                            <option value="preparation">Préparation</option>
                            <option value="redaction">Rédaction</option>
                            <option value="soutenue">Soutenue</option>
                            <option value="suspendue">Suspendue</option>
                            <option value="abandonnee">Abandonnée</option>
                        </select>
                    </div>

                    <div>
                        <label for="filter-ead" class="mb-1.5 block text-xs font-semibold text-gray-600">Équipe d'accueil</label>
                        <select id="filter-ead" v-model="filterEad" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-transparent focus:ring-2 focus:ring-ed-primary/40 lg:w-auto lg:min-w-[180px]">
                            <option value="">Toutes les équipes</option>
                            <option v-for="ead in eads" :key="ead.id" :value="ead.id">{{ ead.nom }}</option>
                        </select>
                    </div>

                    <div>
                        <span class="mb-1.5 block text-xs font-semibold text-gray-600">Affichage</span>
                        <div class="inline-flex rounded-lg border border-gray-300 p-0.5">
                            <button
                                type="button"
                                class="inline-flex h-9 w-9 items-center justify-center rounded-md transition"
                                :class="viewMode === 'grid' ? 'bg-ed-primary text-white' : 'text-gray-500 hover:text-gray-800'"
                                aria-label="Vue grille"
                                @click="setViewMode('grid')"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h7V4H4v2zm9 0h7V4h-7v2zM4 13h7v-2H4v2zm9 0h7v-2h-7v2zM4 20h7v-2H4v2zm9 0h7v-2h-7v2z" />
                                </svg>
                            </button>
                            <button
                                type="button"
                                class="inline-flex h-9 w-9 items-center justify-center rounded-md transition"
                                :class="viewMode === 'list' ? 'bg-ed-primary text-white' : 'text-gray-500 hover:text-gray-800'"
                                aria-label="Vue liste"
                                @click="setViewMode('list')"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div v-if="hasActiveFilters">
                        <button
                            type="button"
                            class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                            @click="resetFilters"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Réinitialiser
                        </button>
                    </div>
                </div>
            </div>

            <!-- Résultats -->
            <div class="mt-8">
                <div v-if="!doctorants?.data?.length" class="rounded-xl border border-dashed border-gray-300 bg-white p-12 text-center">
                    <p class="text-sm font-medium text-gray-600">Aucun doctorant trouvé.</p>
                    <button
                        v-if="hasActiveFilters"
                        type="button"
                        class="mt-4 inline-flex items-center rounded-lg bg-ed-primary px-4 py-2 text-sm font-semibold text-white transition hover:bg-ed-secondary"
                        @click="resetFilters"
                    >
                        Réinitialiser les filtres
                    </button>
                </div>

                <div v-else>
                    <!-- Grille -->
                    <div v-if="viewMode === 'grid'" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        <Link
                            v-for="doctorant in doctorants.data"
                            :key="doctorant.id"
                            :href="route('doctorants.show', doctorant.uuid)"
                            class="group flex flex-col rounded-xl border border-gray-200 bg-white p-4 transition hover:border-ed-primary/40 hover:shadow-sm"
                        >
                            <div class="flex items-center gap-3">
                                <span class="flex h-11 w-11 flex-none items-center justify-center rounded-full bg-ed-primary text-sm font-semibold text-white">
                                    {{ doctorant.initials }}
                                </span>
                                <div class="min-w-0 flex-1">
                                    <h3 class="truncate text-sm font-semibold text-gray-900 transition-colors group-hover:text-ed-primary">
                                        {{ doctorant.name || 'Non renseigné' }}
                                    </h3>
                                    <p v-if="doctorant.matricule" class="truncate text-xs text-gray-500">{{ doctorant.matricule }}</p>
                                </div>
                                <span v-if="doctorant.badge" class="flex-none rounded-full px-2 py-0.5 text-[10px] font-semibold text-white" :class="doctorant.badge.class">
                                    {{ doctorant.badge.text }}
                                </span>
                            </div>

                            <div v-if="doctorant.these_principale" class="mt-3 space-y-1 text-xs text-gray-600">
                                <p v-if="doctorant.these_principale.specialite" class="line-clamp-1">
                                    <span class="text-gray-400">Spécialité :</span> {{ doctorant.these_principale.specialite.nom }}
                                </p>
                                <p v-if="doctorant.these_principale.ead" class="line-clamp-1">
                                    <span class="text-gray-400">Équipe :</span> {{ doctorant.these_principale.ead.nom }}
                                </p>
                            </div>
                            <p v-else class="mt-3 text-xs italic text-gray-400">Aucune thèse renseignée</p>

                            <div class="mt-3 flex justify-end border-t border-gray-100 pt-3">
                                <span class="inline-flex items-center gap-1.5 text-[12px] font-semibold text-ed-primary">
                                    Voir le profil
                                    <svg class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5 motion-reduce:transform-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            </div>
                        </Link>
                    </div>

                    <!-- Liste -->
                    <div v-else class="overflow-hidden rounded-xl border border-gray-200 bg-white">
                        <Link
                            v-for="doctorant in doctorants.data"
                            :key="doctorant.id"
                            :href="route('doctorants.show', doctorant.uuid)"
                            class="group flex items-center gap-4 border-b border-gray-100 p-4 transition last:border-b-0 hover:bg-gray-50"
                        >
                            <span class="flex h-12 w-12 flex-none items-center justify-center rounded-full bg-ed-primary text-base font-semibold text-white">
                                {{ doctorant.initials }}
                            </span>

                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <h3 class="truncate font-semibold text-gray-900 transition-colors group-hover:text-ed-primary">
                                        {{ doctorant.name || 'Non renseigné' }}
                                    </h3>
                                    <span v-if="doctorant.badge" class="flex-none rounded-full px-2 py-0.5 text-[10px] font-semibold text-white" :class="doctorant.badge.class">
                                        {{ doctorant.badge.text }}
                                    </span>
                                </div>
                                <p v-if="doctorant.matricule" class="text-xs text-gray-500">Matricule : {{ doctorant.matricule }}</p>
                                <p v-if="doctorant.these_principale" class="mt-1 line-clamp-1 text-sm text-gray-600">
                                    {{ doctorant.these_principale.sujet_these }}
                                </p>
                                <p v-else class="mt-1 text-sm italic text-gray-400">Aucune thèse renseignée</p>
                            </div>

                            <div class="hidden flex-none flex-col items-end gap-1 text-xs text-gray-500 sm:flex">
                                <span v-if="doctorant.these_principale?.specialite" class="rounded-full bg-ed-primary/10 px-2 py-0.5 font-medium text-ed-primary">
                                    {{ doctorant.these_principale.specialite.nom }}
                                </span>
                                <span v-if="doctorant.these_principale?.ead" class="rounded-full bg-gray-100 px-2 py-0.5 font-medium text-gray-700">
                                    {{ doctorant.these_principale.ead.nom }}
                                </span>
                            </div>

                            <svg class="h-5 w-5 flex-none text-gray-300 transition group-hover:translate-x-0.5 group-hover:text-ed-primary motion-reduce:transform-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>
                    </div>

                    <div class="mt-8">
                        <Pagination v-if="doctorants?.links" :links="doctorants.links" />
                    </div>
                </div>
            </div>
        </Container>
    </section>
</template>
