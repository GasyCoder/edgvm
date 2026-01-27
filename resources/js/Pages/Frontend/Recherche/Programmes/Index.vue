<script setup>
import { computed, ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';

const props = defineProps({
    filters: Object,
    specialites: Array,
    eads: Array,
});

const search = ref(props.filters?.search || '');
const eadFilter = ref(props.filters?.ead || '');
const viewMode = computed(() => props.filters?.view || 'grid');

let searchTimeout = null;

const updateFilters = (values) => {
    router.get(route('programmes.index'), {
        search: search.value,
        ead: eadFilter.value,
        view: viewMode.value,
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
        updateFilters({ search: value });
    }, 300);
});

const filterByEad = (slug) => {
    eadFilter.value = slug || '';
    updateFilters({ ead: slug || '' });
};

const setView = (mode) => {
    updateFilters({ view: mode });
};

const clearFilters = () => {
    search.value = '';
    eadFilter.value = '';
    updateFilters({ search: '', ead: '' });
};

defineOptions({
    layout: FrontendLayout,
});
</script>

<template>
    <Head title="Programmes doctoraux" />

    <div>
        <section class="relative gradient-primary pt-24 md:pt-28 pb-16 md:pb-20 overflow-hidden">
            <div class="absolute inset-0 opacity-15 pointer-events-none">
                <div class="absolute -top-10 right-0 w-40 h-40 bg-white rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 -left-10 w-40 h-40 bg-ed-yellow rounded-full blur-3xl"></div>
            </div>

            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center text-white">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 mb-4">
                    <span class="w-2 h-2 rounded-full bg-ed-yellow"></span>
                    <span class="text-xs font-semibold tracking-[0.18em] uppercase">
                        Programmes doctoraux EDGVM
                    </span>
                </div>
                <h1 class="text-3xl md:text-4xl font-black mb-3">
                     Programmes Doctoraux
                </h1>
                <p class="text-base md:text-lg text-white/90 max-w-2xl mx-auto">
                    Decouvrez nos specialites de recherche et trouvez le programme adapte a vos ambitions.
                </p>
            </div>
        </section>

        <section class="py-12 bg-gradient-to-b from-gray-50 to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                    <aside class="lg:col-span-1 space-y-6">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                            <h3 class="text-sm font-bold text-gray-900 mb-3"> Rechercher un programme</h3>
                            <div class="relative">
                                <input
                                    v-model="search"
                                    type="text"
                                    placeholder="Nom, mots-cles..."
                                    class="w-full pl-10 pr-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent bg-white"
                                >
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-bold text-gray-900"> Equipes d'accueil</h3>
                                <span class="text-[11px] text-gray-400">{{ eads?.length || 0 }} EAD</span>
                            </div>

                            <div class="space-y-2 max-h-[360px] overflow-y-auto pr-1">
                                <button
                                    type="button"
                                    class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs sm:text-sm font-medium border transition-all mb-1"
                                    :class="eadFilter === '' ? 'bg-ed-primary text-white border-ed-primary shadow-sm' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'"
                                    @click="filterByEad('')"
                                >
                                    <span>Toutes les equipes</span>
                                    <span class="text-[11px]" :class="eadFilter === '' ? 'text-white/80' : 'text-gray-500'">
                                        {{ specialites?.length || 0 }}
                                    </span>
                                </button>

                                <button
                                    v-for="item in eads"
                                    :key="item.id"
                                    type="button"
                                    class="w-full text-left px-3 py-2 rounded-lg transition text-xs sm:text-sm font-medium flex items-center justify-between border"
                                    :class="eadFilter === item.slug ? 'bg-ed-secondary text-white border-ed-secondary shadow-sm' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'"
                                    @click="filterByEad(item.slug)"
                                >
                                    <span class="truncate">{{ item.nom }}</span>
                                    <span class="ml-2 text-[11px]" :class="eadFilter === item.slug ? 'text-white/80' : 'text-gray-500'">
                                        {{ item.specialites_count }}
                                    </span>
                                </button>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-ed-primary to-ed-secondary rounded-2xl shadow-md p-5 text-white">
                            <h3 class="text-sm font-bold mb-3"> En chiffres</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-white/80">Programmes</span>
                                    <span class="text-xl font-bold">{{ specialites?.length || 0 }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-white/80">Equipes d'accueil</span>
                                    <span class="text-xl font-bold">{{ eads?.length || 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </aside>

                    <main class="lg:col-span-3 space-y-6">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-5 space-y-3">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <p class="text-sm text-gray-700 font-medium">
                                        {{ specialites?.length || 0 }} programme(s) trouve(s)
                                    </p>
                                    <p v-if="eadFilter" class="text-xs text-gray-500">
                                        Filtre par EAD :
                                        <span class="font-semibold text-gray-800">
                                            {{ eads?.find((item) => item.slug === eadFilter)?.nom || '-' }}
                                        </span>
                                    </p>
                                </div>

                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-gray-500 uppercase tracking-wide hidden sm:inline">Affichage :</span>
                                    <div class="inline-flex items-center rounded-full bg-gray-100 p-1">
                                        <button
                                            type="button"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-full text-xs transition-all"
                                            :class="viewMode === 'grid' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-800'"
                                            @click="setView('grid')"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M4 6h7V4H4v2zm9 0h7V4h-7v2zM4 13h7v-2H4v2zm9 0h7v-2h-7v2zM4 20h7v-2H4v2zm9 0h7v-2h-7v2z"/>
                                            </svg>
                                        </button>
                                        <button
                                            type="button"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-full text-xs transition-all"
                                            :class="viewMode === 'list' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-800'"
                                            @click="setView('list')"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h10"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div v-if="eadFilter || search" class="flex flex-wrap items-center gap-2 pt-2 border-t border-gray-100">
                                <span class="text-xs text-gray-500 uppercase tracking-wide">Filtres actifs :</span>

                                <span v-if="eadFilter" class="inline-flex items-center gap-1.5 px-3 py-1 bg-ed-primary text-white text-xs rounded-full">
                                    {{ eads?.find((item) => item.slug === eadFilter)?.nom || 'EAD' }}
                                    <button type="button" class="ml-1 hover:text-ed-yellow" @click="filterByEad('')">x</button>
                                </span>

                                <span v-if="search" class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-700 text-white text-xs rounded-full">
                                    "{{ search }}"
                                    <button type="button" class="ml-1 hover:text-gray-200" @click="search = ''">x</button>
                                </span>

                                <button type="button" class="ml-auto text-xs text-red-600 hover:text-red-800 font-medium" @click="clearFilters">
                                    Effacer les filtres
                                </button>
                            </div>
                        </div>

                        <div v-if="!specialites?.length" class="text-center py-16">
                            <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <p class="text-lg text-gray-500 font-medium mb-2">Aucun programme trouve</p>
                            <button v-if="eadFilter || search" type="button" class="mt-3 px-5 py-2.5 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary text-sm font-semibold shadow-sm" @click="clearFilters">
                                Voir tous les programmes
                            </button>
                        </div>

                        <div v-else>
                            <div v-if="viewMode === 'list'" class="space-y-4">
                                <article v-for="specialite in specialites" :key="specialite.id" class="group bg-white rounded-2xl shadow-sm hover:shadow-md border border-gray-100 hover:-translate-y-0.5 transition-all duration-300 p-5 md:p-6 flex flex-col md:flex-row gap-4">
                                    <div class="flex-1 flex gap-3">
                                        <div class="hidden sm:flex w-10 h-10 rounded-xl bg-ed-primary/10 text-ed-primary items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <h3 class="text-base md:text-lg font-bold text-gray-900 mb-1 group-hover:text-ed-primary transition-colors line-clamp-2">
                                                {{ specialite.nom }}
                                            </h3>

                                            <p v-if="specialite.ead" class="text-xs text-gray-500 mb-1 line-clamp-1">
                                                Equipe d'accueil :
                                                <span class="font-semibold text-gray-800">
                                                    {{ specialite.ead.nom }}
                                                </span>
                                            </p>

                                            <p v-if="specialite.description" class="text-sm text-gray-600 mb-2 line-clamp-2 md:line-clamp-3">
                                                {{ specialite.description }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="md:w-64 border-t md:border-t-0 md:border-l border-gray-100 pt-3 md:pt-0 md:pl-4 flex flex-col gap-3 justify-between">
                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="text-center p-2.5 bg-blue-50/80 rounded-lg">
                                                <p class="text-lg font-bold text-blue-700">{{ specialite.theses_en_cours }}</p>
                                                <p class="text-[11px] text-gray-600 uppercase tracking-wide">En cours</p>
                                            </div>
                                            <div class="text-center p-2.5 bg-green-50/80 rounded-lg">
                                                <p class="text-lg font-bold text-green-700">{{ specialite.theses_soutenues }}</p>
                                                <p class="text-[11px] text-gray-600 uppercase tracking-wide">Soutenues</p>
                                            </div>
                                        </div>

                                        <div class="flex justify-end md:justify-start">
                                            <Link :href="route('programmes.show', specialite.slug)" class="inline-flex items-center text-ed-primary font-semibold text-sm hover:text-ed-secondary transition-colors">
                                                <span>Voir le programme</span>
                                                <svg class="w-4 h-4 ml-2 translate-x-0 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </Link>
                                        </div>
                                    </div>
                                </article>
                            </div>

                            <div v-else class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                <article v-for="specialite in specialites" :key="specialite.id" class="group bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
                                    <div class="px-6 pt-5 pb-4 border-b border-gray-100">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-ed-primary/10 text-ed-primary flex items-center justify-center">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                </svg>
                                            </div>
                                            <div class="min-w-0">
                                                <h3 class="text-base md:text-lg font-bold text-gray-900 group-hover:text-ed-primary transition-colors line-clamp-2">
                                                    {{ specialite.nom }}
                                                </h3>
                                                <p v-if="specialite.ead" class="text-xs text-gray-500 mt-0.5 line-clamp-1">
                                                    {{ specialite.ead.nom }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-6 flex-1 flex flex-col">
                                        <p v-if="specialite.description" class="text-sm text-gray-600 mb-4 line-clamp-3 leading-relaxed">
                                            {{ specialite.description }}
                                        </p>

                                        <div class="mt-auto grid grid-cols-2 gap-3">
                                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                                <p class="text-lg font-bold text-blue-700">{{ specialite.theses_en_cours }}</p>
                                                <p class="text-[11px] text-gray-600 uppercase tracking-wide">En cours</p>
                                            </div>
                                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                                <p class="text-lg font-bold text-green-700">{{ specialite.theses_soutenues }}</p>
                                                <p class="text-[11px] text-gray-600 uppercase tracking-wide">Soutenues</p>
                                            </div>
                                        </div>

                                        <Link :href="route('programmes.show', specialite.slug)" class="mt-4 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-ed-primary text-white rounded-xl text-xs font-bold shadow-sm hover:bg-ed-secondary transition">
                                            Voir le programme
                                        </Link>
                                    </div>
                                </article>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </section>
    </div>
</template>
