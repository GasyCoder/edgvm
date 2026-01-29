<script setup>
import { computed, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';

const props = defineProps({
    ead: Object,
    specialites: Array,
    theses: Array,
});

const showAll = ref(false);
const searchQuery = ref('');

const filteredTheses = computed(() => {
    if (!searchQuery.value) {
        return props.theses || [];
    }

    const query = searchQuery.value.toLowerCase();

    return (props.theses || []).filter((these) => {
        const name = these.doctorant?.name?.toLowerCase() || '';
        const titre = (these.sujet_these || '').toLowerCase();
        return name.includes(query) || titre.includes(query);
    });
});

const displayedTheses = computed(() => {
    return showAll.value ? filteredTheses.value : filteredTheses.value.slice(0, 6);
});

const hasMore = computed(() => filteredTheses.value.length > 6);

defineOptions({
    layout: FrontendLayout,
});
</script>

<template>
    <Head :title="ead?.nom || 'EAD'" />

    <div>
        <section class="relative gradient-primary pt-20 sm:pt-24 md:pt-28 pb-12 sm:pb-16 md:pb-20 overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-10 right-10 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <nav class="mb-6">
                    <ol class="flex items-center gap-2 text-xs text-white/70">
                        <li><Link :href="route('home')" class="hover:text-white transition">Accueil</Link></li>
                        <li>></li>
                        <li><Link :href="route('ead.index')" class="hover:text-white transition">EAD</Link></li>
                        <li>></li>
                        <li class="text-white font-semibold">{{ ead?.nom }}</li>
                    </ol>
                </nav>

                <div v-if="ead?.domaine" class="mb-4">
                    <span class="inline-block px-4 py-2 bg-white/20 backdrop-blur-lg text-white text-sm font-semibold rounded-full border border-white/30">
                        {{ ead.domaine }}
                    </span>
                </div>

                <h1 class="text-3xl md:text-4xl font-bold text-white mb-6">
                    {{ ead?.nom }}
                </h1>

                <div class="flex flex-wrap gap-4 text-white/90 text-sm">
                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-lg rounded-full px-4 py-2 border border-white/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span>{{ ead?.specialites_count || 0 }} specialites</span>
                    </div>

                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-lg rounded-full px-4 py-2 border border-white/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>{{ ead?.doctorats_count || 0 }} doctorants actifs</span>
                    </div>

                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-lg rounded-full px-4 py-2 border border-white/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ ead?.theses_soutenues_count || 0 }} theses soutenues</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-12 bg-gradient-to-b from-gray-50 to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                    <div class="lg:col-span-2 space-y-6 lg:space-y-8">
                        <div class="bg-white rounded-2xl shadow-xl p-4 sm:p-6 lg:p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Presentation
                            </h2>
                            <div class="prose prose-gray max-w-none">
                                <p class="text-gray-700 leading-relaxed">{{ ead?.description }}</p>
                            </div>
                        </div>

                        <div v-if="specialites?.length" class="bg-white rounded-2xl shadow-xl p-4 sm:p-6 lg:p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                Specialites de recherche
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <Link
                                    v-for="specialite in specialites"
                                    :key="specialite.id"
                                    :href="route('programmes.show', specialite.slug)"
                                    class="group p-5 bg-gradient-to-br from-gray-50 to-white border-2 border-gray-100 rounded-xl hover:border-ed-primary hover:shadow-lg transition-all"
                                >
                                    <div class="flex items-start gap-3">
                                        <div class="w-10 h-10 bg-ed-primary/10 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-ed-primary group-hover:scale-110 transition-all">
                                            <svg class="w-5 h-5 text-ed-primary group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="font-bold text-gray-900 group-hover:text-ed-primary transition-colors mb-1">
                                                {{ specialite.nom }}
                                            </h3>
                                            <p class="text-xs text-gray-600 line-clamp-2">
                                                {{ specialite.description }}
                                            </p>
                                            <div class="flex items-center gap-3 mt-2 text-xs text-gray-500">
                                                <span>{{ specialite.theses_en_cours }} en cours</span>
                                                <span>*</span>
                                                <span>{{ specialite.theses_soutenues }} soutenues</span>
                                            </div>
                                        </div>
                                    </div>
                                </Link>
                            </div>
                        </div>

                        <div v-if="theses?.length" class="bg-white rounded-2xl shadow-xl p-4 sm:p-6 lg:p-8">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                                <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    Doctorants en cours
                                    <span class="text-lg font-normal text-gray-500">({{ filteredTheses.length }})</span>
                                </h2>

                                <div v-if="theses.length > 6" class="relative w-full sm:w-64">
                                    <input
                                        v-model="searchQuery"
                                        type="text"
                                        placeholder="Rechercher un doctorant..."
                                        class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                    >
                                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <Link
                                    v-for="these in displayedTheses"
                                    :key="these.id"
                                    :href="route('doctorants.show', these.doctorant?.id)"
                                    class="group flex items-center gap-4 p-4 bg-gradient-to-r from-gray-50 to-white border border-gray-100 rounded-xl hover:border-blue-300 hover:shadow-lg transition-all duration-200 cursor-pointer"
                                >
                                    <div class="relative w-11 h-11 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                        <span class="text-white font-bold text-sm">
                                            {{ these.doctorant?.name?.split(' ').map((n) => n[0]).join('').substring(0, 2) }}
                                        </span>
                                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-2">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2">
                                                    <p class="font-semibold text-gray-900 text-sm group-hover:text-blue-600 transition-colors">
                                                        {{ these.doctorant?.name }}
                                                    </p>
                                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </div>
                                                <p class="text-xs text-gray-500 mt-0.5">Matricule: {{ these.doctorant?.matricule || 'N/A' }}</p>
                                                <p class="text-xs text-gray-600 line-clamp-1 mt-1">{{ these.sujet_these }}</p>
                                            </div>

                                            <span v-if="these.specialite" class="hidden sm:inline-flex px-2 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-md whitespace-nowrap">
                                                {{ these.specialite.nom }}
                                            </span>
                                        </div>

                                        <div class="flex items-center gap-3 mt-2 text-xs text-gray-500">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                Debut: {{ new Date(these.date_debut).getFullYear() }}
                                            </span>
                                            <span v-if="these.date_prevue_fin" class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Fin prevue: {{ new Date(these.date_prevue_fin).getFullYear() }}
                                            </span>
                                        </div>
                                    </div>
                                </Link>
                            </div>

                            <div v-if="filteredTheses.length === 0" class="text-center py-8">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <p class="text-gray-500 text-sm">Aucun doctorant trouve</p>
                            </div>

                            <div v-if="hasMore && !searchQuery" class="mt-6 text-center">
                                <button
                                    type="button"
                                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 text-blue-700 font-semibold rounded-lg transition-all duration-200 border border-blue-200"
                                    @click="showAll = !showAll"
                                >
                                    <span>{{ showAll ? 'Voir moins' : `Voir tous les doctorants (${filteredTheses.length})` }}</span>
                                    <svg class="w-4 h-4 transition-transform duration-200" :class="showAll && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <aside class="lg:col-span-1">
                        <div class="lg:sticky lg:top-24 space-y-4 sm:space-y-6">
                            <div v-if="ead?.responsable" class="bg-white rounded-2xl shadow-xl p-4 sm:p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Responsable</h3>
                                <div class="flex items-start gap-4">
                                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-bold text-gray-900">{{ ead.responsable.name }}</p>
                                        <p v-if="ead.responsable.grade" class="text-sm text-gray-600">{{ ead.responsable.grade }}</p>
                                        <a v-if="ead.responsable.email" :href="`mailto:${ead.responsable.email}`" class="text-sm text-blue-600 hover:text-blue-800 break-all">
                                            {{ ead.responsable.email }}
                                        </a>
                                        <p v-if="ead.responsable.phone" class="text-sm text-gray-600 mt-1">TEL {{ ead.responsable.phone }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl shadow-xl p-4 sm:p-6 text-white">
                                <h3 class="text-lg font-bold mb-4"> Statistiques</h3>
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center pb-3 border-b border-white/20">
                                        <span class="text-sm text-white/80">Specialites</span>
                                        <span class="text-2xl font-bold">{{ ead?.specialites_count || 0 }}</span>
                                    </div>
                                    <div class="flex justify-between items-center pb-3 border-b border-white/20">
                                        <span class="text-sm text-white/80">Doctorants</span>
                                        <span class="text-2xl font-bold">{{ ead?.doctorats_count || 0 }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-white/80">Theses soutenues</span>
                                        <span class="text-2xl font-bold">{{ ead?.theses_soutenues_count || 0 }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-2xl shadow-xl p-4 sm:p-6 border-2 border-blue-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-3">Contactez-nous</h3>
                                <p class="text-sm text-gray-600 mb-4">Pour plus d'informations sur cette equipe d'accueil doctoral.</p>
                                <a href="mailto:contact@edgvm.mg" class="block text-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold text-sm">
                                    Nous contacter
                                </a>
                            </div>

                            <div class="bg-white rounded-2xl shadow-xl p-6">
                                <Link :href="route('ead.index')" class="flex items-center gap-2 text-gray-600 hover:text-ed-primary transition text-sm font-semibold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    Toutes les EAD
                                </Link>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
    </div>
</template>
