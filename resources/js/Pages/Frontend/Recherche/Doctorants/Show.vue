<script setup>
import { computed, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';

const props = defineProps({
    doctorant: Object,
    thesePrincipale: Object,
    thesesHistorique: Array,
});

const resumeExpanded = ref(false);

const statusLabel = computed(() => {
    if (!props.thesePrincipale?.statut) {
        return 'These';
    }

    return props.thesePrincipale.statut === 'soutenue' ? 'These soutenue' : 'These en cours';
});

defineOptions({
    layout: FrontendLayout,
});
</script>

<template>
    <Head :title="doctorant?.name || 'Doctorant'" />

    <div>
        <section class="relative gradient-primary pt-20 sm:pt-24 md:pt-28 pb-12 sm:pb-16 md:pb-20 overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-10 right-10 w-96 h-96 bg-white rounded-full blur-3xl"></div>
                <div class="absolute bottom-10 left-10 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 mt-8 sm:mt-12 md:mt-16">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6">
                    <div class="relative">
                        <div class="w-20 h-20 sm:w-28 sm:h-28 bg-gradient-to-br from-white to-gray-100 rounded-full flex items-center justify-center shadow-2xl border-4 border-white/30">
                            <span class="text-2xl sm:text-4xl font-bold text-indigo-600">
                                {{ doctorant?.initials || 'NN' }}
                            </span>
                        </div>
                        <div class="absolute -bottom-1 -right-1 sm:-bottom-2 sm:-right-2 w-6 h-6 sm:w-8 sm:h-8 bg-green-500 border-2 sm:border-4 border-white rounded-full"></div>
                    </div>

                    <div class="flex-1">
                        <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-1 sm:mb-2">
                            {{ doctorant?.name || 'Non renseigne' }}
                        </h1>

                        <p v-if="doctorant?.matricule" class="text-white/80 text-xs sm:text-sm mb-2 sm:mb-3">
                            Matricule: {{ doctorant.matricule }}
                        </p>

                        <div class="flex flex-wrap gap-1.5 sm:gap-2">
                            <span v-if="doctorant?.niveau" class="inline-flex items-center gap-1 sm:gap-1.5 px-2 sm:px-3 py-1 sm:py-1.5 bg-white/20 backdrop-blur-lg text-white text-xs sm:text-sm font-semibold rounded-full border border-white/30">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 14l9-5-9-5-9 5 9 5z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055
                                            a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                </svg>
                                {{ doctorant.niveau }}
                            </span>

                            <span v-if="doctorant?.badge" class="inline-flex items-center gap-1 sm:gap-1.5 px-2 sm:px-3 py-1 sm:py-1.5 backdrop-blur-lg text-xs sm:text-sm font-semibold rounded-full border" :class="doctorant.badge.class">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293
                                            a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293
                                            a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                          clip-rule="evenodd"/>
                                </svg>
                                {{ doctorant.badge.text }}
                            </span>

                            <span v-if="thesePrincipale?.specialite" class="inline-flex items-center gap-1 sm:gap-1.5 px-2 sm:px-3 py-1 sm:py-1.5 bg-blue-500/20 backdrop-blur-lg text-white text-xs sm:text-sm font-semibold rounded-full border border-blue-400/30">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 hidden xs:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13
                                            C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13
                                            C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13
                                            C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <span class="line-clamp-1">{{ thesePrincipale.specialite.nom }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-8 sm:py-10 md:py-12 bg-gradient-to-b from-gray-50 to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                    <div class="lg:col-span-2 space-y-4 sm:space-y-6 lg:space-y-8">
                        <div v-if="thesePrincipale" class="bg-white rounded-xl sm:rounded-2xl shadow-xl p-4 sm:p-6 lg:p-8">
                            <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-4 sm:mb-6 flex items-center gap-2">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414
                                            a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                {{ statusLabel }}
                            </h2>

                            <div class="space-y-4 sm:space-y-6">
                                <div>
                                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 sm:mb-2">Sujet de these</h3>
                                    <p class="text-sm sm:text-base text-gray-700 font-bold leading-relaxed">
                                        {{ thesePrincipale.sujet_these }}
                                    </p>
                                </div>

                                <div v-if="thesePrincipale.description">
                                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 sm:mb-2">Description</h3>
                                    <p class="text-sm sm:text-base text-gray-700 leading-relaxed">
                                        {{ thesePrincipale.description }}
                                    </p>
                                </div>

                                <div v-if="thesePrincipale.resume_these">
                                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-3 flex items-center gap-2">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414
                                                     a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Resume
                                    </h3>

                                    <p v-if="!resumeExpanded" class="text-sm sm:text-base text-gray-700 leading-relaxed whitespace-pre-line text-justify">
                                        {{ thesePrincipale.resume_limite }}
                                    </p>
                                    <p v-else class="text-sm sm:text-base text-gray-700 leading-relaxed whitespace-pre-line text-justify">
                                        {{ thesePrincipale.resume_these }}
                                    </p>

                                    <button
                                        v-if="thesePrincipale.show_read_more"
                                        type="button"
                                        class="mt-2 sm:mt-3 inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors"
                                        @click="resumeExpanded = !resumeExpanded"
                                    >
                                        <span>{{ resumeExpanded ? 'Reduire' : 'Lire la suite' }}</span>
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 transition-transform" :class="resumeExpanded && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                </div>

                                <div v-if="thesePrincipale.mots_cles?.length">
                                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-3 flex items-center gap-2">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7
                                                     a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        Mots-cles
                                    </h3>
                                    <div class="flex flex-wrap gap-1.5 sm:gap-2">
                                        <span v-for="mot in thesePrincipale.mots_cles" :key="mot" class="px-2 sm:px-3 py-1 sm:py-1.5 bg-indigo-50 text-indigo-700 text-xs sm:text-sm font-medium rounded-full border border-indigo-100">
                                            {{ mot }}
                                        </span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4 mt-4 sm:mt-6">
                                    <div v-if="thesePrincipale.date_debut" class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg sm:rounded-xl p-3 sm:p-4 border border-blue-100">
                                        <p class="text-[10px] sm:text-xs text-blue-600 font-semibold mb-0.5 sm:mb-1">Date de debut</p>
                                        <p class="text-sm sm:text-lg font-bold text-blue-900">
                                            {{ thesePrincipale.date_debut }}
                                        </p>
                                    </div>

                                    <div v-if="thesePrincipale.date_publication || thesePrincipale.date_prevue_fin" class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg sm:rounded-xl p-3 sm:p-4 border border-purple-100">
                                        <p class="text-[10px] sm:text-xs text-purple-600 font-semibold mb-0.5 sm:mb-1">
                                            {{ thesePrincipale.statut === 'soutenue' ? 'Soutenance' : (thesePrincipale.date_publication ? 'Publication' : 'Fin prevue') }}
                                        </p>
                                        <p class="text-sm sm:text-lg font-bold text-purple-900">
                                            {{ thesePrincipale.date_publication || thesePrincipale.date_prevue_fin }}
                                        </p>
                                    </div>

                                    <div v-if="thesePrincipale.duree" class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg sm:rounded-xl p-3 sm:p-4 border border-green-100">
                                        <p class="text-[10px] sm:text-xs text-green-600 font-semibold mb-0.5 sm:mb-1">Duree</p>
                                        <p class="text-sm sm:text-lg font-bold text-green-900">
                                            {{ thesePrincipale.duree }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-else class="bg-white rounded-xl sm:rounded-2xl shadow-xl p-4 sm:p-6 md:p-8">
                            <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-2 sm:mb-3">These</h2>
                            <p class="text-sm sm:text-base text-gray-600">
                                Aucune these n'est actuellement associee a ce doctorant.
                            </p>
                        </div>

                        <div v-if="thesePrincipale?.encadrants?.length" class="bg-white rounded-xl sm:rounded-2xl shadow-xl p-4 sm:p-6 md:p-8">
                            <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-4 sm:mb-6 flex items-center gap-2">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                Encadrement
                            </h2>

                            <div class="space-y-3 sm:space-y-4">
                                <div v-for="encadrant in thesePrincipale.encadrants" :key="encadrant.id" class="flex flex-col sm:flex-row sm:items-start gap-3 sm:gap-4 p-3 sm:p-4 bg-gradient-to-r from-gray-50 to-white border border-gray-100 rounded-lg sm:rounded-xl hover:border-indigo-200 hover:shadow-md transition-all">
                                    <div class="flex items-center gap-3 sm:gap-0">
                                        <div class="w-10 h-10 sm:w-14 sm:h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 sm:w-7 sm:h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                      d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                      clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="sm:hidden flex-1 min-w-0">
                                            <p class="font-bold text-gray-900 text-sm">
                                                {{ encadrant.name || 'Non renseigne' }}
                                            </p>
                                            <span class="inline-block px-2 py-0.5 bg-indigo-100 text-indigo-700 text-[10px] font-semibold rounded-full mt-0.5">
                                                {{ encadrant.role || 'Encadrant' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-1 hidden sm:block">
                                        <div class="flex items-start justify-between gap-2">
                                            <div>
                                                <p class="font-bold text-gray-900">
                                                    {{ encadrant.name || 'Non renseigne' }}
                                                </p>
                                                <p v-if="encadrant.grade" class="text-sm text-gray-600">{{ encadrant.grade }}</p>
                                                <a v-if="encadrant.email" :href="`mailto:${encadrant.email}`" class="text-sm text-indigo-600 hover:text-indigo-800 hover:underline">
                                                    {{ encadrant.email }}
                                                </a>
                                            </div>
                                            <span class="px-2 sm:px-3 py-1 bg-indigo-100 text-indigo-700 text-[10px] sm:text-xs font-semibold rounded-full flex-shrink-0">
                                                {{ encadrant.role || 'Encadrant' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="sm:hidden text-xs text-gray-600 space-y-0.5">
                                        <p v-if="encadrant.grade">{{ encadrant.grade }}</p>
                                        <a v-if="encadrant.email" :href="`mailto:${encadrant.email}`" class="text-indigo-600 hover:text-indigo-800 block">
                                            {{ encadrant.email }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="thesesHistorique?.length" class="bg-white rounded-xl sm:rounded-2xl shadow-xl p-4 sm:p-6 md:p-8">
                            <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-4 sm:mb-6 flex items-center gap-2">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Historique
                            </h2>

                            <div class="space-y-2 sm:space-y-3">
                                <div v-for="these in thesesHistorique" :key="these.id" class="flex items-start gap-2 sm:gap-3 p-3 sm:p-4 bg-gray-50 rounded-lg">
                                    <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 text-xs sm:text-sm line-clamp-2">
                                            {{ these.sujet_these }}
                                        </p>
                                        <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 mt-1">
                                            <span class="text-[10px] sm:text-xs text-gray-600">
                                                {{ these.date_debut || '' }} - {{ these.date_publication || 'En cours' }}
                                            </span>
                                            <span class="px-1.5 sm:px-2 py-0.5 rounded text-[10px] sm:text-xs font-medium" :class="these.statut === 'soutenue' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'">
                                                {{ these.statut }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <aside class="lg:col-span-1">
                        <div class="lg:sticky lg:top-24 space-y-4 sm:space-y-6">
                            <div v-if="thesePrincipale" class="bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl sm:rounded-2xl shadow-xl p-4 sm:p-6 text-white">
                                <h3 class="text-base sm:text-lg font-bold mb-3 sm:mb-4 flex items-center gap-2">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5
                                                 m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1
                                                 m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    Rattachement
                                </h3>

                                <div class="space-y-3 sm:space-y-4">
                                    <div v-if="thesePrincipale.ead">
                                        <p class="text-[10px] sm:text-xs text-white/70 mb-0.5 sm:mb-1">EAD</p>
                                        <Link :href="route('ead.show', thesePrincipale.ead.slug)" class="text-sm sm:text-base font-semibold hover:underline block">
                                            {{ thesePrincipale.ead.nom }}
                                        </Link>
                                    </div>

                                    <div v-if="thesePrincipale.specialite">
                                        <p class="text-[10px] sm:text-xs text-white/70 mb-0.5 sm:mb-1">Specialite</p>
                                        <Link :href="route('programmes.show', thesePrincipale.specialite.slug)" class="text-sm sm:text-base font-semibold hover:underline block">
                                            {{ thesePrincipale.specialite.nom }}
                                        </Link>
                                    </div>
                                </div>
                            </div>

                            <div v-if="doctorant?.date_inscription || doctorant?.theses_count" class="bg-white rounded-xl sm:rounded-2xl shadow-xl p-4 sm:p-6">
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-4 flex items-center gap-2">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2
                                                 a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0
                                                 a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14
                                                 a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    Stats
                                </h3>

                                <div class="space-y-2 sm:space-y-3 text-xs sm:text-sm">
                                    <div v-if="doctorant?.date_inscription" class="flex justify-between items-center pb-2 sm:pb-3 border-b border-gray-100">
                                        <span class="text-gray-600">Inscription</span>
                                        <span class="font-semibold text-gray-900">
                                            {{ doctorant.date_inscription }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between items-center pb-2 sm:pb-3 border-b border-gray-100">
                                        <span class="text-gray-600">Theses</span>
                                        <span class="font-semibold text-gray-900">
                                            {{ doctorant.theses_count || 0 }}
                                        </span>
                                    </div>

                                    <div v-if="doctorant?.theses_soutenues_count" class="flex justify-between items-center">
                                        <span class="text-gray-600">Soutenues</span>
                                        <span class="font-semibold text-green-600">
                                            {{ doctorant.theses_soutenues_count }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl p-4 sm:p-6">
                                <Link
                                    v-if="thesePrincipale?.ead"
                                    :href="route('ead.show', thesePrincipale.ead.id)"
                                    class="flex items-center gap-2 text-gray-600 hover:text-indigo-600 transition text-xs sm:text-sm font-semibold"
                                >
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    Retour a l'EAD
                                </Link>
                                <Link v-else :href="route('ead.index')" class="flex items-center gap-2 text-gray-600 hover:text-indigo-600 transition text-xs sm:text-sm font-semibold">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
