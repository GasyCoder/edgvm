<script setup>
import { Head, Link } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import Container from '@/Components/Frontend/UI/Container.vue';

defineProps({
    specialites: Array,
});

defineOptions({
    layout: FrontendLayout,
});
</script>

<template>
    <Head title="Programmes de recherche" />

    <!-- Hero -->
    <section class="mt-16 bg-ed-teal-dark py-12 text-white sm:mt-20 sm:py-16">
        <Container>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-ed-yellow">
                Programme doctoral EDGVM
            </p>
            <h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">
                Programmes de recherche
            </h1>
            <p class="mt-3 max-w-2xl text-sm leading-relaxed text-white/85 sm:text-base">
                Les spécialités de recherche doctorale, chacune rattachée à une équipe d'accueil.
            </p>

            <nav class="mt-5 flex items-center gap-2 text-sm text-white/70" aria-label="Fil d'Ariane">
                <Link :href="route('home')" class="transition-colors hover:text-ed-yellow">Accueil</Link>
                <span aria-hidden="true">/</span>
                <span class="font-semibold text-white">Programmes</span>
            </nav>
        </Container>
    </section>

    <!-- Liste -->
    <section class="bg-gray-50 py-12 md:py-16">
        <Container>
            <div class="mb-6 flex items-baseline justify-between gap-4">
                <h2 class="text-lg font-bold text-gray-900">Les programmes disponibles</h2>
                <p class="text-sm text-gray-500">
                    {{ specialites?.length || 0 }} programme<span v-if="(specialites?.length || 0) > 1">s</span>
                </p>
            </div>

            <div v-if="!specialites?.length" class="rounded-xl border border-dashed border-gray-300 bg-white p-12 text-center">
                <p class="text-sm font-medium text-gray-600">Aucun programme disponible pour le moment.</p>
            </div>

            <template v-else>
                <!-- Desktop : tableau -->
                <div class="hidden overflow-hidden rounded-xl border border-gray-200 bg-white md:block">
                    <table class="min-w-full divide-y divide-gray-200 text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-600">Programme / Spécialité</th>
                                <th scope="col" class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-600">Équipe d'accueil</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="specialite in specialites" :key="specialite.id" class="transition-colors hover:bg-gray-50">
                                <td class="px-6 py-4 font-semibold text-gray-900">{{ specialite.nom }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ specialite.ead?.nom || specialite.ead || '—' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile : liste -->
                <ul class="divide-y divide-gray-200 overflow-hidden rounded-xl border border-gray-200 bg-white md:hidden">
                    <li v-for="specialite in specialites" :key="specialite.id" class="p-4">
                        <p class="font-semibold text-gray-900">{{ specialite.nom }}</p>
                        <p class="mt-1 text-sm text-gray-500">
                            Équipe d'accueil : <span class="text-gray-700">{{ specialite.ead?.nom || specialite.ead || '—' }}</span>
                        </p>
                    </li>
                </ul>
            </template>
        </Container>
    </section>
</template>
