<script setup>
import { computed, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import patternUrl from '@/assets/pattern.svg';

const props = defineProps({
    page: Object,
});

const openOrganigramme = ref(false);

const hasContent = computed(() => {
    return Boolean(props.page?.contenu) || (props.page?.sections || []).length > 0;
});

const formatText = (content) => {
    if (!content) {
        return '';
    }

    return content.replace(/\n/g, '<br>');
};

defineOptions({
    layout: FrontendLayout,
});
</script>

<template>
    <Head :title="page?.titre || 'Page'" />

    <div>
        <section class="relative gradient-primary pt-16 sm:pt-20 md:pt-24 pb-10 sm:pb-12 md:pb-16 overflow-hidden">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute inset-0 opacity-10" :style="{ backgroundImage: `url(${patternUrl})` }"></div>
                <div class="absolute -top-24 -right-10 w-80 h-80 md:w-96 md:h-96 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -left-10 w-80 h-80 md:w-96 md:h-96 bg-white/10 rounded-full blur-3xl"></div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <nav class="mb-5 md:mb-7">
                    <ol class="flex flex-wrap items-center gap-2 text-[11px] sm:text-xs md:text-sm text-white/70">
                        <li>
                            <Link :href="route('home')" class="hover:text-white transition">
                                Accueil
                            </Link>
                        </li>
                        <li>
                            <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </li>
                        <li class="font-semibold text-white">
                            {{ page?.titre }}
                        </li>
                    </ol>
                </nav>

                <div class="max-w-3xl">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-[11px] sm:text-xs text-white/80 mb-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                        Page de contenu de l'EDGVM
                    </span>

                    <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-[2.75rem] font-semibold text-white mb-3 leading-tight">
                        {{ page?.titre }}
                    </h1>

                    <p v-if="page?.updated_at" class="text-white/80 text-[11px] sm:text-xs md:text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Derniere mise a jour :
                        <span class="font-semibold">
                            {{ page.updated_at }}
                        </span>
                    </p>
                </div>
            </div>
        </section>

        <section class="relative bg-gradient-to-b from-gray-50 to-white pb-14 md:pb-16 pt-4 md:pt-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div v-if="!hasContent" class="-mt-14 md:-mt-16">
                    <div class="bg-white rounded-2xl shadow-xl p-8 sm:p-10 md:p-12 border border-gray-100 text-center">
                        <div class="max-w-md mx-auto">
                            <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5
                                             a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414
                                             a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-semibold text-gray-900 mb-3">
                                Contenu en cours de redaction
                            </h3>
                            <p class="text-gray-600 leading-relaxed">
                                Cette page sera bientot enrichie avec des informations detaillees par l'equipe de l'Ecole Doctorale.
                            </p>
                        </div>
                    </div>
                </div>

                <div v-else class="-mt-10 sm:-mt-14 md:-mt-16">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 sm:gap-6 lg:gap-8">
                        <div class="lg:col-span-2 space-y-5 sm:space-y-7 lg:space-y-9">
                            <article v-if="page?.contenu" class="bg-white rounded-2xl shadow-xl p-4 sm:p-6 md:p-8 border border-gray-100">
                                <header class="mb-5 md:mb-7">
                                    <h2 class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-900 flex items-center gap-2">
                                        <span class="w-1.5 h-7 bg-gradient-to-b from-blue-600 to-indigo-600 rounded-full"></span>
                                        Presentation
                                    </h2>
                                </header>

                                <div class="prose prose-indigo prose-sm sm:prose max-w-none text-justify leading-relaxed" v-html="page.contenu"></div>
                            </article>

                            <div v-if="page?.sections?.length" class="space-y-5 sm:space-y-7 lg:space-y-9">
                                <section
                                    v-for="section in page.sections"
                                    :key="section.id"
                                    class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 hover:shadow-2xl transition-shadow duration-300"
                                >
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-0" v-if="section.image_url">
                                        <div
                                            class="relative h-56 sm:h-64 md:h-72 lg:h-full overflow-hidden group"
                                            :class="section.index % 2 === 0 ? '' : 'lg:order-2'"
                                        >
                                            <img :src="section.image_url" :alt="section.titre" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-black/5 to-transparent pointer-events-none"></div>
                                            <div class="absolute top-4 left-4 inline-flex items-center px-3 py-1 rounded-full bg-black/50 text-white text-xs font-medium backdrop-blur">
                                                Section {{ section.index + 1 }}
                                            </div>
                                        </div>

                                        <div class="p-4 sm:p-6 md:p-8 flex flex-col justify-center" :class="section.index % 2 === 0 ? '' : 'lg:order-1'">
                                            <h2 v-if="section.titre" class="text-xl sm:text-2xl md:text-3xl font-semibold text-gray-900 mb-4 md:mb-5 flex items-center gap-3">
                                                <span class="w-8 h-8 md:w-9 md:h-9 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white text-base md:text-lg flex items-center justify-center shadow-md">
                                                    {{ section.index + 1 }}
                                                </span>
                                                <span>{{ section.titre }}</span>
                                            </h2>
                                            <div v-if="section.contenu" class="prose prose-gray prose-sm sm:prose max-w-none text-justify leading-relaxed" v-html="formatText(section.contenu)"></div>
                                        </div>
                                    </div>

                                    <div v-else class="bg-white rounded-2xl shadow-xl p-4 sm:p-6 md:p-10 border border-gray-100 hover:shadow-2xl transition-shadow duration-300">
                                        <h2 v-if="section.titre" class="text-xl sm:text-2xl md:text-3xl font-semibold text-gray-900 mb-4 md:mb-5 flex items-center gap-3">
                                            <span class="flex items-center justify-center w-8 h-8 md:w-9 md:h-9 bg-gradient-to-br from-blue-600 to-indigo-600 text-white text-base md:text-lg font-semibold rounded-xl shadow-md">
                                                {{ section.index + 1 }}
                                            </span>
                                            {{ section.titre }}
                                        </h2>
                                        <div v-if="section.contenu" class="prose prose-gray prose-sm sm:prose max-w-none text-justify leading-relaxed" v-html="formatText(section.contenu)"></div>
                                    </div>
                                </section>
                            </div>
                        </div>

                        <aside class="lg:col-span-1 space-y-4 sm:space-y-6">
                            <div v-if="page?.slug === 'a-propos'" class="relative">
                                <button
                                    type="button"
                                    class="group w-full rounded-2xl overflow-hidden shadow-xl border border-gray-100 bg-gradient-to-br from-green-600 to-blue-700 relative"
                                    @click="openOrganigramme = true"
                                >
                                    <div class="absolute inset-0">
                                        <img
                                            src="/images/organigramme-edgvm.png"
                                            alt="Organigramme de l'Ecole Doctorale EDGVM"
                                            class="w-full h-full object-cover opacity-20 group-hover:opacity-90 group-hover:scale-105 transition-all duration-500"
                                        >
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/30 to-transparent"></div>
                                    </div>

                                    <div class="relative z-10 p-4 sm:p-6 md:p-7 flex flex-col gap-3 text-white">
                                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-black/40 backdrop-blur text-xs font-medium">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                                            Organigramme officiel EDGVM
                                        </div>

                                        <h3 class="text-base sm:text-lg md:text-xl font-semibold flex items-center gap-2">
                                            <svg class="w-5 h-5 md:w-6 md:h-6 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M6 20h12M6 4h12M6 4l2 16m8-16l-2 16"/>
                                            </svg>
                                            Organigramme de l'Ecole Doctorale
                                        </h3>

                                        <p class="text-[11px] sm:text-xs md:text-sm text-white/80 leading-relaxed">
                                            Visualisez la structure de la direction, des equipes d'accueil et des instances de l'EDGVM.
                                        </p>

                                        <div class="mt-2 flex flex-wrap items-center justify-between gap-2 text-xs md:text-sm">
                                            <span class="inline-flex items-center gap-1 text-white/80">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M4 6h16M4 12h16M4 18h16"/>
                                                </svg>
                                                Cliquer pour agrandir
                                            </span>

                                            <a
                                                href="/docs/organigramme-edgvm.pdf"
                                                target="_blank"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/15 hover:bg-white/25 text-[11px] font-medium"
                                                @click.stop
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M12 5v14m0-14l-4 4m4-4l4 4M5 19h14"/>
                                                </svg>
                                                Version PDF
                                            </a>
                                        </div>
                                    </div>
                                </button>

                                <div v-if="openOrganigramme" class="fixed inset-0 z-[999] flex items-center justify-center bg-black/70 backdrop-blur-sm" @keydown.esc="openOrganigramme = false">
                                    <div class="relative max-w-5xl w-full mx-4">
                                        <div class="flex items-center justify-between mb-3 text-white">
                                            <h4 class="text-sm md:text-base font-semibold">
                                                Organigramme de l'Ecole Doctorale EDGVM
                                            </h4>

                                            <div class="flex items-center gap-2">
                                                <a href="/docs/organigramme-edgvm.pdf" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/90 text-gray-800 text-xs font-medium shadow">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M12 5v14m0-14l-4 4m4-4l4 4M5 19h14"/>
                                                    </svg>
                                                    PDF
                                                </a>
                                                <button type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/10 text-white text-xs" @click="openOrganigramme = false">
                                                    Fermer
                                                </button>
                                            </div>
                                        </div>

                                        <div class="relative bg-white rounded-2xl overflow-hidden shadow-2xl max-h-[75vh] overflow-auto">
                                            <img src="/images/organigramme-edgvm.png" alt="Organigramme" class="w-full h-auto">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
