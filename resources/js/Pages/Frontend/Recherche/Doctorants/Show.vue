<script setup>
import { computed, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import Container from '@/Components/Frontend/UI/Container.vue';
import { cleanText } from '@/utils/text';

const props = defineProps({
    doctorant: Object,
    thesePrincipale: Object,
    thesesHistorique: Array,
});

const resumeExpanded = ref(false);

const statusLabel = computed(() => {
    if (!props.thesePrincipale?.statut) {
        return 'Thèse';
    }

    return props.thesePrincipale.statut === 'soutenue' ? 'Thèse soutenue' : 'Thèse en cours';
});

defineOptions({
    layout: FrontendLayout,
});
</script>

<template>
    <Head :title="doctorant?.name || 'Doctorant'" />

    <!-- Hero -->
    <section class="mt-16 bg-ed-teal-dark py-12 text-white sm:mt-20 sm:py-14">
        <Container>
            <nav class="mb-6 flex items-center gap-2 text-sm text-white/70" aria-label="Fil d'Ariane">
                <Link :href="route('home')" class="transition-colors hover:text-ed-yellow">Accueil</Link>
                <span aria-hidden="true">/</span>
                <Link :href="route('doctorants.index')" class="transition-colors hover:text-ed-yellow">Doctorants</Link>
                <span aria-hidden="true">/</span>
                <span class="font-semibold text-white">{{ doctorant?.name || 'Doctorant' }}</span>
            </nav>

            <div class="flex flex-col items-start gap-5 sm:flex-row sm:items-center sm:gap-6">
                <span class="flex h-20 w-20 flex-none items-center justify-center rounded-full bg-white text-2xl font-semibold text-ed-primary shadow-sm sm:h-24 sm:w-24 sm:text-3xl">
                    {{ doctorant?.initials || 'NN' }}
                </span>

                <div class="min-w-0">
                    <h1 class="text-2xl font-bold tracking-tight sm:text-3xl">{{ doctorant?.name || 'Non renseigné' }}</h1>
                    <p v-if="doctorant?.matricule" class="mt-1 text-sm text-white/75">Matricule : {{ doctorant.matricule }}</p>

                    <div class="mt-3 flex flex-wrap gap-2 text-xs">
                        <span v-if="doctorant?.niveau" class="inline-flex items-center rounded-full border border-white/20 bg-white/10 px-3 py-1 font-medium">
                            {{ doctorant.niveau }}
                        </span>
                        <span v-if="doctorant?.badge" class="inline-flex items-center rounded-full border border-white/20 bg-white/10 px-3 py-1 font-medium">
                            {{ doctorant.badge.text }}
                        </span>
                        <span v-if="thesePrincipale?.specialite" class="inline-flex items-center rounded-full border border-white/20 bg-white/10 px-3 py-1 font-medium">
                            {{ thesePrincipale.specialite.nom }}
                        </span>
                    </div>
                </div>
            </div>
        </Container>
    </section>

    <section class="bg-gray-50 py-10 md:py-14">
        <Container>
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 lg:gap-8">
                <!-- Colonne principale -->
                <div class="space-y-6 lg:col-span-2">
                    <!-- Thèse -->
                    <article v-if="thesePrincipale" class="rounded-xl border border-gray-200 bg-white p-6 md:p-8">
                        <div class="mb-5 flex items-center justify-between gap-3">
                            <h2 class="text-lg font-bold text-gray-900 md:text-xl">{{ statusLabel }}</h2>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-500">Sujet de thèse</h3>
                                <p class="mt-1.5 font-semibold leading-relaxed text-gray-900">{{ thesePrincipale.sujet_these }}</p>
                            </div>

                            <div v-if="thesePrincipale.description">
                                <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-500">Description</h3>
                                <p class="mt-1.5 text-sm leading-relaxed text-gray-700">{{ cleanText(thesePrincipale.description) }}</p>
                            </div>

                            <div v-if="thesePrincipale.resume_these">
                                <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-500">Résumé</h3>
                                <p class="mt-1.5 text-sm leading-relaxed text-gray-700">
                                    {{ resumeExpanded ? cleanText(thesePrincipale.resume_these) : cleanText(thesePrincipale.resume_limite) }}
                                </p>
                                <button
                                    v-if="thesePrincipale.show_read_more"
                                    type="button"
                                    class="mt-2 inline-flex items-center gap-1.5 text-sm font-semibold text-ed-primary transition-colors hover:text-ed-secondary"
                                    @click="resumeExpanded = !resumeExpanded"
                                >
                                    <span>{{ resumeExpanded ? 'Réduire' : 'Lire la suite' }}</span>
                                    <svg class="h-4 w-4 transition-transform" :class="resumeExpanded && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>

                            <div v-if="thesePrincipale.mots_cles?.length">
                                <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-500">Mots-clés</h3>
                                <div class="mt-2 flex flex-wrap gap-1.5">
                                    <span v-for="mot in thesePrincipale.mots_cles" :key="mot" class="rounded-full bg-ed-primary/10 px-3 py-1 text-xs font-medium text-ed-primary">
                                        {{ mot }}
                                    </span>
                                </div>
                            </div>

                            <dl class="grid grid-cols-1 gap-3 border-t border-gray-100 pt-5 sm:grid-cols-3">
                                <div v-if="thesePrincipale.date_debut" class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                    <dt class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Date de début</dt>
                                    <dd class="mt-1 text-base font-semibold text-gray-900">{{ thesePrincipale.date_debut }}</dd>
                                </div>
                                <div v-if="thesePrincipale.date_publication || thesePrincipale.date_prevue_fin" class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                    <dt class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">
                                        {{ thesePrincipale.statut === 'soutenue' ? 'Soutenance' : (thesePrincipale.date_publication ? 'Publication' : 'Fin prévue') }}
                                    </dt>
                                    <dd class="mt-1 text-base font-semibold text-gray-900">{{ thesePrincipale.date_publication || thesePrincipale.date_prevue_fin }}</dd>
                                </div>
                                <div v-if="thesePrincipale.duree" class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                    <dt class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Durée</dt>
                                    <dd class="mt-1 text-base font-semibold text-gray-900">{{ thesePrincipale.duree }}</dd>
                                </div>
                            </dl>
                        </div>
                    </article>

                    <article v-else class="rounded-xl border border-gray-200 bg-white p-6 md:p-8">
                        <h2 class="text-lg font-bold text-gray-900">Thèse</h2>
                        <p class="mt-2 text-sm text-gray-600">Aucune thèse n'est actuellement associée à ce doctorant.</p>
                    </article>

                    <!-- Encadrement -->
                    <article v-if="thesePrincipale?.encadrants?.length" class="rounded-xl border border-gray-200 bg-white p-6 md:p-8">
                        <h2 class="mb-5 text-lg font-bold text-gray-900 md:text-xl">Encadrement</h2>
                        <ul class="space-y-3">
                            <li v-for="encadrant in thesePrincipale.encadrants" :key="encadrant.id" class="flex items-start gap-4 rounded-lg border border-gray-200 p-4">
                                <span class="flex h-11 w-11 flex-none items-center justify-center rounded-full bg-ed-primary/10 text-ed-primary">
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between gap-2">
                                        <p class="font-semibold text-gray-900">{{ encadrant.name || 'Non renseigné' }}</p>
                                        <span class="flex-none rounded-full bg-ed-primary/10 px-2.5 py-0.5 text-[11px] font-semibold text-ed-primary">{{ encadrant.role || 'Encadrant' }}</span>
                                    </div>
                                    <p v-if="encadrant.grade" class="text-sm text-gray-600">{{ encadrant.grade }}</p>
                                    <a v-if="encadrant.email" :href="`mailto:${encadrant.email}`" class="text-sm text-ed-primary transition-colors hover:text-ed-secondary">{{ encadrant.email }}</a>
                                </div>
                            </li>
                        </ul>
                    </article>

                    <!-- Historique -->
                    <article v-if="thesesHistorique?.length" class="rounded-xl border border-gray-200 bg-white p-6 md:p-8">
                        <h2 class="mb-5 text-lg font-bold text-gray-900 md:text-xl">Historique</h2>
                        <ul class="space-y-2">
                            <li v-for="these in thesesHistorique" :key="these.id" class="flex items-start justify-between gap-3 rounded-lg bg-gray-50 p-4">
                                <div class="min-w-0">
                                    <p class="line-clamp-2 text-sm font-semibold text-gray-900">{{ these.sujet_these }}</p>
                                    <p class="mt-0.5 text-xs text-gray-500">{{ these.date_debut || '' }} – {{ these.date_publication || 'En cours' }}</p>
                                </div>
                                <span class="flex-none rounded-full px-2 py-0.5 text-[11px] font-medium" :class="these.statut === 'soutenue' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'">
                                    {{ these.statut }}
                                </span>
                            </li>
                        </ul>
                    </article>
                </div>

                <!-- Aside -->
                <aside class="lg:col-span-1">
                    <div class="space-y-5 lg:sticky lg:top-24">
                        <div v-if="thesePrincipale?.ead || thesePrincipale?.specialite" class="rounded-xl border border-gray-200 bg-white p-5">
                            <h3 class="mb-4 text-sm font-semibold text-gray-900">Rattachement</h3>
                            <div class="space-y-4">
                                <div v-if="thesePrincipale.ead">
                                    <p class="text-xs text-gray-500">Équipe d'accueil</p>
                                    <Link :href="route('ead.show', thesePrincipale.ead.slug)" class="mt-0.5 block font-semibold text-ed-primary transition-colors hover:text-ed-secondary">
                                        {{ thesePrincipale.ead.nom }}
                                    </Link>
                                </div>
                                <div v-if="thesePrincipale.specialite">
                                    <p class="text-xs text-gray-500">Spécialité</p>
                                    <Link :href="route('programmes.show', thesePrincipale.specialite.slug)" class="mt-0.5 block font-semibold text-ed-primary transition-colors hover:text-ed-secondary">
                                        {{ thesePrincipale.specialite.nom }}
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <div v-if="doctorant?.date_inscription || doctorant?.theses_count" class="rounded-xl border border-gray-200 bg-white p-5">
                            <h3 class="mb-3 text-sm font-semibold text-gray-900">Informations</h3>
                            <dl class="divide-y divide-gray-100 text-sm">
                                <div v-if="doctorant?.date_inscription" class="flex items-center justify-between py-2">
                                    <dt class="text-gray-600">Inscription</dt>
                                    <dd class="font-semibold text-gray-900">{{ doctorant.date_inscription }}</dd>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <dt class="text-gray-600">Thèses</dt>
                                    <dd class="font-semibold text-gray-900">{{ doctorant.theses_count || 0 }}</dd>
                                </div>
                                <div v-if="doctorant?.theses_soutenues_count" class="flex items-center justify-between py-2">
                                    <dt class="text-gray-600">Soutenues</dt>
                                    <dd class="font-semibold text-ed-primary">{{ doctorant.theses_soutenues_count }}</dd>
                                </div>
                            </dl>
                        </div>

                        <Link
                            :href="thesePrincipale?.ead ? route('ead.show', thesePrincipale.ead.slug) : route('ead.index')"
                            class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 transition-colors hover:text-ed-primary"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            {{ thesePrincipale?.ead ? "Retour à l'équipe d'accueil" : "Toutes les équipes d'accueil" }}
                        </Link>
                    </div>
                </aside>
            </div>
        </Container>
    </section>
</template>
