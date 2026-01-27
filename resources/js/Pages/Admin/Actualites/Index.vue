<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';
import Pagination from '@/Components/Common/Pagination.vue';

const props = defineProps({
    filters: Object,
    stats: Object,
    actualites: Object,
});

const search = ref(props.filters?.search ?? '');
const filterVisible = ref(props.filters?.visible ?? 'all');
const showDeleteModal = ref(false);
const actualiteToDelete = ref(null);

let searchTimeout = null;
watch(search, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 300);
});

watch(filterVisible, () => applyFilters());

const applyFilters = () => {
    router.get(route('admin.actualites.index'), {
        search: search.value || undefined,
        visible: filterVisible.value !== 'all' ? filterVisible.value : undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

const toggleVisibility = (actualite) => {
    router.patch(route('admin.actualites.toggle', actualite.slug), {}, { preserveScroll: true });
};

const confirmDelete = (actualite) => {
    actualiteToDelete.value = actualite;
    showDeleteModal.value = true;
};

const deleteActualite = () => {
    if (!actualiteToDelete.value) return;
    router.delete(route('admin.actualites.destroy', actualiteToDelete.value.slug), {
        preserveScroll: true,
        onFinish: () => {
            showDeleteModal.value = false;
            actualiteToDelete.value = null;
        },
    });
};

</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-slate-900">Actualites</h1>
        </template>

        <Head title="Actualites" />
        <FlashMessage />

        <div class="space-y-6">
            <PageHeader
                badge="Gestion"
                title="Actualites"
                description="Gestion de la visibilite, contenu et publication."
            >
                <template #actions>
                    <Link
                        :href="route('admin.actualites.create')"
                        class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                        </svg>
                        Nouvelle actualite
                    </Link>
                </template>
            </PageHeader>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-slate-100 bg-white p-4">
                <p class="text-sm text-slate-500">Total</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ stats?.total ?? 0 }}</p>
            </div>
            <div class="rounded-2xl border border-slate-100 bg-white p-4">
                <p class="text-sm text-slate-500">Visibles</p>
                <p class="mt-2 text-2xl font-semibold text-emerald-700">{{ stats?.visibles ?? 0 }}</p>
            </div>
            <div class="rounded-2xl border border-slate-100 bg-white p-4">
                <p class="text-sm text-slate-500">Cachees</p>
                <p class="mt-2 text-2xl font-semibold text-slate-700">{{ stats?.cachees ?? 0 }}</p>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-100 bg-white p-4">
            <div class="grid grid-cols-1 gap-3 md:grid-cols-12">
                <div class="md:col-span-4">
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
                            </svg>
                        </span>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Rechercher une actualite..."
                            class="w-full rounded-xl border border-slate-200 py-2.5 pl-9 pr-3 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                        />
                    </div>
                </div>
                <div class="md:col-span-2">
                    <select
                        v-model="filterVisible"
                        class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                    >
                        <option value="all">Toutes</option>
                        <option value="visible">Visibles</option>
                        <option value="hidden">Cachees</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-left text-slate-600">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Image</th>
                            <th class="px-5 py-3 font-semibold">Titre</th>
                            <th class="px-5 py-3 font-semibold">Categorie</th>
                            <th class="px-5 py-3 font-semibold">Auteur</th>
                            <th class="px-5 py-3 font-semibold">Date</th>
                            <th class="px-5 py-3 font-semibold">Visible</th>
                            <th class="px-5 py-3 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="actualite in actualites.data" :key="actualite.id" class="hover:bg-slate-50/70">
                            <td class="px-5 py-4">
                                <div class="h-12 w-12 overflow-hidden rounded-xl border border-slate-200 bg-slate-100">
                                    <img v-if="actualite.image_url" :src="actualite.image_url" :alt="actualite.titre" class="h-full w-full object-cover" />
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-semibold text-slate-900">{{ actualite.titre }}</div>
                                <div class="mt-1 text-xs text-slate-500">{{ actualite.extrait }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <span v-if="actualite.category" class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-800">
                                    <span class="h-2 w-2 rounded-full" :style="{ backgroundColor: actualite.category.couleur }"></span>
                                    {{ actualite.category.nom }}
                                </span>
                                <span v-else class="text-xs text-slate-400">Aucune</span>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ actualite.auteur?.name ?? 'Non defini' }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ actualite.date_publication ?? '—' }}</td>
                            <td class="px-5 py-4">
                                <button
                                    type="button"
                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors"
                                    :class="actualite.visible ? 'bg-ed-primary' : 'bg-slate-200'"
                                    @click="toggleVisibility(actualite)"
                                >
                                    <span
                                        class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                                        :class="actualite.visible ? 'translate-x-6' : 'translate-x-1'"
                                    ></span>
                                </button>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <Link
                                        :href="route('admin.actualites.edit', actualite.slug)"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-100"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.232-6.232a2.5 2.5 0 113.536 3.536L12.536 16.536A4 4 0 0110 17H7v-3a4 4 0 011-2.536z" />
                                        </svg>
                                        Modifier
                                    </Link>
                                    <button
                                        type="button"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50"
                                        @click="confirmDelete(actualite)"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 011-1h4a1 1 0 011 1m-7 0h8" />
                                        </svg>
                                        Supprimer
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!actualites.data.length">
                            <td colspan="7" class="px-6 py-10 text-center text-slate-500">Aucune actualite trouvee.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

            <Pagination v-if="actualites.links" :links="actualites.links" />
        </div>

    <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-slate-900/40" @click="showDeleteModal = false"></div>
        <div class="relative w-full max-w-lg rounded-2xl bg-white p-6">
            <h3 class="text-lg font-semibold text-slate-900">Confirmer la suppression</h3>
            <p class="mt-2 text-sm text-slate-600">
                Supprimer l'actualite {{ actualiteToDelete?.titre }} ? Cette action est irreversible.
            </p>
            <div class="mt-6 flex justify-end gap-2">
                <button type="button" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50" @click="showDeleteModal = false">
                    Annuler
                </button>
                <button type="button" class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700" @click="deleteActualite">
                    Supprimer
                </button>
            </div>
        </div>
    </div>
    </AdminLayout>
</template>
