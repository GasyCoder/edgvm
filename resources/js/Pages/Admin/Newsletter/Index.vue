<script setup>
import { computed, ref, watch } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';
import Pagination from '@/Components/Common/Pagination.vue';

const props = defineProps({
    subscribers: Object,
    stats: Object,
    filters: Object,
});

const search = ref(props.filters?.search ?? '');
const filterType = ref(props.filters?.type ?? 'all');
const filterActif = ref(props.filters?.actif ?? 'all');

const showModal = ref(false);
const editingId = ref(null);

const form = useForm({
    email: '',
    nom: '',
    type: 'autre',
    actif: true,
});

const importForm = useForm({
    importFile: null,
});

const bulkForm = useForm({
    emails: '',
    type: 'autre',
    actif: true,
});

const importInput = ref(null);
const importDragging = ref(false);

const applyFilters = () => {
    router.get(route('admin.newsletter.subscribers'), {
        search: search.value || undefined,
        type: filterType.value !== 'all' ? filterType.value : undefined,
        actif: filterActif.value !== 'all' ? filterActif.value : undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

let searchTimeout = null;
watch(search, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 300);
});

watch(filterType, () => applyFilters());
watch(filterActif, () => applyFilters());

const openCreate = () => {
    editingId.value = null;
    form.reset();
    form.type = 'autre';
    form.actif = true;
    showModal.value = true;
};

const openEdit = (subscriber) => {
    editingId.value = subscriber.id;
    form.email = subscriber.email;
    form.nom = subscriber.nom ?? '';
    form.type = subscriber.type;
    form.actif = subscriber.actif;
    showModal.value = true;
};

const saveSubscriber = () => {
    if (editingId.value) {
        form.put(route('admin.newsletter.subscribers.update', editingId.value), {
            preserveScroll: true,
            onSuccess: () => {
                showModal.value = false;
            },
        });
        return;
    }

    form.post(route('admin.newsletter.subscribers.store'), {
        preserveScroll: true,
        onSuccess: () => {
            showModal.value = false;
        },
    });
};

const toggleActif = (subscriber) => {
    router.patch(route('admin.newsletter.subscribers.toggle', subscriber.id), {}, { preserveScroll: true });
};

const deleteSubscriber = (subscriber) => {
    router.delete(route('admin.newsletter.subscribers.destroy', subscriber.id), { preserveScroll: true });
};

const exportUrl = computed(() => {
    return route('admin.newsletter.subscribers.export', {
        search: search.value || undefined,
        type: filterType.value !== 'all' ? filterType.value : undefined,
        actif: filterActif.value !== 'all' ? filterActif.value : undefined,
    });
});

const setImportFile = (event) => {
    importForm.importFile = event.target.files?.[0] ?? null;
};

const handleImportFile = (file) => {
    if (!file) {
        return;
    }
    importForm.importFile = file;
};

const onImportDrop = (event) => {
    event.preventDefault();
    importDragging.value = false;
    handleImportFile(event.dataTransfer?.files?.[0] ?? null);
};

const clearImportFile = () => {
    importForm.importFile = null;
    if (importInput.value) {
        importInput.value.value = '';
    }
};

const submitImport = () => {
    importForm.post(route('admin.newsletter.subscribers.import'), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            importForm.reset();
        },
    });
};

