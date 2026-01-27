<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import patternUrl from '@/assets/pattern.svg';

const props = defineProps({
    these: Object,
});

const doctorantName = computed(() => props.these?.doctorant?.name || '');

defineOptions({
    layout: FrontendLayout,
});
</script>

<template>
    <Head :title="these?.sujet_these || 'These'" />

    <div>
        <section class="relative gradient-primary pt-20 md:pt-24 pb-14 md:pb-16 overflow-hidden">
            <div class="absolute inset-0">
                <div class="absolute inset-0 opacity-10" :style="{ backgroundImage: `url(${patternUrl})` }"></div>
                <div class="absolute -top-10 right-0 w-72 h-72 bg-white/15 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 -left-16 w-80 h-80 bg-emerald-500/25 rounded-full blur-3xl"></div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <nav class="mb-6">
                    <ol class="flex items-center gap-2 text-xs md:text-sm text-white/75">
                        <li><Link :href="route('home')" class="hover:text-white transition">Accueil</Link></li>
                        <li>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </li>
                        <li><Link :href="route('theses.index')" class="hover:text-white transition">Theses</Link></li>
                        <li>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </li>
                        <li class="text-white font-semibold line-clamp-1 max-w-[260px] md:max-w-xl">
                            {{ these?.sujet_these }}
                        </li>
                    </ol>
                </nav>

                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8">
                    <div class="flex-1 space-y-4">
                        <p class="text-[11px] md:text-xs uppercase tracking-[0.2em] text-emerald-100">
                            These de doctorat
                        </p>

                        <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-white leading-snug">
                            {{ these?.sujet_these }}
                        </h1>

                        <div class="flex flex-wrap items-center gap-3 text-xs text-white/85">
                            <div v-if="doctorantName" class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur border border-white/20">
                                <div class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center text-[11px] font-semibold">
                                    {{ these?.doctorant?.initials || '' }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold text-white">{{ doctorantName }}</span>
                                    <span class="text-[11px] text-white/70">Doctorant</span>
                                </div>
                            </div>

                            <span v-if="these?.specialite" class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-100/60 text-xs">
                                <svg class="w-4 h-4 text-emerald-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"/>
                                </svg>
                                {{ these.specialite.nom || 'Specialite' }}
                            </span>

                            <span v-if="these?.ead" class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/20 border border-teal-100/60 text-xs">
                                <svg class="w-4 h-4 text-teal-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h10M4 18h10"/>
                                </svg>
                                {{ these.ead.sigle || these.ead.nom || "Equipe d'accueil" }}
                            </span>
                        </div>
                    </div>

                    <div class="w-full lg:w-auto lg:min-w-[260px] flex flex-col items-start lg:items-end gap-3">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full border text-xs font-semibold" :class="these?.statut_badge_classes">
                            <span class="w-1.5 h-1.5 rounded-full bg-current/70 mr-1.5"></span>
                            {{ these?.statut_label }}
                        </span>

                        <div class="flex flex-col items-start lg:items-end gap-1.5 text-[11px] text-white/85">
                            <span v-if="these?.date_debut" class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7 a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Debut de la these : {{ these.date_debut }}
                            </span>

                            <span v-if="these?.statut === 'soutenue' && these?.date_publication" class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Date de soutenance : {{ these.date_publication }}
                            </span>

                            <span v-if="these?.statut !== 'soutenue' && these?.date_prevue_fin" class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Fin prevue : {{ these.date_prevue_fin }}
                            </span>
                        </div>

                        <Link :href="route('theses.index')" class="inline-flex items-center gap-2 text-[11px] text-white/80 hover:text-white mt-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Retour a la liste des theses
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-12 md:py-16 bg-gradient-to-b from-slate-50 to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-8">
                <div class="lg:col-span-9 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                        <h2 class="text-lg md:text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-gradient-to-b from-emerald-600 to-teal-600 rounded-full"></span>
                            Resume de la these
                        </h2>

                        <div v-if="these?.resume_these" class="text-sm md:text-base text-gray-800 text-justify leading-relaxed space-y-3" v-html="these.resume_these.replace(/\n/g, '<br>')"></div>
                        <p v-else class="text-sm text-gray-500 italic">Le resume de cette these n'a pas encore ete renseigne.</p>

                        <div v-if="these?.fichier_pdf_url" class="mt-6 pt-4 border-t border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <p class="text-xs text-gray-500">Le manuscrit complet est disponible au format PDF.</p>
                            <a :href="these.fichier_pdf_url" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full bg-emerald-600 text-white text-xs font-semibold shadow-sm hover:bg-emerald-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16"/>
                                </svg>
                                Telecharger le manuscrit (PDF)
                            </a>
                        </div>
                    </div>

                    <div v-if="these?.mots_cles?.length" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-7">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M7 7h.01M7 3h10a2 2 0 012 2v4 a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h2z"/>
                            </svg>
                            Mots-cles
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            <span v-for="keyword in these.mots_cles" :key="keyword" class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs border border-emerald-100">
                                {{ keyword }}
                            </span>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-7">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="w-1.5 h-5 bg-gradient-to-b from-emerald-600 to-teal-600 rounded-full"></span>
                            Informations principales
                        </h2>

                        <dl class="space-y-2 text-sm">
                            <div v-if="doctorantName" class="flex flex-wrap gap-x-2 text-gray-900">
                                <dt class="font-medium text-gray-700">Doctorant :</dt>
                                <dd>{{ doctorantName }}</dd>
                            </div>

                            <div v-if="these?.specialite" class="flex flex-wrap gap-x-2 text-gray-900">
                                <dt class="font-medium text-gray-700">Specialite :</dt>
                                <dd>{{ these.specialite.nom || '' }}</dd>
                            </div>

                            <div v-if="these?.ead" class="flex flex-wrap gap-x-2 text-gray-900">
                                <dt class="font-medium text-gray-700">Equipe d'accueil :</dt>
                                <dd>{{ these.ead.nom || '' }}</dd>
                            </div>

                            <div v-if="these?.universite_soutenance" class="flex flex-wrap gap-x-2 text-gray-900">
                                <dt class="font-medium text-gray-700">Universite :</dt>
                                <dd>{{ these.universite_soutenance }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <aside class="lg:col-span-3 space-y-6">
                    <div v-if="these?.encadrants?.length" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Encadrants</h3>
                        <div class="space-y-3">
                            <div v-for="encadrant in these.encadrants" :key="encadrant.id" class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-700 font-semibold text-sm">
                                    {{ encadrant.name?.split(' ').map((part) => part[0]).join('').slice(0, 2) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ encadrant.name }}</p>
                                    <p v-if="encadrant.grade" class="text-xs text-gray-500">{{ encadrant.grade }}</p>
                                    <p v-if="encadrant.role" class="text-xs text-gray-500">{{ encadrant.role }}</p>
                                    <a v-if="encadrant.email" :href="`mailto:${encadrant.email}`" class="text-xs text-emerald-600 hover:text-emerald-800">
                                        {{ encadrant.email }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </section>
    </div>
</template>
