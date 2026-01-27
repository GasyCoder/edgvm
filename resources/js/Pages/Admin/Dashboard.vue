<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    stats: Object,
});

const thesesEnCours = computed(() => props.stats?.theses_en_cours ?? 0);
const thesesSoutenues = computed(() => props.stats?.theses_soutenues ?? 0);
const totalTheses = computed(() => thesesEnCours.value + thesesSoutenues.value);
const ratioEnCours = computed(() => (totalTheses.value > 0 ? (thesesEnCours.value / totalTheses.value) * 100 : 0));
const ratioSoutenues = computed(() => (totalTheses.value > 0 ? (thesesSoutenues.value / totalTheses.value) * 100 : 0));

</script>

<template>
    <AdminLayout>
        <template #header>
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-semibold text-slate-900 md:text-xl">Dashboard administrateur</h2>
                <p class="mt-0.5 text-xs text-slate-500 md:text-sm">
                    Vue synthetique des indicateurs EDGVM.
                </p>
            </div>
            <span
                class="inline-flex items-center gap-1 rounded-full border border-emerald-100 bg-emerald-50 px-3 py-1 text-[11px] font-semibold text-emerald-700"
            >
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                <span>Administrateur</span>
            </span>
        </div>
        </template>

        <Head title="Dashboard admin" />

    <div class="mx-auto max-w-screen-2xl space-y-6">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[1.2fr,1fr]">
            <div class="rounded-3xl border border-slate-100 bg-gradient-to-br from-white via-slate-50 to-slate-100 p-6 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Console admin</p>
                        <h3 class="mt-2 text-2xl font-bold text-slate-900 md:text-3xl">Pilotage EDGVM</h3>
                        <p class="mt-2 max-w-xl text-sm text-slate-600">
                            Suivi en temps reel des contenus, des chercheurs et des theses. Gardez la plateforme propre et active.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <Link
                            :href="route('admin.actualites.index')"
                            class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-ed-primary/40 hover:text-ed-primary"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v1m2 12a2 2 0 01-2-2V8m2 12a2 2 0 002-2v-6a2 2 0 00-2-2h-2M9 7h4M9 11h4" />
                            </svg>
                            Actualites
                        </Link>
                        <Link
                            :href="route('admin.evenements.index')"
                            class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-ed-primary/40 hover:text-ed-primary"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2v-8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            Evenements
                        </Link>
                        <Link
                            :href="route('admin.media.index')"
                            class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-ed-primary/40 hover:text-ed-primary"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                            </svg>
                            Mediatheque
                        </Link>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Utilisateurs</p>
                            <div class="rounded-xl bg-ed-primary/10 p-2 text-ed-primary">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="mt-3 text-2xl font-bold text-slate-900">{{ stats?.total_users ?? 0 }}</p>
                        <p class="mt-1 text-xs text-slate-500">Total comptes actifs</p>
                    </div>

                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50/60 p-4 shadow-sm">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-emerald-600">Doctorants</p>
                            <div class="rounded-xl bg-emerald-100 p-2 text-emerald-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        </div>
                        <p class="mt-3 text-2xl font-bold text-emerald-700">{{ stats?.total_doctorants ?? 0 }}</p>
                        <p class="mt-1 text-xs text-emerald-600/80">Doctorants encadres</p>
                    </div>

                    <div class="rounded-2xl border border-sky-100 bg-sky-50/60 p-4 shadow-sm">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-sky-600">Encadrants</p>
                            <div class="rounded-xl bg-sky-100 p-2 text-sky-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="mt-3 text-2xl font-bold text-sky-700">{{ stats?.total_encadrants ?? 0 }}</p>
                        <p class="mt-1 text-xs text-sky-600/80">Encadrants actifs</p>
                    </div>

                    <div class="rounded-2xl border border-amber-100 bg-amber-50/70 p-4 shadow-sm">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-amber-600">Newsletter</p>
                            <div class="rounded-xl bg-amber-100 p-2 text-amber-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <p class="mt-3 text-2xl font-bold text-amber-700">{{ stats?.total_subscribers ?? 0 }}</p>
                        <p class="mt-1 text-xs text-amber-600/80">Abonnes total</p>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <h3 class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </span>
                    Statistiques theses
                </h3>

                <div class="mt-5 space-y-4 text-sm">
                    <div>
                        <div class="mb-1.5 flex justify-between">
                            <span class="text-slate-600">Theses en cours</span>
                            <span class="font-semibold text-slate-800">{{ thesesEnCours }}</span>
                        </div>
                        <div class="h-2 w-full rounded-full bg-slate-100">
                            <div class="h-2 rounded-full bg-orange-500" :style="{ width: `${ratioEnCours}%` }"></div>
                        </div>
                    </div>

                    <div>
                        <div class="mb-1.5 flex justify-between">
                            <span class="text-slate-600">Theses soutenues</span>
                            <span class="font-semibold text-slate-800">{{ thesesSoutenues }}</span>
                        </div>
                        <div class="h-2 w-full rounded-full bg-slate-100">
                            <div class="h-2 rounded-full bg-emerald-500" :style="{ width: `${ratioSoutenues}%` }"></div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-indigo-100 bg-gradient-to-r from-indigo-50 to-sky-50 p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-indigo-500">Total theses</p>
                                <p class="mt-1 text-2xl font-bold text-slate-900">{{ totalTheses }}</p>
                            </div>
                            <div class="rounded-2xl bg-white/70 p-3 text-indigo-600 shadow-sm">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <Link :href="route('admin.theses.index')" class="flex items-center gap-3 rounded-2xl border border-slate-100 bg-slate-50 p-3 text-xs font-semibold text-slate-700 transition hover:bg-slate-100">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-white text-slate-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </span>
                            Consulter les theses
                        </Link>
                        <Link :href="route('admin.doctorants.index')" class="flex items-center gap-3 rounded-2xl border border-slate-100 bg-slate-50 p-3 text-xs font-semibold text-slate-700 transition hover:bg-slate-100">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-white text-slate-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            Gérer les doctorants
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[1fr,1.2fr]">
            <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <h3 class="mb-4 flex items-center gap-2 text-sm font-semibold text-slate-900">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-ed-primary/10 text-ed-primary">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </span>
                    Actions rapides
                </h3>

                <div class="space-y-3">
                    <Link :href="route('admin.doctorants.create')" class="group flex items-center gap-4 rounded-2xl border border-slate-100 bg-slate-50 p-4 transition hover:-translate-y-0.5 hover:bg-white hover:shadow-sm">
                        <div class="rounded-2xl bg-ed-primary/10 p-3 text-ed-primary transition group-hover:scale-105">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Ajouter un doctorant</p>
                            <p class="mt-0.5 text-xs text-slate-500">Creer un nouveau profil.</p>
                        </div>
                    </Link>

                    <Link :href="route('admin.actualites.create')" class="group flex items-center gap-4 rounded-2xl border border-slate-100 bg-slate-50 p-4 transition hover:-translate-y-0.5 hover:bg-white hover:shadow-sm">
                        <div class="rounded-2xl bg-amber-100 p-3 text-amber-600 transition group-hover:scale-105">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v1m2 12a2 2 0 01-2-2V8m2 12a2 2 0 002-2v-6a2 2 0 00-2-2h-2M9 7h4M9 11h4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Publier une actualite</p>
                            <p class="mt-0.5 text-xs text-slate-500">Mettre en avant une info cle.</p>
                        </div>
                    </Link>

                    <Link :href="route('admin.evenements.create')" class="group flex items-center gap-4 rounded-2xl border border-slate-100 bg-slate-50 p-4 transition hover:-translate-y-0.5 hover:bg-white hover:shadow-sm">
                        <div class="rounded-2xl bg-emerald-100 p-3 text-emerald-600 transition group-hover:scale-105">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2v-8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Planifier un evenement</p>
                            <p class="mt-0.5 text-xs text-slate-500">Creer un seminaire ou atelier.</p>
                        </div>
                    </Link>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h3 class="text-sm font-semibold text-slate-900">Acces rapides</h3>
                    <span class="rounded-full border border-slate-200 px-3 py-1 text-[11px] font-semibold text-slate-500">Raccourcis metiers</span>
                </div>
                <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <Link :href="route('admin.doctorants.index')" class="flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-xs font-semibold text-slate-700 transition hover:bg-slate-100">
                        Doctorants
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </Link>
                    <Link :href="route('admin.encadrants.index')" class="flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-xs font-semibold text-slate-700 transition hover:bg-slate-100">
                        Encadrants
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </Link>
                    <Link :href="route('admin.actualites.index')" class="flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-xs font-semibold text-slate-700 transition hover:bg-slate-100">
                        Actualites
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </Link>
                    <Link :href="route('admin.evenements.index')" class="flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-xs font-semibold text-slate-700 transition hover:bg-slate-100">
                        Evenements
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </Link>
                    <Link :href="route('admin.media.index')" class="flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-xs font-semibold text-slate-700 transition hover:bg-slate-100">
                        Mediatheque
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </Link>
                    <Link :href="route('admin.newsletter.subscribers')" class="flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-xs font-semibold text-slate-700 transition hover:bg-slate-100">
                        Newsletter
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </Link>
                </div>
            </div>
        </div>
    </div>
    </AdminLayout>
</template>
