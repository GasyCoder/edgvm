<script setup>
import { ref, watch } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';
import Pagination from '@/Components/Common/Pagination.vue';

const props = defineProps({
    filters: Object,
    tags: Object,
});

const search = ref(props.filters?.search ?? '');
const showModal = ref(false);
const editingId = ref(null);

const form = useForm({
    nom: '',
});

let searchTimeout = null;
watch(search, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(route('admin.tags.index'), { search: search.value || undefined }, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        });
    }, 300);
});

const openCreate = () => {
    editingId.value = null;
    form.reset();
    showModal.value = true;
};

const openEdit = (tag) => {
    editingId.value = tag.id;
    form.nom = tag.nom;
    showModal.value = true;
};

const submit = () => {
    if (editingId.value) {
        form.put(route('admin.tags.update', editingId.value), {
            preserveScroll: true,
            onSuccess: () => {
                showModal.value = false;
            },
        });
        return;
    }
    form.post(route('admin.tags.store'), {
        preserveScroll: true,
        onSuccess: () => {
            showModal.value = false;
        },
    });
};

const removeTag = (tag) => {
    if (!confirm('Supprimer ce tag ?')) return;
    router.delete(route('admin.tags.destroy', tag.id), { preserveScroll: true });
};

</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-slate-900">Tags</h1>
        </template>

        <Head title="Tags" />
        <FlashMessage />

        <div class="space-y-6">
            <PageHeader
                badge="Organisation"
                title="Tags"
                description="Gestion des tags d'actualites."
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
                        Nouveau tag
                    </button>
                </template>
            </PageHeader>

            <input
                v-model="search"
                type="text"
                placeholder="Rechercher un tag..."
                class="w-full max-w-md rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
            />

            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-left text-slate-600">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Nom</th>
                            <th class="px-5 py-3 font-semibold">Slug</th>
                            <th class="px-5 py-3 font-semibold">Articles</th>
                            <th class="px-5 py-3 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="tag in tags.data" :key="tag.id" class="hover:bg-slate-50/70">
                            <td class="px-5 py-4 font-medium text-slate-900">{{ tag.nom }}</td>
                            <td class="px-5 py-4 text-slate-500">{{ tag.slug }}</td>
                            <td class="px-5 py-4 text-slate-500">{{ tag.actualites_count }}</td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-100" @click="openEdit(tag)">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.232-6.232a2.5 2.5 0 113.536 3.536L12.536 16.536A4 4 0 0110 17H7v-3a4 4 0 011-2.536z" />
                                        </svg>
                                        Modifier
                                    </button>
                                    <button class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50" @click="removeTag(tag)">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 011-1h4a1 1 0 011 1m-7 0h8" />
                                        </svg>
                                        Supprimer
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!tags.data.length">
                            <td colspan="4" class="px-6 py-10 text-center text-slate-500">Aucun tag trouve.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pagination v-if="tags.links" :links="tags.links" />
        </div>

        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-slate-900/40" @click="showModal = false"></div>
            <div class="relative w-full max-w-md rounded-2xl bg-white p-6">
                <h3 class="text-lg font-semibold text-slate-900">{{ editingId ? 'Modifier le tag' : 'Nouveau tag' }}</h3>
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

                    <div class="flex justify-end gap-2 pt-4">
                        <button type="button" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50" @click="showModal = false">
                            Annuler
                        </button>
                        <button type="submit" class="rounded-xl bg-ed-primary px-4 py-2 text-sm font-semibold text-white hover:bg-ed-secondary" :disabled="form.processing">
                            {{ editingId ? 'Mettre a jour' : 'Creer' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
