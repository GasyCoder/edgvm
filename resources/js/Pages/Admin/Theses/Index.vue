<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';
import Pagination from '@/Components/Common/Pagination.vue';

const props = defineProps({
    filters: Object,
    statuts: Array,
    eads: Array,
    specialites: Array,
    theses: Object,
    stats: Object,
});

const search = ref(props.filters?.search || '');
const statut = ref(props.filters?.statut || '');
const ead = ref(props.filters?.ead || '');
const specialite = ref(props.filters?.specialite || '');

const importForm = useForm({
    import_file: null,
});

const importInput = ref(null);
const importDragging = ref(false);

const handleImportFile = (file) => {
    if (!file) {
        return;
    }

    importForm.import_file = file;
};

const onImportDrop = (event) => {
    event.preventDefault();
    importDragging.value = false;
    handleImportFile(event.dataTransfer?.files?.[0] ?? null);
};

const clearImportFile = () => {
    importForm.import_file = null;
    if (importInput.value) {
        importInput.value.value = '';
    }
};

const updateFilters = () => {
    router.get(route('admin.theses.index'), {
        search: search.value,
        statut: statut.value,
        ead: ead.value,
        specialite: specialite.value,
    }, { preserveState: true, preserveScroll: true });
};

const clearFilters = () => {
    search.value = '';
    statut.value = '';
    ead.value = '';
    specialite.value = '';
    updateFilters();
};

const deleteThese = (these) => {
    if (!confirm(`Supprimer la these "${these.sujet_these}" ?`)) return;

    router.delete(route('admin.theses.destroy', these.id), { preserveScroll: true });
};

const statutClasses = (statutValue) => {
    switch (statutValue) {
        case 'en_cours':
            return 'bg-emerald-50 text-emerald-700 border-emerald-200';
        case 'soutenue':
            return 'bg-blue-50 text-blue-700 border-blue-200';
        case 'preparation':
            return 'bg-amber-50 text-amber-700 border-amber-200';
        case 'redaction':
            return 'bg-indigo-50 text-indigo-700 border-indigo-200';
        case 'suspendue':
            return 'bg-orange-50 text-orange-700 border-orange-200';
        case 'abandonnee':
            return 'bg-red-50 text-red-700 border-red-200';
        default:
            return 'bg-slate-50 text-slate-600 border-slate-200';
    }
};

const submitImport = () => {
    importForm.post(route('admin.theses.import'), {
        forceFormData: true,
    });
};

