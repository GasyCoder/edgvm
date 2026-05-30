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
    importForm.post(route('admin.theses.import'), {
        forceFormData: true,
        onSuccess: () => {
            clearImportFile();
            showImport.value = false;
        },
    });
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

const statutMeta = {
    en_cours: { label: 'En cours', class: 'bg-emerald-50 text-emerald-700 border-emerald-200' },
    soutenue: { label: 'Soutenue', class: 'bg-blue-50 text-blue-700 border-blue-200' },
    preparation: { label: 'Préparation', class: 'bg-amber-50 text-amber-700 border-amber-200' },
    redaction: { label: 'Rédaction', class: 'bg-indigo-50 text-indigo-700 border-indigo-200' },
    suspendue: { label: 'Suspendue', class: 'bg-orange-50 text-orange-700 border-orange-200' },
    abandonnee: { label: 'Abandonnée', class: 'bg-red-50 text-red-700 border-red-200' },
};

const statutOf = (value) => statutMeta[value] || { label: value, class: 'bg-gray-50 text-slate-600 border-gray-200' };

const theseToDelete = ref(null);
const deleting = ref(false);

const deleteMessage = computed(() =>
    theseToDelete.value ? `La thèse « ${theseToDelete.value.sujet_these} » sera définitivement supprimée.` : '',
);

const confirmDelete = () => {
    if (!theseToDelete.value) return;

    router.delete(route('admin.theses.destroy', theseToDelete.value.id), {
        preserveScroll: true,
        onStart: () => { deleting.value = true; },
        onFinish: () => {
            deleting.value = false;
            theseToDelete.value = null;
        },
    });
};

