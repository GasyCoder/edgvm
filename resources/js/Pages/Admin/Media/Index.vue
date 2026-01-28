<script setup>
import { computed, ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';
import Pagination from '@/Components/Common/Pagination.vue';

const props = defineProps({
    filters: Object,
    medias: Object,
    stats: Object,
});

const search = ref(props.filters?.search ?? '');
const typeFilter = ref(props.filters?.type ?? 'all');
const viewMode = ref(props.filters?.view ?? 'grid');
const showDeleteModal = ref(false);
const mediaToDelete = ref(null);

const formattedTotalSize = computed(() => {
    const size = props.stats?.taille_totale ?? 0;
    return `${(size / 1024 / 1024).toFixed(2)} MB`;
});

const applyFilters = () => {
    router.get(route('admin.media.index'), {
        search: search.value || undefined,
        type: typeFilter.value !== 'all' ? typeFilter.value : undefined,
        view: viewMode.value !== 'grid' ? viewMode.value : undefined,
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

const toggleView = () => {
    viewMode.value = viewMode.value === 'grid' ? 'list' : 'grid';
    applyFilters();
};

const confirmDelete = (media) => {
    mediaToDelete.value = media;
    showDeleteModal.value = true;
};

const deleteMedia = () => {
    if (!mediaToDelete.value) return;
    router.delete(route('admin.media.destroy', mediaToDelete.value.id), {
        preserveScroll: true,
        onFinish: () => {
            showDeleteModal.value = false;
            mediaToDelete.value = null;
        },
    });
};

</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-slate-900">Mediatheque</h1>
        </template>

        <Head title="Mediatheque" />
        <FlashMessage />

        <div class="space-y-6">
            <PageHeader
                badge="Contenus"
                title="Mediatheque"
                description="Gestion des images, documents et videos."
            >
                <template #actions>
                    <Link
                        :href="route('admin.media.upload')"
                        class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                        </svg>
                        Uploader
                    </Link>
                </template>
            </PageHeader>

            <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div class="relative w-full max-w-md">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
                            </svg>
                        </span>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Rechercher un media..."
                            class="w-full rounded-xl border border-slate-200 py-2.5 pl-9 pr-3 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                        />
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <select
                            v-model="typeFilter"
                            class="rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                        >
                            <option value="all">Tous les types</option>
                            <option value="image">Images</option>
                            <option value="document">Documents</option>
                            <option value="video">Videos</option>
                        </select>

                        <button
                            type="button"
                            @click="toggleView"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 hover:border-ed-primary/40 hover:text-ed-primary"
                        >
                            <svg v-if="viewMode === 'grid'" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg v-else class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h7M4 12h7M4 18h7M15 6h5M15 12h5M15 18h5" />
                            </svg>
                            <span v-if="viewMode === 'grid'">Vue liste</span>
                            <span v-else>Vue grille</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Total</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{ stats?.total ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Images</p>
                    <p class="mt-2 text-2xl font-semibold text-blue-600">{{ stats?.images ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Documents</p>
                    <p class="mt-2 text-2xl font-semibold text-emerald-600">{{ stats?.documents ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Videos</p>
                    <p class="mt-2 text-2xl font-semibold text-purple-600">{{ stats?.videos ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Taille totale</p>
                    <p class="mt-2 text-xl font-semibold text-slate-900">{{ formattedTotalSize }}</p>
                </div>
            </div>

            <div v-if="viewMode === 'grid'" class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6">
            <div
                v-for="media in medias.data"
                :key="media.id"
                class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
            >
                <div class="aspect-square bg-slate-100">
                    <img
                        v-if="media.type === 'image'"
                        :src="media.url"
                        :alt="media.nom_original"
                        class="h-full w-full object-cover transition duration-200 group-hover:scale-105"
                    />
                    <div v-else class="flex h-full flex-col items-center justify-center gap-2 p-4 text-slate-500">
                        <span class="text-xs text-slate-400">{{ media.type }}</span>
                    </div>
                </div>
                <span
                    class="absolute left-3 top-3 rounded-full border px-2.5 py-1 text-[10px] font-semibold capitalize"
                    :class="media.type === 'image' ? 'border-blue-200 bg-blue-50 text-blue-700' : media.type === 'document' ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-purple-200 bg-purple-50 text-purple-700'"
                >
                    {{ media.type }}
                </span>
                <div class="border-t border-slate-100 p-2">
                    <p class="truncate text-xs text-slate-700">{{ media.nom_original }}</p>
                    <p class="text-[11px] text-slate-400">{{ (media.taille_bytes / 1024).toFixed(2) }} KB</p>
                </div>
                <div class="absolute inset-0 flex items-center justify-center gap-2 bg-slate-900/50 opacity-0 transition group-hover:opacity-100">
                    <a
                        :href="media.url"
                        target="_blank"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white text-slate-700"
                        title="Voir"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </a>
                    <button
                        type="button"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-red-600 text-white"
                        title="Supprimer"
                        @click="confirmDelete(media)"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 011-1h4a1 1 0 011 1m-7 0h8" />
                        </svg>
                    </button>
                </div>
            </div>

            <div v-if="!medias.data.length" class="col-span-full rounded-2xl border border-dashed border-slate-200 bg-white p-10 text-center text-sm text-slate-500">
                Aucun media trouve.
            </div>
            </div>

            <div v-else class="overflow-hidden rounded-2xl border border-slate-100 bg-white">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-left text-slate-600">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Apercu</th>
                        <th class="px-5 py-3 font-semibold">Nom</th>
                        <th class="px-5 py-3 font-semibold">Type</th>
                        <th class="px-5 py-3 font-semibold">Taille</th>
                        <th class="px-5 py-3 font-semibold">Date</th>
                        <th class="px-5 py-3 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr v-for="media in medias.data" :key="media.id" class="hover:bg-slate-50/70">
                        <td class="px-5 py-4">
                            <img v-if="media.type === 'image'" :src="media.url" class="h-12 w-12 rounded-xl object-cover" />
                            <div v-else class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-xs text-slate-400">
                                {{ media.type }}
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="font-medium text-slate-900">{{ media.nom_original }}</div>
                            <div class="text-xs text-slate-500">{{ media.nom_fichier }}</div>
                        </td>
                        <td class="px-5 py-4 text-slate-500">{{ media.type }}</td>
                        <td class="px-5 py-4 text-slate-500">{{ (media.taille_bytes / 1024).toFixed(2) }} KB</td>
                        <td class="px-5 py-4 text-slate-500">{{ media.created_at }}</td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a
                                    :href="media.url"
                                    target="_blank"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-700 transition hover:bg-slate-100"
                                    title="Voir"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <button
                                    type="button"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-red-200 text-red-600 transition hover:bg-red-50"
                                    title="Supprimer"
                                    @click="confirmDelete(media)"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 011-1h4a1 1 0 011 1m-7 0h8" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!medias.data.length">
                        <td colspan="6" class="px-6 py-10 text-center text-slate-500">Aucun media trouve.</td>
                    </tr>
                </tbody>
            </table>
            </div>

            <Pagination v-if="medias.links" :links="medias.links" />
        </div>

        <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-slate-900/40" @click="showDeleteModal = false"></div>
            <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-slate-900">Confirmer la suppression</h3>
                <p class="mt-2 text-sm text-slate-600">
                    Supprimer le media {{ mediaToDelete?.nom_original }} ? Cette action est definitive.
                </p>
                <div class="mt-6 flex justify-end gap-2">
                    <button
                        type="button"
                        class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                        @click="showDeleteModal = false"
                    >
                        Annuler
                    </button>
                    <button
                        type="button"
                        class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700"
                        @click="deleteMedia"
                    >
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
