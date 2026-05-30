<script setup>
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    stats: Object,
});

const page = usePage();
const can = (ability) => Boolean(page.props.auth?.can?.[ability]);

const thesesEnCours = computed(() => props.stats?.theses_en_cours ?? 0);
const thesesSoutenues = computed(() => props.stats?.theses_soutenues ?? 0);
const totalTheses = computed(() => thesesEnCours.value + thesesSoutenues.value);
const ratioEnCours = computed(() => (totalTheses.value > 0 ? (thesesEnCours.value / totalTheses.value) * 100 : 0));
const ratioSoutenues = computed(() => (totalTheses.value > 0 ? (thesesSoutenues.value / totalTheses.value) * 100 : 0));

const kpis = computed(() => [
    { label: 'Utilisateurs', value: props.stats?.total_users ?? 0, hint: 'Comptes actifs', icon: 'users' },
    { label: 'Doctorants', value: props.stats?.total_doctorants ?? 0, hint: 'Encadrés', icon: 'cap' },
    { label: 'Encadrants', value: props.stats?.total_encadrants ?? 0, hint: 'Actifs', icon: 'team' },
    { label: 'Abonnés', value: props.stats?.total_subscribers ?? 0, hint: 'Newsletter', icon: 'mail' },
]);

const icons = {
    users: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
    cap: 'M12 14l9-5-9-5-9 5 9 5zm0 0v6m-6-3v3c0 .5.3 1 1 1.3 1.6.8 3.6 1.7 5 1.7s3.4-.9 5-1.7c.7-.3 1-.8 1-1.3v-3',
    team: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
    mail: 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
    arrow: 'M9 5l7 7-7 7',
};

const accesRapides = computed(() => [
    { label: 'Doctorants', href: route('admin.doctorants.index'), show: can('gestion.access') },
    { label: 'Encadrants', href: route('admin.encadrants.index'), show: can('gestion.access') },
    { label: 'Thèses', href: route('admin.theses.index'), show: can('gestion.access') },
    { label: 'Actualités', href: route('admin.actualites.index'), show: can('contenu.access') },
    { label: 'Événements', href: route('admin.evenements.index'), show: can('contenu.access') },
    { label: 'Médiathèque', href: route('admin.media.index'), show: can('contenu.access') },
].filter((item) => item.show));
</script>

