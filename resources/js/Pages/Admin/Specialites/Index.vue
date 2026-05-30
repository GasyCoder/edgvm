<script setup>
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import Card from '@/Components/Admin/Card.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';
import Pagination from '@/Components/Common/Pagination.vue';
import ConfirmDialog from '@/Components/Common/ConfirmDialog.vue';

const props = defineProps({
    filters: Object,
    eads: Array,
    specialites: Object,
    stats: Object,
});

const search = ref(props.filters?.search || '');
const ead = ref(props.filters?.ead || '');
const showModal = ref(false);
const editingSpecialite = ref(null);

const form = useForm({
    nom: '',
    ead_id: '',
    description: '',
});

let searchTimeout = null;
watch(search, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => updateFilters(), 300);
});

const updateFilters = () => {
    router.get(route('admin.specialites.index'), {
        search: search.value,
        ead: ead.value,
    }, { preserveState: true, preserveScroll: true });
};

const clearFilters = () => {
    search.value = '';
    ead.value = '';
    updateFilters();
};

const openCreate = () => {
    editingSpecialite.value = null;
    form.reset();
    form.clearErrors();
    showModal.value = true;
};

const openEdit = (specialite) => {
    editingSpecialite.value = specialite;
    form.nom = specialite.nom;
    form.ead_id = specialite.ead?.id || '';
    form.description = specialite.description || '';
    form.clearErrors();
    showModal.value = true;
};

const submit = () => {
    const options = {
        preserveScroll: true,
        onSuccess: () => {
            showModal.value = false;
        },
    };

    if (editingSpecialite.value) {
        form.put(route('admin.specialites.update', editingSpecialite.value.slug), options);
        return;
    }

    form.post(route('admin.specialites.store'), options);
};

const specialiteToDelete = ref(null);
const deleting = ref(false);

const deleteMessage = computed(() =>
    specialiteToDelete.value ? `La spécialité « ${specialiteToDelete.value.nom} » sera définitivement supprimée.` : '',
);

const confirmDelete = () => {
    if (!specialiteToDelete.value) return;

    router.delete(route('admin.specialites.destroy', specialiteToDelete.value.slug), {
        preserveScroll: true,
        onStart: () => { deleting.value = true; },
        onFinish: () => {
            deleting.value = false;
            specialiteToDelete.value = null;
        },
    });
};

