<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    these: Object,
});
</script>

<template>
    <AdminLayout>
        <template #header>
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 md:text-xl">These</h2>
                    <p class="mt-1 text-xs text-slate-500 md:text-sm">Details et encadrants.</p>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.theses.edit', these.id)" class="rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary">
                        Modifier
                    </Link>
                    <Link :href="route('admin.theses.jury.edit', these.id)" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Jury
                    </Link>
                    <Link :href="route('admin.theses.index')" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Retour
                    </Link>
                </div>
            </div>
        </template>

        <Head title="These" />

        <div class="grid gap-6 lg:grid-cols-3">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 lg:col-span-2">
                <h3 class="text-sm font-semibold text-slate-700">Informations</h3>
                <div class="mt-4 space-y-4 text-sm">
                    <div>
                        <p class="text-xs text-slate-500">Sujet</p>
                        <p class="font-semibold text-slate-900">{{ these.sujet_these }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Doctorant</p>
                        <p class="font-semibold text-slate-900">{{ these.doctorant?.name || '-' }}</p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <p class="text-xs text-slate-500">Statut</p>
                            <p class="font-semibold text-slate-900">{{ these.statut }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Universite soutenance</p>
                            <p class="font-semibold text-slate-900">{{ these.universite_soutenance || '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Date debut</p>
                            <p class="font-semibold text-slate-900">{{ these.date_debut || '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Date publication</p>
                            <p class="font-semibold text-slate-900">{{ these.date_publication || '-' }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Description</p>
                        <p class="text-slate-700">{{ these.description || 'Aucune description.' }}</p>
                    </div>
                </div>
            </section>

            <aside class="space-y-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h3 class="text-sm font-semibold text-slate-700">Encadrants</h3>
                    <div class="mt-4 space-y-2 text-sm">
                        <div v-for="encadrant in these.encadrants" :key="encadrant.id" class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                            <p class="font-semibold text-slate-900">{{ encadrant.name }}</p>
                            <p class="text-xs text-slate-500">{{ encadrant.role || 'directeur' }}</p>
                        </div>
                        <p v-if="!these.encadrants?.length" class="text-xs text-slate-500">Aucun encadrant.</p>
                    </div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h3 class="text-sm font-semibold text-slate-700">Jurys</h3>
                    <div class="mt-4 space-y-2 text-sm">
                        <div v-for="jury in these.jurys" :key="jury.id" class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                            <p class="font-semibold text-slate-900">{{ jury.nom }}</p>
                            <p class="text-xs text-slate-500">{{ jury.role || '-' }} {{ jury.ordre ? `· ordre ${jury.ordre}` : '' }}</p>
                        </div>
                        <p v-if="!these.jurys?.length" class="text-xs text-slate-500">Aucun jury.</p>
                    </div>
                </div>
            </aside>
        </div>
    </AdminLayout>
</template>
