<script setup>
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';
import Pagination from '@/Components/Common/Pagination.vue';
import ConfirmDialog from '@/Components/Common/ConfirmDialog.vue';

const props = defineProps({
    filters: Object,
    stats: Object,
    niveaux: Array,
    statuts: Array,
    eads: Array,
    doctorants: Object,
});

const page = usePage();
const can = (ability) => Boolean(page.props.auth?.can?.[ability]);

const search = ref(props.filters?.search || '');
const statut = ref(props.filters?.statut || '');
const niveau = ref(props.filters?.niveau || '');
const ead = ref(props.filters?.ead || '');
const showImport = ref(false);
const selected = ref([]);

const importForm = useForm({ import_file: null });

const queryPayload = () => ({
    search: search.value,
    statut: statut.value,
    niveau: niveau.value,
    ead: ead.value,
    archives: props.filters?.archives ? 1 : undefined,
});

const updateFilters = (extra = {}) => {
    selected.value = [];
    router.get(route('admin.doctorants.index'), { ...queryPayload(), ...extra }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const clearFilters = () => {
    search.value = '';
    statut.value = '';
    niveau.value = '';
    ead.value = '';
    updateFilters();
};

const setArchives = (value) => {
    selected.value = [];
    router.get(route('admin.doctorants.index'), {
        ...queryPayload(),
        archives: value ? 1 : undefined,
    }, { preserveState: true, preserveScroll: true });
};

const hasFilters = computed(() => search.value || statut.value || niveau.value || ead.value);
const isArchives = computed(() => Boolean(props.filters?.archives));

const statutMeta = {
    actif: { label: 'Actif', class: 'bg-emerald-50 text-emerald-700 border-emerald-200' },
    diplome: { label: 'Diplômé', class: 'bg-blue-50 text-blue-700 border-blue-200' },
    suspendu: { label: 'Suspendu', class: 'bg-amber-50 text-amber-700 border-amber-200' },
    abandonne: { label: 'Abandonné', class: 'bg-red-50 text-red-700 border-red-200' },
};

const statutOf = (value) => statutMeta[value] || { label: value, class: 'bg-gray-50 text-gray-600 border-gray-200' };

const initials = (name) => {
    if (!name) return '–';

    return name.split(' ').filter(Boolean).slice(0, 2).map((p) => p.charAt(0).toUpperCase()).join('');
};

const allSelected = computed(() =>
    props.doctorants.data.length > 0 && selected.value.length === props.doctorants.data.length,
);

const toggleAll = (event) => {
    selected.value = event.target.checked ? props.doctorants.data.map((d) => d.id) : [];
};

const submitImport = () => {
    importForm.post(route('admin.doctorants.import'), {
        forceFormData: true,
        onSuccess: () => {
            importForm.reset();
            showImport.value = false;
        },
    });
};

// Actions groupées
const bulkForm = useForm({ action: '', ids: [] });
const confirmAction = ref(null);
const showDocsMenu = ref(false);

const confirmMeta = {
    archive: { title: 'Archiver la sélection', message: 'Les doctorants sélectionnés seront déplacés dans les archives.', confirmLabel: 'Archiver', variant: 'primary' },
    restore: { title: 'Restaurer la sélection', message: 'Les doctorants sélectionnés réintégreront la liste principale.', confirmLabel: 'Restaurer', variant: 'primary' },
    notify: { title: 'Envoyer une notification', message: 'Un e-mail récapitulatif sera envoyé aux doctorants sélectionnés disposant d\'une adresse.', confirmLabel: 'Envoyer', variant: 'primary' },
    delete: { title: 'Supprimer la sélection', message: 'Cette action est irréversible. Les doctorants sélectionnés seront définitivement supprimés.', confirmLabel: 'Supprimer', variant: 'danger' },
};

const currentConfirm = computed(() => (confirmAction.value ? confirmMeta[confirmAction.value] : null));

const askBulk = (action) => {
    if (!selected.value.length) return;
    confirmAction.value = action;
};

const runBulk = () => {
    const action = confirmAction.value;

    bulkForm.transform(() => ({ action, ids: selected.value })).post(route('admin.doctorants.bulk'), {
        preserveScroll: true,
        onSuccess: () => {
            selected.value = [];
        },
        onFinish: () => {
            confirmAction.value = null;
        },
    });
};

const downloadDocuments = (type) => {
    showDocsMenu.value = false;
    if (!selected.value.length) return;

    window.open(route('admin.doctorants.documents', { type, ids: selected.value }), '_blank');
};

// Observation
const observForm = useForm({ observation: '' });
const editingObservation = ref(null);

const openObservation = (doctorant) => {
    editingObservation.value = doctorant;
    observForm.observation = doctorant.observation || '';
    observForm.clearErrors();
};

const saveObservation = () => {
    observForm.put(route('admin.doctorants.observation.update', editingObservation.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            editingObservation.value = null;
        },
    });
};

const deleteDoctorant = (doctorant) => {
    selected.value = [doctorant.id];
    confirmAction.value = 'delete';
};
</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-gray-900">Doctorants</h1>
        </template>

        <Head title="Doctorants" />
        <FlashMessage />

        <div class="space-y-6">
            <PageHeader
                badge="Gestion académique"
                title="Doctorants"
                description="Suivi des doctorants, de leur parcours et de leurs thèses."
            >
                <template #actions>
                    <button type="button" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50" @click="showImport = !showImport">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16V4m0 12l-3-3m3 3l3-3M4 20h16" />
                        </svg>
                        Importer
                    </button>
                    <Link :href="route('admin.doctorants.export')" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 4v12m0 0l-4-4m4 4l4-4" />
                        </svg>
                        Exporter
                    </Link>
                    <Link :href="route('admin.doctorants.create')" class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-ed-secondary">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                        </svg>
                        Nouveau doctorant
                    </Link>
                </template>
            </PageHeader>

            <!-- Onglets Actifs / Archives -->
            <div class="flex flex-wrap items-center gap-2">
                <button type="button" class="inline-flex items-center gap-2 rounded-xl border px-4 py-2 text-sm font-semibold transition" :class="!isArchives ? 'border-ed-primary bg-ed-primary/5 text-ed-teal-dark' : 'border-gray-200 text-gray-600 hover:bg-gray-50'" @click="setArchives(false)">
                    Doctorants actifs
                    <span class="rounded-full bg-white px-2 text-xs tabular-nums text-gray-600">{{ stats?.total ?? 0 }}</span>
                </button>
                <button type="button" class="inline-flex items-center gap-2 rounded-xl border px-4 py-2 text-sm font-semibold transition" :class="isArchives ? 'border-ed-primary bg-ed-primary/5 text-ed-teal-dark' : 'border-gray-200 text-gray-600 hover:bg-gray-50'" @click="setArchives(true)">
                    Archives
                    <span class="rounded-full bg-white px-2 text-xs tabular-nums text-gray-600">{{ stats?.archives ?? 0 }}</span>
                </button>
            </div>

            <div v-if="showImport" class="rounded-xl border border-gray-200 bg-white p-5">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700">Importer des doctorants</h3>
                        <p class="mt-1 text-xs text-gray-500">Formats acceptés : .xlsx, .xls.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <label class="group flex cursor-pointer items-center gap-3 rounded-xl border border-dashed border-gray-300 bg-gray-50 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:border-ed-primary hover:bg-white">
                            <input type="file" accept=".xlsx,.xls" class="hidden" @change="(e) => (importForm.import_file = e.target.files?.[0] || null)" />
                            <span>{{ importForm.import_file?.name || 'Choisir un fichier' }}</span>
                        </label>
                        <button type="button" class="rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary" :disabled="importForm.processing" @click="submitImport">
                            Lancer l'import
                        </button>
                    </div>
                </div>
                <p v-if="importForm.errors.import_file" class="mt-3 text-xs text-red-600">{{ importForm.errors.import_file }}</p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white">
                <!-- Filtres -->
                <div class="flex flex-col gap-3 border-b border-gray-100 p-4 lg:flex-row lg:items-center">
                    <div class="relative flex-1">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                        </svg>
                        <input v-model="search" type="text" placeholder="Nom, matricule, email ou sujet de thèse…" class="w-full rounded-xl border-gray-200 pl-10 text-sm focus:border-ed-primary focus:ring-ed-primary" @keyup.enter="updateFilters()" />
                    </div>
                    <select v-model="niveau" class="w-full rounded-xl border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary lg:w-32" @change="updateFilters()">
                        <option value="">Tous niveaux</option>
                        <option v-for="n in niveaux" :key="n" :value="n">{{ n }}</option>
                    </select>
                    <select v-model="statut" class="w-full rounded-xl border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary lg:w-40" @change="updateFilters()">
                        <option value="">Tous statuts</option>
                        <option v-for="s in statuts" :key="s" :value="s">{{ statutOf(s).label }}</option>
                    </select>
                    <select v-model="ead" class="w-full rounded-xl border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary lg:w-48" @change="updateFilters()">
                        <option value="">Toutes équipes</option>
                        <option v-for="item in eads" :key="item.id" :value="item.id">{{ item.sigle || item.nom }}</option>
                    </select>
                    <div class="flex items-center gap-2">
                        <button type="button" class="rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary" @click="updateFilters()">Filtrer</button>
                        <button v-if="hasFilters" type="button" class="rounded-xl border border-gray-200 px-3 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50" @click="clearFilters">Réinitialiser</button>
                    </div>
                </div>

                <!-- Barre d'actions groupées -->
                <div v-if="selected.length" class="flex flex-wrap items-center gap-3 border-b border-gray-100 bg-ed-primary/5 px-4 py-3">
                    <span class="text-sm font-semibold text-ed-teal-dark">{{ selected.length }} sélectionné(s)</span>
                    <div class="ml-auto flex flex-wrap items-center gap-2">
                        <button v-if="!isArchives" type="button" class="rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50" @click="askBulk('archive')">Archiver</button>
                        <button v-else type="button" class="rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50" @click="askBulk('restore')">Restaurer</button>
                        <button type="button" class="rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50" @click="askBulk('notify')">Notifier</button>

                        <div class="relative">
                            <button type="button" class="inline-flex items-center gap-1 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50" @click="showDocsMenu = !showDocsMenu">
                                Documents
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div v-if="showDocsMenu">
                                <div class="fixed inset-0 z-10" @click="showDocsMenu = false"></div>
                                <div class="absolute right-0 z-20 mt-1 w-60 overflow-hidden rounded-xl border border-gray-200 bg-white py-1 shadow-lg">
                                    <button type="button" class="block w-full px-4 py-2 text-left text-xs font-medium text-gray-700 hover:bg-gray-50" @click="downloadDocuments('admission')">Admission en doctorat</button>
                                    <button type="button" class="block w-full px-4 py-2 text-left text-xs font-medium text-gray-700 hover:bg-gray-50" @click="downloadDocuments('attestation')">Attestation d'inscription</button>
                                    <button type="button" class="block w-full px-4 py-2 text-left text-xs font-medium text-gray-700 hover:bg-gray-50" @click="downloadDocuments('evaluation')">Fiche d'évaluation</button>
                                    <button type="button" class="block w-full border-t border-gray-100 px-4 py-2 text-left text-xs font-semibold text-gray-800 hover:bg-gray-50" @click="downloadDocuments('rapport')">Rapport du doctorant</button>
                                </div>
                            </div>
                        </div>

                        <button v-if="can('records.delete')" type="button" class="rounded-lg border border-red-200 bg-white px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50" @click="askBulk('delete')">Supprimer</button>
                    </div>
                </div>

                <!-- Tableau -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                <th class="w-10 px-4 py-3">
                                    <input type="checkbox" class="rounded border-gray-300 text-ed-primary focus:ring-ed-primary" :checked="allSelected" @change="toggleAll" />
                                </th>
                                <th class="px-4 py-3">Doctorant</th>
                                <th class="px-4 py-3">Niveau</th>
                                <th class="px-4 py-3">Statut</th>
                                <th class="px-4 py-3">Équipe</th>
                                <th class="px-4 py-3 text-center">Obs.</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <tr v-for="doctorant in doctorants.data" :key="doctorant.id" class="transition hover:bg-gray-50" :class="selected.includes(doctorant.id) && 'bg-ed-primary/5'">
                                <td class="px-4 py-4">
                                    <input type="checkbox" v-model="selected" :value="doctorant.id" class="rounded border-gray-300 text-ed-primary focus:ring-ed-primary" />
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-ed-primary/10 text-xs font-semibold text-ed-teal-dark">{{ initials(doctorant.name) }}</span>
                                        <div class="min-w-0">
                                            <p class="truncate font-semibold text-gray-900">{{ doctorant.name }}</p>
                                            <p class="text-xs text-gray-500">{{ doctorant.matricule }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center rounded-full bg-ed-primary/10 px-2.5 py-0.5 text-xs font-semibold text-ed-teal-dark">{{ doctorant.niveau }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold" :class="statutOf(doctorant.statut).class">{{ statutOf(doctorant.statut).label }}</span>
                                </td>
                                <td class="px-4 py-4 text-gray-600">{{ doctorant.ead?.sigle || doctorant.ead?.nom || '—' }}</td>
                                <td class="px-4 py-4 text-center">
                                    <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border transition" :class="doctorant.observation ? 'border-amber-200 bg-amber-50 text-amber-600 hover:bg-amber-100' : 'border-gray-200 text-gray-400 hover:bg-gray-50'" :title="doctorant.observation || 'Ajouter une observation'" @click="openObservation(doctorant)">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex justify-end gap-1">
                                        <Link :href="route('admin.doctorants.show', doctorant.id)" class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-gray-200 text-gray-600 transition hover:bg-gray-50" title="Voir">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </Link>
                                        <Link :href="route('admin.doctorants.edit', doctorant.id)" class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-gray-200 text-gray-600 transition hover:border-ed-primary hover:bg-ed-primary/5 hover:text-ed-primary" title="Modifier">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.232-6.232a2.5 2.5 0 113.536 3.536L12.536 16.536A4 4 0 0110 17H7v-3a4 4 0 011-2.536z" />
                                            </svg>
                                        </Link>
                                        <button v-if="can('records.delete')" type="button" class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-gray-200 text-gray-600 transition hover:border-red-300 hover:bg-red-50 hover:text-red-600" title="Supprimer" @click="deleteDoctorant(doctorant)">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 011-1h4a1 1 0 011 1m-7 0h8" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!doctorants.data.length">
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <p class="text-sm font-medium text-gray-600">{{ isArchives ? 'Aucun doctorant archivé' : 'Aucun doctorant trouvé' }}</p>
                                    <p class="mt-1 text-xs text-gray-400">Ajustez votre recherche ou vos filtres.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="doctorants.links?.length" class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-4 py-3">
                    <p class="text-xs text-gray-500">{{ doctorants.from ?? 0 }}–{{ doctorants.to ?? 0 }} sur {{ doctorants.total ?? 0 }} doctorant(s)</p>
                    <Pagination :links="doctorants.links" />
                </div>
            </div>
        </div>

        <!-- Confirmation actions groupées -->
        <ConfirmDialog
            :show="confirmAction !== null"
            :title="currentConfirm?.title || ''"
            :message="currentConfirm?.message || ''"
            :confirm-label="currentConfirm?.confirmLabel || 'Confirmer'"
            :variant="currentConfirm?.variant || 'primary'"
            :processing="bulkForm.processing"
            @confirm="runBulk"
            @cancel="confirmAction = null"
        />

        <!-- Édition observation -->
        <ConfirmDialog
            :show="editingObservation !== null"
            title="Observation administrative"
            confirm-label="Enregistrer"
            :processing="observForm.processing"
            @confirm="saveObservation"
            @cancel="editingObservation = null"
        >
            <div class="mt-3">
                <p class="mb-2 text-xs text-gray-500">Note interne (comportement, dérogation, situation administrative ou financière…).</p>
                <textarea v-model="observForm.observation" rows="4" class="w-full rounded-xl border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" placeholder="Saisir une observation…"></textarea>
                <p v-if="observForm.errors.observation" class="mt-1 text-xs text-red-600">{{ observForm.errors.observation }}</p>
            </div>
        </ConfirmDialog>
    </AdminLayout>
</template>
