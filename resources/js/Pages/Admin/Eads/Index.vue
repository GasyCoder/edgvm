<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';
import Pagination from '@/Components/Common/Pagination.vue';

const props = defineProps({
    filters: Object,
    eads: Object,
    stats: Object,
});

const search = ref(props.filters?.search || '');

const updateFilters = () => {
    router.get(route('admin.ead.index'), {
        search: search.value,
    }, { preserveState: true, preserveScroll: true });
};

const clearFilters = () => {
    search.value = '';
    updateFilters();
};

const deleteEad = (ead) => {
    if (!confirm(`Supprimer l'EAD "${ead.nom}" ?`)) return;

    router.delete(route('admin.ead.destroy', ead.slug), {
        preserveScroll: true,
    });
};

const hasFilters = computed(() => search.value);
</script>

<template>
    <AdminLayout>
        <template #header>
            <h2 class="text-lg font-semibold text-slate-900 md:text-xl">Équipes d'accueil</h2>
        </template>

        <Head title="Équipes d'accueil" />
        <FlashMessage />

        <div class="mx-auto max-w-screen-2xl space-y-6">
            <PageHeader
                badge="Gestion académique"
                title="Équipes d'Accueil Doctorales"
                description="Gérez les équipes d'accueil, leurs responsables et leurs spécialités."
            >
                <template #actions>
                    <Link
                        :href="route('admin.ead.create')"
                        class="inline-flex items-center gap-2 rounded-lg bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-ed-secondary"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                        </svg>
                        Nouvelle équipe
                    </Link>
                </template>
            </PageHeader>

            <!-- Statistiques -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total équipes</p>
                    <p class="mt-2 text-2xl font-bold tabular-nums text-gray-900">{{ stats?.total ?? 0 }}</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Avec responsable</p>
                    <p class="mt-2 text-2xl font-bold tabular-nums text-ed-primary">{{ stats?.avec_responsable ?? 0 }}</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Avec spécialités</p>
                    <p class="mt-2 text-2xl font-bold tabular-nums text-ed-primary">{{ stats?.avec_specialites ?? 0 }}</p>
                </div>
            </div>

            <!-- Recherche -->
            <div class="flex flex-wrap items-end gap-3 rounded-xl border border-gray-200 bg-white p-4">
                <div class="min-w-[220px] flex-1">
                    <label class="mb-1.5 block text-xs font-semibold text-gray-600">Recherche</label>
                    <div class="relative">
                        <svg class="pointer-events-none absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input v-model="search" type="text" placeholder="Nom, slug, description…" class="w-full rounded-lg border border-gray-300 py-2.5 pl-10 pr-3 text-sm placeholder:text-gray-400 focus:border-transparent focus:ring-2 focus:ring-ed-primary/40" @keyup.enter="updateFilters" />
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" class="rounded-lg bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-ed-secondary" @click="updateFilters">Filtrer</button>
                    <button v-if="hasFilters" type="button" class="rounded-lg border border-gray-300 px-3 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50" @click="clearFilters">Réinitialiser</button>
                </div>
            </div>

            <!-- Tableau -->
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left">
                        <thead class="bg-gray-50">
                            <tr class="text-xs font-semibold uppercase tracking-wider text-gray-600">
                                <th class="px-5 py-3.5">Équipe d'accueil</th>
                                <th class="px-5 py-3.5">Responsable</th>
                                <th class="px-5 py-3.5">Activité</th>
                                <th class="px-5 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm">
                            <tr v-for="ead in eads.data" :key="ead.id" class="transition-colors hover:bg-gray-50">
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-gray-900">{{ ead.sigle || ead.nom }}</p>
                                    <p class="text-xs text-gray-500">{{ ead.sigle ? ead.nom : '/' + ead.slug }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <div v-if="ead.responsable">
                                        <p class="font-medium text-gray-900">{{ ead.responsable.name }}</p>
                                        <p class="text-xs text-gray-500">{{ ead.responsable.grade || 'Encadrant' }}</p>
                                    </div>
                                    <span v-else class="text-xs italic text-gray-400">Non assigné</span>
                                </td>
                                <td class="px-5 py-4 text-gray-600">
                                    <p class="tabular-nums">{{ ead.specialites_count }} spécialités</p>
                                    <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-gray-400">
                                        <span class="tabular-nums">{{ ead.encadrants_count }} encadrants</span>
                                        <span class="tabular-nums">{{ ead.theses_count }} thèses</span>
                                        <span v-if="ead.specialites_count === 0" class="rounded-full bg-amber-50 px-2 py-0.5 font-semibold text-amber-700">Sans spécialité</span>
                                        <span v-if="ead.theses_count === 0" class="rounded-full bg-amber-50 px-2 py-0.5 font-semibold text-amber-700">Sans thèse</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-1.5">
                                        <Link :href="route('admin.ead.edit', ead.slug)" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition hover:border-ed-primary/40 hover:bg-ed-primary/5 hover:text-ed-primary" title="Modifier">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.232-6.232a2.5 2.5 0 113.536 3.536L12.536 16.536A4 4 0 0110 17H7v-3a4 4 0 011-2.536z" />
                                            </svg>
                                        </Link>
                                        <button type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition hover:border-red-300 hover:bg-red-50 hover:text-red-600" title="Supprimer" @click="deleteEad(ead)">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 011-1h4a1 1 0 011 1m-7 0h8" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!eads.data.length">
                                <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500">Aucune équipe d'accueil trouvée.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="eads.links?.length" class="border-t border-gray-100 px-4 py-3">
                    <Pagination :links="eads.links" />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
