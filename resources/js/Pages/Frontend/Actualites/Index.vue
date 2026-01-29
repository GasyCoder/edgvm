<script setup>
import { computed, ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import Pagination from '@/Components/Common/Pagination.vue';

const props = defineProps({
    filters: Object,
    actualites: Object,
    categories: Array,
    tags: Array,
});

const search = ref(props.filters?.search || '');
const viewMode = computed(() => props.filters?.view || 'grid');

let searchTimeout = null;

const activeCategory = computed(() => {
    if (!props.filters?.category) {
        return null;
    }

    return props.categories?.find((item) => item.slug === props.filters.category) || null;
});

const activeTag = computed(() => {
    if (!props.filters?.tag) {
        return null;
    }

    return props.tags?.find((item) => item.slug === props.filters.tag) || null;
});

const updateFilters = (values) => {
    router.get(route('actualites.index'), {
        category: props.filters?.category || '',
        tag: props.filters?.tag || '',
        search: search.value || '',
        view: viewMode.value,
        ...values,
    }, {
        preserveScroll: true,
        preserveState: true,
    });
};

const clearFilters = () => {
    search.value = '';
    router.get(route('actualites.index'), {
        view: viewMode.value,
    }, {
        preserveScroll: true,
        preserveState: true,
    });
};

const setView = (mode) => {
    updateFilters({ view: mode, page: 1 });
};

const setCategory = (slug) => {
    updateFilters({ category: slug, page: 1 });
};

const setTag = (slug) => {
    updateFilters({ tag: slug, page: 1 });
};

watch(search, (value) => {
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }

    searchTimeout = setTimeout(() => {
        updateFilters({ search: value, page: 1 });
    }, 300);
});

defineOptions({
    layout: FrontendLayout,
});
</script>

