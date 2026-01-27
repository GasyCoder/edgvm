<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';
import Pagination from '@/Components/Common/Pagination.vue';

const props = defineProps({
    evenements: Object,
    filters: Object,
    stats: Object,
    types: Object,
});

const search = ref(props.filters?.search ?? '');
const typeFilter = ref(props.filters?.type ?? '');
const publieFilter = ref(props.filters?.publie ?? 'all');

const showDeleteModal = ref(false);
const evenementToDelete = ref(null);

const applyFilters = () => {
    router.get(route('admin.evenements.index'), {
        search: search.value || undefined,
        type: typeFilter.value || undefined,
        publie: publieFilter.value !== 'all' ? publieFilter.value : undefined,
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

watch(typeFilter, () => applyFilters());
watch(publieFilter, () => applyFilters());

const confirmDelete = (evenement) => {
    evenementToDelete.value = evenement;
    showDeleteModal.value = true;
};

const deleteEvenement = () => {
    if (!evenementToDelete.value) return;

    router.delete(route('admin.evenements.destroy', evenementToDelete.value.id), {
        preserveScroll: true,
        onFinish: () => {
            showDeleteModal.value = false;
            evenementToDelete.value = null;
        },
    });
};

const togglePublication = (evenement) => {
    router.patch(route('admin.evenements.toggle', evenement.id), {}, {
        preserveScroll: true,
    });
};

const archiveEvenement = (evenement) => {
    router.patch(route('admin.evenements.archive', evenement.id), {}, {
        preserveScroll: true,
    });
};

const restoreEvenement = (evenement) => {
    router.patch(route('admin.evenements.restore', evenement.id), {}, {
        preserveScroll: true,
    });
};
</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-slate-900">Evenements</h1>
        </template>

        <Head title="Evenements" />
        <FlashMessage />

        <div class="space-y-6">
            <PageHeader
                badge="Agenda"
                title="Evenements"
                description="Suivez les rendez-vous, publications et archives."
            >
                <template #actions>
                    <Link
                        :href="route('admin.evenements.create')"
                        class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                        </svg>
                        Nouvel evenement
                    </Link>
                </template>
            </PageHeader>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Total</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{ stats?.total ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-emerald-600">Publies</p>
                    <p class="mt-2 text-2xl font-semibold text-emerald-700">{{ stats?.publies ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Brouillons</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{ stats?.brouillons ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-amber-100 bg-amber-50 p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-amber-600">Archives</p>
                    <p class="mt-2 text-2xl font-semibold text-amber-700">{{ stats?.archives ?? 0 }}</p>
                </div>
            </div>

            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="relative w-full max-w-md">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
                        </svg>
                    </span>
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Rechercher un evenement..."
                        class="w-full rounded-xl border border-slate-200 py-2.5 pl-9 pr-3 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                    />
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <select
                        v-model="typeFilter"
                        class="rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                    >
                        <option value="">Tous les types</option>
                        <option v-for="(label, value) in types" :key="value" :value="value">
                            {{ label }}
                        </option>
                    </select>
                    <select
                        v-model="publieFilter"
                        class="rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                    >
                        <option value="all">Tous les statuts</option>
                        <option value="publie">Publies</option>
                        <option value="non">Brouillons</option>
                    </select>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <div class="grid grid-cols-1 gap-4 p-4 md:hidden">
                    <div
                        v-for="evenement in evenements.data"
                        :key="evenement.id"
                        class="rounded-2xl border border-slate-100 bg-slate-50 p-4"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">{{ evenement.titre }}</h3>
                                <p class="mt-1 text-xs text-slate-500">{{ evenement.periode_aff || 'Date a definir' }}</p>
                                <p v-if="evenement.lieu" class="mt-1 text-xs text-slate-500">{{ evenement.lieu }}</p>
                            </div>
                            <span class="rounded-full px-2 py-1 text-[11px] font-semibold" :class="evenement.type_classe">
                                {{ evenement.type_texte }}
                            </span>
                        </div>
                        <div class="mt-3 flex flex-wrap items-center gap-2 text-xs">
                            <span v-if="evenement.est_archive" class="rounded-full border border-amber-200 bg-amber-50 px-2 py-1 text-amber-700">Archive</span>
                            <span v-else-if="evenement.est_publie" class="rounded-full border border-emerald-200 bg-emerald-50 px-2 py-1 text-emerald-700">Publie</span>
                            <span v-else class="rounded-full border border-slate-200 bg-white px-2 py-1 text-slate-600">Brouillon</span>
                            <span v-if="evenement.est_important" class="rounded-full border border-red-200 bg-red-50 px-2 py-1 text-red-700">Important</span>
                            <span v-if="evenement.est_termine" class="rounded-full border border-slate-200 bg-white px-2 py-1 text-slate-500">Termine</span>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <Link
                                :href="route('admin.evenements.edit', evenement.id)"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-white"
                            >
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.232-6.232a2.5 2.5 0 113.536 3.536L12.536 16.536A4 4 0 0110 17H7v-3a4 4 0 011-2.536z" />
                                </svg>
                                Modifier
                            </Link>
                            <button
                                type="button"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-white"
                                :disabled="evenement.est_archive"
                                @click="togglePublication(evenement)"
                            >
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v8m0 0l-3-3m3 3l3-3M4 12v6a2 2 0 002 2h12a2 2 0 002-2v-6" />
                                </svg>
                                {{ evenement.est_publie ? 'Mettre en brouillon' : 'Publier' }}
                            </button>
                            <button
                                v-if="evenement.est_archive"
                                type="button"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-50"
                                @click="restoreEvenement(evenement)"
                            >
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h6M4 7v6m0-6l3.5 3.5A7 7 0 1119 12a7 7 0 01-7 7" />
                                </svg>
                                Restaurer
                            </button>
                            <button
                                v-else
                                type="button"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-amber-200 px-3 py-1.5 text-xs font-semibold text-amber-700 hover:bg-amber-50 disabled:cursor-not-allowed disabled:opacity-60"
                                :disabled="!evenement.est_termine"
                                @click="archiveEvenement(evenement)"
                            >
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V8a2 2 0 00-2-2H6a2 2 0 00-2 2v5m16 0l-4 7H8l-4-7m16 0H4" />
                                </svg>
                                Archiver
                            </button>
                            <button
                                type="button"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50"
                                @click="confirmDelete(evenement)"
                            >
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 011-1h4a1 1 0 011 1m-7 0h8" />
                                </svg>
                                Supprimer
                            </button>
                        </div>
                    </div>
                </div>

                <table class="hidden w-full text-sm md:table">
                    <thead class="border-b border-slate-100 text-left text-xs uppercase tracking-[0.16em] text-slate-400">
                        <tr>
                            <th class="px-4 py-3">Evenement</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Statut</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="evenement in evenements.data" :key="evenement.id" class="hover:bg-slate-50/60">
                            <td class="px-4 py-4">
                                <div class="flex items-start gap-3">
                                    <div v-if="evenement.image_url" class="h-12 w-12 overflow-hidden rounded-xl border border-slate-200">
                                        <img :src="evenement.image_url" alt="" class="h-full w-full object-cover" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">{{ evenement.titre }}</p>
                                        <p v-if="evenement.lieu" class="mt-1 text-xs text-slate-500">{{ evenement.lieu }}</p>
                                        <p v-if="evenement.document" class="mt-1 text-xs text-slate-400">PDF: {{ evenement.document.nom }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold" :class="evenement.type_classe">
                                    {{ evenement.type_texte }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span v-if="evenement.est_archive" class="rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs text-amber-700">Archive</span>
                                    <span v-else-if="evenement.est_publie" class="rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs text-emerald-700">Publie</span>
                                    <span v-else class="rounded-full border border-slate-200 bg-white px-2.5 py-1 text-xs text-slate-600">Brouillon</span>
                                    <span v-if="evenement.est_important" class="rounded-full border border-red-200 bg-red-50 px-2.5 py-1 text-xs text-red-700">Important</span>
                                    <span v-if="evenement.est_termine" class="rounded-full border border-slate-200 bg-white px-2.5 py-1 text-xs text-slate-500">Termine</span>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-sm font-semibold text-slate-900">{{ evenement.periode_aff || 'Date a definir' }}</p>
                                <p v-if="evenement.heure_debut" class="mt-1 text-xs text-slate-500">{{ evenement.heure_debut }}</p>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex flex-wrap justify-end gap-2">
                                    <Link
                                        :href="route('admin.evenements.edit', evenement.id)"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-white"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.232-6.232a2.5 2.5 0 113.536 3.536L12.536 16.536A4 4 0 0110 17H7v-3a4 4 0 011-2.536z" />
                                        </svg>
                                        Modifier
                                    </Link>
                                    <button
                                        type="button"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-white disabled:cursor-not-allowed disabled:opacity-60"
                                        :disabled="evenement.est_archive"
                                        @click="togglePublication(evenement)"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v8m0 0l-3-3m3 3l3-3M4 12v6a2 2 0 002 2h12a2 2 0 002-2v-6" />
                                        </svg>
                                        {{ evenement.est_publie ? 'Brouillon' : 'Publier' }}
                                    </button>
                                    <button
                                        v-if="evenement.est_archive"
                                        type="button"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-50"
                                        @click="restoreEvenement(evenement)"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h6M4 7v6m0-6l3.5 3.5A7 7 0 1119 12a7 7 0 01-7 7" />
                                        </svg>
                                        Restaurer
                                    </button>
                                    <button
                                        v-else
                                        type="button"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-amber-200 px-3 py-1.5 text-xs font-semibold text-amber-700 hover:bg-amber-50 disabled:cursor-not-allowed disabled:opacity-60"
                                        :disabled="!evenement.est_termine"
                                        @click="archiveEvenement(evenement)"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V8a2 2 0 00-2-2H6a2 2 0 00-2 2v5m16 0l-4 7H8l-4-7m16 0H4" />
                                        </svg>
                                        Archiver
                                    </button>
                                    <button
                                        type="button"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50"
                                        @click="confirmDelete(evenement)"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 011-1h4a1 1 0 011 1m-7 0h8" />
                                        </svg>
                                        Supprimer
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div v-if="!evenements.data.length" class="px-6 py-10 text-center text-sm text-slate-500">
                    Aucun evenement trouve.
                </div>
            </div>

            <Pagination v-if="evenements.links" :links="evenements.links" />
        </div>

        <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-slate-900/40" @click="showDeleteModal = false"></div>
            <div class="relative w-full max-w-md rounded-2xl bg-white p-6">
                <h3 class="text-lg font-semibold text-slate-900">Supprimer l'evenement</h3>
                <p class="mt-2 text-sm text-slate-600">
                    Cette action est definitive. Voulez-vous vraiment supprimer cet evenement ?
                </p>
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button
                        type="button"
                        class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700"
                        @click="showDeleteModal = false"
                    >
                        Annuler
                    </button>
                    <button
                        type="button"
                        class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700"
                        @click="deleteEvenement"
                    >
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
