<script setup>
import { Head, Link } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';

const props = defineProps({
    evenement: Object,
});

defineOptions({
    layout: FrontendLayout,
});
</script>

<template>
    <Head :title="evenement?.titre || 'Evenement'" />

    <div>
        <section class="relative pt-24 md:pt-28 pb-10 overflow-hidden">
            <div v-if="evenement?.image_url" class="absolute inset-0">
                <img :src="evenement.image_url" alt="Image de l'evenement" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/75 to-black/80"></div>
            </div>
            <div v-else class="absolute inset-0">
                <div class="absolute inset-0 opacity-15 pointer-events-none">
                    <div class="absolute -top-10 right-0 w-40 h-40 bg-white rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 -left-10 w-40 h-40 bg-ed-yellow rounded-full blur-3xl"></div>
                </div>
                <div class="absolute inset-0 bg-gradient-to-b from-ed-primary via-ed-secondary to-black/80 opacity-80"></div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                    <Link :href="route('evenements.index')" class="inline-flex items-center gap-1.5 text-xs sm:text-sm text-white/80 hover:text-white">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        <span>Retour a l'agenda</span>
                    </Link>

                    <div class="inline-flex items-center gap-1.5 rounded-full bg-black/35 text-white px-3 py-1.5 text-[11px] font-medium">
                        <span class="inline-flex w-1.5 h-1.5 rounded-full" :class="evenement?.est_termine ? 'bg-amber-400' : 'bg-emerald-400'"></span>
                        <span>{{ evenement?.est_termine ? 'Evenement termine' : 'Evenement a venir' }}</span>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row items-start gap-6 text-white">
                    <div class="flex md:flex-col items-center md:items-stretch gap-3">
                        <div class="flex flex-col items-center justify-center rounded-2xl bg-black/25 backdrop-blur w-20 h-20 border border-white/20 shadow-lg shadow-black/40">
                            <span class="text-2xl font-black leading-none">
                                {{ evenement?.jour }}
                            </span>
                            <span class="text-[11px] uppercase tracking-wide">
                                {{ evenement?.mois }}
                            </span>
                        </div>
                    </div>

                    <div class="flex-1 min-w-0 space-y-3">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/20 text-[11px] font-semibold tracking-[0.18em] uppercase">
                            <span class="w-2 h-2 rounded-full bg-ed-yellow"></span>
                            <span>Agenda EDGVM</span>
                        </div>

                        <h1 class="text-2xl sm:text-3xl md:text-4xl font-black leading-tight">
                            {{ evenement?.titre }}
                        </h1>

                        <div class="space-y-1.5 text-sm text-white/90">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>{{ evenement?.periode_aff }}</span>
                                </span>

                                <span v-if="evenement?.heure_debut_aff" class="inline-flex items-center gap-1.5 text-white/85">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>{{ evenement?.heure_debut_aff }}</span>
                                </span>
                            </div>

                            <div v-if="evenement?.lieu" class="inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M17.657 16.657L13.414 20.9a1 1 0 0 1-1.414 0L6.343 16.657a8 8 0 1 1 11.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M15 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                </svg>
                                <span>{{ evenement?.lieu }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col items-end gap-2">
                        <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-semibold" :class="evenement?.type_classe">
                            {{ evenement?.type_texte }}
                        </span>

                        <span v-if="evenement?.est_important" class="inline-flex items-center gap-1 rounded-full bg-red-50/95 px-2.5 py-1 text-[11px] font-semibold text-red-700 shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 9v3.5m0 4h.01M12 5a7 7 0 100 14 7 7 0 000-14z"/>
                            </svg>
                            Important
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-10 md:py-14 bg-gradient-to-b from-gray-50 via-white to-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 md:p-6 lg:p-7 space-y-6">
                            <div>
                                <h2 class="text-sm font-semibold text-gray-800 mb-2">Description</h2>
                                <div v-if="evenement?.description" class="text-sm text-gray-700 leading-relaxed space-y-3" v-html="evenement.description.replace(/\n/g, '<br>')"></div>
                                <p v-else class="text-sm text-gray-500">Aucune description detaillee n'a ete fournie pour cet evenement.</p>
                            </div>

                            <div v-if="evenement?.image_url" class="pt-2">
                                <h3 class="text-sm font-semibold text-gray-800 mb-2">Visuel de l'evenement</h3>
                                <div class="relative w-full rounded-2xl overflow-hidden border border-gray-200 bg-gray-50 shadow-sm">
                                    <img :src="evenement.image_url" alt="Visuel de l'evenement" class="w-full h-64 md:h-72 object-cover">
                                </div>
                            </div>

                            <div v-if="evenement?.document?.url" class="pt-2 border-t border-gray-100">
                                <h3 class="text-sm font-semibold text-gray-800 mb-2 flex items-center gap-2">
                                    <span class="inline-flex w-7 h-7 items-center justify-center rounded-full bg-emerald-50 text-emerald-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 3h4a2 2 0 012 2v4M15 3l-6 6M15 3v4a2 2 0 01-2 2h-4M5 21h14a2 2 0 002-2V7a2 2 0 00-.586-1.414l-3-3A2 2 0 0014 2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </span>
                                    <span>Document associe</span>
                                </h3>

                                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 rounded-xl border border-emerald-100 bg-emerald-50/60 px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-white text-emerald-700 border border-emerald-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m0 0l3-3m-3 3l3 3m7-6.5V17a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h5.5"/>
                                            </svg>
                                        </div>
                                        <div class="flex flex-col text-xs">
                                            <span class="font-semibold text-emerald-900 truncate max-w-[220px]">
                                                {{ evenement?.document?.display_name || evenement?.document?.nom_original }}
                                            </span>
                                            <span class="text-[11px] text-emerald-700">
                                                {{ (evenement?.document?.mime_type || 'PDF').toUpperCase() }}
                                                <span v-if="evenement?.document?.created_at">* ajoute le {{ evenement.document.created_at }}</span>
                                            </span>
                                        </div>
                                    </div>

                                    <a :href="evenement.document.url" target="_blank" class="inline-flex items-center gap-1.5 rounded-full bg-emerald-600 px-3 py-1.5 text-[11px] font-semibold text-white hover:bg-emerald-700 shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"/>
                                        </svg>
                                        <span>Telecharger le PDF</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <aside class="lg:col-span-1 space-y-6">
                        <div class="bg-white rounded-2xl shadow-xl p-6 border-2 border-blue-100">
                            <h3 class="text-lg font-bold text-gray-900 mb-3">Contactez-nous</h3>
                            <p class="text-sm text-gray-600 mb-4">
                                Pour plus d'informations sur cet evenement.
                            </p>
                            <Link :href="route('contact')" class="block text-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold text-sm">
                                Nous contacter
                            </Link>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
    </div>
</template>
