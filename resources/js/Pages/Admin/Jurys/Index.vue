<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import Card from '@/Components/Admin/Card.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';
import Pagination from '@/Components/Common/Pagination.vue';
import ConfirmDialog from '@/Components/Common/ConfirmDialog.vue';

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

const juryToDelete = ref(null);
const deleting = ref(false);

const deleteMessage = computed(() =>
    juryToDelete.value ? `Le membre de jury « ${juryToDelete.value.nom} » sera définitivement supprimé.` : '',
);

const confirmDelete = () => {
    if (!juryToDelete.value) return;

    router.delete(route('admin.jurys.destroy', juryToDelete.value.id), {
        preserveScroll: true,
        onStart: () => { deleting.value = true; },
        onFinish: () => {
            deleting.value = false;
            juryToDelete.value = null;
        },
    });
};

const initials = (name) => {
    if (!name) return '–';
    return name.split(' ').filter(Boolean).slice(0, 2).map((p) => p.charAt(0).toUpperCase()).join('');
};

const hasFilters = computed(() => search.value);
</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-bold text-slate-700">Jurys</h1>
        </template>

        <Head title="Jurys" />
        <FlashMessage />

        <div class="space-y-6">
            <PageHeader
                badge="Gestion académique"
                title="Membres de jury"
                description="Personnalités participant aux soutenances de thèse."
            >
                <template #actions>
                    <Link :href="route('admin.jurys.create')" class="inline-flex items-center gap-2 rounded-md bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-ed-secondary">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" /></svg>
                        Nouveau membre
                    </Link>
                </template>
            </PageHeader>

            <!-- KPI -->
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="flex items-center gap-4 rounded-md border border-gray-200 bg-white p-5">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-ed-primary/10 text-ed-teal-dark">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a3 3 0 10-3-3" /></svg>
                    </span>
                    <div>
                        <p class="text-xs font-medium text-slate-400">Total membres</p>
                        <p class="text-xl font-bold tabular-nums text-slate-700">{{ stats?.total ?? 0 }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 rounded-md border border-gray-200 bg-white p-5">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-cyan-100 text-cyan-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </span>
                    <div>
                        <p class="text-xs font-medium text-slate-400">Ayant siégé en jury</p>
                        <p class="text-xl font-bold tabular-nums text-slate-700">{{ stats?.avec_theses ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Tableau -->
            <Card no-padding>
                <div class="flex flex-col gap-3 border-b border-gray-100 p-4 lg:flex-row lg:items-center">
                    <div class="relative flex-1">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" /></svg>
                        <input v-model="search" type="text" placeholder="Nom, grade, université…" class="w-full rounded-md border-gray-200 pl-10 text-sm focus:border-ed-primary focus:ring-ed-primary" @keyup.enter="updateFilters" />
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" class="rounded-md bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary" @click="updateFilters">Filtrer</button>
                        <button v-if="hasFilters" type="button" class="rounded-md border border-gray-200 px-3 py-2.5 text-sm font-semibold text-slate-600 hover:bg-gray-50" @click="clearFilters">Réinitialiser</button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                <th class="px-5 py-3">Membre</th>
                                <th class="px-5 py-3">Université</th>
                                <th class="px-5 py-3">Contact</th>
                                <th class="px-5 py-3 text-center">Thèses</th>
                                <th class="px-5 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <tr v-for="jury in jurys.data" :key="jury.id" class="transition hover:bg-gray-50">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-ed-primary/10 text-xs font-semibold text-ed-teal-dark">{{ initials(jury.nom) }}</span>
                                        <div class="min-w-0">
                                            <p class="truncate font-semibold text-slate-700">{{ jury.nom }}</p>
                                            <p class="text-xs text-slate-400">{{ jury.grade || '—' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-slate-600">{{ jury.universite || '—' }}</td>
                                <td class="px-5 py-4">
                                    <p class="text-slate-600">{{ jury.email || '—' }}</p>
                                    <p class="text-xs text-slate-400">{{ jury.phone || '—' }}</p>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex h-7 min-w-[1.75rem] items-center justify-center rounded-md bg-gray-100 px-2 text-xs font-semibold text-slate-600">{{ jury.theses_count }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-1.5">
                                        <Link :href="route('admin.jurys.edit', jury.id)" class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-md border border-gray-200 text-slate-500 transition hover:border-ed-primary hover:bg-ed-primary/5 hover:text-ed-primary" title="Modifier">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.232-6.232a2.5 2.5 0 113.536 3.536L12.536 16.536A4 4 0 0110 17H7v-3a4 4 0 011-2.536z" /></svg>
                                        </Link>
                                        <button type="button" class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-md border border-gray-200 text-slate-500 transition hover:border-red-300 hover:bg-red-50 hover:text-red-600" title="Supprimer" @click="juryToDelete = jury">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 011-1h4a1 1 0 011 1m-7 0h8" /></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!jurys.data.length">
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <p class="text-sm font-medium text-slate-600">Aucun membre de jury trouvé</p>
                                    <p class="mt-1 text-xs text-slate-400">Ajustez la recherche ou ajoutez un membre.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="jurys.links?.length" class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-4 py-3">
                    <p class="text-xs text-slate-400">{{ jurys.from ?? 0 }}–{{ jurys.to ?? 0 }} sur {{ jurys.total ?? 0 }} membre(s)</p>
                    <Pagination :links="jurys.links" />
                </div>
            </Card>
        </div>

        <ConfirmDialog
            :show="juryToDelete !== null"
            title="Supprimer le membre de jury"
            :message="deleteMessage"
            confirm-label="Supprimer"
            variant="danger"
            :processing="deleting"
            @confirm="confirmDelete"
            @cancel="juryToDelete = null"
        />
    </AdminLayout>
</template>
