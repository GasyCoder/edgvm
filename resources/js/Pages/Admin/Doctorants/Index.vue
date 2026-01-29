<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';
import Pagination from '@/Components/Common/Pagination.vue';

const props = defineProps({
    filters: Object,
    stats: Object,
    eads: Array,
    doctorants: Object,
});

const search = ref(props.filters?.search || '');
const statut = ref(props.filters?.statut || '');
const ead = ref(props.filters?.ead || '');

const importForm = useForm({
    import_file: null,
});

const updateFilters = () => {
    router.get(route('admin.doctorants.index'), {
        search: search.value,
        statut: statut.value,
        ead: ead.value,
    }, { preserveState: true, preserveScroll: true });
};

const clearFilters = () => {
    search.value = '';
    statut.value = '';
    ead.value = '';
    updateFilters();
};

const deleteDoctorant = (doctorant) => {
    if (!confirm(`Supprimer le doctorant ${doctorant.matricule} ?`)) return;

    router.delete(route('admin.doctorants.destroy', doctorant.id), { preserveScroll: true });
};

const statutClasses = (statutValue) => {
    switch (statutValue) {
        case 'actif':
            return 'bg-emerald-50 text-emerald-700 border-emerald-200';
        case 'diplome':
            return 'bg-blue-50 text-blue-700 border-blue-200';
        case 'suspendu':
            return 'bg-amber-50 text-amber-700 border-amber-200';
        case 'abandonne':
            return 'bg-red-50 text-red-700 border-red-200';
        default:
            return 'bg-slate-50 text-slate-600 border-slate-200';
    }
};

const submitImport = () => {
    importForm.post(route('admin.doctorants.import'), {
        forceFormData: true,
    });
};

const hasFilters = computed(() => search.value || statut.value || ead.value);
</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-slate-900">Doctorants</h1>
        </template>

        <Head title="Doctorants" />
        <FlashMessage />

        <div class="space-y-4">
            <PageHeader
                badge="Recherche"
                title="Doctorants"
                description="Suivi des doctorants et de leurs theses."
            >
                <template #actions>
                    <Link :href="route('admin.doctorants.export')" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:bg-slate-50" title="Exporter">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16V4m0 12l-3-3m3 3l3-3M4 20h16" />
                        </svg>
                    </Link>
                    <Link :href="route('admin.doctorants.create')" class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-ed-primary text-white transition hover:bg-ed-secondary" title="Nouveau doctorant">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                        </svg>
                    </Link>
                </template>
            </PageHeader>

            <div class="grid gap-4 rounded-2xl border border-slate-200 bg-white p-4 lg:grid-cols-3">
                <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <p class="text-xs text-slate-500">Total doctorants</p>
                    <p class="text-lg font-semibold text-slate-900">{{ stats?.total ?? 0 }}</p>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <p class="text-xs text-slate-500">Actifs</p>
                    <p class="text-lg font-semibold text-emerald-600">{{ stats?.actifs ?? 0 }}</p>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <p class="text-xs text-slate-500">Diplomes</p>
                    <p class="text-lg font-semibold text-indigo-600">{{ stats?.diplomes ?? 0 }}</p>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:flex lg:flex-wrap lg:items-end">
                    <div class="sm:col-span-2 lg:flex-1 lg:min-w-[200px]">
                        <label class="text-xs font-semibold text-slate-600">Recherche</label>
                        <input v-model="search" type="text" placeholder="Nom ou matricule" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
                    </div>
                    <div class="w-full sm:w-auto">
                        <label class="text-xs font-semibold text-slate-600">Statut</label>
                        <select v-model="statut" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                            <option value="">Tous</option>
                            <option value="actif">Actif</option>
                            <option value="diplome">Diplome</option>
                            <option value="suspendu">Suspendu</option>
                            <option value="abandonne">Abandonne</option>
                        </select>
                    </div>
                    <div class="w-full sm:w-auto">
                        <label class="text-xs font-semibold text-slate-600">EAD</label>
                        <select v-model="ead" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                            <option value="">Tous</option>
                            <option v-for="item in eads" :key="item.id" :value="item.id">{{ item.nom }}</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 sm:col-span-2 lg:col-span-1">
                        <button type="button" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white" @click="updateFilters">Filtrer</button>
                        <button v-if="hasFilters" type="button" class="rounded-xl border border-slate-200 px-3 py-2 text-sm" @click="clearFilters">Reinitialiser</button>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                <p class="text-xs font-semibold text-slate-600">Importer des doctorants</p>
                <div class="mt-3 flex flex-wrap items-center gap-3">
                    <label class="group flex cursor-pointer items-center gap-3 rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-ed-primary hover:bg-white">
                        <input type="file" accept=".xlsx,.xls" class="hidden" @change="(e) => (importForm.import_file = e.target.files?.[0] || null)" />
                        <svg class="h-4 w-4 text-slate-400 group-hover:text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16V4m0 12l-3-3m3 3l3-3M4 20h16" />
                        </svg>
                        <span>Choisir un fichier</span>
                    </label>
                    <button type="button" class="rounded-xl bg-ed-primary px-4 py-2 text-sm font-semibold text-white hover:bg-ed-secondary" :disabled="importForm.processing" @click="submitImport">
                        Importer
                    </button>
                    <p v-if="importForm.errors.import_file" class="text-xs text-red-600">{{ importForm.errors.import_file }}</p>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                <th class="px-5 py-3">Doctorant</th>
                                <th class="px-5 py-3">Niveau</th>
                                <th class="px-5 py-3">EAD</th>
                                <th class="px-5 py-3">Theses</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <tr v-for="doctorant in doctorants.data" :key="doctorant.id" class="hover:bg-slate-50">
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-900">{{ doctorant.name }}</p>
                                    <p class="text-xs text-slate-500">{{ doctorant.matricule }}</p>
                                </td>
                                <td class="px-5 py-4 text-slate-600">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="text-xs font-semibold text-slate-700">{{ doctorant.niveau }}</span>
                                        <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-semibold" :class="statutClasses(doctorant.statut)">
                                            {{ doctorant.statut }}
                                        </span>
                                        <span v-if="doctorant.theses_count === 0" class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-2 py-0.5 text-xs font-semibold text-amber-700">
                                            Sans these
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-slate-600">{{ doctorant.ead?.nom || '-' }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ doctorant.theses_count }}</td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex justify-end gap-1">
                                        <Link :href="route('admin.doctorants.show', doctorant.id)" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:bg-slate-100" title="Voir">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </Link>
                                        <Link :href="route('admin.doctorants.edit', doctorant.id)" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:border-ed-primary hover:bg-ed-primary/5 hover:text-ed-primary" title="Modifier">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.232-6.232a2.5 2.5 0 113.536 3.536L12.536 16.536A4 4 0 0110 17H7v-3a4 4 0 011-2.536z" />
                                            </svg>
                                        </Link>
                                        <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:border-red-300 hover:bg-red-50 hover:text-red-600" title="Supprimer" @click="deleteDoctorant(doctorant)">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 011-1h4a1 1 0 011 1m-7 0h8" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!doctorants.data.length">
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">Aucun doctorant trouve.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="doctorants.links?.length" class="border-t border-slate-100 px-4 py-3">
                    <Pagination :links="doctorants.links" />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
