<script setup>
import { computed, ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';

const props = defineProps({
    filters: Object,
    domaines: Array,
    eads: Array,
});

const search = ref(props.filters?.search || '');
const domaine = ref(props.filters?.domaine || '');
const viewMode = computed(() => props.filters?.view || 'grid');

let searchTimeout = null;

const updateFilters = (values) => {
    router.get(route('ead.index'), {
        search: search.value,
        domaine: domaine.value,
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

const setDomaine = (value) => {
    domaine.value = value || '';
    updateFilters({ domaine: value || '' });
};

const setView = (mode) => {
    updateFilters({ view: mode });
};

const resetFilters = () => {
    search.value = '';
    domaine.value = '';
    updateFilters({ search: '', domaine: '' });
};

defineOptions({
    layout: FrontendLayout,
});
</script>

<template>
    <Head title="Equipes d'accueil doctorales" />

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
                        Programme doctoral EDGVM
                    </span>
                </div>
                <h1 class="text-3xl md:text-4xl font-black mb-3">
                     Equipes d'Accueil Doctorales
                </h1>
                <p class="text-base md:text-lg text-white/90 max-w-2xl mx-auto">
                    Decouvrez les EAD, leurs domaines d'expertise et les equipes qui encadrent les doctorants.
                </p>
            </div>
        </section>

        <section class="py-12 bg-gradient-to-b from-gray-50 to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-5 lg:p-6 space-y-4">
                    <div>
                        <div class="relative max-w-2xl">
                            <input
                                v-model="search"
                                type="text"
                                placeholder="Rechercher une equipe (nom, domaine, description)..."
                                class="w-full pl-11 pr-4 py-3.5 text-sm sm:text-base border border-gray-200 rounded-xl focus:ring-2 focus:ring-ed-primary focus:border-transparent shadow-sm bg-white"
                            >
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Domaine :</span>

                            <button
                                type="button"
                                class="px-3 py-1.5 rounded-full text-xs sm:text-sm font-semibold border transition-all"
                                :class="domaine === '' ? 'bg-ed-primary text-white border-ed-primary shadow-sm' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'"
                                @click="setDomaine('')"
                            >
                                Tous
                            </button>

                            <button
                                v-for="item in domaines"
                                :key="item"
                                type="button"
                                class="px-3 py-1.5 rounded-full text-xs sm:text-sm font-semibold border max-w-[180px] truncate transition-all"
                                :class="domaine === item ? 'bg-ed-secondary text-white border-ed-secondary shadow-sm' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'"
                                @click="setDomaine(item)"
                            >
                                {{ item }}
                            </button>
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
                </div>

                <div v-if="!eads?.length" class="text-center py-16">
                    <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <p class="text-lg text-gray-500 font-medium mb-2">Aucune EAD trouvee</p>
                    <button v-if="search || domaine" type="button" class="mt-3 px-5 py-2.5 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary text-sm font-semibold shadow-sm" @click="resetFilters">
                        Reinitialiser les filtres
                    </button>
                </div>

                <div v-else>
                    <div v-if="viewMode === 'list'" class="space-y-4">
                        <article v-for="ead in eads" :key="ead.id" class="group bg-white rounded-2xl shadow-sm hover:shadow-md border border-gray-100 hover:-translate-y-0.5 transition-all duration-300 p-5 md:p-6 flex flex-col md:flex-row gap-4">
                            <div class="flex-1 flex gap-3">
                                <div class="hidden sm:flex w-10 h-10 rounded-xl bg-blue-50 items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 6a2 2 0 012-2h4.5a1 1 0 01.7.29l2.5 2.5a1 1 0 00.7.29H18a2 2 0 012 2v9a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-base md:text-lg font-bold text-gray-900 mb-1 group-hover:text-ed-primary transition-colors line-clamp-2">
                                        {{ ead.nom }}
                                    </h3>
                                    <p v-if="ead.domaine" class="text-xs text-gray-500 mb-1 line-clamp-1">
                                        {{ ead.domaine }}
                                    </p>
                                    <p v-if="ead.description" class="text-sm text-gray-600 mb-2 line-clamp-2 md:line-clamp-3">
                                        {{ ead.description }}
                                    </p>
                                    <div v-if="ead.responsable" class="mt-1 flex items-center gap-2 text-xs text-gray-500">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>
                                            Responsable :
                                            <span class="font-semibold text-gray-800">
                                                {{ ead.responsable.name || ead.responsable.nom }}
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="md:w-64 border-t md:border-t-0 md:border-l border-gray-100 pt-3 md:pt-0 md:pl-4 flex flex-col gap-3 justify-between">
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="text-center p-2.5 bg-blue-50/80 rounded-lg">
                                        <p class="text-lg font-bold text-blue-700">{{ ead.specialites_count }}</p>
                                        <p class="text-[11px] text-gray-600 uppercase tracking-wide">Specialites</p>
                                    </div>
                                    <div class="text-center p-2.5 bg-green-50/80 rounded-lg">
                                        <p class="text-lg font-bold text-green-700">{{ ead.doctorats_count }}</p>
                                        <p class="text-[11px] text-gray-600 uppercase tracking-wide">Doctorants</p>
                                    </div>
                                </div>

                                <div class="flex justify-end md:justify-start">
                                    <Link :href="route('ead.show', ead.slug)" class="inline-flex items-center text-ed-primary font-semibold text-sm hover:text-ed-secondary transition-colors">
                                        <span>Voir l'equipe</span>
                                        <svg class="w-4 h-4 ml-2 translate-x-0 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </Link>
                                </div>
                            </div>
                        </article>
                    </div>

                    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                        <article v-for="ead in eads" :key="ead.id" class="group bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 hover:-translate-y-1.5 transition-all duration-300 flex flex-col h-full">
                            <div class="px-6 pt-5 pb-4 border-b border-gray-100">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M4 6a2 2 0 012-2h4.5a1 1 0 01.7.29l2.5 2.5a1 1 0 00.7.29H18a2 2 0 012 2v9a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/>
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <h3 class="text-base md:text-lg font-bold text-gray-900 group-hover:text-ed-primary transition-colors line-clamp-2">
                                                {{ ead.nom }}
                                            </h3>
                                            <p v-if="ead.domaine" class="text-xs text-gray-500 mt-0.5 line-clamp-1">
                                                {{ ead.domaine }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 flex-1 flex flex-col">
                                <p v-if="ead.description" class="text-sm text-gray-600 mb-4 line-clamp-3 leading-relaxed">
                                    {{ ead.description }}
                                </p>

                                <div v-if="ead.responsable" class="flex items-center gap-2 mb-4 p-3 bg-gray-50 rounded-lg">
                                    <svg class="w-5 h-5 text-gray-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs text-gray-500">Responsable de l'equipe</p>
                                        <p class="text-sm font-semibold text-gray-900 truncate">
                                            {{ ead.responsable.name || ead.responsable.nom }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-auto grid grid-cols-2 gap-3">
                                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                                        <p class="text-lg font-bold text-blue-700">{{ ead.specialites_count }}</p>
                                        <p class="text-[11px] text-gray-600 uppercase tracking-wide">Specialites</p>
                                    </div>
                                    <div class="text-center p-3 bg-green-50 rounded-lg">
                                        <p class="text-lg font-bold text-green-700">{{ ead.doctorats_count }}</p>
                                        <p class="text-[11px] text-gray-600 uppercase tracking-wide">Doctorants</p>
                                    </div>
                                </div>

                                <Link :href="route('ead.show', ead.slug)" class="mt-4 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-ed-primary text-white rounded-xl text-xs font-bold shadow-sm hover:bg-ed-secondary transition">
                                    Voir l'equipe
                                </Link>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
