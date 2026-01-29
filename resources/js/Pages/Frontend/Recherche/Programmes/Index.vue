<script setup>
import { Head } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';

defineProps({
    specialites: Array,
});

defineOptions({
    layout: FrontendLayout,
});
</script>

<template>
    <Head title="Programmes de recherche" />

    <div>
        <!-- Hero Section -->
        <section class="relative gradient-primary pt-20 sm:pt-24 md:pt-28 pb-12 sm:pb-16 md:pb-20 overflow-hidden">
            <div class="absolute inset-0 opacity-15 pointer-events-none">
                <div class="absolute -top-10 right-0 w-40 h-40 bg-white rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 -left-10 w-40 h-40 bg-ed-yellow rounded-full blur-3xl"></div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center text-white">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 mb-4">
                    <span class="w-2 h-2 rounded-full bg-ed-yellow"></span>
                    <span class="text-xs font-semibold tracking-[0.18em] uppercase">
                        EDGVM
                    </span>
                </div>
                <h1 class="text-3xl md:text-4xl font-black mb-3">
                    Programmes de Recherche
                </h1>
                <p class="text-base md:text-lg text-white/90 max-w-2xl mx-auto">
                    Nos specialites de recherche doctorale.
                </p>
            </div>
        </section>

        <!-- Liste des programmes -->
        <section class="py-12 sm:py-16 bg-gradient-to-b from-gray-50 to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between sm:gap-6">
                    <div class="max-w-2xl space-y-2">
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">
                            Les programmes disponibles
                        </h2>
                        <p class="text-sm sm:text-base text-gray-600">
                            Chaque specialite est associee a une equipe d'accueil doctorale (EAD).
                        </p>
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-4 py-2 text-xs sm:text-sm font-semibold text-gray-700 shadow-sm">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-ed-primary/10 text-ed-primary">
                            {{ specialites?.length || 0 }}
                        </span>
                        Programmes disponibles
                    </div>
                </div>

                <div v-if="!specialites?.length" class="text-center py-16">
                    <p class="text-lg text-gray-500">Aucun programme disponible.</p>
                </div>

                <div v-else class="grid grid-cols-1 gap-4 sm:gap-6 lg:grid-cols-2 mt-8">
                    <article
                        v-for="specialite in specialites"
                        :key="specialite.id"
                        class="group rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-ed-primary/40 hover:shadow-md"
                    >
                        <div class="flex flex-col gap-4">
                            <div class="flex items-start gap-3">
                                <span class="mt-1.5 h-2.5 w-2.5 flex-shrink-0 rounded-full bg-ed-primary"></span>
                                <div class="flex min-w-0 flex-col gap-2">
                                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 break-words">
                                        {{ specialite.nom }}
                                    </h3>
                                    <div class="flex flex-col gap-1 text-sm text-gray-600">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">EAD</span>
                                        <span class="break-words">
                                            {{ specialite.ead?.nom || specialite.ead || 'Non renseignee' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <span class="h-1.5 w-1.5 rounded-full bg-ed-yellow"></span>
                                Programme de recherche doctorale
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>
    </div>
</template>
