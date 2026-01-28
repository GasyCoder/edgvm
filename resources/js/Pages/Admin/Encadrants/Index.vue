<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';
import Pagination from '@/Components/Common/Pagination.vue';

const props = defineProps({
    filters: Object,
    grades: Array,
    encadrants: Object,
    stats: Object,
});

const search = ref(props.filters?.search || '');
const grade = ref(props.filters?.grade || '');

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

const deleteEncadrant = (encadrant) => {
    if (!confirm(`Supprimer l'encadrant "${encadrant.name}" ?`)) return;

    router.delete(route('admin.encadrants.destroy', encadrant.id), { preserveScroll: true });
};

const thesesBadgeClass = (count) => {
    return count > 0
        ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
        : 'bg-amber-50 text-amber-700 border-amber-200';
};

const submitImport = () => {
    importForm.post(route('admin.encadrants.import'), {
        forceFormData: true,
    });
};

const hasFilters = computed(() => search.value || grade.value);
</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-slate-900">Encadrants</h1>
        </template>

        <Head title="Encadrants" />
        <FlashMessage />

        <div class="space-y-4">
            <PageHeader
                badge="Recherche"
                title="Encadrants"
                description="Gerez les encadrants et leurs theses."
            >
                <template #actions>
                    <Link :href="route('admin.encadrants.export')" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16V4m0 12l-3-3m3 3l3-3M4 20h16" />
                        </svg>
                        Exporter
                    </Link>
                    <Link :href="route('admin.encadrants.create')" class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                        </svg>
                        Nouvel encadrant
                    </Link>
                </template>
            </PageHeader>

            <div class="grid gap-4 rounded-2xl border border-slate-200 bg-white p-4 lg:grid-cols-2">
                <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <p class="text-xs text-slate-500">Total encadrants</p>
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
                    <input v-model="search" type="text" placeholder="Nom, email, grade, specialite" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
                </div>
                <div class="min-w-[180px]">
                    <label class="text-xs font-semibold text-slate-600">Grade</label>
                    <select v-model="grade" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        <option value="">Tous</option>
                        <option v-for="item in grades" :key="item" :value="item">{{ item }}</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white" @click="updateFilters">Filtrer</button>
                    <button v-if="hasFilters" type="button" class="rounded-xl border border-slate-200 px-3 py-2 text-sm" @click="clearFilters">Reinitialiser</button>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                <p class="text-xs font-semibold text-slate-600">Importer des encadrants</p>
                <div class="mt-3 grid gap-3 lg:grid-cols-[1fr_auto] lg:items-center">
                    <div
                        class="relative rounded-xl border-2 border-dashed transition-all duration-200"
                        :class="importDragging ? 'border-ed-primary bg-ed-primary/5' : 'border-slate-200 hover:border-slate-300'"
                        @dragover.prevent="importDragging = true"
                        @dragleave.prevent="importDragging = false"
                        @drop="onImportDrop"
                    >
                        <div class="px-4 py-3">
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
                            <div v-else class="flex items-center gap-3">
                                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-600">Cliquez pour choisir</p>
                                    <p class="text-xs text-slate-400">ou glissez-deposez · CSV/XLSX</p>
                                </div>
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
                                <th class="px-5 py-3">Encadrant</th>
                                <th class="px-5 py-3">Grade</th>
                                <th class="px-5 py-3">Specialite</th>
                                <th class="px-5 py-3">Theses</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <tr v-for="encadrant in encadrants.data" :key="encadrant.id" class="hover:bg-slate-50">
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-900">{{ encadrant.name }}</p>
                                    <p class="text-xs text-slate-500">{{ encadrant.email || '-' }}</p>
                                </td>
                                <td class="px-5 py-4 text-slate-600">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span>{{ encadrant.grade || '-' }}</span>
                                        <span v-if="!encadrant.grade" class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-2 py-0.5 text-xs font-semibold text-slate-600">
                                            Grade manquant
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-slate-600">{{ encadrant.specialite || '-' }}</td>
                                <td class="px-5 py-4 text-slate-600">
                                    <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-semibold" :class="thesesBadgeClass(encadrant.theses_count)">
                                        {{ encadrant.theses_count }} theses
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <Link :href="route('admin.encadrants.show', encadrant.id)" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-100">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Voir
                                        </Link>
                                        <Link :href="route('admin.encadrants.edit', encadrant.id)" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-100">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.232-6.232a2.5 2.5 0 113.536 3.536L12.536 16.536A4 4 0 0110 17H7v-3a4 4 0 011-2.536z" />
                                            </svg>
                                            Modifier
                                        </Link>
                                        <button type="button" class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50" @click="deleteEncadrant(encadrant)">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 011-1h4a1 1 0 011 1m-7 0h8" />
                                            </svg>
                                            Supprimer
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!encadrants.data.length">
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">Aucun encadrant trouve.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="encadrants.links?.length" class="border-t border-slate-100 px-4 py-3">
                    <Pagination :links="encadrants.links" />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
