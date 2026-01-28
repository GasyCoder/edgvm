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
            <h1 class="text-lg font-semibold text-slate-900">EAD</h1>
        </template>

        <Head title="EAD" />
        <FlashMessage />

        <div class="space-y-4">
            <PageHeader
                badge="Recherche"
                title="Equipes d'Accueil Doctorales"
                description="Gerez les equipes d'accueil doctorales."
            >
                <template #actions>
                    <Link :href="route('admin.ead.create')" class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-ed-primary text-white transition hover:bg-ed-secondary" title="Nouvel EAD">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                        </svg>
                    </Link>
                </template>
            </PageHeader>

            <div class="grid gap-4 rounded-2xl border border-slate-200 bg-white p-4 lg:grid-cols-3">
                <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <p class="text-xs text-slate-500">Total EAD</p>
                    <p class="text-lg font-semibold text-slate-900">{{ stats?.total ?? 0 }}</p>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <p class="text-xs text-slate-500">Avec responsable</p>
                    <p class="text-lg font-semibold text-emerald-600">{{ stats?.avec_responsable ?? 0 }}</p>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <p class="text-xs text-slate-500">Avec specialites</p>
                    <p class="text-lg font-semibold text-indigo-600">{{ stats?.avec_specialites ?? 0 }}</p>
                </div>
            </div>

            <div class="flex flex-wrap items-end gap-3 rounded-2xl border border-slate-200 bg-white p-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="text-xs font-semibold text-slate-600">Recherche</label>
                    <input v-model="search" type="text" placeholder="Nom, slug, description..." class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white" @click="updateFilters">Filtrer</button>
                    <button v-if="hasFilters" type="button" class="rounded-xl border border-slate-200 px-3 py-2 text-sm" @click="clearFilters">Reinitialiser</button>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                <th class="px-5 py-3">Nom</th>
                                <th class="px-5 py-3">Responsable</th>
                                <th class="px-5 py-3">Stats</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <tr v-for="ead in eads.data" :key="ead.id" class="hover:bg-slate-50">
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-900">{{ ead.nom }}</p>
                                    <p class="text-xs text-slate-500">/{{ ead.slug }}</p>
                                </td>
                                <td class="px-5 py-4 text-slate-600">
                                    <div v-if="ead.responsable">
                                        <p class="font-medium text-slate-900">{{ ead.responsable.name }}</p>
                                        <p class="text-xs text-slate-500">{{ ead.responsable.grade || 'Encadrant' }}</p>
                                    </div>
                                    <span v-else class="text-xs text-slate-400">Aucun</span>
                                </td>
                                <td class="px-5 py-4 text-slate-600">
                                    <p>{{ ead.specialites_count }} specialites</p>
                                    <div class="mt-1 flex flex-wrap items-center gap-2 text-xs">
                                        <span class="text-slate-400">{{ ead.encadrants_count }} encadrants</span>
                                        <span class="text-slate-400">{{ ead.theses_count }} theses</span>
                                        <span v-if="ead.specialites_count === 0" class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-2 py-0.5 font-semibold text-amber-700">
                                            Sans specialite
                                        </span>
                                        <span v-if="ead.theses_count === 0" class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-2 py-0.5 font-semibold text-amber-700">
                                            Sans these
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex justify-end gap-1">
                                        <Link :href="route('admin.ead.edit', ead.slug)" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:border-ed-primary hover:bg-ed-primary/5 hover:text-ed-primary" title="Modifier">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.232-6.232a2.5 2.5 0 113.536 3.536L12.536 16.536A4 4 0 0110 17H7v-3a4 4 0 011-2.536z" />
                                            </svg>
                                        </Link>
                                        <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:border-red-300 hover:bg-red-50 hover:text-red-600" title="Supprimer" @click="deleteEad(ead)">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 011-1h4a1 1 0 011 1m-7 0h8" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!eads.data.length">
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-500">Aucun EAD trouve.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="eads.links?.length" class="border-t border-slate-100 px-4 py-3">
                    <Pagination :links="eads.links" />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