const hasFilters = computed(() => search.value || ead.value);
</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-bold text-slate-700">Spécialités</h1>
        </template>

        <Head title="Spécialités" />
        <FlashMessage />

        <div class="space-y-6">
            <PageHeader
                badge="Gestion académique"
                title="Spécialités"
                description="Programmes de recherche rattachés aux équipes d'accueil."
            >
                <template #actions>
                    <button type="button" class="inline-flex items-center gap-2 rounded-md bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-ed-secondary" @click="openCreate">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                        </svg>
                        Nouvelle spécialité
                    </button>
                </template>
            </PageHeader>

            <!-- KPI -->
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="flex items-center gap-4 rounded-md border border-gray-200 bg-white p-5">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-ed-primary/10 text-ed-teal-dark">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    </span>
                    <div>
                        <p class="text-xs font-medium text-slate-400">Total spécialités</p>
                        <p class="text-xl font-bold tabular-nums text-slate-700">{{ stats?.total ?? 0 }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 rounded-md border border-gray-200 bg-white p-5">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-primary-100 text-primary-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6l7 4-7 4-7-4 7-4zm0 8l7 4-7 4-7-4 7-4z" /></svg>
                    </span>
                    <div>
                        <p class="text-xs font-medium text-slate-400">Rattachées à une EAD</p>
                        <p class="text-xl font-bold tabular-nums text-slate-700">{{ stats?.avec_ead ?? 0 }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 rounded-md border border-gray-200 bg-white p-5">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-cyan-100 text-cyan-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </span>
                    <div>
                        <p class="text-xs font-medium text-slate-400">Avec thèses</p>
                        <p class="text-xl font-bold tabular-nums text-slate-700">{{ stats?.avec_theses ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Tableau + filtres -->
            <Card no-padding>
                <div class="flex flex-col gap-3 border-b border-gray-100 p-4 lg:flex-row lg:items-center">
                    <div class="relative flex-1">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                        </svg>
                        <input v-model="search" type="text" placeholder="Rechercher par nom ou description…" class="w-full rounded-md border-gray-200 pl-10 text-sm focus:border-ed-primary focus:ring-ed-primary" />
                    </div>
                    <select v-model="ead" class="w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary lg:w-56" @change="updateFilters">
                        <option value="">Toutes les équipes</option>
                        <option v-for="item in eads" :key="item.id" :value="item.id">{{ item.sigle || item.nom }}</option>
                    </select>
                    <button v-if="hasFilters" type="button" class="rounded-md border border-gray-200 px-3 py-2.5 text-sm font-semibold text-slate-600 hover:bg-gray-50" @click="clearFilters">Réinitialiser</button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                <th class="px-5 py-3">Spécialité / Programme</th>
                                <th class="px-5 py-3">Équipe d'accueil</th>
                                <th class="px-5 py-3 text-center">Thèses</th>
                                <th class="px-5 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <tr v-for="specialite in specialites.data" :key="specialite.id" class="transition hover:bg-gray-50">
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-700">{{ specialite.nom }}</p>
                                    <p v-if="specialite.description" class="mt-0.5 max-w-md truncate text-xs text-slate-400">{{ specialite.description }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <span v-if="specialite.ead" class="inline-flex items-center rounded-full bg-ed-primary/10 px-2.5 py-0.5 text-xs font-semibold text-ed-teal-dark" :title="specialite.ead.nom">
                                        {{ specialite.ead.sigle || specialite.ead.nom }}
                                    </span>
                                    <span v-else class="text-slate-400">—</span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex h-7 min-w-[1.75rem] items-center justify-center rounded-md bg-gray-100 px-2 text-xs font-semibold text-slate-600">{{ specialite.theses_count }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-1.5">
                                        <button type="button" class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-md border border-gray-200 text-slate-500 transition hover:border-ed-primary hover:bg-ed-primary/5 hover:text-ed-primary" title="Modifier" @click="openEdit(specialite)">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.232-6.232a2.5 2.5 0 113.536 3.536L12.536 16.536A4 4 0 0110 17H7v-3a4 4 0 011-2.536z" />
                                            </svg>
                                        </button>
                                        <button type="button" class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-md border border-gray-200 text-slate-500 transition hover:border-red-300 hover:bg-red-50 hover:text-red-600" title="Supprimer" @click="specialiteToDelete = specialite">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 011-1h4a1 1 0 011 1m-7 0h8" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!specialites.data.length">
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <p class="text-sm font-medium text-slate-600">Aucune spécialité trouvée</p>
                                    <p class="mt-1 text-xs text-slate-400">Ajustez la recherche ou créez une nouvelle spécialité.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="specialites.links?.length" class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-4 py-3">
                    <p class="text-xs text-slate-400">{{ specialites.from ?? 0 }}–{{ specialites.to ?? 0 }} sur {{ specialites.total ?? 0 }} spécialité(s)</p>
                    <Pagination :links="specialites.links" />
                </div>
            </Card>
        </div>

        <!-- Modal création / édition -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition duration-150 ease-out" enter-from-class="opacity-0" enter-to-class="opacity-100"
                leave-active-class="transition duration-100 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0"
            >
                <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="!form.processing && (showModal = false)"></div>
                    <div class="relative w-full max-w-md rounded-md border border-gray-200 bg-white p-6 shadow-xl">
                        <h3 class="text-base font-bold text-slate-700">{{ editingSpecialite ? 'Modifier la spécialité' : 'Nouvelle spécialité' }}</h3>
                        <p class="mt-0.5 text-xs text-slate-400">Une spécialité correspond à un programme de recherche d'une équipe d'accueil.</p>

                        <form class="mt-5 space-y-4" @submit.prevent="submit">
                            <div>
                                <label class="text-xs font-medium text-slate-600">Nom <span class="text-red-500">*</span></label>
                                <input v-model="form.nom" type="text" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" :class="form.errors.nom && 'border-red-300'" />
                                <p v-if="form.errors.nom" class="mt-1 text-xs text-red-600">{{ form.errors.nom }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-slate-600">Équipe d'accueil <span class="text-red-500">*</span></label>
                                <select v-model="form.ead_id" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" :class="form.errors.ead_id && 'border-red-300'">
                                    <option value="">Sélectionner une équipe</option>
                                    <option v-for="item in eads" :key="item.id" :value="item.id">{{ item.sigle ? `${item.sigle} — ${item.nom}` : item.nom }}</option>
                                </select>
                                <p v-if="form.errors.ead_id" class="mt-1 text-xs text-red-600">{{ form.errors.ead_id }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-slate-600">Description</label>
                                <textarea v-model="form.description" rows="3" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary"></textarea>
                                <p v-if="form.errors.description" class="mt-1 text-xs text-red-600">{{ form.errors.description }}</p>
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button type="button" class="rounded-md border border-gray-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-gray-50" @click="showModal = false">Annuler</button>
                                <button type="submit" class="rounded-md bg-ed-primary px-4 py-2 text-sm font-semibold text-white hover:bg-ed-secondary disabled:opacity-60" :disabled="form.processing">
                                    {{ form.processing ? 'Enregistrement…' : (editingSpecialite ? 'Mettre à jour' : 'Créer') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Transition>
        </Teleport>

        <ConfirmDialog
            :show="specialiteToDelete !== null"
            title="Supprimer la spécialité"
            :message="deleteMessage"
            confirm-label="Supprimer"
            variant="danger"
            :processing="deleting"
            @confirm="confirmDelete"
            @cancel="specialiteToDelete = null"
        />
    </AdminLayout>
</template>
