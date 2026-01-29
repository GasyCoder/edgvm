<script setup>
import { computed, ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import patternUrl from '@/assets/pattern.svg';
import Pagination from '@/Components/Common/Pagination.vue';

const props = defineProps({
    filters: Object,
    theses: Object,
});

const search = ref(props.filters?.search || '');
const viewMode = computed(() => props.filters?.viewMode || 'grid');
const statut = ref(props.filters?.statut || '');

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
    <Head title="Theses" />

    <div>
        <section class="relative gradient-primary pt-20 sm:pt-24 md:pt-28 pb-12 sm:pb-16 md:pb-20 overflow-hidden">
            <div class="absolute inset-0">
                <div class="absolute inset-0 opacity-10" :style="{ backgroundImage: `url(${patternUrl})` }"></div>
                <div class="absolute -top-10 right-0 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 -left-16 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl"></div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <nav class="mb-6">
                    <ol class="flex items-center gap-2 text-xs md:text-sm text-white/70">
                        <li>
                            <Link :href="route('home')" class="hover:text-white transition">Accueil</Link>
                        </li>
                        <li>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </li>
                        <li class="text-white font-semibold">Theses</li>
                    </ol>
                </nav>

                <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                    <div>
                        <p class="text-sm uppercase tracking-[0.2em] text-teal-200 mb-2">
                            Repertoire scientifique
                        </p>
                        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight mb-3">
                            Theses de l'Ecole Doctorale
                        </h1>
                        <p class="text-sm md:text-base text-white/80 max-w-2xl">
                            Consultez les travaux de recherche des doctorants.
                        </p>
                    </div>

                    <div class="bg-white/10 border border-white/20 rounded-2xl px-4 py-3 md:px-5 md:py-4 backdrop-blur max-w-xs md:max-w-sm">
                        <p class="text-xs text-white/70 mb-1">Theses referencees</p>
                        <p class="text-2xl font-semibold text-white">
                            {{ theses?.meta?.total || 0 }}
                            <span class="text-sm font-normal text-white/70">projets</span>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-10 md:py-14 bg-gradient-to-b from-slate-50 to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 md:p-5 flex flex-col gap-4 md:gap-5">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="w-full lg:max-w-xl">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1.5">
                                Rechercher une these
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>
                                    </svg>
                                </span>
                                <input
                                    v-model="search"
                                    type="text"
                                    placeholder="Sujet, doctorant, mots-cles..."
                                    class="w-full pl-9 pr-3 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>
                            <p class="mt-1 text-[11px] text-gray-400">
                                Exemple : "vulnerabilite climatique", "mangroves", "Boeny"...
                            </p>
                        </div>

                        <div class="flex items-center justify-between lg:justify-end gap-3">
                            <span class="text-xs font-semibold text-gray-500 uppercase">Affichage</span>
                            <div class="inline-flex items-center rounded-xl border border-gray-200 bg-gray-50 p-0.5">
                                <button
                                    type="button"
                                    class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-medium"
                                    :class="viewMode === 'grid' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                    @click="setViewMode('grid')"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 6h7V4H4v2zm9 0h7V4h-7v2zM4 13h7v-2H4v2zm9 0h7v-2h-7v2zM4 20h7v-2H4v2zm9 0h7v-2h-7v2z"/>
                                    </svg>
                                    Grille
                                </button>

                                <button
                                    type="button"
                                    class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-medium"
                                    :class="viewMode === 'list' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                    @click="setViewMode('list')"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                    </svg>
                                    Liste
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 sm:gap-6">
                    <div class="lg:col-span-3 order-2 lg:order-1">
                        <div v-if="theses?.data?.length">
                            <div v-if="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                <Link
                                    v-for="these in theses.data"
                                    :key="these.id"
                                    :href="route('theses.show', these.id)"
                                    class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 flex flex-col overflow-hidden"
                                >
                                    <div class="px-5 pt-4 flex items-start justify-between gap-3">
                                        <div class="flex-1">
                                            <div class="inline-flex items-center gap-2 mb-2">
                                                <span v-if="these.specialite" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-indigo-50 text-[11px] text-indigo-700">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"/>
                                                    </svg>
                                                    {{ these.specialite.nom || 'Specialite' }}
                                                </span>

                                                <span v-if="these.ead" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-sky-50 text-[11px] text-sky-700 font-bold">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h10M4 18h10"/>
                                                    </svg>
                                                    {{ (these.ead.sigle || these.ead.nom || 'EAD').slice(0, 5) }}
                                                </span>
                                            </div>

                                            <h2 class="text-sm font-semibold text-gray-900 line-clamp-3 group-hover:text-blue-700">
                                                {{ these.sujet_these }}
                                            </h2>
                                        </div>

                                        <div class="ml-2">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full border text-[11px] font-medium" :class="these.statut_badge_classes">
                                                {{ these.statut_label }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="px-5 py-3 flex-1 flex flex-col gap-3">
                                        <div v-if="these.doctorant" class="flex items-center gap-2 text-xs text-gray-600">
                                            <div class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center text-[11px] font-semibold text-gray-700">
                                                {{ these.doctorant.initials }}
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="font-medium text-gray-800">{{ these.doctorant.name }}</span>
                                                <span class="text-[11px] text-gray-400">Doctorant</span>
                                            </div>
                                        </div>

                                        <p v-if="these.resume" class="text-xs text-gray-600 line-clamp-3">
                                            {{ these.resume }}
                                        </p>
                                        <p v-else class="text-xs text-gray-400 italic">Resume non renseigne.</p>
                                    </div>

                                    <div class="px-5 pb-4 pt-2 mt-auto border-t border-gray-100 bg-gray-50/60 flex items-center justify-between text-[11px] text-gray-500">
                                        <div class="flex items-center gap-3">
                                            <div v-if="these.date_debut" class="flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7
                                                             a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span>Debut {{ these.date_debut }}</span>
                                            </div>

                                            <div v-if="these.date_publication" class="flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span>Publie {{ these.date_publication }}</span>
                                            </div>
                                        </div>

                                        <span class="inline-flex items-center gap-1 text-blue-600 font-medium">
                                            Voir la fiche
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </span>
                                    </div>
                                </Link>
                            </div>

                            <div v-else class="space-y-4">
                                <Link
                                    v-for="these in theses.data"
                                    :key="these.id"
                                    :href="route('theses.show', these.id)"
                                    class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex flex-col md:flex-row md:items-stretch overflow-hidden"
                                >
                                    <div class="md:w-56 bg-gray-50/80 border-b md:border-b-0 md:border-r border-gray-100 p-4 flex flex-col justify-between gap-3">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full border text-[11px] font-medium" :class="these.statut_badge_classes">
                                            {{ these.statut_label }}
                                        </span>
                                        <div class="space-y-1 text-[11px] text-gray-500">
                                            <p v-if="these.date_debut">Debut : {{ these.date_debut }}</p>
                                            <p v-if="these.date_prevue_fin">Fin prevue : {{ these.date_prevue_fin }}</p>
                                            <p v-if="these.date_publication">Soutenue : {{ these.date_publication }}</p>
                                        </div>
                                    </div>

                                    <div class="flex-1 p-5">
                                        <div class="flex flex-wrap items-center gap-2 mb-3">
                                            <span v-if="these.specialite" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-indigo-50 text-[11px] text-indigo-700">
                                                {{ these.specialite.nom || 'Specialite' }}
                                            </span>
                                            <span v-if="these.ead" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-sky-50 text-[11px] text-sky-700 font-bold">
                                                {{ these.ead.sigle || these.ead.nom || 'EAD' }}
                                            </span>
                                        </div>

                                        <h2 class="text-base md:text-lg font-semibold text-gray-900 mb-2 group-hover:text-blue-700 line-clamp-2">
                                            {{ these.sujet_these }}
                                        </h2>

                                        <p v-if="these.resume" class="text-xs text-gray-600 line-clamp-3 mb-3">
                                            {{ these.resume }}
                                        </p>

                                        <div v-if="these.doctorant" class="flex items-center gap-2 text-xs text-gray-600">
                                            <div class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center text-[11px] font-semibold text-gray-700">
                                                {{ these.doctorant.initials }}
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="font-medium text-gray-800">{{ these.doctorant.name }}</span>
                                                <span class="text-[11px] text-gray-400">Doctorant</span>
                                            </div>
                                        </div>
                                    </div>
                                </Link>
                            </div>

                            <div class="mt-8">
                                <Pagination v-if="theses?.links" :links="theses.links" />
                            </div>
                        </div>
                        <div v-else class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 13h6m-6-4h6m5 12H4a2 2 0 01-2-2V5
                                                 a2 2 0 012-2h7l2 2h7a2 2 0 012 2v10
                                                 a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-800">Aucune these trouvee.</p>
                                <p class="text-xs text-gray-500">Modifiez vos criteres de recherche ou reessayez plus tard.</p>
                            </div>
                        </div>
                    </div>

                    <aside class="lg:col-span-1 space-y-4 sm:space-y-5 order-1 lg:order-2">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                            <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <span class="w-1.5 h-5 bg-gradient-to-b from-blue-600 to-indigo-600 rounded-full"></span>
                                Filtrer par statut
                            </h3>

                            <div class="space-y-2">
                                <button
                                    v-for="item in [
                                        { value: '', label: 'Toutes les theses', desc: 'Sans filtre de statut', color: 'bg-gray-400' },
                                        { value: 'preparation', label: 'En preparation', desc: 'Sujet valide, debut de travaux', color: 'bg-amber-500' },
                                        { value: 'en_cours', label: 'En cours', desc: 'Travaux et redaction en cours', color: 'bg-blue-500' },
                                        { value: 'soutenue', label: 'Soutenue', desc: 'These soutenue', color: 'bg-emerald-500' },
                                    ]"
                                    :key="item.value"
                                    type="button"
                                    class="w-full text-left px-3 py-2 rounded-xl border text-xs transition flex items-start gap-2"
                                    :class="statut === item.value ? 'bg-blue-50 border-blue-200 text-blue-800 shadow-sm' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50'"
                                    @click="setStatut(item.value)"
                                >
                                    <span class="mt-1">
                                        <span class="w-2 h-2 rounded-full inline-block" :class="item.color"></span>
                                    </span>
                                    <span class="flex-1">
                                        <span class="block font-semibold">{{ item.label }}</span>
                                        <span class="block text-[11px] text-gray-500">{{ item.desc }}</span>
                                    </span>
                                </button>
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 text-xs text-blue-900">
                            <p class="font-semibold mb-1">Astuce</p>
                            <p class="leading-relaxed">
                                Combinez la recherche par mots-cles avec le filtre de statut pour retrouver rapidement
                                une these precise (par auteur, annee ou thematique).
                            </p>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
    </div>
</template>
