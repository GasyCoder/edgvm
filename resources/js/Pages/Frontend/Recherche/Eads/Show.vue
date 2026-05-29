<script setup>
import { computed, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import Container from '@/Components/Frontend/UI/Container.vue';
import { cleanText } from '@/utils/text';

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

const initials = (name) => (name || '').split(' ').map((n) => n[0]).join('').substring(0, 2).toUpperCase();
const year = (date) => (date ? new Date(date).getFullYear() : '');

defineOptions({
    layout: FrontendLayout,
});
</script>

<template>
    <Head :title="ead?.nom || 'Équipe d\'accueil'" />

    <!-- Hero -->
    <section class="mt-16 bg-ed-teal-dark py-12 text-white sm:mt-20 sm:py-14">
        <Container>
            <nav class="mb-5 flex flex-wrap items-center gap-2 text-sm text-white/70" aria-label="Fil d'Ariane">
                <Link :href="route('home')" class="transition-colors hover:text-ed-yellow">Accueil</Link>
                <span aria-hidden="true">/</span>
                <Link :href="route('ead.index')" class="transition-colors hover:text-ed-yellow">Équipes d'accueil</Link>
                <span aria-hidden="true">/</span>
                <span class="font-semibold text-white">{{ ead?.nom }}</span>
            </nav>

            <p v-if="ead?.domaine" class="text-xs font-semibold uppercase tracking-[0.2em] text-ed-yellow">{{ ead.domaine }}</p>
            <h1 class="mt-2 max-w-3xl text-2xl font-bold leading-tight tracking-tight sm:text-3xl">{{ ead?.nom }}</h1>

            <div class="mt-5 flex flex-wrap gap-2 text-sm">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3 py-1.5">{{ ead?.specialites_count || 0 }} spécialités</span>
                <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3 py-1.5">{{ ead?.doctorats_count || 0 }} doctorants actifs</span>
                <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3 py-1.5">{{ ead?.theses_soutenues_count || 0 }} thèses soutenues</span>
            </div>
        </Container>
    </section>

    <section class="bg-gray-50 py-12 md:py-14">
        <Container>
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 lg:gap-8">
                <!-- Colonne principale -->
                <div class="space-y-6 lg:col-span-2">
                    <!-- Présentation -->
                    <article v-if="ead?.description" class="rounded-xl border border-gray-200 bg-white p-6 md:p-8">
                        <h2 class="mb-3 text-lg font-bold text-gray-900 md:text-xl">Présentation</h2>
                        <p class="text-justify text-sm leading-relaxed text-gray-700">{{ cleanText(ead.description) }}</p>
                    </article>

                    <!-- Spécialités -->
                    <article v-if="specialites?.length" class="rounded-xl border border-gray-200 bg-white p-6 md:p-8">
                        <h2 class="mb-5 text-lg font-bold text-gray-900 md:text-xl">Spécialités de recherche</h2>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <Link
                                v-for="specialite in specialites"
                                :key="specialite.id"
                                :href="route('programmes.show', specialite.slug)"
                                class="group rounded-xl border border-gray-200 p-4 transition hover:border-ed-primary/40 hover:shadow-sm"
                            >
                                <h3 class="font-semibold text-gray-900 transition-colors group-hover:text-ed-primary">{{ specialite.nom }}</h3>
                                <p v-if="specialite.description" class="mt-1 line-clamp-2 text-xs leading-relaxed text-gray-600">{{ cleanText(specialite.description) }}</p>
                                <div class="mt-2 flex items-center gap-3 text-xs text-gray-500">
                                    <span>{{ specialite.theses_en_cours }} en cours</span>
                                    <span aria-hidden="true" class="text-gray-300">·</span>
                                    <span>{{ specialite.theses_soutenues }} soutenues</span>
                                </div>
                            </Link>
                        </div>
                    </article>

                    <!-- Doctorants -->
                    <article v-if="theses?.length" class="rounded-xl border border-gray-200 bg-white p-6 md:p-8">
                        <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <h2 class="text-lg font-bold text-gray-900 md:text-xl">
                                Doctorants en cours <span class="font-normal text-gray-500">({{ filteredTheses.length }})</span>
                            </h2>
                            <div v-if="theses.length > 6" class="relative w-full sm:w-64">
                                <svg class="pointer-events-none absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                <input v-model="searchQuery" type="text" placeholder="Rechercher un doctorant…" class="w-full rounded-lg border border-gray-300 py-2 pl-9 pr-3 text-sm placeholder:text-gray-400 focus:border-transparent focus:ring-2 focus:ring-ed-primary/40">
                            </div>
                        </div>

                        <div class="space-y-2.5">
                            <Link
                                v-for="these in displayedTheses"
                                :key="these.id"
                                :href="route('doctorants.show', these.doctorant?.uuid)"
                                class="group flex items-center gap-4 rounded-lg border border-gray-200 p-4 transition hover:border-ed-primary/40 hover:bg-gray-50"
                            >
                                <span class="flex h-11 w-11 flex-none items-center justify-center rounded-full bg-ed-primary text-sm font-semibold text-white">{{ initials(these.doctorant?.name) }}</span>

                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <p class="truncate font-semibold text-gray-900 transition-colors group-hover:text-ed-primary">{{ these.doctorant?.name }}</p>
                                            <p class="text-xs text-gray-500">Matricule : {{ these.doctorant?.matricule || 'N/A' }}</p>
                                            <p class="mt-1 line-clamp-1 text-xs text-gray-600">{{ these.sujet_these }}</p>
                                        </div>
                                        <span v-if="these.specialite" class="hidden flex-none rounded-full bg-ed-primary/10 px-2 py-0.5 text-[11px] font-medium text-ed-primary sm:inline">{{ these.specialite.nom }}</span>
                                    </div>
                                    <div class="mt-2 flex items-center gap-3 text-xs text-gray-500">
                                        <span v-if="these.date_debut">Début : {{ year(these.date_debut) }}</span>
                                        <span v-if="these.date_prevue_fin">Fin prévue : {{ year(these.date_prevue_fin) }}</span>
                                    </div>
                                </div>

                                <svg class="h-5 w-5 flex-none text-gray-300 transition group-hover:translate-x-0.5 group-hover:text-ed-primary motion-reduce:transform-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </Link>
                        </div>

                        <div v-if="filteredTheses.length === 0" class="py-8 text-center text-sm text-gray-500">Aucun doctorant trouvé.</div>

                        <div v-if="hasMore && !searchQuery" class="mt-6 text-center">
                            <button type="button" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:border-ed-primary/50 hover:text-ed-primary" @click="showAll = !showAll">
                                <span>{{ showAll ? 'Voir moins' : `Voir tous les doctorants (${filteredTheses.length})` }}</span>
                                <svg class="h-4 w-4 transition-transform" :class="showAll && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                        </div>
                    </article>
                </div>

                <!-- Aside -->
                <aside class="lg:col-span-1">
                    <div class="space-y-5 lg:sticky lg:top-24">
                        <div v-if="ead?.responsable" class="rounded-xl border border-gray-200 bg-white p-6">
                            <h3 class="mb-4 text-sm font-semibold text-gray-900">Responsable</h3>
                            <div class="flex items-start gap-4">
                                <span class="flex h-14 w-14 flex-none items-center justify-center rounded-full bg-ed-primary/10 text-ed-primary">
                                    <svg class="h-7 w-7" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
                                </span>
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-900">{{ ead.responsable.name }}</p>
                                    <p v-if="ead.responsable.grade" class="text-sm text-gray-600">{{ ead.responsable.grade }}</p>
                                    <a v-if="ead.responsable.email" :href="`mailto:${ead.responsable.email}`" class="break-all text-sm text-ed-primary transition-colors hover:text-ed-secondary">{{ ead.responsable.email }}</a>
                                    <p v-if="ead.responsable.phone" class="mt-1 text-sm text-gray-600">Tél. {{ ead.responsable.phone }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white p-6">
                            <h3 class="mb-3 text-sm font-semibold text-gray-900">Statistiques</h3>
                            <dl class="divide-y divide-gray-100 text-sm">
                                <div class="flex items-center justify-between py-2.5">
                                    <dt class="text-gray-600">Spécialités</dt>
                                    <dd class="text-lg font-semibold tabular-nums text-ed-primary">{{ ead?.specialites_count || 0 }}</dd>
                                </div>
                                <div class="flex items-center justify-between py-2.5">
                                    <dt class="text-gray-600">Doctorants</dt>
                                    <dd class="text-lg font-semibold tabular-nums text-ed-primary">{{ ead?.doctorats_count || 0 }}</dd>
                                </div>
                                <div class="flex items-center justify-between py-2.5">
                                    <dt class="text-gray-600">Thèses soutenues</dt>
                                    <dd class="text-lg font-semibold tabular-nums text-ed-primary">{{ ead?.theses_soutenues_count || 0 }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white p-6">
                            <h3 class="text-sm font-semibold text-gray-900">Une question sur cette équipe ?</h3>
                            <p class="mt-1 text-xs leading-relaxed text-gray-500">Contactez le secrétariat de l'École Doctorale.</p>
                            <Link :href="route('contact')" class="mt-3 inline-flex w-full items-center justify-center gap-1.5 rounded-lg bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-ed-secondary">
                                Nous contacter
                            </Link>
                        </div>

                        <Link :href="route('ead.index')" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 transition-colors hover:text-ed-primary">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                            Toutes les équipes d'accueil
                        </Link>
                    </div>
                </aside>
            </div>
        </Container>
    </section>
</template>
