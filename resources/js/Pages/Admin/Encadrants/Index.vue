<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import Card from '@/Components/Admin/Card.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';
import Pagination from '@/Components/Common/Pagination.vue';
import ConfirmDialog from '@/Components/Common/ConfirmDialog.vue';

const props = defineProps({
    filters: Object,
    grades: Array,
    encadrants: Object,
    stats: Object,
});

const search = ref(props.filters?.search || '');
const grade = ref(props.filters?.grade || '');
const showImport = ref(false);

const importForm = useForm({ import_file: null });
const importInput = ref(null);
const importDragging = ref(false);

const handleImportFile = (file) => {
    if (!file) return;
    importForm.import_file = file;
};

const onImportDrop = (event) => {
    event.preventDefault();
    importDragging.value = false;
    handleImportFile(event.dataTransfer?.files?.[0] ?? null);
};

const clearImportFile = () => {
    importForm.import_file = null;
    if (importInput.value) importInput.value.value = '';
};

const submitImport = () => {
    importForm.post(route('admin.encadrants.import'), {
        forceFormData: true,
        onSuccess: () => {
            clearImportFile();
            showImport.value = false;
        },
    });
};

const updateFilters = () => {
    router.get(route('admin.encadrants.index'), {
        search: search.value,
        grade: grade.value,
    }, { preserveState: true, preserveScroll: true });
};

const clearFilters = () => {
    search.value = '';
    grade.value = '';
    updateFilters();
};

const encadrantToDelete = ref(null);
const deleting = ref(false);

const deleteMessage = computed(() =>
    encadrantToDelete.value ? `L'encadrant « ${encadrantToDelete.value.name} » sera définitivement supprimé.` : '',
);

const confirmDelete = () => {
    if (!encadrantToDelete.value) return;

    router.delete(route('admin.encadrants.destroy', encadrantToDelete.value.id), {
        preserveScroll: true,
        onStart: () => { deleting.value = true; },
        onFinish: () => {
            deleting.value = false;
            encadrantToDelete.value = null;
        },
    });
};

const initials = (name) => {
    if (!name) return '–';
    return name.split(' ').filter(Boolean).slice(0, 2).map((p) => p.charAt(0).toUpperCase()).join('');
};

