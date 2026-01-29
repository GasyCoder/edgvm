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
        <section class="py-12 bg-gradient-to-b from-gray-50 to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- En-tete -->
                <div class="flex items-center justify-between gap-4 pb-3 sm:pb-4 mb-4 sm:mb-6 border-b-2 border-gray-300">
                    <span class="text-sm sm:text-base font-bold text-gray-700 uppercase tracking-wide">Nom</span>
                    <span class="text-sm sm:text-base font-bold text-gray-700 uppercase tracking-wide hidden sm:block">EAD</span>
                </div>

                <div v-if="!specialites?.length" class="text-center py-16">
                    <p class="text-lg text-gray-500">Aucun programme disponible.</p>
                </div>

                <ul v-else class="space-y-3 sm:space-y-4">
                    <li
                        v-for="specialite in specialites"
                        :key="specialite.id"
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 sm:gap-4 py-2 sm:py-0 border-b border-gray-100 sm:border-0"
                    >
                        <span class="flex items-center gap-2 sm:gap-3 text-sm sm:text-lg text-gray-900">
                            <span class="w-2 sm:w-2.5 h-2 sm:h-2.5 rounded-full bg-ed-primary flex-shrink-0"></span>
                            {{ specialite.nom }}
                        </span>
                        <span v-if="specialite.ead" class="text-xs sm:text-base text-gray-600 sm:text-right flex-shrink-0 pl-4 sm:pl-0">
                            {{ specialite.ead }}
                        </span>
                    </li>
                </ul>
            </div>
        </section>
    </div>
</template>
