<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';
import Pagination from '@/Components/Common/Pagination.vue';

const props = defineProps({
    partenaires: Object,
    filters: Object,
});

const search = ref(props.filters?.search ?? '');

const applyFilters = () => {
    router.get(route('admin.partenaires.index'), {
        search: search.value || undefined,
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

const toggleVisibility = (partenaire) => {
    router.patch(route('admin.partenaires.toggle', partenaire.id), {}, { preserveScroll: true });
};

const deletePartenaire = (partenaire) => {
    router.delete(route('admin.partenaires.destroy', partenaire.id), { preserveScroll: true });
};
</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-slate-900">Partenaires</h1>
        </template>

        <Head title="Partenaires" />
        <FlashMessage />

        <div class="space-y-6">
            <PageHeader
                badge="Site"
                title="Partenaires"
                description="Gerer les partenaires et logos."
            >
                <template #actions>
                    <Link :href="route('admin.partenaires.create')" class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                        </svg>
                        Nouveau partenaire
                    </Link>
                </template>
            </PageHeader>

            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="relative w-full max-w-md">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
                        </svg>
                    </span>
                    <input v-model="search" type="text" placeholder="Rechercher un partenaire..." class="w-full rounded-xl border border-slate-200 py-2.5 pl-9 pr-3 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <table class="w-full text-sm">
                    <thead class="border-b border-slate-100 text-left text-xs uppercase tracking-[0.16em] text-slate-400">
                        <tr>
                            <th class="px-4 py-3">Partenaire</th>
                            <th class="px-4 py-3">Ordre</th>
                            <th class="px-4 py-3">Statut</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="partenaire in partenaires.data" :key="partenaire.id" class="hover:bg-slate-50/60">
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div v-if="partenaire.logo_url" class="h-10 w-10 overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                        <img :src="partenaire.logo_url" alt="" class="h-full w-full object-contain" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">{{ partenaire.nom }}</p>
                                        <p v-if="partenaire.url" class="mt-1 text-xs text-slate-500">{{ partenaire.url }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600">{{ partenaire.ordre }}</td>
                            <td class="px-4 py-4">
                                <span
                                    class="rounded-full border px-2.5 py-1 text-xs"
                                    :class="partenaire.visible ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-white text-slate-500'"
                                >
                                    {{ partenaire.visible ? 'Visible' : 'Masque' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex flex-wrap justify-end gap-2">
                                    <button type="button" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-white" @click="toggleVisibility(partenaire)">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        {{ partenaire.visible ? 'Masquer' : 'Afficher' }}
                                    </button>
                                    <Link :href="route('admin.partenaires.edit', partenaire.id)" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-white">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.232-6.232a2.5 2.5 0 113.536 3.536L12.536 16.536A4 4 0 0110 17H7v-3a4 4 0 011-2.536z" />
                                        </svg>
                                        Modifier
                                    </Link>
                                    <button type="button" class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50" @click="deletePartenaire(partenaire)">
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

                <div v-if="!partenaires.data.length" class="px-6 py-10 text-center text-sm text-slate-500">
                    Aucun partenaire disponible.
                </div>
            </div>

            <Pagination v-if="partenaires.links" :links="partenaires.links" />
        </div>
    </AdminLayout>
</template>