const hasFilters = computed(() => search.value || grade.value);
</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-bold text-slate-700">Encadrants</h1>
        </template>

        <Head title="Encadrants" />
        <FlashMessage />

        <div class="space-y-6">
            <PageHeader
                badge="Gestion académique"
                title="Encadrants"
                description="Directeurs et co-directeurs de thèse."
            >
                <template #actions>
                    <button type="button" class="inline-flex items-center gap-2 rounded-md border border-gray-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-gray-50" @click="showImport = !showImport">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16V4m0 12l-3-3m3 3l3-3M4 20h16" /></svg>
                        Importer
                    </button>
                    <Link :href="route('admin.encadrants.export')" class="inline-flex items-center gap-2 rounded-md border border-gray-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-gray-50">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 4v12m0 0l-4-4m4 4l4-4" /></svg>
                        Exporter
                    </Link>
                    <Link :href="route('admin.encadrants.create')" class="inline-flex items-center gap-2 rounded-md bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-ed-secondary">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" /></svg>
                        Nouvel encadrant
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
                        <p class="text-xs font-medium text-slate-400">Total encadrants</p>
                        <p class="text-xl font-bold tabular-nums text-slate-700">{{ stats?.total ?? 0 }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 rounded-md border border-gray-200 bg-white p-5">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-cyan-100 text-cyan-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </span>
                    <div>
                        <p class="text-xs font-medium text-slate-400">Avec thèses encadrées</p>
                        <p class="text-xl font-bold tabular-nums text-slate-700">{{ stats?.avec_theses ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Import -->
            <Card v-if="showImport" title="Importer des encadrants" subtitle="CSV / XLSX · glisser-déposer ou choisir un fichier">
                <div class="flex flex-wrap items-center gap-3">
                    <div
                        class="relative flex min-w-[240px] flex-1 items-center gap-2 rounded-md border border-dashed px-3 py-2.5 text-sm transition"
                        :class="importDragging ? 'border-ed-primary bg-ed-primary/5' : 'border-gray-300 hover:border-ed-primary/50'"
                        @dragover.prevent="importDragging = true"
                        @dragleave.prevent="importDragging = false"
                        @drop="onImportDrop"
                    >
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        <span v-if="!importForm.import_file" class="text-slate-500">Choisir un fichier</span>
                        <span v-else class="max-w-[220px] truncate text-slate-700">{{ importForm.import_file.name }}</span>
                        <button v-if="importForm.import_file" type="button" class="ml-auto inline-flex h-6 w-6 items-center justify-center rounded text-slate-400 transition hover:bg-red-50 hover:text-red-500" @click="clearImportFile">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                        <input ref="importInput" type="file" class="absolute inset-0 h-full w-full cursor-pointer opacity-0" @change="(e) => handleImportFile(e.target.files?.[0] ?? null)" />
                    </div>
                    <button type="button" class="rounded-md bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary disabled:opacity-60" :disabled="importForm.processing || !importForm.import_file" @click="submitImport">
                        Lancer l'import
                    </button>
                </div>
                <p v-if="importForm.errors.import_file" class="mt-2 text-xs text-red-600">{{ importForm.errors.import_file }}</p>
            </Card>

            <!-- Tableau -->
            <Card no-padding>
                <div class="flex flex-col gap-3 border-b border-gray-100 p-4 lg:flex-row lg:items-center">
                    <div class="relative flex-1">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" /></svg>
                        <input v-model="search" type="text" placeholder="Nom, email, grade, spécialité…" class="w-full rounded-md border-gray-200 pl-10 text-sm focus:border-ed-primary focus:ring-ed-primary" @keyup.enter="updateFilters" />
                    </div>
                    <select v-model="grade" class="w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary lg:w-48" @change="updateFilters">
                        <option value="">Tous les grades</option>
                        <option v-for="item in grades" :key="item" :value="item">{{ item }}</option>
                    </select>
                    <div class="flex items-center gap-2">
                        <button type="button" class="rounded-md bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary" @click="updateFilters">Filtrer</button>
                        <button v-if="hasFilters" type="button" class="rounded-md border border-gray-200 px-3 py-2.5 text-sm font-semibold text-slate-600 hover:bg-gray-50" @click="clearFilters">Réinitialiser</button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                <th class="px-5 py-3">Encadrant</th>
                                <th class="px-5 py-3">Grade</th>
                                <th class="px-5 py-3">Spécialité</th>
                                <th class="px-5 py-3 text-center">Thèses</th>
                                <th class="px-5 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <tr v-for="encadrant in encadrants.data" :key="encadrant.id" class="transition hover:bg-gray-50">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-ed-primary/10 text-xs font-semibold text-ed-teal-dark">{{ initials(encadrant.name) }}</span>
                                        <div class="min-w-0">
                                            <p class="truncate font-semibold text-slate-700">{{ encadrant.name }}</p>
                                            <p class="text-xs text-slate-400">{{ encadrant.email || '—' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <span v-if="encadrant.grade" class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">{{ encadrant.grade }}</span>
                                    <span v-else class="text-slate-400">—</span>
                                </td>
                                <td class="px-5 py-4 text-slate-600">{{ encadrant.specialite || '—' }}</td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex h-7 min-w-[1.75rem] items-center justify-center rounded-md bg-gray-100 px-2 text-xs font-semibold text-slate-600">{{ encadrant.theses_count }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-1.5">
                                        <Link :href="route('admin.encadrants.show', encadrant.id)" class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-md border border-gray-200 text-slate-500 transition hover:bg-gray-50" title="Voir">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </Link>
                                        <Link :href="route('admin.encadrants.edit', encadrant.id)" class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-md border border-gray-200 text-slate-500 transition hover:border-ed-primary hover:bg-ed-primary/5 hover:text-ed-primary" title="Modifier">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.232-6.232a2.5 2.5 0 113.536 3.536L12.536 16.536A4 4 0 0110 17H7v-3a4 4 0 011-2.536z" /></svg>
                                        </Link>
                                        <button type="button" class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-md border border-gray-200 text-slate-500 transition hover:border-red-300 hover:bg-red-50 hover:text-red-600" title="Supprimer" @click="encadrantToDelete = encadrant">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 011-1h4a1 1 0 011 1m-7 0h8" /></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!encadrants.data.length">
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <p class="text-sm font-medium text-slate-600">Aucun encadrant trouvé</p>
                                    <p class="mt-1 text-xs text-slate-400">Ajustez la recherche ou ajoutez un encadrant.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="encadrants.links?.length" class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-4 py-3">
                    <p class="text-xs text-slate-400">{{ encadrants.from ?? 0 }}–{{ encadrants.to ?? 0 }} sur {{ encadrants.total ?? 0 }} encadrant(s)</p>
                    <Pagination :links="encadrants.links" />
                </div>
            </Card>
        </div>

        <ConfirmDialog
            :show="encadrantToDelete !== null"
            title="Supprimer l'encadrant"
            :message="deleteMessage"
            confirm-label="Supprimer"
            variant="danger"
            :processing="deleting"
            @confirm="confirmDelete"
            @cancel="encadrantToDelete = null"
        />
    </AdminLayout>
</template>
