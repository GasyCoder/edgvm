<script setup>
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';
import Pagination from '@/Components/Common/Pagination.vue';

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
    searchTimeout = setTimeout(() => {
        updateFilters();
    }, 300);
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

const deleteSpecialite = (specialite) => {
    if (!confirm(`Supprimer la specialite "${specialite.nom}" ?`)) return;

    router.delete(route('admin.specialites.destroy', specialite.slug), { preserveScroll: true });
};

const openCreate = () => {
    editingSpecialite.value = null;
    form.reset();
    showModal.value = true;
};

const openEdit = (specialite) => {
    editingSpecialite.value = specialite;
    form.nom = specialite.nom;
    form.ead_id = specialite.ead?.id || '';
    form.description = specialite.description || '';
    showModal.value = true;
};

const submit = () => {
    if (editingSpecialite.value) {
        form.put(route('admin.specialites.update', editingSpecialite.value.slug), {
            preserveScroll: true,
            onSuccess: () => {
                showModal.value = false;
            },
        });
        return;
    }

    form.post(route('admin.specialites.store'), {
        preserveScroll: true,
        onSuccess: () => {
            showModal.value = false;
        },
    });
};

const hasFilters = computed(() => search.value || ead.value);
</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-slate-900">Specialites</h1>
        </template>

        <Head title="Specialites" />
        <FlashMessage />

        <div class="space-y-4">
            <PageHeader
                badge="Organisation"
                title="Specialites"
                description="Gerez les specialites associees aux EAD."
            >
                <template #actions>
                    <button
                        type="button"
                        class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary"
                        @click="openCreate"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                        </svg>
                        Nouvelle specialite
                    </button>
                </template>
            </PageHeader>

            <div class="grid gap-4 rounded-2xl border border-slate-200 bg-white p-4 lg:grid-cols-3">
                <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <p class="text-xs text-slate-500">Total specialites</p>
                    <p class="text-lg font-semibold text-slate-900">{{ stats?.total ?? 0 }}</p>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <p class="text-xs text-slate-500">Avec EAD</p>
                    <p class="text-lg font-semibold text-emerald-600">{{ stats?.avec_ead ?? 0 }}</p>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <p class="text-xs text-slate-500">Avec theses</p>
                    <p class="text-lg font-semibold text-indigo-600">{{ stats?.avec_theses ?? 0 }}</p>
                </div>
            </div>

            <div class="flex flex-wrap items-end gap-3 rounded-2xl border border-slate-200 bg-white p-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="text-xs font-semibold text-slate-600">Recherche</label>
                    <input v-model="search" type="text" placeholder="Nom, slug, description..." class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
                </div>
                <div class="min-w-[200px]">
                    <label class="text-xs font-semibold text-slate-600">EAD</label>
                    <select v-model="ead" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        <option value="">Tous</option>
                        <option v-for="item in eads" :key="item.id" :value="item.id">{{ item.nom }}</option>
                    </select>
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
                                <th class="px-5 py-3">EAD</th>
                                <th class="px-5 py-3">Theses</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <tr v-for="specialite in specialites.data" :key="specialite.id" class="hover:bg-slate-50">
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-900">{{ specialite.nom }}</p>
                                    <p class="text-xs text-slate-500">/{{ specialite.slug }}</p>
                                </td>
                                <td class="px-5 py-4 text-slate-600">{{ specialite.ead?.nom || '-' }}</td>
                                <td class="px-5 py-4 text-slate-600">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span>{{ specialite.theses_count }} theses</span>
                                        <span v-if="specialite.theses_count === 0" class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-2 py-0.5 text-xs font-semibold text-amber-700">
                                            Sans these
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button type="button" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-100" @click="openEdit(specialite)">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.232-6.232a2.5 2.5 0 113.536 3.536L12.536 16.536A4 4 0 0110 17H7v-3a4 4 0 011-2.536z" />
                                            </svg>
                                            Modifier
                                        </button>
                                        <button type="button" class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50" @click="deleteSpecialite(specialite)">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 011-1h4a1 1 0 011 1m-7 0h8" />
                                            </svg>
                                            Supprimer
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!specialites.data.length">
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-500">Aucune specialite trouvee.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="specialites.links?.length" class="border-t border-slate-100 px-4 py-3">
                    <Pagination :links="specialites.links" />
                </div>
            </div>
        </div>

        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-slate-900/40" @click="showModal = false"></div>
            <div class="relative w-full max-w-md rounded-2xl bg-white p-6">
                <h3 class="text-lg font-semibold text-slate-900">{{ editingSpecialite ? 'Modifier la specialite' : 'Nouvelle specialite' }}</h3>
                <form class="mt-4 space-y-4" @submit.prevent="submit">
                    <div>
                        <label class="text-xs font-semibold text-slate-700">Nom *</label>
                        <input
                            v-model="form.nom"
                            type="text"
                            class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                        />
                        <p v-if="form.errors.nom" class="mt-1 text-xs text-red-600">{{ form.errors.nom }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-700">EAD *</label>
                        <select v-model="form.ead_id" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20">
                            <option value="">Selectionner un EAD</option>
                            <option v-for="item in eads" :key="item.id" :value="item.id">{{ item.nom }}</option>
                        </select>
                        <p v-if="form.errors.ead_id" class="mt-1 text-xs text-red-600">{{ form.errors.ead_id }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-700">Description</label>
                        <textarea
                            v-model="form.description"
                            rows="3"
                            class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                        ></textarea>
                        <p v-if="form.errors.description" class="mt-1 text-xs text-red-600">{{ form.errors.description }}</p>
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <button type="button" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50" @click="showModal = false">
                            Annuler
                        </button>
                        <button type="submit" class="rounded-xl bg-ed-primary px-4 py-2 text-sm font-semibold text-white hover:bg-ed-secondary" :disabled="form.processing">
                            {{ editingSpecialite ? 'Mettre a jour' : 'Creer' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
