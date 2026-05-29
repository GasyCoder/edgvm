<script setup>
import { computed, ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import Container from '@/Components/Frontend/UI/Container.vue';
import Pagination from '@/Components/Common/Pagination.vue';
import { cleanText } from '@/utils/text';

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

const hasActiveFilters = computed(() => Boolean(props.filters?.category || props.filters?.tag || props.filters?.search));

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
    router.get(route('actualites.index'), { view: viewMode.value }, {
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
    <Head title="Actualités" />

    <!-- Hero -->
    <section class="mt-16 bg-ed-teal-dark py-12 text-white sm:mt-20 sm:py-16">
        <Container>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-ed-yellow">Espace actualités EDGVM</p>
            <h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Actualités</h1>
            <p class="mt-3 max-w-2xl text-sm leading-relaxed text-white/85 sm:text-base">
                Nouvelles, annonces et événements de l'École Doctorale.
            </p>

            <nav class="mt-5 flex items-center gap-2 text-sm text-white/70" aria-label="Fil d'Ariane">
                <Link :href="route('home')" class="transition-colors hover:text-ed-yellow">Accueil</Link>
                <span aria-hidden="true">/</span>
                <span class="font-semibold text-white">Actualités</span>
            </nav>
        </Container>
    </section>

    <!-- Barre de filtres active -->
    <div class="sticky top-16 z-30 border-b border-gray-200 bg-white/95 backdrop-blur sm:top-20">
        <Container>
            <div class="flex items-center justify-between gap-4 py-3">
                <div class="flex flex-1 flex-wrap items-center gap-2">
                    <template v-if="hasActiveFilters">
                        <span class="text-sm font-medium text-gray-600">Filtres :</span>
                        <span v-if="filters?.category" class="inline-flex items-center gap-1.5 rounded-full bg-ed-primary px-3 py-1 text-sm text-white">
                            {{ activeCategory?.nom || filters.category }}
                            <button type="button" class="hover:text-ed-yellow" aria-label="Retirer" @click="setCategory('')">×</button>
                        </span>
                        <span v-if="filters?.tag" class="inline-flex items-center gap-1.5 rounded-full bg-gray-700 px-3 py-1 text-sm text-white">
                            #{{ activeTag?.nom || filters.tag }}
                            <button type="button" class="hover:text-gray-300" aria-label="Retirer" @click="setTag('')">×</button>
                        </span>
                        <span v-if="filters?.search" class="inline-flex items-center gap-1.5 rounded-full bg-gray-500 px-3 py-1 text-sm text-white">
                            « {{ filters.search }} »
                            <button type="button" class="hover:text-gray-200" aria-label="Retirer" @click="search = ''">×</button>
                        </span>
                        <button type="button" class="ml-1 text-sm font-medium text-red-600 hover:text-red-700" @click="clearFilters">Tout effacer</button>
                    </template>
                    <span v-else class="text-sm text-gray-500">{{ actualites?.meta?.total || 0 }} actualité(s) publiée(s)</span>
                </div>

                <div class="inline-flex flex-none rounded-lg border border-gray-300 p-0.5">
                    <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-md transition" :class="viewMode === 'grid' ? 'bg-ed-primary text-white' : 'text-gray-500 hover:text-gray-800'" aria-label="Vue grille" @click="setView('grid')">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                    </button>
                    <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-md transition" :class="viewMode === 'list' ? 'bg-ed-primary text-white' : 'text-gray-500 hover:text-gray-800'" aria-label="Vue liste" @click="setView('list')">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                </div>
            </div>
        </Container>
    </div>

    <section class="bg-gray-50 py-10 md:py-14">
        <Container>
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-4 lg:gap-8">
                <!-- Sidebar -->
                <aside class="space-y-5 lg:order-2 lg:col-span-1">
                    <div class="rounded-xl border border-gray-200 bg-white p-5">
                        <h3 class="mb-3 text-sm font-semibold text-gray-900">Rechercher</h3>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Mot-clé, thématique…"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm placeholder:text-gray-400 focus:border-transparent focus:ring-2 focus:ring-ed-primary/40"
                        >
                    </div>

                    <div v-if="categories?.length" class="rounded-xl border border-gray-200 bg-white p-5">
                        <h3 class="mb-3 text-sm font-semibold text-gray-900">Catégories</h3>
                        <ul class="space-y-1">
                            <li v-for="cat in categories" :key="cat.id">
                                <button
                                    type="button"
                                    class="flex w-full items-center justify-between gap-2 rounded-lg px-3 py-2 text-left text-sm transition"
                                    :class="filters?.category === cat.slug ? 'bg-ed-primary/5 font-semibold text-ed-primary' : 'text-gray-700 hover:bg-gray-50'"
                                    @click="setCategory(cat.slug)"
                                >
                                    <span class="flex items-center gap-2">
                                        <span class="h-2.5 w-2.5 rounded-full" :style="{ backgroundColor: cat.couleur || '#16826A' }"></span>
                                        {{ cat.nom }}
                                    </span>
                                    <span class="text-xs text-gray-400">{{ cat.actualites_count }}</span>
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-5">
                        <h3 class="text-sm font-semibold text-gray-900">Agenda EDGVM</h3>
                        <p class="mt-1 text-xs leading-relaxed text-gray-500">Soutenances, séminaires, conférences et ateliers à venir.</p>
                        <Link
                            :href="route('evenements.index')"
                            class="mt-4 inline-flex w-full items-center justify-center gap-1.5 rounded-lg bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-ed-secondary"
                        >
                            Voir les événements
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </Link>
                    </div>

                    <div v-if="tags?.length" class="rounded-xl border border-gray-200 bg-white p-5">
                        <h3 class="mb-3 text-sm font-semibold text-gray-900">Tags</h3>
                        <div class="flex flex-wrap gap-2">
                            <button
                                v-for="tag in tags"
                                :key="tag.id"
                                type="button"
                                class="rounded-full px-3 py-1.5 text-xs font-medium transition"
                                :class="filters?.tag === tag.slug ? 'bg-ed-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                @click="setTag(tag.slug)"
                            >
                                {{ tag.nom }}
                            </button>
                        </div>
                    </div>
                </aside>

                <!-- Liste -->
                <main class="lg:order-1 lg:col-span-3">
                    <div v-if="!actualites?.data?.length" class="rounded-xl border border-dashed border-gray-300 bg-white p-12 text-center">
                        <p class="text-sm font-medium text-gray-600">Aucune actualité trouvée.</p>
                        <button v-if="hasActiveFilters" type="button" class="mt-4 inline-flex items-center rounded-lg bg-ed-primary px-4 py-2 text-sm font-semibold text-white transition hover:bg-ed-secondary" @click="clearFilters">
                            Afficher toutes les actualités
                        </button>
                    </div>

                    <div v-else>
                        <!-- Grille -->
                        <div v-if="viewMode === 'grid'" class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                            <article v-for="actualite in actualites.data" :key="actualite.id" class="group flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white transition hover:border-ed-primary/40 hover:shadow-sm">
                                <div class="relative h-44 overflow-hidden">
                                    <img v-if="actualite.image_url" :src="actualite.image_url" :alt="actualite.titre" class="h-full w-full object-cover transition duration-500 group-hover:scale-105 motion-reduce:transform-none" loading="lazy" decoding="async">
                                    <div v-else class="h-full w-full bg-ed-primary"></div>

                                    <div v-if="actualite.est_important" class="absolute left-3 top-3">
                                        <span class="rounded-full bg-red-600 px-2 py-0.5 text-[10px] font-semibold text-white shadow">Important</span>
                                    </div>
                                    <div v-if="actualite.category" class="absolute bottom-3 left-3">
                                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-semibold text-white shadow" :style="{ backgroundColor: actualite.category.couleur || '#16826A' }">{{ actualite.category.nom }}</span>
                                    </div>
                                </div>

                                <div class="flex flex-1 flex-col p-5">
                                    <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-[11px] text-gray-500">
                                        <span>{{ actualite.date_publication }}</span>
                                        <span aria-hidden="true" class="text-gray-300">·</span>
                                        <span>{{ actualite.reading_time }} min</span>
                                        <span aria-hidden="true" class="text-gray-300">·</span>
                                        <span>{{ actualite.vues_formatted }} vues</span>
                                    </div>

                                    <h3 class="mt-2 line-clamp-2 text-base font-semibold text-gray-900">
                                        <Link :href="route('actualites.show', actualite.slug)" class="transition-colors group-hover:text-ed-primary">{{ actualite.titre }}</Link>
                                    </h3>

                                    <p class="mt-1.5 line-clamp-2 text-sm leading-relaxed text-gray-600">{{ cleanText(actualite.resume) }}</p>

                                    <div v-if="actualite.tags?.length" class="mt-3 flex flex-wrap gap-1.5">
                                        <span v-for="tag in actualite.tags.slice(0, 3)" :key="tag.id" class="rounded bg-gray-100 px-2 py-0.5 text-[10px] font-medium text-gray-600">#{{ tag.nom }}</span>
                                    </div>

                                    <Link :href="route('actualites.show', actualite.slug)" class="group/lire mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-ed-primary transition-colors hover:text-ed-secondary">
                                        Lire la suite
                                        <svg class="h-4 w-4 transition-transform group-hover/lire:translate-x-0.5 motion-reduce:transform-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                    </Link>
                                </div>
                            </article>
                        </div>

                        <!-- Liste -->
                        <div v-else class="space-y-5">
                            <article v-for="actualite in actualites.data" :key="actualite.id" class="group flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white transition hover:border-ed-primary/40 hover:shadow-sm md:flex-row">
                                <div class="relative h-48 flex-none overflow-hidden md:h-auto md:w-72">
                                    <img v-if="actualite.image_url" :src="actualite.image_url" :alt="actualite.titre" class="h-full w-full object-cover transition duration-500 group-hover:scale-105 motion-reduce:transform-none" loading="lazy" decoding="async">
                                    <div v-else class="h-full w-full bg-ed-primary"></div>
                                    <div v-if="actualite.est_important" class="absolute left-3 top-3">
                                        <span class="rounded-full bg-red-600 px-2 py-0.5 text-[10px] font-semibold text-white shadow">Important</span>
                                    </div>
                                </div>

                                <div class="flex-1 p-5 md:p-6">
                                    <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-[11px] text-gray-500">
                                        <span>{{ actualite.date_publication }}</span>
                                        <template v-if="actualite.category">
                                            <span aria-hidden="true" class="text-gray-300">·</span>
                                            <span class="font-semibold" :style="{ color: actualite.category.couleur || '#16826A' }">{{ actualite.category.nom }}</span>
                                        </template>
                                        <span aria-hidden="true" class="text-gray-300">·</span>
                                        <span>{{ actualite.reading_time }} min</span>
                                        <span v-if="actualite.auteur" aria-hidden="true" class="text-gray-300">·</span>
                                        <span v-if="actualite.auteur">{{ actualite.auteur.name }}</span>
                                    </div>

                                    <h3 class="mt-2 line-clamp-2 text-lg font-semibold text-gray-900">
                                        <Link :href="route('actualites.show', actualite.slug)" class="transition-colors group-hover:text-ed-primary">{{ actualite.titre }}</Link>
                                    </h3>

                                    <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-gray-600">{{ cleanText(actualite.contenu_extrait) }}</p>

                                    <div v-if="actualite.tags?.length" class="mt-3 flex flex-wrap gap-1.5">
                                        <span v-for="tag in actualite.tags.slice(0, 5)" :key="tag.id" class="rounded bg-gray-100 px-2 py-0.5 text-[10px] font-medium text-gray-600">#{{ tag.nom }}</span>
                                    </div>

                                    <Link :href="route('actualites.show', actualite.slug)" class="group/lire mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-ed-primary transition-colors hover:text-ed-secondary">
                                        Lire l'article
                                        <svg class="h-4 w-4 transition-transform group-hover/lire:translate-x-0.5 motion-reduce:transform-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                    </Link>
                                </div>
                            </article>
                        </div>

                        <div class="mt-10">
                            <Pagination v-if="actualites?.links" :links="actualites.links" />
                        </div>
                    </div>
                </main>
            </div>
        </Container>
    </section>
</template>