const submitBulk = () => {
    bulkForm.post(route('admin.newsletter.subscribers.bulk'), {
        preserveScroll: true,
        onSuccess: () => {
            bulkForm.reset();
            bulkForm.type = 'autre';
            bulkForm.actif = true;
        },
    });
};
</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-slate-900">Newsletter</h1>
        </template>

        <Head title="Newsletter" />
        <FlashMessage />

        <div class="space-y-6">
            <PageHeader
                badge="Communication"
                title="Newsletter"
                description="Gestion des abonnes."
            >
                <template #actions>
                    <Link :href="exportUrl" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16V4m0 12l-3-3m3 3l3-3M4 20h16" />
                        </svg>
                        Exporter
                    </Link>
                    <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary" @click="openCreate">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                        </svg>
                        Ajouter
                    </button>
                </template>
            </PageHeader>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">
                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Total</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{ stats?.total ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-emerald-600">Actifs</p>
                    <p class="mt-2 text-2xl font-semibold text-emerald-700">{{ stats?.actifs ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Inactifs</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{ stats?.inactifs ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-sky-100 bg-sky-50 p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-sky-600">Doctorants</p>
                    <p class="mt-2 text-2xl font-semibold text-sky-700">{{ stats?.doctorants ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-indigo-100 bg-indigo-50 p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-indigo-600">Encadrants</p>
                    <p class="mt-2 text-2xl font-semibold text-indigo-700">{{ stats?.encadrants ?? 0 }}</p>
                </div>
            </div>

            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="relative w-full max-w-md">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
                        </svg>
                    </span>
                    <input v-model="search" type="text" placeholder="Rechercher un email..." class="w-full rounded-xl border border-slate-200 py-2.5 pl-9 pr-3 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <select v-model="filterType" class="rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20">
                        <option value="all">Tous les types</option>
                        <option value="doctorant">Doctorant</option>
                        <option value="encadrant">Encadrant</option>
                        <option value="autre">Autre</option>
                    </select>
                    <select v-model="filterActif" class="rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20">
                        <option value="all">Tous les statuts</option>
                        <option value="actif">Actif</option>
                        <option value="inactif">Inactif</option>
                    </select>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <div class="overflow-x-auto -mx-px">
                    <div class="inline-block min-w-full align-middle">
                        <table class="min-w-full text-sm">
                    <thead class="border-b border-slate-100 text-left text-xs uppercase tracking-[0.16em] text-slate-400">
                        <tr>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Statut</th>
                            <th class="px-4 py-3">Inscription</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="subscriber in subscribers.data" :key="subscriber.id" class="hover:bg-slate-50/60">
                            <td class="px-4 py-4">
                                <p class="text-sm font-semibold text-slate-900">{{ subscriber.email }}</p>
                                <p v-if="subscriber.nom" class="mt-1 text-xs text-slate-500">{{ subscriber.nom }}</p>
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-600">{{ subscriber.type }}</td>
                            <td class="px-4 py-4">
                                <span
                                    class="rounded-full border px-2.5 py-1 text-xs"
                                    :class="subscriber.actif ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-white text-slate-500'"
                                >
                                    {{ subscriber.actif ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-500">{{ subscriber.created_at }}</td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex flex-wrap justify-end gap-2">
                                    <button
                                        type="button"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:border-ed-primary hover:bg-ed-primary/5 hover:text-ed-primary"
                                        :title="subscriber.actif ? 'Desactiver' : 'Activer'"
                                        @click="toggleActif(subscriber)"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v8m0 0l-3-3m3 3l3-3M4 12v6a2 2 0 002 2h12a2 2 0 002-2v-6" />
                                        </svg>
                                    </button>
                                    <button
                                        type="button"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:border-ed-primary hover:bg-ed-primary/5 hover:text-ed-primary"
                                        title="Modifier"
                                        @click="openEdit(subscriber)"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.232-6.232a2.5 2.5 0 113.536 3.536L12.536 16.536A4 4 0 0110 17H7v-3a4 4 0 011-2.536z" />
                                        </svg>
                                    </button>
                                    <button
                                        type="button"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-red-200 text-red-600 transition hover:bg-red-50"
                                        title="Supprimer"
                                        @click="deleteSubscriber(subscriber)"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 011-1h4a1 1 0 011 1m-7 0h8" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                        </table>
                    </div>
                </div>

                <div v-if="!subscribers.data.length" class="px-6 py-10 text-center text-sm text-slate-500">
                    Aucun abonne trouve.
                </div>
            </div>

            <Pagination v-if="subscribers.links" :links="subscribers.links" />

            <div class="rounded-2xl border border-slate-100 bg-white p-6">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">Ajouter des emails en masse</h3>
                        <p class="mt-1 text-xs text-slate-500">Un email par ligne, separateur virgule ou point-virgule.</p>
                    </div>
                    <button
                        type="button"
                        class="rounded-xl bg-ed-primary px-4 py-2 text-xs font-semibold text-white hover:bg-ed-secondary disabled:cursor-not-allowed disabled:opacity-60"
                        :disabled="bulkForm.processing || !bulkForm.emails"
                        @click="submitBulk"
                    >
                        Ajouter
                    </button>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-4 lg:grid-cols-3">
                    <div class="lg:col-span-2">
                        <textarea
                            v-model="bulkForm.emails"
                            rows="5"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                            placeholder="email1@exemple.com&#10;email2@exemple.com"
                        ></textarea>
                        <p v-if="bulkForm.errors.emails" class="mt-2 text-xs text-red-600">{{ bulkForm.errors.emails }}</p>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Type</label>
                            <select v-model="bulkForm.type" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20">
                                <option value="doctorant">Doctorant</option>
                                <option value="encadrant">Encadrant</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <label class="flex items-start gap-3 text-sm">
                            <input v-model="bulkForm.actif" type="checkbox" class="mt-0.5 rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20" />
                            <span>
                                <span class="font-semibold text-slate-800">Actif</span>
                                <span class="block text-xs text-slate-500">Abonnes actifs.</span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-6">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">Importer des abonnes</h3>
                        <p class="mt-1 text-xs text-slate-500">Formats acceptes: CSV, XLS, XLSX.</p>
                    </div>
                    <button
                        type="button"
                        class="rounded-xl bg-ed-primary px-4 py-2 text-xs font-semibold text-white hover:bg-ed-secondary disabled:cursor-not-allowed disabled:opacity-60"
                        :disabled="importForm.processing || !importForm.importFile"
                        @click="submitImport"
                    >
                        Importer
                    </button>
                </div>

                <div
                    class="mt-4 relative rounded-2xl border-2 border-dashed transition-all duration-200"
                    :class="importDragging ? 'border-ed-primary bg-ed-primary/5' : 'border-slate-200 hover:border-slate-300'"
                    @dragover.prevent="importDragging = true"
                    @dragleave.prevent="importDragging = false"
                    @drop="onImportDrop"
                >
                    <div class="p-5">
                        <div v-if="importForm.importFile" class="flex flex-wrap items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-slate-700 truncate">{{ importForm.importFile.name }}</p>
                                <p class="text-xs text-slate-500">Cliquez ou deposez pour remplacer.</p>
                            </div>
                            <button
                                type="button"
                                class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 transition hover:bg-red-50 hover:text-red-500"
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
                            <p class="mt-2 text-sm font-medium text-slate-600">Deposez un fichier ou cliquez pour choisir</p>
                            <p class="text-xs text-slate-400">CSV, XLS, XLSX</p>
                        </div>
                    </div>
                    <input
                        ref="importInput"
                        type="file"
                        accept=".xlsx,.xls,.csv"
                        class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                        @change="(e) => handleImportFile(e.target.files?.[0] ?? null)"
                    />
                </div>

                <p v-if="importForm.errors.importFile" class="mt-2 text-xs text-red-600">{{ importForm.errors.importFile }}</p>
            </div>
        </div>

        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-slate-900/40" @click="showModal = false"></div>
            <div class="relative w-full max-w-lg rounded-2xl bg-white p-6">
                <h3 class="text-lg font-semibold text-slate-900">{{ editingId ? 'Modifier' : 'Nouvel abonne' }}</h3>
                <div class="mt-4 space-y-4">
                    <div>
                        <label class="text-xs font-semibold text-slate-700">Email</label>
                        <input v-model="form.email" type="email" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                        <p v-if="form.errors.email" class="mt-2 text-xs text-red-600">{{ form.errors.email }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-700">Nom</label>
                        <input v-model="form.nom" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-700">Type</label>
                        <select v-model="form.type" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20">
                            <option value="doctorant">Doctorant</option>
                            <option value="encadrant">Encadrant</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    <label class="flex items-start gap-3 text-sm">
                        <input v-model="form.actif" type="checkbox" class="mt-0.5 rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20" />
                        <span>
                            <span class="font-semibold text-slate-800">Actif</span>
                            <span class="block text-xs text-slate-500">Abonne actif.</span>
                        </span>
                    </label>
                </div>
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700" @click="showModal = false">Annuler</button>
                    <button type="button" class="rounded-xl bg-ed-primary px-4 py-2 text-sm font-semibold text-white hover:bg-ed-secondary" :disabled="form.processing" @click="saveSubscriber">Enregistrer</button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