const hasFilters = computed(() => search.value || statut.value || ead.value || specialite.value);
</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-slate-900">Theses</h1>
        </template>

        <Head title="Theses" />
        <FlashMessage />

        <div class="space-y-4">
            <PageHeader
                badge="Recherche"
                title="Theses"
                description="Suivi des theses et des jurys."
            >
                <template #actions>
                    <Link
                        :href="route('admin.theses.export')"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                    >
                        Exporter
                    </Link>
                    <Link
                        :href="route('admin.theses.create')"
                        class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary"
                    >
                        Nouvelle these
                    </Link>
                </template>
            </PageHeader>

            <div class="grid gap-4 rounded-2xl border border-slate-200 bg-white p-4 lg:grid-cols-3">
                <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <p class="text-xs text-slate-500">Total theses</p>
                    <p class="text-lg font-semibold text-slate-900">{{ stats?.total ?? 0 }}</p>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <p class="text-xs text-slate-500">En cours</p>
                    <p class="text-lg font-semibold text-emerald-600">{{ stats?.en_cours ?? 0 }}</p>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <p class="text-xs text-slate-500">Soutenues</p>
                    <p class="text-lg font-semibold text-indigo-600">{{ stats?.soutenue ?? 0 }}</p>
                </div>
            </div>

            <div class="flex flex-wrap items-end gap-3 rounded-2xl border border-slate-200 bg-white p-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="text-xs font-semibold text-slate-600">Recherche</label>
                    <input v-model="search" type="text" placeholder="Sujet, doctorant, matricule" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
                </div>
                <div class="min-w-[160px]">
                    <label class="text-xs font-semibold text-slate-600">Statut</label>
                    <select v-model="statut" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        <option value="">Tous</option>
                        <option v-for="item in statuts" :key="item" :value="item">{{ item }}</option>
                    </select>
                </div>
                <div class="min-w-[180px]">
                    <label class="text-xs font-semibold text-slate-600">EAD</label>
                    <select v-model="ead" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        <option value="">Tous</option>
                        <option v-for="item in eads" :key="item.id" :value="item.id">{{ item.nom }}</option>
                    </select>
                </div>
                <div class="min-w-[180px]">
                    <label class="text-xs font-semibold text-slate-600">Specialite</label>
                    <select v-model="specialite" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        <option value="">Toutes</option>
                        <option v-for="item in specialites" :key="item.id" :value="item.id">{{ item.nom }}</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white" @click="updateFilters">Filtrer</button>
                    <button v-if="hasFilters" type="button" class="rounded-xl border border-slate-200 px-3 py-2 text-sm" @click="clearFilters">Reinitialiser</button>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                <p class="text-xs font-semibold text-slate-600">Importer des theses</p>
                <div class="mt-3 grid gap-3 lg:grid-cols-[1fr_auto] lg:items-end">
                    <div
                        class="relative rounded-xl border-2 border-dashed transition-all duration-200"
                        :class="importDragging ? 'border-ed-primary bg-ed-primary/5' : 'border-slate-200 hover:border-slate-300'"
                        @dragover.prevent="importDragging = true"
                        @dragleave.prevent="importDragging = false"
                        @drop="onImportDrop"
                    >
                        <div class="p-4">
                            <div v-if="importForm.import_file" class="flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-slate-700 truncate">
                                        {{ importForm.import_file.name }}
                                    </p>
                                    <p class="text-xs text-slate-500">Cliquez ou deposez pour remplacer</p>
                                </div>
                                <button
                                    type="button"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-red-50 hover:text-red-500"
                                    @click="clearImportFile"
                                >
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div v-else class="text-center">
                                <svg class="mx-auto h-9 w-9 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="mt-2 text-sm font-medium text-slate-600">Deposez le fichier ici</p>
                                <p class="text-xs text-slate-400">CSV, XLSX · selon le template</p>
                            </div>
                        </div>
                        <input
                            ref="importInput"
                            type="file"
                            class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                            @change="(e) => handleImportFile(e.target.files?.[0] ?? null)"
                        />
                    </div>
                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white"
                            :disabled="importForm.processing || !importForm.import_file"
                            @click="submitImport"
                        >
                            Importer
                        </button>
                        <p v-if="importForm.errors.import_file" class="text-xs text-red-600">{{ importForm.errors.import_file }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                <th class="px-5 py-3">These</th>
                                <th class="px-5 py-3">Doctorant</th>
                                <th class="px-5 py-3">Structure</th>
                                <th class="px-5 py-3">Encadrants/Jurys</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <tr v-for="these in theses.data" :key="these.id" class="hover:bg-slate-50">
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-900">{{ these.sujet_these }}</p>
                                    <div class="mt-1 flex flex-wrap items-center gap-2">
                                        <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-semibold" :class="statutClasses(these.statut)">
                                            {{ these.statut }}
                                        </span>
                                        <span v-if="these.encadrants_count === 0" class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-2 py-0.5 text-xs font-semibold text-amber-700">
                                            Sans encadrant
                                        </span>
                                        <span v-if="these.jurys_count === 0" class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-2 py-0.5 text-xs font-semibold text-amber-700">
                                            Sans jury
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-slate-600">
                                    <p>{{ these.doctorant?.name || '-' }}</p>
                                    <p class="text-xs text-slate-400">{{ these.doctorant?.matricule || '' }}</p>
                                </td>
                                <td class="px-5 py-4 text-slate-600">
                                    <p>{{ these.ead?.nom || '-' }}</p>
                                    <p class="text-xs text-slate-400">{{ these.specialite?.nom || '-' }}</p>
                                </td>
                                <td class="px-5 py-4 text-slate-600">
                                    <p>{{ these.encadrants_count }} encadrants</p>
                                    <p class="text-xs text-slate-400">{{ these.jurys_count }} jurys</p>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <Link :href="route('admin.theses.show', these.id)" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-100">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Voir
                                        </Link>
                                        <Link :href="route('admin.theses.edit', these.id)" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-100">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.232-6.232a2.5 2.5 0 113.536 3.536L12.536 16.536A4 4 0 0110 17H7v-3a4 4 0 011-2.536z" />
                                            </svg>
                                            Modifier
                                        </Link>
                                        <Link :href="route('admin.theses.jury.edit', these.id)" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-100">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8m-8 4h6M5 7h14M5 17h6" />
                                            </svg>
                                            Jury
                                        </Link>
                                        <button type="button" class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50" @click="deleteThese(these)">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 011-1h4a1 1 0 011 1m-7 0h8" />
                                            </svg>
                                            Supprimer
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!theses.data.length">
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">Aucune these trouvee.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="theses.links?.length" class="border-t border-slate-100 px-4 py-3">
                    <Pagination :links="theses.links" />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
