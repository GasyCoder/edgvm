<script setup>
import { computed, ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
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

    <div>
        <section class="relative gradient-primary pt-20 sm:pt-24 md:pt-28 pb-12 sm:pb-16 md:pb-20 overflow-hidden">
            <div class="absolute inset-0 opacity-20 pointer-events-none">
                <div class="absolute -top-10 -right-10 w-72 h-72 bg-white/40 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 -left-16 w-80 h-80 bg-ed-yellow/40 rounded-full blur-3xl"></div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <nav class="mb-6">
                    <ol class="flex items-center gap-2 text-xs text-white/70">
                        <li>
                            <Link :href="route('home')" class="hover:text-white transition">
                                Accueil
                            </Link>
                        </li>
                        <li>></li>
                        <li class="text-white font-semibold">Doctorants</li>
                    </ol>
                </nav>

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-8">
                    <div class="space-y-3">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-black/10 border border-white/20">
                            <span class="w-2 h-2 rounded-full bg-ed-yellow animate-pulse"></span>
                            <span class="text-xs uppercase tracking-[0.18em] text-white/80 font-semibold">
                                Ecole Doctorale EDGVM
                            </span>
                        </div>

                        <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-white text-shadow">
                            Nos doctorants
                        </h1>

                        <p class="text-xs sm:text-sm md:text-base text-white/90">
                            Affichage de
                            <span class="font-semibold">{{ doctorants?.meta?.from || 0 }} - {{ doctorants?.meta?.to || 0 }}</span>
                            sur
                            <span class="font-semibold">{{ doctorants?.meta?.total || 0 }}</span>
                            doctorant(s) ayant une these.
                        </p>
                    </div>

                    <div class="flex gap-2 sm:gap-4 md:gap-5">
                        <div class="glass rounded-xl sm:rounded-2xl px-2.5 sm:px-5 py-2 sm:py-4 border border-white/20 flex-1 sm:flex-none sm:min-w-[130px]">
                            <p class="text-[10px] sm:text-xs text-white/80 uppercase tracking-wide flex items-center gap-1">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-ed-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="hidden xs:inline">Total</span> inscrits
                            </p>
                            <p class="text-lg sm:text-2xl font-bold text-white mt-0.5 sm:mt-1">
                                {{ stats?.total || 0 }}
                            </p>
                        </div>

                        <div class="glass rounded-xl sm:rounded-2xl px-2.5 sm:px-5 py-2 sm:py-4 border border-white/20 flex-1 sm:flex-none sm:min-w-[130px]">
                            <p class="text-[10px] sm:text-xs text-white/80 uppercase tracking-wide flex items-center gap-1">
                                <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-emerald-400 rounded-full"></span>
                                Actifs
                            </p>
                            <p class="text-lg sm:text-2xl font-bold text-white mt-0.5 sm:mt-1">
                                {{ stats?.actifs || 0 }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-transparent -mt-6 md:-mt-8 mb-4 relative z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white/95 backdrop-blur-lg shadow-lg rounded-2xl border border-gray-100 px-3 py-4 sm:px-4 sm:py-5 md:px-6 md:py-6">
                    <div class="flex flex-col gap-4">
                        <div>
                            <label class="text-xs md:text-sm font-semibold text-gray-600 mb-1.5 block">
                                Rechercher un doctorant
                            </label>
                            <div class="relative">
                                <input
                                    v-model="search"
                                    type="text"
                                    placeholder="Nom, prenom ou matricule..."
                                    class="w-full pl-11 pr-4 py-3 text-sm md:text-base rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-ed-primary/60 focus:border-transparent placeholder:text-gray-400 transition"
                                >
                                <svg class="absolute left-3 top-2.5 md:top-2.5 w-5 h-5 md:w-6 md:h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 21l-5.2-5.2M10.5 18a7.5 7.5 0 110-15 7.5 7.5 0 010 15z"/>
                                </svg>
                            </div>
                            <p v-if="search" class="mt-1 text-[11px] text-gray-500">
                                Resultats pour : <span class="font-semibold text-gray-700">"{{ search }}"</span>
                            </p>
                        </div>

                        <div class="grid grid-cols-2 sm:flex sm:flex-wrap md:flex-nowrap items-end gap-2 sm:gap-3 md:gap-4">
                            <div class="col-span-1">
                                <label class="text-[10px] sm:text-[11px] uppercase tracking-wide text-gray-500 font-semibold mb-1 block">
                                    Statut
                                </label>
                                <select
                                    v-model="filterStatut"
                                    class="px-2 sm:px-3 py-2 sm:py-2.5 text-xs sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-full sm:w-auto"
                                >
                                    <option value="">Tous statuts</option>
                                    <option value="en_cours">En cours</option>
                                    <option value="preparation">Preparation</option>
                                    <option value="redaction">Redaction</option>
                                    <option value="soutenue">Soutenue</option>
                                    <option value="suspendue">Suspendue</option>
                                    <option value="abandonnee">Abandonnee</option>
                                </select>
                            </div>

                            <div class="col-span-1">
                                <label class="text-[10px] sm:text-[11px] uppercase tracking-wide text-gray-500 font-semibold mb-1 block">
                                    EAD
                                </label>
                                <select
                                    v-model="filterEad"
                                    class="px-2 sm:px-3 py-2 sm:py-2.5 text-xs sm:text-sm rounded-lg border border-gray-200 bg-white focus:ring-2 focus:ring-ed-primary/60 focus:border-transparent w-full sm:w-auto sm:min-w-[170px]"
                                >
                                    <option value="">Toutes EAD</option>
                                    <option v-for="ead in eads" :key="ead.id" :value="ead.id">
                                        {{ ead.nom }}
                                    </option>
                                </select>
                            </div>

                            <div class="col-span-1">
                                <label class="text-[10px] sm:text-[11px] uppercase tracking-wide text-gray-500 font-semibold mb-1 block">
                                    Vue
                                </label>
                                <div class="flex bg-gray-100 rounded-full p-0.5 sm:p-1 w-fit">
                                    <button
                                        type="button"
                                        class="p-2 sm:px-3 sm:py-1.5 rounded-full text-xs md:text-sm flex items-center gap-1 transition min-w-[36px] sm:min-w-0 justify-center"
                                        :class="viewMode === 'grid' ? 'bg-white shadow-sm text-ed-primary' : 'text-gray-600'"
                                        @click="setViewMode('grid')"
                                    >
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6
                                                    a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16
                                                    a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16
                                                    a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                        </svg>
                                        <span class="hidden md:inline">Grille</span>
                                    </button>
                                    <button
                                        type="button"
                                        class="p-2 sm:px-3 sm:py-1.5 rounded-full text-xs md:text-sm flex items-center gap-1 transition min-w-[36px] sm:min-w-0 justify-center"
                                        :class="viewMode === 'list' ? 'bg-white shadow-sm text-ed-primary' : 'text-gray-600'"
                                        @click="setViewMode('list')"
                                    >
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 6h16M4 12h16M4 18h16"/>
                                        </svg>
                                        <span class="hidden md:inline">Liste</span>
                                    </button>
                                </div>
                            </div>

                            <div v-if="search || filterStatut || filterEad" class="col-span-1 sm:ml-auto">
                                <label class="text-[10px] sm:text-[11px] uppercase tracking-wide text-transparent font-semibold mb-1 block sm:hidden">&nbsp;</label>
                                <button
                                    type="button"
                                    class="inline-flex items-center justify-center gap-1 px-2 sm:px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs sm:text-sm font-semibold rounded-lg transition w-full sm:w-auto"
                                    @click="resetFilters"
                                >
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span class="hidden sm:inline">Reinitialiser</span>
                                    <span class="sm:hidden">Reset</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-6 sm:py-8 bg-gradient-to-b from-gray-50 to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div v-if="!doctorants?.data?.length" class="text-center py-10 sm:py-16 px-4">
                    <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gray-100 mb-3 sm:mb-4">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2
                                    c0-.656-.126-1.283-.356-1.857M7 20H2v-2
                                    a3 3 0 015.356-1.857M7 20v-2
                                    c0-.656.126-1.283.356-1.857m0 0
                                    a5.002 5.002 0 019.288 0M15 7
                                    a3 3 0 11-6 0 3 3 0 016 0zm6 3
                                    a2 2 0 11-4 0 2 2 0 014 0zM7 10
                                    a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Aucun doctorant trouve</h3>
                    <p class="text-sm sm:text-base text-gray-600 mb-4">Modifiez vos criteres ou reinitialiser les filtres.</p>
                    <button type="button" class="btn-secondary text-xs sm:text-sm" @click="resetFilters">
                        Reinitialiser
                    </button>
                </div>

                <div v-else>
                    <div v-if="viewMode === 'grid'" class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4">
                        <Link
                            v-for="doctorant in doctorants.data"
                            :key="doctorant.id"
                            :href="route('doctorants.show', doctorant.id)"
                            class="card card-hover group overflow-hidden border border-gray-100 hover:border-ed-primary/40"
                        >
                            <div class="relative bg-gradient-to-br from-ed-primary via-ed-secondary to-emerald-700 h-16 sm:h-20 flex items-center justify-center">
                                <div class="w-11 h-11 sm:w-14 sm:h-14 bg-white rounded-full flex items-center justify-center shadow-glow">
                                    <span class="text-base sm:text-xl font-bold text-ed-primary">
                                        {{ doctorant.initials }}
                                    </span>
                                </div>

                                <div v-if="doctorant.badge" class="absolute top-1.5 right-1.5 sm:top-2 sm:right-2">
                                    <span class="px-1.5 sm:px-2 py-0.5 rounded-full text-[10px] sm:text-[11px] font-semibold text-white shadow-sm" :class="doctorant.badge.class">
                                        {{ doctorant.badge.text }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-3 sm:p-4">
                                <h3 class="font-semibold text-gray-900 text-xs sm:text-sm mb-0.5 sm:mb-1 line-clamp-1 group-hover:text-ed-primary transition-colors">
                                    {{ doctorant.name || 'Non renseigne' }}
                                </h3>

                                <p v-if="doctorant.matricule" class="text-[10px] sm:text-xs text-gray-500 mb-1.5 sm:mb-2">
                                    {{ doctorant.matricule }}
                                </p>

                                <div v-if="doctorant.these_principale" class="space-y-1 sm:space-y-1.5 text-[10px] sm:text-xs">
                                    <div v-if="doctorant.these_principale.specialite" class="flex items-start gap-1 sm:gap-1.5">
                                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-ed-primary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13
                                                    C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13
                                                    C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13
                                                    C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        <span class="text-gray-600 line-clamp-1">
                                            {{ doctorant.these_principale.specialite.nom }}
                                        </span>
                                    </div>

                                    <div v-if="doctorant.these_principale.ead" class="flex items-start gap-1 sm:gap-1.5">
                                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-purple-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5
                                                    m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1
                                                    m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        <span class="text-gray-600 line-clamp-1">
                                            {{ doctorant.these_principale.ead.nom }}
                                        </span>
                                    </div>
                                </div>

                                <p v-else class="text-[10px] sm:text-xs text-gray-400 italic mt-1">Aucune these</p>

                                <div class="mt-2 sm:mt-3 pt-2 sm:pt-3 border-t border-gray-100 flex justify-end">
                                    <span class="inline-flex items-center gap-1 sm:gap-1.5 text-[10px] sm:text-[11px] font-semibold text-ed-primary group-hover:gap-2 transition-all">
                                        Voir
                                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </Link>
                    </div>

                    <div v-else class="space-y-2 sm:space-y-3">
                        <Link
                            v-for="doctorant in doctorants.data"
                            :key="doctorant.id"
                            :href="route('doctorants.show', doctorant.id)"
                            class="card card-hover group flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 p-3 sm:p-4 border border-gray-100 hover:border-ed-primary/40"
                        >
                            <div class="flex items-center gap-3 sm:gap-0">
                                <div class="relative flex-shrink-0">
                                    <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-gradient-to-br from-ed-primary to-ed-secondary flex items-center justify-center text-white text-sm sm:text-base font-bold shadow-glow">
                                        {{ doctorant.initials }}
                                    </div>

                                    <span v-if="doctorant.badge" class="absolute -bottom-1 -right-1 px-1.5 sm:px-2 py-0.5 text-[10px] sm:text-[11px] rounded-full text-white font-semibold border-2 border-white" :class="doctorant.badge.class">
                                        {{ doctorant.badge.text }}
                                    </span>
                                </div>

                                <div class="sm:hidden min-w-0 flex-1">
                                    <h3 class="font-semibold text-gray-900 text-sm group-hover:text-ed-primary transition-colors line-clamp-1">
                                        {{ doctorant.name || 'Non renseigne' }}
                                    </h3>
                                    <p v-if="doctorant.matricule" class="text-[11px] text-gray-500">
                                        {{ doctorant.matricule }}
                                    </p>
                                </div>

                                <svg class="sm:hidden w-4 h-4 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>

                            <div class="flex-1 min-w-0 hidden sm:block">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-2">
                                    <div class="min-w-0">
                                        <h3 class="font-semibold text-gray-900 group-hover:text-ed-primary transition-colors">
                                            {{ doctorant.name || 'Non renseigne' }}
                                        </h3>
                                        <p v-if="doctorant.matricule" class="text-xs text-gray-500 mt-0.5">
                                            Matricule : {{ doctorant.matricule }}
                                        </p>

                                        <p v-if="doctorant.these_principale" class="mt-2 text-sm text-gray-600 line-clamp-2">
                                            {{ doctorant.these_principale.sujet_these }}
                                        </p>
                                        <p v-else class="mt-2 text-sm text-gray-400 italic">
                                            Aucune these renseignee
                                        </p>
                                    </div>

                                    <div v-if="doctorant.these_principale" class="flex flex-wrap md:flex-col items-start md:items-end gap-1 md:gap-2 flex-shrink-0">
                                        <span v-if="doctorant.these_principale.specialite" class="badge badge-primary text-[11px]">
                                            {{ doctorant.these_principale.specialite.nom }}
                                        </span>
                                        <span v-if="doctorant.these_principale.ead" class="badge bg-purple-50 text-purple-700 border border-purple-100 text-[11px]">
                                            {{ doctorant.these_principale.ead.nom }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div v-if="doctorant.these_principale" class="sm:hidden flex flex-wrap gap-1.5">
                                <span v-if="doctorant.these_principale.specialite" class="badge badge-primary text-[10px] px-2 py-0.5">
                                    {{ doctorant.these_principale.specialite.nom }}
                                </span>
                                <span v-if="doctorant.these_principale.ead" class="badge bg-purple-50 text-purple-700 border border-purple-100 text-[10px] px-2 py-0.5">
                                    {{ doctorant.these_principale.ead.nom }}
                                </span>
                            </div>

                            <div class="hidden sm:block flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-ed-primary group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </Link>
                    </div>

                    <div class="mt-6 sm:mt-8">
                        <Pagination v-if="doctorants?.links" :links="doctorants.links" />
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