const hasFilters = computed(() => search.value || statut.value || ead.value || specialite.value);
</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-bold text-slate-700">Thèses</h1>
        </template>

        <Head title="Thèses" />
        <FlashMessage />

        <div class="space-y-6">
            <PageHeader
                badge="Gestion académique"
                title="Thèses"
                description="Suivi des thèses, encadrements et jurys."
            >
                <template #actions>
                    <button type="button" class="inline-flex items-center gap-2 rounded-md border border-gray-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-gray-50" @click="showImport = !showImport">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16V4m0 12l-3-3m3 3l3-3M4 20h16" /></svg>
                        Importer
                    </button>
                    <Link :href="route('admin.theses.export')" class="inline-flex items-center gap-2 rounded-md border border-gray-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-gray-50">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 4v12m0 0l-4-4m4 4l4-4" /></svg>
                        Exporter
                    </Link>
                    <Link :href="route('admin.theses.create')" class="inline-flex items-center gap-2 rounded-md bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-ed-secondary">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" /></svg>
                        Nouvelle thèse
                    </Link>
                </template>
            </PageHeader>

            <!-- KPI -->
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="flex items-center gap-4 rounded-md border border-gray-200 bg-white p-5">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-ed-primary/10 text-ed-teal-dark">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </span>
                    <div>
                        <p class="text-xs font-medium text-slate-400">Total thèses</p>
                        <p class="text-xl font-bold tabular-nums text-slate-700">{{ stats?.total ?? 0 }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 rounded-md border border-gray-200 bg-white p-5">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </span>
                    <div>
                        <p class="text-xs font-medium text-slate-400">En cours</p>
                        <p class="text-xl font-bold tabular-nums text-slate-700">{{ stats?.en_cours ?? 0 }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 rounded-md border border-gray-200 bg-white p-5">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </span>
                    <div>
                        <p class="text-xs font-medium text-slate-400">Soutenues</p>
                        <p class="text-xl font-bold tabular-nums text-slate-700">{{ stats?.soutenue ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Import -->
            <Card v-if="showImport" title="Importer des thèses" subtitle="CSV / XLSX · glisser-déposer ou choisir un fichier">
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
                <div class="flex flex-col gap-3 border-b border-gray-100 p-4 lg:flex-row lg:flex-wrap lg:items-center">
                    <div class="relative flex-1 lg:min-w-[220px]">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" /></svg>
                        <input v-model="search" type="text" placeholder="Sujet, doctorant, matricule…" class="w-full rounded-md border-gray-200 pl-10 text-sm focus:border-ed-primary focus:ring-ed-primary" @keyup.enter="updateFilters" />
                    </div>
                    <select v-model="statut" class="w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary lg:w-40" @change="updateFilters">
                        <option value="">Tous statuts</option>
                        <option v-for="item in statuts" :key="item" :value="item">{{ statutOf(item).label }}</option>
                    </select>
                    <select v-model="ead" class="w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary lg:w-44" @change="updateFilters">
                        <option value="">Toutes équipes</option>
                        <option v-for="item in eads" :key="item.id" :value="item.id">{{ item.sigle || item.nom }}</option>
                    </select>
                    <select v-model="specialite" class="w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary lg:w-48" @change="updateFilters">
                        <option value="">Toutes spécialités</option>
                        <option v-for="item in specialites" :key="item.id" :value="item.id">{{ item.nom }}</option>
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
                                <th class="px-5 py-3">Thèse</th>
                                <th class="px-5 py-3">Doctorant</th>
                                <th class="px-5 py-3">Structure</th>
                                <th class="px-5 py-3 text-center">Encadr. / Jurys</th>
                                <th class="px-5 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <tr v-for="these in theses.data" :key="these.id" class="transition hover:bg-gray-50">
                                <td class="px-5 py-4">
                                    <p class="line-clamp-2 max-w-sm font-semibold text-slate-700" :title="these.sujet_these">{{ these.sujet_these }}</p>
                                    <span class="mt-1 inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-semibold" :class="statutOf(these.statut).class">{{ statutOf(these.statut).label }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-slate-600">{{ these.doctorant?.name || '—' }}</p>
                                    <p class="text-xs text-slate-400">{{ these.doctorant?.matricule || '' }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <span v-if="these.ead" class="inline-flex items-center rounded-full bg-ed-primary/10 px-2.5 py-0.5 text-xs font-semibold text-ed-teal-dark" :title="these.ead.nom">{{ these.ead.sigle || these.ead.nom }}</span>
                                    <span v-else class="text-slate-400">—</span>
                                    <p class="mt-1 max-w-[220px] truncate text-xs text-slate-400" :title="these.specialite?.nom">{{ these.specialite?.nom || '—' }}</p>
                                </td>
                                <td class="px-5 py-4 text-center text-slate-600">
                                    <span class="tabular-nums">{{ these.encadrants_count }}</span>
                                    <span class="text-slate-300"> / </span>
                                    <span class="tabular-nums">{{ these.jurys_count }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-1.5">
                                        <Link :href="route('admin.theses.show', these.id)" class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-md border border-gray-200 text-slate-500 transition hover:bg-gray-50" title="Voir">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </Link>
                                        <Link :href="route('admin.theses.edit', these.id)" class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-md border border-gray-200 text-slate-500 transition hover:border-ed-primary hover:bg-ed-primary/5 hover:text-ed-primary" title="Modifier">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.232-6.232a2.5 2.5 0 113.536 3.536L12.536 16.536A4 4 0 0110 17H7v-3a4 4 0 011-2.536z" /></svg>
                                        </Link>
                                        <Link :href="route('admin.theses.jury.edit', these.id)" class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-md border border-gray-200 text-slate-500 transition hover:bg-gray-50" title="Jury">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8m-8 4h6M5 7h14M5 17h6" /></svg>
                                        </Link>
                                        <button type="button" class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-md border border-gray-200 text-slate-500 transition hover:border-red-300 hover:bg-red-50 hover:text-red-600" title="Supprimer" @click="theseToDelete = these">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 011-1h4a1 1 0 011 1m-7 0h8" /></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!theses.data.length">
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <p class="text-sm font-medium text-slate-600">Aucune thèse trouvée</p>
                                    <p class="mt-1 text-xs text-slate-400">Ajustez les filtres ou ajoutez une thèse.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="theses.links?.length" class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-4 py-3">
                    <p class="text-xs text-slate-400">{{ theses.from ?? 0 }}–{{ theses.to ?? 0 }} sur {{ theses.total ?? 0 }} thèse(s)</p>
                    <Pagination :links="theses.links" />
                </div>
            </Card>
        </div>

        <ConfirmDialog
            :show="theseToDelete !== null"
            title="Supprimer la thèse"
            :message="deleteMessage"
            confirm-label="Supprimer"
            variant="danger"
            :processing="deleting"
            @confirm="confirmDelete"
            @cancel="theseToDelete = null"
        />
    </AdminLayout>
</template>