<template>
    <AdminLayout>
        <template #header>
            <div>
                <h2 class="text-lg font-semibold text-slate-900 md:text-xl">Tableau de bord</h2>
                <p class="mt-0.5 text-xs text-slate-500 md:text-sm">Vue d'ensemble de l'École Doctorale EDGVM.</p>
            </div>
        </template>

        <Head title="Tableau de bord" />

        <div class="mx-auto max-w-screen-2xl space-y-6">
            <!-- KPI -->
            <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
                <div v-for="kpi in kpis" :key="kpi.label" class="rounded-xl border border-gray-200 bg-white p-5">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">{{ kpi.label }}</p>
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-ed-primary/10 text-ed-primary">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="icons[kpi.icon]" />
                            </svg>
                        </span>
                    </div>
                    <p class="mt-3 text-3xl font-bold tabular-nums tracking-tight text-gray-900">{{ kpi.value }}</p>
                    <p class="mt-1 text-xs text-gray-500">{{ kpi.hint }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Statistiques thèses -->
                <section v-if="can('gestion.access')" class="rounded-xl border border-gray-200 bg-white p-6">
                    <h3 class="text-sm font-semibold text-gray-900">Statistiques des thèses</h3>

                    <div class="mt-5 space-y-4 text-sm">
                        <div>
                            <div class="mb-1.5 flex justify-between">
                                <span class="text-gray-600">En cours</span>
                                <span class="font-semibold tabular-nums text-gray-900">{{ thesesEnCours }}</span>
                            </div>
                            <div class="h-2 w-full overflow-hidden rounded-full bg-gray-100">
                                <div class="h-2 rounded-full bg-ed-yellow" :style="{ width: `${ratioEnCours}%` }"></div>
                            </div>
                        </div>

                        <div>
                            <div class="mb-1.5 flex justify-between">
                                <span class="text-gray-600">Soutenues</span>
                                <span class="font-semibold tabular-nums text-gray-900">{{ thesesSoutenues }}</span>
                            </div>
                            <div class="h-2 w-full overflow-hidden rounded-full bg-gray-100">
                                <div class="h-2 rounded-full bg-ed-primary" :style="{ width: `${ratioSoutenues}%` }"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-gray-50 p-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total thèses</p>
                                <p class="mt-1 text-2xl font-bold tabular-nums text-gray-900">{{ totalTheses }}</p>
                            </div>
                            <Link :href="route('admin.theses.index')" class="inline-flex items-center gap-1.5 text-sm font-semibold text-ed-primary transition-colors hover:text-ed-secondary">
                                Consulter
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="icons.arrow" /></svg>
                            </Link>
                        </div>
                    </div>
                </section>

                <!-- Actions rapides -->
                <section class="rounded-xl border border-gray-200 bg-white p-6">
                    <h3 class="mb-4 text-sm font-semibold text-gray-900">Actions rapides</h3>
                    <div class="space-y-3">
                        <Link
                            v-if="can('gestion.access')"
                            :href="route('admin.doctorants.create')"
                            class="group flex items-center gap-4 rounded-lg border border-gray-200 p-4 transition hover:border-ed-primary/40 hover:bg-gray-50"
                        >
                            <span class="flex h-10 w-10 flex-none items-center justify-center rounded-lg bg-ed-primary/10 text-ed-primary">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Ajouter un doctorant</p>
                                <p class="mt-0.5 text-xs text-gray-500">Créer un nouveau profil.</p>
                            </div>
                        </Link>

                        <Link
                            v-if="can('contenu.access')"
                            :href="route('admin.actualites.create')"
                            class="group flex items-center gap-4 rounded-lg border border-gray-200 p-4 transition hover:border-ed-primary/40 hover:bg-gray-50"
                        >
                            <span class="flex h-10 w-10 flex-none items-center justify-center rounded-lg bg-ed-primary/10 text-ed-primary">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v1m2 12a2 2 0 01-2-2V8m2 12a2 2 0 002-2v-6a2 2 0 00-2-2h-2M9 7h4M9 11h4" /></svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Publier une actualité</p>
                                <p class="mt-0.5 text-xs text-gray-500">Mettre en avant une information.</p>
                            </div>
                        </Link>

                        <Link
                            v-if="can('contenu.access')"
                            :href="route('admin.evenements.create')"
                            class="group flex items-center gap-4 rounded-lg border border-gray-200 p-4 transition hover:border-ed-primary/40 hover:bg-gray-50"
                        >
                            <span class="flex h-10 w-10 flex-none items-center justify-center rounded-lg bg-ed-primary/10 text-ed-primary">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2v-8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Planifier un événement</p>
                                <p class="mt-0.5 text-xs text-gray-500">Séminaire, soutenance, atelier…</p>
                            </div>
                        </Link>
                    </div>
                </section>
            </div>

            <!-- Accès rapides -->
            <section v-if="accesRapides.length" class="rounded-xl border border-gray-200 bg-white p-6">
                <h3 class="mb-4 text-sm font-semibold text-gray-900">Accès rapides</h3>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                    <Link
                        v-for="item in accesRapides"
                        :key="item.label"
                        :href="item.href"
                        class="flex items-center justify-between rounded-lg border border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 transition hover:border-ed-primary/40 hover:bg-gray-50 hover:text-ed-primary"
                    >
                        {{ item.label }}
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="icons.arrow" /></svg>
                    </Link>
                </div>
            </section>
        </div>
    </AdminLayout>
</template>
