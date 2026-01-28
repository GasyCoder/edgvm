<script setup>
import { computed, ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';
import Pagination from '@/Components/Common/Pagination.vue';

const props = defineProps({
    slider: Object,
    slides: Object,
    filters: Object,
});

const search = ref(props.filters?.search ?? '');
const showDeleteModal = ref(false);
const slideToDelete = ref(null);

const orderItems = ref(
    (props.slides?.data || []).map((slide) => ({
        id: slide.id,
        ordre: slide.ordre,
    }))
);

watch(() => props.slides?.data, (value) => {
    orderItems.value = (value || []).map((slide) => ({
        id: slide.id,
        ordre: slide.ordre,
    }));
});

const applyFilters = () => {
    router.get(route('admin.slides.index', props.slider.id), {
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

const toggleVisibility = (slide) => {
    router.patch(route('admin.slides.toggle', { slider: props.slider.id, slide: slide.id }), {}, { preserveScroll: true });
};

const confirmDelete = (slide) => {
    slideToDelete.value = slide;
    showDeleteModal.value = true;
};

const deleteSlide = () => {
    if (!slideToDelete.value) return;

    router.delete(route('admin.slides.destroy', { slider: props.slider.id, slide: slideToDelete.value.id }), {
        preserveScroll: true,
        onFinish: () => {
            showDeleteModal.value = false;
            slideToDelete.value = null;
        },
    });
};

const saveOrder = () => {
    router.patch(route('admin.slides.order', props.slider.id), {
        slides: orderItems.value,
    }, {
        preserveScroll: true,
    });
};

const slidesHaveOrderChanges = computed(() => {
    return (props.slides?.data || []).some((slide) => {
        const found = orderItems.value.find((item) => item.id === slide.id);
        return found && Number(found.ordre) !== Number(slide.ordre);
    });
});
</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-slate-900">Slides</h1>
        </template>

        <Head title="Slides" />
        <FlashMessage />

        <div class="space-y-6">
            <PageHeader
                badge="Contenus"
                :title="`Slides - ${slider.nom}`"
                description="Gere les visuels du slider selectionne."
            >
                <template #actions>
                    <Link
                        :href="route('admin.sliders.index')"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                    >
                        Retour sliders
                    </Link>
                    <Link
                        :href="route('admin.slides.create', slider.id)"
                        class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                        </svg>
                        Ajouter un slide
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
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Rechercher un slide..."
                        class="w-full rounded-xl border border-slate-200 py-2.5 pl-9 pr-3 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                    />
                </div>
                <button
                    type="button"
                    class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-60"
                    :disabled="!slidesHaveOrderChanges"
                    @click="saveOrder"
                >
                    Enregistrer l'ordre
                </button>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <table class="w-full text-sm">
                    <thead class="border-b border-slate-100 text-left text-xs uppercase tracking-[0.16em] text-slate-400">
                        <tr>
                            <th class="px-4 py-3">Slide</th>
                            <th class="px-4 py-3">Ordre</th>
                            <th class="px-4 py-3">Statut</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="(slide, index) in slides.data" :key="slide.id" class="hover:bg-slate-50/60">
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-4">
                                    <div v-if="slide.image_url" class="h-12 w-16 overflow-hidden rounded-xl border border-slate-200">
                                        <img :src="slide.image_url" alt="" class="h-full w-full object-cover" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">{{ slide.titre_highlight }}</p>
                                        <p v-if="slide.description" class="mt-1 text-xs text-slate-500 line-clamp-1">{{ slide.description }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <input
                                    v-model.number="orderItems[index].ordre"
                                    type="number"
                                    min="1"
                                    class="w-20 rounded-lg border border-slate-200 px-2 py-1 text-xs focus:border-ed-primary focus:ring-ed-primary/20"
                                />
                            </td>
                            <td class="px-4 py-4">
                                <span
                                    class="rounded-full border px-2.5 py-1 text-xs"
                                    :class="slide.visible ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-white text-slate-500'"
                                >
                                    {{ slide.visible ? 'Visible' : 'Masque' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex flex-wrap justify-end gap-2">
                                    <button
                                        type="button"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-100"
                                        @click="toggleVisibility(slide)"
                                    >
                                        <svg v-if="slide.visible" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg v-else class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                        {{ slide.visible ? 'Masquer' : 'Afficher' }}
                                    </button>
                                    <Link
                                        :href="route('admin.slides.edit', { slider: slider.id, slide: slide.id })"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:border-ed-primary hover:bg-ed-primary/5 hover:text-ed-primary"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.232-6.232a2.5 2.5 0 113.536 3.536L12.536 16.536A4 4 0 0110 17H7v-3a4 4 0 011-2.536z" />
                                        </svg>
                                        Modifier
                                    </Link>
                                    <button
                                        type="button"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50"
                                        @click="confirmDelete(slide)"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 011-1h4a1 1 0 011 1m-7 0h8" />
                                        </svg>
                                        Supprimer
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div v-if="!slides.data.length" class="px-6 py-10 text-center text-sm text-slate-500">
                    Aucun slide pour ce slider.
                </div>
            </div>

            <Pagination v-if="slides.links" :links="slides.links" />
        </div>

        <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-slate-900/40" @click="showDeleteModal = false"></div>
            <div class="relative w-full max-w-md rounded-2xl bg-white p-6">
                <h3 class="text-lg font-semibold text-slate-900">Supprimer le slide</h3>
                <p class="mt-2 text-sm text-slate-600">Ce slide sera supprime definitivement.</p>
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700" @click="showDeleteModal = false">Annuler</button>
                    <button type="button" class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700" @click="deleteSlide">Supprimer</button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
