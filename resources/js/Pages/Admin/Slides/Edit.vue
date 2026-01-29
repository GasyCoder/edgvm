<script setup>
import { onBeforeUnmount, ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const props = defineProps({
    slider: Object,
    actualites: Array,
    media: Object,
    uploadedMediaId: Number,
    slide: Object,
});

const mediaMode = ref('library');
const uploadPreviewUrl = ref(null);
const libraryPreviewUrl = ref(props.slide.image_url ?? null);
const imageInput = ref(null);
const currentImageUrl = ref(props.slide.image_url);
const showMediaSelector = ref(false);
const mediaUploadInput = ref(null);

const form = useForm({
    _method: 'PUT',
    titre: props.slide.titre || '',
    description: props.slide.description || '',
    new_image: null,
    media_id: props.slide.image_id ?? null,
    actualite_id: props.slide.actualite_id || null,
    texte_cta: props.slide.texte_cta || 'En savoir plus',
    ordre: props.slide.ordre ?? 1,
    visible: props.slide.visible ?? true,
    couleur_texte_titre: props.slide.couleur_texte_titre || '#FFFFFF',
    couleur_cta: props.slide.couleur_cta || '#FFFFFF',
});

const mediaUploadForm = useForm({
    files: [],
    redirect_to: '',
});

const setNewImage = (event) => {
    const file = event.target.files?.[0] ?? null;
    form.new_image = file;
    form.media_id = null;
    mediaMode.value = 'upload';

    if (uploadPreviewUrl.value) {
        URL.revokeObjectURL(uploadPreviewUrl.value);
    }

    uploadPreviewUrl.value = file ? URL.createObjectURL(file) : null;
};

const clearNewImage = () => {
    if (uploadPreviewUrl.value) {
        URL.revokeObjectURL(uploadPreviewUrl.value);
    }

    uploadPreviewUrl.value = null;
    form.new_image = null;

    if (imageInput.value) {
        imageInput.value.value = '';
    }
};

const setMediaUploadFiles = (event) => {
    mediaUploadForm.files = Array.from(event.target.files ?? []);
};

const clearMediaUploadFiles = () => {
    mediaUploadForm.files = [];

    if (mediaUploadInput.value) {
        mediaUploadInput.value.value = '';
    }
};

const mediaUploadError = ref(null);
const mediaUploadSuccess = ref(null);

const submitMediaUpload = async () => {
    if (!mediaUploadForm.files.length || mediaUploadForm.processing) {
        return;
    }

    mediaUploadForm.processing = true;
    mediaUploadError.value = null;
    mediaUploadSuccess.value = null;

    const formData = new FormData();
    mediaUploadForm.files.forEach((file) => {
        formData.append('files[]', file);
    });
    formData.append('ajax', '1');

    try {
        const response = await fetch(route('admin.media.store'), {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                Accept: 'application/json',
            },
        });

        const data = await response.json();

        if (data.success && data.media?.length > 0) {
            mediaUploadSuccess.value = data.message;
            clearMediaUploadFiles();

            // Ajouter les medias uploades au debut de la liste
            props.media.data = [...data.media, ...props.media.data];

            // Auto-selectionner le premier media uploade
            selectImage(data.media[0]);
        } else {
            mediaUploadError.value = data.errors?.join(', ') || 'Erreur lors de l\'upload';
        }
    } catch {
        mediaUploadError.value = 'Erreur de connexion';
    } finally {
        mediaUploadForm.processing = false;
    }
};

const openMediaSelector = () => {
    mediaMode.value = 'library';
    showMediaSelector.value = true;
};

const selectImage = (mediaItem) => {
    form.media_id = mediaItem.id;
    libraryPreviewUrl.value = mediaItem.url;
    showMediaSelector.value = false;
    clearNewImage();
    mediaMode.value = 'library';
};

const changeMediaPage = (link) => {
    if (!link?.url) {
        return;
    }

    const url = new URL(link.url);
    const page = url.searchParams.get('media_page');

    router.get(
        route('admin.slides.edit', { slider: props.slider.id, slide: props.slide.id }),
        { media_page: page },
        {
            only: ['media'],
            preserveState: true,
            preserveScroll: true,
        },
    );
};

