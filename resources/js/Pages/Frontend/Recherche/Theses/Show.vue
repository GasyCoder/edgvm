<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import Container from '@/Components/Frontend/UI/Container.vue';

const props = defineProps({
    these: Object,
});

const doctorantName = computed(() => props.these?.doctorant?.name || '');

const hasAside = computed(() => Boolean(props.these?.encadrants?.length || props.these?.jurys?.length));

const initials = (name) => (name || '').split(' ').map((part) => part[0]).join('').slice(0, 2).toUpperCase();

defineOptions({
    layout: FrontendLayout,
});
</script>

<template>
    <Head :title="these?.sujet_these || 'Thèse'" />

    <!-- Hero -->
    <section class="mt-16 bg-ed-teal-dark py-12 text-white sm:mt-20 sm:py-14">
        <Container>
            <nav class="mb-6 flex flex-wrap items-center gap-2 text-sm text-white/70" aria-label="Fil d'Ariane">
                <Link :href="route('home')" class="transition-colors hover:text-ed-yellow">Accueil</Link>
                <span aria-hidden="true">/</span>
                <Link :href="route('theses.index')" class="transition-colors hover:text-ed-yellow">Thèses</Link>
                <span aria-hidden="true">/</span>
                <span class="line-clamp-1 max-w-[260px] font-semibold text-white md:max-w-xl">{{ these?.sujet_these }}</span>
            </nav>

            <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                <div class="flex-1">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-ed-yellow">Thèse de doctorat</p>
                    <h1 class="mt-2 text-2xl font-bold leading-snug tracking-tight sm:text-3xl">{{ these?.sujet_these }}</h1>

                    <div class="mt-4 flex flex-wrap items-center gap-2 text-xs">
                        <span v-if="doctorantName" class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3 py-1.5">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white/15 text-[10px] font-semibold">{{ these?.doctorant?.initials || '' }}</span>
                            <span class="font-medium">{{ doctorantName }}</span>
                        </span>
                        <span v-if="these?.specialite" class="inline-flex items-center rounded-full border border-white/15 bg-white/5 px-3 py-1.5">{{ these.specialite.nom || 'Spécialité' }}</span>
                        <span v-if="these?.ead" class="inline-flex items-center rounded-full border border-white/15 bg-white/5 px-3 py-1.5">{{ these.ead.sigle || these.ead.nom || "Équipe d'accueil" }}</span>
                    </div>
                </div>

                <div class="flex flex-col items-start gap-2 lg:items-end">
                    <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold" :class="these?.statut_badge_classes">{{ these?.statut_label }}</span>
                    <div class="flex flex-col items-start gap-1 text-[11px] text-white/80 lg:items-end">
                        <span v-if="these?.date_debut">Début : {{ these.date_debut }}</span>
                        <span v-if="these?.statut === 'soutenue' && these?.date_publication">Soutenance : {{ these.date_publication }}</span>
                        <span v-if="these?.statut !== 'soutenue' && these?.date_prevue_fin">Fin prévue : {{ these.date_prevue_fin }}</span>
                    </div>
                </div>
            </div>
        </Container>
    </section>

    <section class="bg-gray-50 py-10 md:py-14">
        <Container>
            <Link :href="route('theses.index')" class="mb-6 inline-flex items-center gap-2 text-sm font-semibold text-gray-600 transition-colors hover:text-ed-primary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                Retour à la liste des thèses
            </Link>

            <div :class="hasAside ? 'grid grid-cols-1 gap-6 lg:grid-cols-12 lg:gap-8' : ''">
                <div :class="hasAside ? 'space-y-6 lg:col-span-8' : 'space-y-6'">
                    <!-- Résumé -->
                    <article class="rounded-xl border border-gray-200 bg-white p-6 md:p-8">
                        <h2 class="mb-4 text-lg font-bold text-gray-900 md:text-xl">Résumé de la thèse</h2>
                        <div v-if="these?.resume_these" class="prose max-w-none text-justify leading-relaxed text-gray-700" v-html="these.resume_these.replace(/\n/g, '<br>')"></div>
                        <p v-else class="text-sm italic text-gray-500">Le résumé de cette thèse n'a pas encore été renseigné.</p>

                        <div v-if="these?.fichier_pdf_url" class="mt-6 flex flex-col gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-xs text-gray-500">Le manuscrit complet est disponible au format PDF.</p>
                            <a :href="these.fichier_pdf_url" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-lg bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-ed-secondary">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16" /></svg>
                                Télécharger le manuscrit (PDF)
                            </a>
                        </div>
                    </article>

                    <!-- Mots-clés -->
                    <article v-if="these?.mots_cles?.length" class="rounded-xl border border-gray-200 bg-white p-6 md:p-7">
                        <h3 class="mb-3 text-sm font-semibold text-gray-900">Mots-clés</h3>
                        <div class="flex flex-wrap gap-2">
                            <span v-for="keyword in these.mots_cles" :key="keyword" class="rounded-full bg-ed-primary/10 px-3 py-1 text-xs font-medium text-ed-primary">{{ keyword }}</span>
                        </div>
                    </article>

                    <!-- Informations principales -->
                    <article class="rounded-xl border border-gray-200 bg-white p-6 md:p-7">
                        <h2 class="mb-4 text-sm font-semibold text-gray-900">Informations principales</h2>
                        <dl class="divide-y divide-gray-100 text-sm">
                            <div v-if="doctorantName" class="flex justify-between gap-4 py-2.5">
                                <dt class="text-gray-500">Doctorant</dt>
                                <dd class="text-right font-medium text-gray-900">{{ doctorantName }}</dd>
                            </div>
                            <div v-if="these?.specialite" class="flex justify-between gap-4 py-2.5">
                                <dt class="text-gray-500">Spécialité</dt>
                                <dd class="text-right font-medium text-gray-900">{{ these.specialite.nom || '' }}</dd>
                            </div>
                            <div v-if="these?.ead" class="flex justify-between gap-4 py-2.5">
                                <dt class="text-gray-500">Équipe d'accueil</dt>
                                <dd class="text-right font-medium text-gray-900">{{ these.ead.nom || '' }}</dd>
                            </div>
                            <div v-if="these?.universite_soutenance" class="flex justify-between gap-4 py-2.5">
                                <dt class="text-gray-500">Université</dt>
                                <dd class="text-right font-medium text-gray-900">{{ these.universite_soutenance }}</dd>
                            </div>
                        </dl>
                    </article>
                </div>

                <!-- Aside -->
                <aside v-if="hasAside" class="space-y-5 lg:col-span-4">
                    <div v-if="these?.encadrants?.length" class="rounded-xl border border-gray-200 bg-white p-6">
                        <h3 class="mb-4 text-sm font-semibold text-gray-900">Encadrement</h3>
                        <ul class="space-y-4">
                            <li v-for="encadrant in these.encadrants" :key="encadrant.id" class="flex items-start gap-3">
                                <span class="flex h-10 w-10 flex-none items-center justify-center rounded-full bg-ed-primary/10 text-sm font-semibold text-ed-primary">{{ initials(encadrant.name) }}</span>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900">{{ encadrant.name }}</p>
                                    <p v-if="encadrant.grade" class="text-xs text-gray-500">{{ encadrant.grade }}</p>
                                    <p v-if="encadrant.role" class="text-xs text-gray-500">{{ encadrant.role }}</p>
                                    <a v-if="encadrant.email" :href="`mailto:${encadrant.email}`" class="break-all text-xs text-ed-primary transition-colors hover:text-ed-secondary">{{ encadrant.email }}</a>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div v-if="these?.jurys?.length" class="rounded-xl border border-gray-200 bg-white p-6">
                        <h3 class="mb-4 text-sm font-semibold text-gray-900">Composition du jury</h3>
                        <ul class="space-y-3">
                            <li v-for="jury in these.jurys" :key="jury.id" class="flex items-start justify-between gap-3">
                                <span class="text-sm font-medium text-gray-900">{{ jury.nom }}</span>
                                <span v-if="jury.role" class="flex-none rounded-full bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-600">{{ jury.role }}</span>
                            </li>
                        </ul>
                    </div>

                </aside>
            </div>
        </Container>
    </section>
</template>