<template>
    <Head title="Actualites" />

    <div>
        <section class="relative bg-gradient-to-br from-ed-primary via-ed-secondary to-ed-primary pt-24 pb-10 sm:pt-28 sm:pb-10 overflow-hidden">
            <div class="absolute inset-0 opacity-15 pointer-events-none">
                <div class="absolute -top-10 right-0 w-40 h-40 sm:w-56 sm:h-56 bg-white rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 -left-10 w-32 h-32 sm:w-44 sm:h-44 bg-ed-yellow rounded-full blur-3xl"></div>
            </div>

            <div class="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-black/10 to-transparent pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="flex flex-col items-center text-center text-white gap-4">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 backdrop-blur">
                        <span class="w-2 h-2 rounded-full bg-ed-yellow"></span>
                        <span class="text-xs font-semibold uppercase tracking-[0.16em]">
                            Espace actualites EDGVM
                        </span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-black leading-tight">
                         Actualites
                    </h1>

                    <p class="text-base sm:text-lg md:text-xl text-white/90 max-w-2xl">
                        Restez informe des dernieres nouvelles, evenements et annonces de l'Ecole Doctorale EDGVM.
                    </p>

                    <div class="flex items-center gap-2 text-sm text-white/80 mt-1">
                        <Link :href="route('home')" class="hover:text-ed-yellow transition-colors">
                            Accueil
                        </Link>
                        <span class="opacity-60">/</span>
                        <span class="font-semibold">Actualites</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-gray-50/95 backdrop-blur border-b sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-2 flex-wrap flex-1">
                        <template v-if="filters?.category || filters?.tag || filters?.search">
                            <span class="text-sm text-gray-600 font-medium">Filtres :</span>

                            <span v-if="filters?.category" class="inline-flex items-center gap-1 px-3 py-1 bg-ed-primary text-white text-sm rounded-full">
                                {{ activeCategory?.nom || filters.category }}
                                <button type="button" class="ml-1 hover:text-ed-yellow" @click="setCategory('')">x</button>
                            </span>

                            <span v-if="filters?.tag" class="inline-flex items-center gap-1 px-3 py-1 bg-purple-600 text-white text-sm rounded-full">
                                {{ activeTag?.nom || filters.tag }}
                                <button type="button" class="ml-1 hover:text-purple-200" @click="setTag('')">x</button>
                            </span>

                            <span v-if="filters?.search" class="inline-flex items-center gap-1 px-3 py-1 bg-gray-600 text-white text-sm rounded-full">
                                "{{ filters.search }}"
                                <button type="button" class="ml-1 hover:text-gray-200" @click="search = ''">x</button>
                            </span>

                            <button type="button" class="text-sm text-red-600 hover:text-red-800 font-medium ml-2" @click="clearFilters">
                                Tout effacer
                            </button>
                        </template>
                        <span v-else class="text-sm text-gray-500">
                            {{ actualites?.meta?.total || 0 }} actualite(s) publiees
                        </span>
                    </div>

                    <div class="flex items-center gap-2 bg-white rounded-full px-1.5 py-1 shadow-sm border border-gray-200">
                        <button
                            type="button"
                            class="p-1.5 rounded-full transition"
                            :class="viewMode === 'grid' ? 'bg-ed-primary text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100'"
                            title="Vue grille"
                            @click="setView('grid')"
                        >
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </button>
                        <button
                            type="button"
                            class="p-1.5 rounded-full transition"
                            :class="viewMode === 'list' ? 'bg-ed-primary text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100'"
                            title="Vue liste"
                            @click="setView('list')"
                        >
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-14 bg-gradient-to-b from-gray-50 via-white to-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8">
                    <aside class="lg:col-span-1 lg:order-2 space-y-4 sm:space-y-6">
                        <div class="bg-white/90 backdrop-blur rounded-2xl p-5 border border-gray-200 shadow-sm">
                            <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <span class="inline-flex w-5 h-5 items-center justify-center rounded-full bg-ed-primary/10 text-ed-primary">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>
                                    </svg>
                                </span>
                                <span>Rechercher</span>
                            </h3>
                            <div class="space-y-2">
                                <input
                                    v-model="search"
                                    type="text"
                                    placeholder="Mot-cle, auteur, thematique..."
                                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-ed-primary focus:border-transparent"
                                >
                                <p class="text-[11px] text-gray-400">
                                    Tapez au moins 2 caracteres pour filtrer les actualites.
                                </p>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm">
                            <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <span class="inline-flex w-5 h-5 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 6h16M4 10h10M4 14h16M4 18h10"/>
                                    </svg>
                                </span>
                                <span>Categories</span>
                            </h3>
                            <ul class="space-y-1.5">
                                <li v-for="cat in categories" :key="cat.id">
                                    <button
                                        type="button"
                                        class="w-full text-left px-3 py-2 rounded-lg transition group"
                                        :class="filters?.category === cat.slug
                                            ? 'bg-ed-primary/5 border border-ed-primary/60 shadow-sm'
                                            : 'bg-gray-50 hover:bg-gray-100 border border-transparent'"
                                        @click="setCategory(cat.slug)"
                                    >
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-2">
                                                <span class="w-2.5 h-2.5 rounded-full" :style="{ backgroundColor: cat.couleur }"></span>
                                                <span class="text-xs font-medium" :class="filters?.category === cat.slug ? 'text-ed-primary' : 'text-gray-700'">
                                                    {{ cat.nom }}
                                                </span>
                                            </span>
                                            <span class="text-[11px] text-gray-500">
                                                {{ cat.actualites_count }}
                                            </span>
                                        </div>
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <div class="bg-gradient-to-br from-ed-primary via-ed-secondary to-amber-500 rounded-2xl shadow-xl text-white p-5">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7
                                                 a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-sm font-bold">
                                        Agenda EDGVM
                                    </h3>
                                    <p class="text-[11px] text-white/80 leading-snug">
                                        Soutenances, seminaires, conferences et ateliers a venir.
                                    </p>
                                </div>
                            </div>

                            <ul class="text-xs text-white/85 space-y-1.5 mb-4">
                                <li class="flex items-start gap-1.5">
                                    <span class="mt-1 w-1.5 h-1.5 rounded-full bg-emerald-300"></span>
                                    <span>Consultez l'agenda complet des evenements de l'Ecole Doctorale.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="mt-1 w-1.5 h-1.5 rounded-full bg-amber-200"></span>
                                    <span>Preparez vos participations aux prochaines sessions scientifiques.</span>
                                </li>
                            </ul>

                            <Link
                                :href="route('evenements.index')"
                                class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-white text-ed-primary rounded-xl text-xs font-bold shadow-md hover:bg-ed-yellow hover:text-white transition-colors"
                            >
                                <span>Voir les evenements a venir</span>
                                <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </Link>
                        </div>

                        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm">
                            <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <span class="inline-flex w-5 h-5 items-center justify-center rounded-full bg-purple-100 text-purple-700">
                                    #
                                </span>
                                <span>Tags</span>
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                <button
                                    v-for="tag in tags"
                                    :key="tag.id"
                                    type="button"
                                    class="px-3 py-1.5 text-xs font-semibold rounded-full transition"
                                    :class="filters?.tag === tag.slug
                                        ? 'bg-purple-600 text-white shadow'
                                        : 'bg-gray-100 text-gray-700 hover:bg-purple-50 border border-gray-200'"
                                    @click="setTag(tag.slug)"
                                >
                                    {{ tag.nom }} ({{ tag.actualites_count }})
                                </button>
                            </div>
                        </div>
                    </aside>

                    <main class="lg:col-span-3 lg:order-1">
                        <div v-if="!actualites?.data?.length" class="text-center py-20">
                            <svg class="mx-auto h-20 w-20 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5
                                         a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414
                                         a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-lg text-gray-600 font-semibold">Aucune actualite trouvee</p>
                            <button
                                v-if="filters?.category || filters?.tag || filters?.search"
                                type="button"
                                class="mt-4 px-6 py-2.5 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary text-sm font-semibold shadow-sm"
                                @click="clearFilters"
                            >
                                Afficher toutes les actualites
                            </button>
                        </div>

                        <div v-else>
                            <div v-if="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                <article v-for="actualite in actualites.data" :key="actualite.id" class="group bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-400 overflow-hidden border border-gray-100 hover:-translate-y-1">
                                    <div class="relative h-40 sm:h-48 overflow-hidden">
                                        <img
                                            v-if="actualite.image_url"
                                            :src="actualite.image_url"
                                            :alt="actualite.titre"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                        >
                                        <div v-else class="w-full h-full bg-gradient-to-br from-ed-primary to-ed-secondary"></div>

                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>

                                        <div v-if="actualite.est_important" class="absolute top-3 left-3">
                                            <span class="px-3 py-1 bg-red-600 text-white text-xs font-bold rounded-full shadow-lg flex items-center gap-1">
                                                <span></span> Important
                                            </span>
                                        </div>

                                        <div v-if="actualite.category" class="absolute bottom-3 left-3">
                                            <span class="px-3 py-1 text-white text-xs font-bold rounded-full shadow-lg backdrop-blur-sm"
                                                  :style="{ backgroundColor: actualite.category.couleur + '80' }">
                                                {{ actualite.category.nom }}
                                            </span>
                                        </div>

                                        <div class="absolute top-3 right-3">
                                            <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-gray-900 text-xs font-bold rounded-full shadow-lg flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ actualite.reading_time }} min
                                            </span>
                                        </div>
                                    </div>

                                    <div class="p-5">
                                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 min-h-[3.5rem]">
                                            <Link :href="route('actualites.show', actualite.slug)" class="block group-hover:text-ed-primary transition-colors">
                                                {{ actualite.titre }}
                                            </Link>
                                        </h3>
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed">
                                            {{ actualite.resume }}
                                        </p>
                                        <div class="flex items-center justify-between mb-4 text-xs text-gray-500">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7
                                                             a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ actualite.date_publication }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M2.458 12C3.732 7.943 7.523 5 12 5
                                                             c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7
                                                             -4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                {{ actualite.vues_formatted }}
                                            </span>
                                        </div>

                                        <div v-if="actualite.tags?.length" class="flex flex-wrap gap-1 mb-4">
                                            <span v-for="tag in actualite.tags.slice(0, 3)" :key="tag.id" class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded">
                                                #{{ tag.nom }}
                                            </span>
                                        </div>

                                        <Link
                                            :href="route('actualites.show', actualite.slug)"
                                            class="group/link inline-flex items-center text-ed-primary font-bold text-sm hover:text-ed-secondary transition-colors"
                                        >
                                            <span>Lire la suite</span>
                                            <svg class="w-4 h-4 ml-2 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </Link>
                                    </div>
                                </article>
                            </div>

                            <div v-else class="space-y-6">
                                <article v-for="actualite in actualites.data" :key="actualite.id" class="group bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-400 overflow-hidden border border-gray-100">
                                    <div class="flex flex-col md:flex-row">
                                        <div class="relative md:w-80 h-48 sm:h-56 md:h-auto overflow-hidden flex-shrink-0">
                                            <img
                                                v-if="actualite.image_url"
                                                :src="actualite.image_url"
                                                :alt="actualite.titre"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                            >
                                            <div v-else class="w-full h-full bg-gradient-to-br from-ed-primary to-ed-secondary"></div>

                                            <div class="absolute inset-0 bg-gradient-to-t md:bg-gradient-to-r from-black/60 via-black/20 to-transparent"></div>

                                            <div v-if="actualite.est_important" class="absolute top-4 left-4">
                                                <span class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-full shadow-lg flex items-center gap-1">
                                                    <span></span> Important
                                                </span>
                                            </div>

                                            <div v-if="actualite.category" class="absolute bottom-4 left-4">
                                                <span class="px-3 py-1.5 text-white text-xs font-bold rounded-full shadow-lg backdrop-blur-sm"
                                                      :style="{ backgroundColor: actualite.category.couleur + '80' }">
                                                    {{ actualite.category.nom }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex-1 p-4 sm:p-6 md:p-8">
                                            <div class="flex items-start justify-between gap-4 mb-4">
                                                <h3 class="text-2xl font-bold text-gray-900 line-clamp-2">
                                                    <Link :href="route('actualites.show', actualite.slug)" class="block group-hover:text-ed-primary transition-colors">
                                                        {{ actualite.titre }}
                                                    </Link>
                                                </h3>
                                                <div class="flex-shrink-0 px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-bold rounded-full flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ actualite.reading_time }} min
                                                </div>
                                            </div>

                                            <div class="flex flex-wrap items-center gap-4 mb-4 text-sm text-gray-500">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7
                                                                 a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ actualite.date_publication }}
                                                </span>
                                                <span v-if="actualite.auteur" class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                    {{ actualite.auteur.name }}
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M2.458 12C3.732 7.943 7.523 5 12 5
                                                                 c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7
                                                                 -4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    {{ actualite.vues_formatted }} vues
                                                </span>
                                            </div>

                                            <p class="text-gray-600 mb-6 line-clamp-3 leading-relaxed">
                                                {{ actualite.contenu_extrait }}
                                            </p>

                                            <div v-if="actualite.tags?.length" class="flex flex-wrap gap-2 mb-6">
                                                <span v-for="tag in actualite.tags.slice(0, 5)" :key="tag.id" class="px-3 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">
                                                    #{{ tag.nom }}
                                                </span>
                                            </div>

                                            <Link
                                                :href="route('actualites.show', actualite.slug)"
                                                class="group/link inline-flex items-center text-ed-primary font-bold hover:text-ed-secondary transition-colors"
                                            >
                                                <span>Lire l'article complet</span>
                                                <svg class="w-5 h-5 ml-2 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </Link>
                                        </div>
                                    </div>
                                </article>
                            </div>

                            <div class="mt-10">
                                <Pagination v-if="actualites?.links" :links="actualites.links" />
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </section>
    </div>
</template>
