<script setup>
import { Head, Link } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import Container from '@/Components/Frontend/UI/Container.vue';

defineProps({
    eads: Array,
});

defineOptions({
    layout: FrontendLayout,
});
</script>

<template>
    <Head title="Équipes d'accueil doctorales" />

    <!-- Hero -->
    <section class="mt-16 bg-ed-teal-dark py-12 text-white sm:mt-20 sm:py-16">
        <Container>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-ed-yellow">
                Programme doctoral EDGVM
            </p>
            <h1 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">
                Équipes d'Accueil Doctorales
            </h1>
            <p class="mt-3 max-w-2xl text-sm leading-relaxed text-white/85 sm:text-base">
                Découvrez les équipes d'accueil et les responsables qui encadrent les doctorants.
            </p>

            <nav class="mt-5 flex items-center gap-2 text-sm text-white/70" aria-label="Fil d'Ariane">
                <Link :href="route('home')" class="transition-colors hover:text-ed-yellow">Accueil</Link>
                <span aria-hidden="true">/</span>
                <span class="font-semibold text-white">Équipes d'accueil</span>
            </nav>
        </Container>
    </section>

    <!-- Liste complète -->
    <section class="bg-gray-50 py-12 md:py-16">
        <Container>
            <div class="mb-6 flex items-baseline justify-between gap-4">
                <h2 class="text-lg font-bold text-gray-900">
                    Toutes les équipes
                </h2>
                <p class="text-sm text-gray-500">
                    {{ eads.length }} équipe<span v-if="eads.length > 1">s</span>
                </p>
            </div>

            <!-- Empty -->
            <div v-if="!eads.length" class="rounded-xl border border-dashed border-gray-300 bg-white p-12 text-center">
                <p class="text-sm font-medium text-gray-600">Aucune équipe d'accueil enregistrée pour le moment.</p>
            </div>

            <template v-else>
                <!-- Desktop : tableau classique -->
                <div class="hidden overflow-hidden rounded-xl border border-gray-200 bg-white md:block">
                    <table class="min-w-full divide-y divide-gray-200 text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-600">Équipe d'accueil</th>
                                <th scope="col" class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-600">Responsable</th>
                                <th scope="col" class="px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-gray-600">Spécialités</th>
                                <th scope="col" class="px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-gray-600">Doctorants</th>
                                <th scope="col" class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-600">
                                    <span class="sr-only">Action</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="ead in eads" :key="ead.id" class="transition-colors hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <Link
                                        :href="route('ead.show', ead.slug)"
                                        class="font-semibold text-gray-900 transition-colors hover:text-ed-primary focus:outline-none focus-visible:text-ed-primary"
                                    >
                                        {{ ead.nom }}
                                    </Link>
                                    <p v-if="ead.description" class="mt-0.5 max-w-md text-sm text-gray-500 line-clamp-1">
                                        {{ ead.description }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ ead.responsable?.name || '—' }}</td>
                                <td class="px-6 py-4 text-center text-sm font-semibold tabular-nums text-gray-900">{{ ead.specialites_count }}</td>
                                <td class="px-6 py-4 text-center text-sm font-semibold tabular-nums text-gray-900">{{ ead.doctorats_count }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-right">
                                    <Link
                                        :href="route('ead.show', ead.slug)"
                                        class="group inline-flex items-center gap-1.5 text-sm font-semibold text-ed-primary transition-colors hover:text-ed-secondary focus:outline-none focus-visible:text-ed-secondary"
                                    >
                                        <span>Voir l'équipe</span>
                                        <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5 motion-reduce:transform-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile : liste -->
                <ul class="divide-y divide-gray-200 overflow-hidden rounded-xl border border-gray-200 bg-white md:hidden">
                    <li v-for="ead in eads" :key="ead.id" class="p-4">
                        <Link
                            :href="route('ead.show', ead.slug)"
                            class="font-semibold text-gray-900 transition-colors hover:text-ed-primary focus:outline-none focus-visible:text-ed-primary"
                        >
                            {{ ead.nom }}
                        </Link>
                        <p v-if="ead.responsable?.name" class="mt-1 text-sm text-gray-500">
                            Responsable : <span class="font-medium text-gray-700">{{ ead.responsable.name }}</span>
                        </p>

                        <dl class="mt-3 flex items-center gap-6 text-sm">
                            <div class="flex items-baseline gap-1.5">
                                <dt class="text-gray-500">Spécialités</dt>
                                <dd class="font-semibold tabular-nums text-gray-900">{{ ead.specialites_count }}</dd>
                            </div>
                            <div class="flex items-baseline gap-1.5">
                                <dt class="text-gray-500">Doctorants</dt>
                                <dd class="font-semibold tabular-nums text-gray-900">{{ ead.doctorats_count }}</dd>
                            </div>
                        </dl>

                        <Link
                            :href="route('ead.show', ead.slug)"
                            class="mt-3 inline-flex items-center gap-1.5 text-sm font-semibold text-ed-primary transition-colors hover:text-ed-secondary"
                        >
                            <span>Voir l'équipe</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>
                    </li>
                </ul>
            </template>
        </Container>
    </section>
</template>