const applyUploadedMediaSelection = () => {
    if (!props.uploadedMediaId) {
        return;
    }

    const selected = props.media?.data?.find((mediaItem) => mediaItem.id === props.uploadedMediaId);

    form.media_id = props.uploadedMediaId;
    libraryPreviewUrl.value = selected?.url ?? null;
    clearNewImage();
    mediaMode.value = 'library';
};

const submit = () => {
    form.post(route('admin.slides.update', [props.slider.id, props.slide.id]), {
        preserveScroll: true,
        forceFormData: true,
    });
};

applyUploadedMediaSelection();

onBeforeUnmount(() => {
    if (uploadPreviewUrl.value) {
        URL.revokeObjectURL(uploadPreviewUrl.value);
    }
});
</script>

<template>
    <AdminLayout>
        <template #header>
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 md:text-xl">Modifier le slide</h2>
                    <p class="mt-1 text-xs text-slate-500 md:text-sm">Slider : {{ slider.nom }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.slides.index', slider.id)" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Retour
                    </Link>
                    <button
                        type="button"
                        class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary disabled:cursor-not-allowed disabled:opacity-60"
                        :disabled="form.processing"
                        @click="submit"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Enregistrer
                    </button>
                </div>
            </div>
        </template>

        <Head title="Modifier le slide" />
        <FlashMessage />

        <div class="space-y-6">
            <nav class="text-xs text-slate-500">
                <ol class="flex flex-wrap items-center gap-2">
                    <li><Link :href="route('admin.dashboard')" class="hover:text-ed-primary">Dashboard</Link></li>
                    <li>/</li>
                    <li><Link :href="route('admin.sliders.index')" class="hover:text-ed-primary">Sliders</Link></li>
                    <li>/</li>
                    <li><Link :href="route('admin.slides.index', slider.id)" class="hover:text-ed-primary">{{ slider.nom }}</Link></li>
                    <li>/</li>
                    <li class="font-semibold text-slate-900">Modifier</li>
                </ol>
            </nav>

            <form class="grid grid-cols-1 gap-6 lg:grid-cols-12" @submit.prevent="submit">
                <!-- Contenu principal -->
                <div class="space-y-6 lg:col-span-8">
                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Titre</h3>
                                <p class="mt-1 text-xs text-slate-500">Le titre principal du slide.</p>
                            </div>
                            <span class="text-xs text-slate-400">Obligatoire</span>
                        </div>
                        <div class="mt-4">
                            <input
                                v-model="form.titre"
                                type="text"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                                placeholder="Ex: Bienvenue a l'Ecole Doctorale"
                            />
                            <p v-if="form.errors.titre" class="mt-2 text-xs text-red-600">{{ form.errors.titre }}</p>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Description</h3>
                                <p class="mt-1 text-xs text-slate-500">Texte descriptif affiche sous le titre.</p>
                            </div>
                            <span class="text-xs text-slate-400">Optionnel</span>
                        </div>
                        <div class="mt-4">
                            <textarea
                                v-model="form.description"
                                rows="4"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                                placeholder="Description du slide..."
                            ></textarea>
                            <p v-if="form.errors.description" class="mt-2 text-xs text-red-600">{{ form.errors.description }}</p>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Lien (CTA)</h3>
                                <p class="mt-1 text-xs text-slate-500">Liez le bouton a une actualite.</p>
                            </div>
                            <span class="text-xs text-slate-400">Optionnel</span>
                        </div>
                        <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Actualite liee</label>
                                <select
                                    v-model="form.actualite_id"
                                    class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                                >
                                    <option :value="null">-- Aucune actualite --</option>
                                    <option v-for="actualite in actualites" :key="actualite.id" :value="actualite.id">
                                        {{ actualite.titre }} ({{ actualite.date }})
                                    </option>
                                </select>
                                <p v-if="form.errors.actualite_id" class="mt-2 text-xs text-red-600">{{ form.errors.actualite_id }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Texte du bouton</label>
                                <input
                                    v-model="form.texte_cta"
                                    type="text"
                                    class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                                    placeholder="En savoir plus"
                                />
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Sidebar -->
                <aside class="space-y-6 lg:col-span-4">
                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <h3 class="text-sm font-semibold text-slate-900">Parametres</h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Ordre d'affichage</label>
                                <input
                                    v-model="form.ordre"
                                    type="number"
                                    min="1"
                                    class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                                />
                            </div>

                            <label class="flex items-start gap-3 text-sm">
                                <input v-model="form.visible" type="checkbox" class="mt-0.5 rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20" />
                                <span>
                                    <span class="font-semibold text-slate-800">Visible</span>
                                    <span class="block text-xs text-slate-500">Afficher ce slide sur le site.</span>
                                </span>
                            </label>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <h3 class="text-sm font-semibold text-slate-900">Couleurs</h3>
                        <p class="mt-1 text-xs text-slate-500">Personnalisez les couleurs du texte.</p>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Couleur du titre</label>
                                <div class="mt-2 flex items-center gap-3">
                                    <input
                                        v-model="form.couleur_texte_titre"
                                        type="color"
                                        class="h-10 w-14 cursor-pointer rounded-lg border border-slate-200"
                                    />
                                    <input
                                        v-model="form.couleur_texte_titre"
                                        type="text"
                                        class="flex-1 rounded-xl border border-slate-200 px-3 py-2 text-sm uppercase focus:border-ed-primary focus:ring-ed-primary/20"
                                        placeholder="#FFFFFF"
                                    />
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Couleur du bouton CTA</label>
                                <div class="mt-2 flex items-center gap-3">
                                    <input
                                        v-model="form.couleur_cta"
                                        type="color"
                                        class="h-10 w-14 cursor-pointer rounded-lg border border-slate-200"
                                    />
                                    <input
                                        v-model="form.couleur_cta"
                                        type="text"
                                        class="flex-1 rounded-xl border border-slate-200 px-3 py-2 text-sm uppercase focus:border-ed-primary focus:ring-ed-primary/20"
                                        placeholder="#FFFFFF"
                                    />
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Image</h3>
                                <p class="mt-1 text-xs text-slate-500">Image de fond du slide.</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="inline-flex rounded-xl border border-slate-200 bg-slate-50 p-1 text-xs font-semibold text-slate-600">
                                <button
                                    type="button"
                                    class="rounded-lg px-3 py-1.5 transition"
                                    :class="mediaMode === 'library' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500'"
                                    @click="mediaMode = 'library'"
                                >
                                    Mediatheque
                                </button>
                                <button
                                    type="button"
                                    class="rounded-lg px-3 py-1.5 transition"
                                    :class="mediaMode === 'upload' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500'"
                                    @click="mediaMode = 'upload'"
                                >
                                    Uploader
                                </button>
                            </div>

                            <div v-if="mediaMode === 'library'" class="mt-4 space-y-4">
                                <div class="flex items-center gap-2">
                                    <button type="button" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50" @click="openMediaSelector">
                                        Selectionner dans la mediatheque
                                    </button>
                                    <Link :href="route('admin.media.upload')" target="_blank" class="text-xs font-semibold text-ed-primary hover:text-ed-secondary">
                                        Uploader dans la mediatheque
                                    </Link>
                                </div>
                                <div v-if="libraryPreviewUrl" class="relative overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                    <img :src="libraryPreviewUrl" alt="Image selectionnee" class="h-44 w-full object-cover" />
                                    <span class="absolute left-2 top-2 rounded-lg bg-slate-900/70 px-2 py-1 text-xs font-medium text-white">
                                        {{ form.media_id === props.slide.image_id ? 'Actuelle' : 'Selectionnee' }}
                                    </span>
                                </div>
                            </div>

                            <div v-else class="relative mt-4">
                                <button v-if="uploadPreviewUrl" type="button" class="mb-3 text-xs font-semibold text-red-600 hover:text-red-700" @click="clearNewImage">
                                    Retirer
                                </button>
                                <div v-if="uploadPreviewUrl" class="relative overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                    <img :src="uploadPreviewUrl" alt="Apercu" class="h-44 w-full object-cover" />
                                    <span class="absolute left-2 top-2 rounded-lg bg-emerald-600 px-2 py-1 text-xs font-medium text-white">Nouvelle</span>
                                </div>
                                <div v-else-if="currentImageUrl" class="relative overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                    <img :src="currentImageUrl" alt="Image actuelle" class="h-44 w-full object-cover" />
                                    <span class="absolute left-2 top-2 rounded-lg bg-slate-900/70 px-2 py-1 text-xs font-medium text-white">Actuelle</span>
                                </div>
                                <div class="relative mt-3">
                                    <input
                                        ref="imageInput"
                                        type="file"
                                        accept="image/*"
                                        class="absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0"
                                        @change="setNewImage"
                                    />
                                    <div class="w-full rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-6 text-center">
                                        <svg class="mx-auto h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="mt-2 text-sm font-semibold text-slate-700">{{ currentImageUrl ? 'Changer l\'image' : 'Choisir une image' }}</p>
                                        <p class="mt-1 text-xs text-slate-500">PNG, JPG jusqu'a 5 Mo</p>
                                    </div>
                                </div>
                            </div>
                            <p v-if="form.errors.new_image" class="mt-2 text-xs text-red-600">{{ form.errors.new_image }}</p>
                            <p v-if="form.errors.media_id" class="mt-2 text-xs text-red-600">{{ form.errors.media_id }}</p>
                        </div>
                    </section>
                </aside>
            </form>
        </div>

        <!-- Sticky bottom bar -->
        <div class="sticky bottom-4 z-20 mt-8">
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white/95 px-4 py-3 shadow-lg backdrop-blur">
                <p class="text-xs text-slate-500">Assurez-vous de sauvegarder vos changements.</p>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.slides.index', slider.id)" class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                        Annuler
                    </Link>
                    <button
                        type="button"
                        class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2 text-xs font-semibold text-white hover:bg-ed-secondary"
                        :disabled="form.processing"
                        @click="submit"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Enregistrer
                    </button>
                </div>
            </div>
        </div>

        <div v-if="showMediaSelector" class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-slate-900/40" @click="showMediaSelector = false"></div>
            <div class="relative w-full max-w-4xl rounded-2xl bg-white p-6 shadow-xl">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h3 class="text-lg font-semibold text-slate-900">Selectionner une image</h3>
                    <button type="button" class="rounded-xl border border-slate-200 px-3 py-2 text-sm" @click="showMediaSelector = false">Fermer</button>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                    <button
                        v-for="mediaItem in media.data"
                        :key="mediaItem.id"
                        type="button"
                        class="group overflow-hidden rounded-xl border border-slate-200 text-left transition hover:border-ed-primary/50"
                        @click="selectImage(mediaItem)"
                    >
                        <img :src="mediaItem.url" alt="" class="h-28 w-full object-cover transition group-hover:opacity-90" />
                        <div class="px-3 py-2 text-xs text-slate-600">
                            {{ mediaItem.nom }}
                        </div>
                    </button>
                </div>
                <div class="mt-6 rounded-xl border border-dashed border-slate-200 bg-slate-50 p-4">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold text-slate-700">Uploader dans la mediatheque</p>
                            <p class="text-xs text-slate-500">Selectionnez une ou plusieurs images.</p>
                        </div>
                        <button
                            type="button"
                            class="rounded-xl bg-ed-primary px-3 py-2 text-xs font-semibold text-white hover:bg-ed-secondary disabled:cursor-not-allowed disabled:opacity-60"
                            :disabled="mediaUploadForm.processing || !mediaUploadForm.files.length"
                            @click="submitMediaUpload"
                        >
                            <span v-if="mediaUploadForm.processing">Envoi...</span>
                            <span v-else>Uploader</span>
                        </button>
                    </div>
                    <div class="mt-3">
                        <input
                            ref="mediaUploadInput"
                            type="file"
                            accept="image/*"
                            multiple
                            class="block w-full text-xs text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-white file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-slate-700 hover:file:bg-slate-100"
                            @change="setMediaUploadFiles"
                        />
                        <div v-if="mediaUploadForm.files.length" class="mt-2 text-xs text-slate-500">
                            {{ mediaUploadForm.files.map((file) => file.name).join(', ') }}
                        </div>
                        <p v-if="mediaUploadError" class="mt-2 text-xs text-red-600">{{ mediaUploadError }}</p>
                        <p v-if="mediaUploadSuccess" class="mt-2 text-xs text-emerald-600">{{ mediaUploadSuccess }}</p>
                    </div>
                </div>
                <div v-if="!media.data.length" class="mt-6 rounded-xl border border-dashed border-slate-200 bg-slate-50 p-6 text-center text-sm text-slate-500">
                    Aucune image dans la mediatheque.
                </div>
                <div v-if="media.links" class="mt-6 flex flex-wrap items-center justify-center gap-2">
                    <button
                        v-for="link in media.links"
                        :key="link.label"
                        type="button"
                        class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50"
                        :disabled="!link.url"
                        v-html="link.label"
                        @click="changeMediaPage(link)"
                    ></button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
