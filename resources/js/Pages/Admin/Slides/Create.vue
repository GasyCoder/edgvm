<script setup>
import { computed, onBeforeUnmount, ref, watch } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const props = defineProps({
    slider: Object,
    images: Array,
    actualiteResults: Array,
    filters: Object,
    defaults: Object,
});

const actualiteSearch = ref(props.filters?.actualiteSearch ?? '');
const imagePreviewUrl = ref(null);
const imageInput = ref(null);
const selectedActualite = ref(null);

const backgroundOptions = [
    { value: 'from-ed-primary/95 via-ed-secondary/90 to-teal-800/95', label: 'Principal' },
    { value: 'from-slate-900/95 via-slate-800 to-slate-700/95', label: 'Nocturne' },
    { value: 'from-emerald-500/90 via-teal-600/90 to-cyan-700/90', label: 'Ocean' },
    { value: 'from-amber-500/90 via-orange-500/90 to-rose-600/90', label: 'Solaire' },
];

const form = useForm({
    titre_ligne1: '',
    titre_highlight: '',
    titre_ligne2: '',
    description: '',
    image_id: null,
    new_image: null,
    lien_cta: '',
    actualite_id: null,
    texte_cta: '',
    ordre: props.defaults?.ordre ?? 1,
    visible: props.defaults?.visible ?? true,
    badge_texte: '',
    badge_icon: props.defaults?.badge_icon ?? 'university',
    couleur_fond: props.defaults?.couleur_fond ?? backgroundOptions[0].value,
});

const selectedImage = computed(() => {
    return props.images?.find((image) => image.id === form.image_id) ?? null;
});

const applySearch = () => {
    router.get(route('admin.slides.create', props.slider.id), {
        actualiteSearch: actualiteSearch.value || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        only: ['actualiteResults', 'filters'],
    });
};

let searchTimeout = null;
watch(actualiteSearch, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applySearch();
    }, 300);
});

const selectActualite = (actualite) => {
    form.actualite_id = actualite.id;
    form.lien_cta = route('actualites.show', actualite.slug);
    selectedActualite.value = actualite;
    actualiteSearch.value = '';
};

const clearActualite = () => {
    form.actualite_id = null;
    selectedActualite.value = null;
    form.lien_cta = '';
};

const setNewImage = (event) => {
    const file = event.target.files?.[0] ?? null;
    form.new_image = file;

    if (imagePreviewUrl.value) {
        URL.revokeObjectURL(imagePreviewUrl.value);
    }

    imagePreviewUrl.value = file ? URL.createObjectURL(file) : null;
};

const clearNewImage = () => {
    if (imagePreviewUrl.value) {
        URL.revokeObjectURL(imagePreviewUrl.value);
    }

    imagePreviewUrl.value = null;
    form.new_image = null;

    if (imageInput.value) {
        imageInput.value.value = '';
    }
};

const selectImage = (image) => {
    form.image_id = image.id;
};

const submit = () => {
    form.post(route('admin.slides.store', props.slider.id), {
        preserveScroll: true,
        forceFormData: true,
    });
};

onBeforeUnmount(() => {
    if (imagePreviewUrl.value) {
        URL.revokeObjectURL(imagePreviewUrl.value);
    }
});
</script>

