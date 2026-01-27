<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';
import Pagination from '@/Components/Common/Pagination.vue';

const props = defineProps({
    annonces: Object,
    filters: Object,
});

const search = ref(props.filters?.search ?? '');
const filterCible = ref(props.filters?.cible ?? '');
const filterPublie = ref(props.filters?.publie ?? 'all');

const showDeleteModal = ref(false);
const annonceToDelete = ref(null);

const applyFilters = () => {
    router.get(route('admin.annonces.index'), {
        search: search.value || undefined,
        cible: filterCible.value || undefined,
        publie: filterPublie.value !== 'all' ? filterPublie.value : undefined,
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

watch(filterCible, () => applyFilters());
watch(filterPublie, () => applyFilters());

const togglePublie = (annonce) => {
    router.patch(route('admin.annonces.toggle', annonce.id), {}, { preserveScroll: true });
};

const sendEmail = (annonce) => {
    router.post(route('admin.annonces.send-email', annonce.id), {}, { preserveScroll: true });
};

const confirmDelete = (annonce) => {
    annonceToDelete.value = annonce;
    showDeleteModal.value = true;
};

const deleteAnnonce = () => {
    if (!annonceToDelete.value) return;

    router.delete(route('admin.annonces.destroy', annonceToDelete.value.id), {
        preserveScroll: true,
        onFinish: () => {
            showDeleteModal.value = false;
            annonceToDelete.value = null;
        },
    });
};
</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-slate-900">Annonces</h1>
        </template>

        <Head title="Annonces" />
        <FlashMessage />

        <div class="space-y-6">
            <PageHeader
                badge="Communication"
                title="Annonces"
                description="Gerer les annonces et envois email."
            >
                <template #actions>
                    <Link :href="route('admin.annonces.create')" class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                        </svg>
                        Nouvelle annonce
                    </Link>
                </template>
            </PageHeader>
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="relative w-full max-w-md">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
                        </svg>
                    </span>
                    <input v-model="search" type="text" placeholder="Rechercher une annonce..." class="w-full rounded-xl border border-slate-200 py-2.5 pl-9 pr-3 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <select v-model="filterCible" class="rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20">
                        <option value="">Toutes les cibles</option>
                        <option value="doctorant">Doctorants</option>
                        <option value="encadrant">Encadrants</option>
                        <option value="both">Tous</option>
                    </select>
                    <select v-model="filterPublie" class="rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20">
                        <option value="all">Tous les statuts</option>
                        <option value="1">Publiees</option>
                        <option value="0">Brouillons</option>
                    </select>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <table class="w-full text-sm">
                    <thead class="border-b border-slate-100 text-left text-xs uppercase tracking-[0.16em] text-slate-400">
                        <tr>
                            <th class="px-4 py-3">Annonce</th>
                            <th class="px-4 py-3">Cible</th>
                            <th class="px-4 py-3">Statut</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="annonce in annonces.data" :key="annonce.id" class="hover:bg-slate-50/60">
                            <td class="px-4 py-4">
                                <p class="text-sm font-semibold text-slate-900">{{ annonce.titre }}</p>
                                <p v-if="annonce.publie_at" class="mt-1 text-xs text-slate-500">Publie le {{ annonce.publie_at }}</p>
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-600">{{ annonce.cible }}</td>
                            <td class="px-4 py-4">
                                <span
                                    class="rounded-full border px-2.5 py-1 text-xs"
                                    :class="annonce.est_publie ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-white text-slate-500'"
                                >
                                    {{ annonce.est_publie ? 'Publiee' : 'Brouillon' }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-xs text-slate-600">{{ annonce.email_cible || 'Non defini' }}</p>
                                <p v-if="annonce.email_envoye_at" class="mt-1 text-xs text-slate-400">Envoye le {{ annonce.email_envoye_at }}</p>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex flex-wrap justify-end gap-2">
                                    <button type="button" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-white" @click="togglePublie(annonce)">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v8m0 0l-3-3m3 3l3-3M4 12v6a2 2 0 002 2h12a2 2 0 002-2v-6" />
                                        </svg>
                                        {{ annonce.est_publie ? 'Brouillon' : 'Publier' }}
                                    </button>
                                    <button type="button" class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-50" @click="sendEmail(annonce)">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l9 6 9-6M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z" />
                                        </svg>
                                        Envoyer email
                                    </button>
                                    <Link :href="route('admin.annonces.edit', annonce.id)" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-white">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.232-6.232a2.5 2.5 0 113.536 3.536L12.536 16.536A4 4 0 0110 17H7v-3a4 4 0 011-2.536z" />
                                        </svg>
                                        Modifier
                                    </Link>
                                    <button type="button" class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50" @click="confirmDelete(annonce)">
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

                <div v-if="!annonces.data.length" class="px-6 py-10 text-center text-sm text-slate-500">
                    Aucune annonce disponible.
                </div>
            </div>

            <Pagination v-if="annonces.links" :links="annonces.links" />
        </div>

        <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-slate-900/40" @click="showDeleteModal = false"></div>
            <div class="relative w-full max-w-md rounded-2xl bg-white p-6">
                <h3 class="text-lg font-semibold text-slate-900">Supprimer l'annonce</h3>
                <p class="mt-2 text-sm text-slate-600">Cette annonce sera supprimee definitivement.</p>
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700" @click="showDeleteModal = false">Annuler</button>
                    <button type="button" class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700" @click="deleteAnnonce">Supprimer</button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
