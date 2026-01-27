<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';
import Pagination from '@/Components/Common/Pagination.vue';

const props = defineProps({
    filters: Object,
    jurys: Object,
    stats: Object,
});

const search = ref(props.filters?.search || '');

const updateFilters = () => {
    router.get(route('admin.jurys.index'), {
        search: search.value,
    }, { preserveState: true, preserveScroll: true });
};

const clearFilters = () => {
    search.value = '';
    updateFilters();
};

const deleteJury = (jury) => {
    if (!confirm(`Supprimer le jury "${jury.nom}" ?`)) return;

    router.delete(route('admin.jurys.destroy', jury.id), { preserveScroll: true });
};

const hasFilters = computed(() => search.value);
</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-slate-900">Jurys</h1>
        </template>

        <Head title="Jurys" />
        <FlashMessage />

        <div class="space-y-4">
            <PageHeader
                badge="Recherche"
                title="Jurys"
                description="Gestion des membres de jury."
            >
                <template #actions>
                    <Link
                        :href="route('admin.jurys.create')"
                        class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary"
                    >
                        Nouveau jury
                    </Link>
                </template>
            </PageHeader>

            <div class="grid gap-4 rounded-2xl border border-slate-200 bg-white p-4 lg:grid-cols-2">
                <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <p class="text-xs text-slate-500">Total jurys</p>
                    <p class="text-lg font-semibold text-slate-900">{{ stats?.total ?? 0 }}</p>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <p class="text-xs text-slate-500">Avec theses</p>
                    <p class="text-lg font-semibold text-emerald-600">{{ stats?.avec_theses ?? 0 }}</p>
                </div>
            </div>

            <div class="flex flex-wrap items-end gap-3 rounded-2xl border border-slate-200 bg-white p-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="text-xs font-semibold text-slate-600">Recherche</label>
                    <input v-model="search" type="text" placeholder="Nom, grade, universite" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
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
                                <th class="px-5 py-3">Universite</th>
                                <th class="px-5 py-3">Contact</th>
                                <th class="px-5 py-3">Theses</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <tr v-for="jury in jurys.data" :key="jury.id" class="hover:bg-slate-50">
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-900">{{ jury.nom }}</p>
                                    <p class="text-xs text-slate-500">{{ jury.grade || '-' }}</p>
                                </td>
                                <td class="px-5 py-4 text-slate-600">{{ jury.universite || '-' }}</td>
                                <td class="px-5 py-4 text-slate-600">
                                    <p>{{ jury.email || '-' }}</p>
                                    <p class="text-xs text-slate-400">{{ jury.phone || '-' }}</p>
                                </td>
                                <td class="px-5 py-4 text-slate-600">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span>{{ jury.theses_count }} theses</span>
                                        <span v-if="jury.theses_count === 0" class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-2 py-0.5 text-xs font-semibold text-amber-700">
                                            Sans these
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <Link :href="route('admin.jurys.edit', jury.id)" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-100">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.232-6.232a2.5 2.5 0 113.536 3.536L12.536 16.536A4 4 0 0110 17H7v-3a4 4 0 011-2.536z" />
                                            </svg>
                                            Modifier
                                        </Link>
                                        <button type="button" class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50" @click="deleteJury(jury)">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 011-1h4a1 1 0 011 1m-7 0h8" />
                                            </svg>
                                            Supprimer
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!jurys.data.length">
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">Aucun jury trouve.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="jurys.links?.length" class="border-t border-slate-100 px-4 py-3">
                    <Pagination :links="jurys.links" />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