<template>
    <AdminLayout>
        <template #header>
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 md:text-xl">Nouveau slide</h2>
                    <p class="mt-1 text-xs text-slate-500 md:text-sm">Slider: {{ slider.nom }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.slides.index', slider.id)" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Retour</Link>
                    <button type="button" class="rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary" :disabled="form.processing" @click="submit">Creer</button>
                </div>
            </div>
        </template>

        <Head title="Nouveau slide" />
        <FlashMessage />

        <div class="space-y-6">
            <form class="grid grid-cols-1 gap-6 lg:grid-cols-12" @submit.prevent="submit">
                <div class="space-y-6 lg:col-span-8">
                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <h3 class="text-sm font-semibold text-slate-900">Contenu</h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Titre highlight *</label>
                                <input v-model="form.titre_highlight" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                                <p v-if="form.errors.titre_highlight" class="mt-2 text-xs text-red-600">{{ form.errors.titre_highlight }}</p>
                            </div>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="text-xs font-semibold text-slate-700">Titre ligne 1</label>
                                    <input v-model="form.titre_ligne1" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-slate-700">Titre ligne 2</label>
                                    <input v-model="form.titre_ligne2" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Description</label>
                                <textarea v-model="form.description" rows="4" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20"></textarea>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <h3 class="text-sm font-semibold text-slate-900">Visuel</h3>
                        <div class="mt-4 space-y-4">
                            <div v-if="imagePreviewUrl" class="relative overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                <img :src="imagePreviewUrl" alt="" class="h-48 w-full object-cover" />
                                <button type="button" class="absolute right-2 top-2 rounded-xl border border-slate-200 bg-white/90 p-2 text-slate-700" @click="clearNewImage">Retirer</button>
                            </div>
                            <div v-else-if="selectedImage" class="relative overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                <img :src="selectedImage.url" alt="" class="h-48 w-full object-cover" />
                            </div>
                            <div class="flex items-center justify-between gap-4 rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-6">
                                <div>
                                    <p class="text-sm font-semibold text-slate-700">Uploader une image</p>
                                    <p class="mt-1 text-xs text-slate-500">PNG, JPG jusqu'a 5MB.</p>
                                </div>
                                <input ref="imageInput" type="file" accept="image/*" class="text-sm" @change="setNewImage" />
                            </div>
                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                                <button
                                    v-for="image in images"
                                    :key="image.id"
                                    type="button"
                                    class="group relative overflow-hidden rounded-xl border border-slate-200"
                                    @click="selectImage(image)"
                                >
                                    <img :src="image.url" alt="" class="h-24 w-full object-cover" />
                                    <div v-if="form.image_id === image.id" class="absolute inset-0 bg-ed-primary/30"></div>
                                </button>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <h3 class="text-sm font-semibold text-slate-900">CTA & actualite</h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Recherche actualite</label>
                                <input v-model="actualiteSearch" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" placeholder="Rechercher une actualite..." />
                            </div>
                            <div v-if="actualiteResults.length" class="max-h-48 overflow-y-auto rounded-xl border border-slate-200 p-2 text-sm">
                                <button
                                    v-for="result in actualiteResults"
                                    :key="result.id"
                                    type="button"
                                    class="flex w-full items-center justify-between rounded-lg px-2.5 py-2 hover:bg-slate-50"
                                    @click="selectActualite(result)"
                                >
                                    <span class="text-slate-700">{{ result.titre }}</span>
                                    <span class="text-xs text-slate-400">{{ result.date_publication }}</span>
                                </button>
                            </div>
                            <div v-if="selectedActualite" class="rounded-xl border border-emerald-200 bg-emerald-50 p-3">
                                <p class="text-xs font-semibold text-emerald-700">Actualite selectionnee</p>
                                <p class="mt-1 text-sm text-emerald-800">{{ selectedActualite.titre }}</p>
                                <button type="button" class="mt-2 text-xs font-semibold text-emerald-700" @click="clearActualite">Retirer</button>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Lien CTA</label>
                                <input v-model="form.lien_cta" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" placeholder="https://..." />
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Texte CTA</label>
                                <input v-model="form.texte_cta" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" placeholder="Voir plus" />
                            </div>
                        </div>
                    </section>
                </div>

                <aside class="space-y-6 lg:col-span-4">
                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <h3 class="text-sm font-semibold text-slate-900">Parametres</h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Ordre</label>
                                <input v-model="form.ordre" type="number" min="1" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                            </div>
                            <label class="flex items-start gap-3 text-sm">
                                <input v-model="form.visible" type="checkbox" class="mt-0.5 rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20" />
                                <span>
                                    <span class="font-semibold text-slate-800">Visible</span>
                                    <span class="block text-xs text-slate-500">Afficher sur le site.</span>
                                </span>
                            </label>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <h3 class="text-sm font-semibold text-slate-900">Badge</h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Texte</label>
                                <input v-model="form.badge_texte" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Icone</label>
                                <select v-model="form.badge_icon" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20">
                                    <option value="university">Universite</option>
                                    <option value="research">Recherche</option>
                                    <option value="students">Etudiants</option>
                                </select>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <h3 class="text-sm font-semibold text-slate-900">Couleur de fond</h3>
                        <div class="mt-4 space-y-2">
                            <label v-for="option in backgroundOptions" :key="option.value" class="flex items-center gap-3 rounded-xl border border-slate-200 px-3 py-2 text-sm">
                                <input v-model="form.couleur_fond" type="radio" :value="option.value" class="text-ed-primary focus:ring-ed-primary/20" />
                                <span class="h-6 w-10 rounded-lg bg-gradient-to-r" :class="option.value"></span>
                                <span class="text-slate-700">{{ option.label }}</span>
                            </label>
                            <p v-if="form.errors.couleur_fond" class="text-xs text-red-600">{{ form.errors.couleur_fond }}</p>
                        </div>
                    </section>
                </aside>
            </form>
        </div>
    </AdminLayout>
</template>
