<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const props = defineProps({
    categories: Array,
    tags: Array,
    media: Object,
    defaults: Object,
});

const showMediaSelector = ref(false);
const mediaSelectorType = ref('featured');
const selectedImageUrl = ref(null);
const galerieImagesData = ref([]);
const editorId = 'actualite-create-editor';

const form = useForm({
    titre: '',
    contenu: '',
    category_ids: [],
    selectedTags: [],
    image_id: null,
    galerieImages: [],
    date_publication: props.defaults?.date_publication ?? '',
    visible: props.defaults?.visible ?? true,
    est_important: props.defaults?.est_important ?? false,
    notifier_abonnes: props.defaults?.notifier_abonnes ?? false,
});

const initTinyMce = () => {
    if (!window.tinymce) return;
    window.tinymce.remove();
    window.tinymce.init({
        selector: `#${editorId}`,
        height: 520,
        menubar: false,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount',
        ],
        toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link image | code | help',
        content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; }',
        setup: (editor) => {
            editor.on('init', () => {
                editor.setContent(form.contenu || '');
            });
            editor.on('change', () => {
                form.contenu = editor.getContent();
            });
        },
    });
};

onMounted(() => initTinyMce());
onBeforeUnmount(() => {
    if (window.tinymce) {
        window.tinymce.remove();
    }
});

const openMediaSelector = (type) => {
    mediaSelectorType.value = type;
    showMediaSelector.value = true;
};

const selectImage = (media) => {
    if (mediaSelectorType.value === 'featured') {
        form.image_id = media.id;
        selectedImageUrl.value = media.url;
    } else {
        if (!form.galerieImages.includes(media.id)) {
            form.galerieImages.push(media.id);
            galerieImagesData.value.push({ id: media.id, url: media.url });
        }
    }
    showMediaSelector.value = false;
};

const removeImage = () => {
    form.image_id = null;
    selectedImageUrl.value = null;
};

const removeGalerieImage = (id) => {
    form.galerieImages = form.galerieImages.filter((value) => value !== id);
    galerieImagesData.value = galerieImagesData.value.filter((item) => item.id !== id);
};

const changeMediaPage = (link) => {
    if (!link.url) return;
    const url = new URL(link.url);
    const page = url.searchParams.get('media_page');
    router.get(route('admin.actualites.create'), { media_page: page }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        only: ['media'],
    });
};

const submit = () => {
    form.post(route('admin.actualites.store'), {
        preserveScroll: true,
    });
};

</script>

<template>
    <AdminLayout>
        <template #header>
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 md:text-xl">Nouvelle actualite</h2>
                    <p class="mt-1 text-xs text-slate-500 md:text-sm">
                        Redigez le contenu, configurez la publication et selectionnez vos medias.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.actualites.index')" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Retour
                    </Link>
                <button
                    type="button"
                    class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary disabled:cursor-not-allowed disabled:opacity-60"
                    :disabled="form.processing"
                    @click="submit"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                    </svg>
                    Creer
                </button>
            </div>
        </div>
        </template>

        <Head title="Nouvelle actualite" />
        <FlashMessage />

        <div class="space-y-6">
            <nav class="text-xs text-slate-500">
                <ol class="flex flex-wrap items-center gap-2">
                    <li><Link :href="route('admin.dashboard')" class="hover:text-ed-primary">Dashboard</Link></li>
                    <li>/</li>
                    <li><Link :href="route('admin.actualites.index')" class="hover:text-ed-primary">Actualites</Link></li>
                    <li>/</li>
                    <li class="font-semibold text-slate-900">Nouvelle</li>
                </ol>
            </nav>

            <form class="grid grid-cols-1 gap-6 lg:grid-cols-12" @submit.prevent="submit">
                <div class="space-y-6 lg:col-span-8">
                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Titre</h3>
                                <p class="mt-1 text-xs text-slate-500">Le titre apparait dans les listes et en detail.</p>
                            </div>
                            <span class="text-xs text-slate-400">Obligatoire</span>
                        </div>
                        <div class="mt-4">
                            <input
                                v-model="form.titre"
                                type="text"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                                placeholder="Titre de l'actualite"
                            />
                            <p v-if="form.errors.titre" class="mt-2 text-xs text-red-600">{{ form.errors.titre }}</p>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Contenu</h3>
                                <p class="mt-1 text-xs text-slate-500">Texte riche via TinyMCE.</p>
                            </div>
                            <span class="text-xs text-slate-400">Obligatoire</span>
                        </div>
                        <div class="mt-4">
                            <textarea :id="editorId" class="w-full"></textarea>
                            <p v-if="form.errors.contenu" class="mt-2 text-xs text-red-600">{{ form.errors.contenu }}</p>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Galerie</h3>
                                <p class="mt-1 text-xs text-slate-500">Images supplementaires de l'article.</p>
                            </div>
                            <button type="button" class="rounded-xl border border-slate-200 px-3.5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50" @click="openMediaSelector('gallery')">
                                Ajouter
                            </button>
                        </div>
                        <div class="mt-4">
                            <div v-if="galerieImagesData.length" class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                                <div v-for="media in galerieImagesData" :key="media.id" class="group relative overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                    <img :src="media.url" alt="" class="h-28 w-full object-cover" />
                                    <button type="button" class="absolute right-2 top-2 inline-flex items-center justify-center rounded-full border border-slate-200 bg-white/90 p-2 text-slate-700 opacity-0 transition group-hover:opacity-100" @click="removeGalerieImage(media.id)">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 011-1h4a1 1 0 011 1m-7 0h8" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div v-else class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">
                                Aucune image dans la galerie.
                            </div>
                        </div>
                    </section>
                </div>

                <aside class="space-y-6 lg:col-span-4">
                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <h3 class="text-sm font-semibold text-slate-900">Parametres</h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Categories</label>
                                <div class="mt-3 max-h-48 space-y-1 overflow-y-auto rounded-xl border border-slate-200 p-2">
                                    <label v-for="category in categories" :key="category.id" class="flex items-center gap-2 rounded-lg px-2.5 py-2 text-sm hover:bg-slate-50">
                                        <input v-model="form.category_ids" type="checkbox" :value="category.id" class="rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20" />
                                        <span class="text-slate-700">{{ category.nom }}</span>
                                    </label>
                                </div>
                                <p class="mt-2 text-xs text-slate-500">Optionnel pour filtrer.</p>
                                <p v-if="form.errors.category_ids" class="mt-2 text-xs text-red-600">{{ form.errors.category_ids }}</p>
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-slate-700">Date de publication</label>
                                <input v-model="form.date_publication" type="date" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                            </div>

                            <label class="flex items-start gap-3 text-sm">
                                <input v-model="form.visible" type="checkbox" class="mt-0.5 rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20" />
                                <span>
                                    <span class="font-semibold text-slate-800">Publier immediatement</span>
                                    <span class="block text-xs text-slate-500">Visible sur le site public.</span>
                                </span>
                            </label>

                            <label class="flex items-start gap-3 text-sm">
                                <input v-model="form.est_important" type="checkbox" class="mt-0.5 rounded border-slate-300 text-red-600 focus:ring-red-600/20" />
                                <span>
                                    <span class="font-semibold text-slate-800">Actualite importante</span>
                                    <span class="block text-xs text-slate-500">Mise en avant homepage.</span>
                                </span>
                            </label>

                            <label class="flex items-start gap-3 text-sm">
                                <input v-model="form.notifier_abonnes" type="checkbox" class="mt-0.5 rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20" />
                                <span>
                                    <span class="font-semibold text-slate-800">Notifier les abonnes</span>
                                    <span class="block text-xs text-slate-500">Email envoye si visible.</span>
                                </span>
                            </label>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Tags</h3>
                                <p class="mt-1 text-xs text-slate-500">Optionnel pour filtrer.</p>
                            </div>
                        </div>
                        <div class="mt-4 max-h-52 space-y-1 overflow-y-auto rounded-xl border border-slate-200 p-2">
                            <label v-for="tag in tags" :key="tag.id" class="flex items-center gap-2 rounded-lg px-2.5 py-2 text-sm hover:bg-slate-50">
                                <input v-model="form.selectedTags" type="checkbox" :value="tag.id" class="rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20" />
                                <span class="text-slate-700">{{ tag.nom }}</span>
                            </label>
                            <p v-if="!tags.length" class="py-6 text-center text-sm text-slate-500">Aucun tag disponible.</p>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Image principale</h3>
                                <p class="mt-1 text-xs text-slate-500">Vignette de l'article.</p>
                            </div>
                            <button v-if="!selectedImageUrl" type="button" class="text-xs font-semibold text-ed-primary hover:text-ed-secondary" @click="openMediaSelector('featured')">
                                Selectionner
                            </button>
                        </div>
                        <div class="mt-4">
                            <div v-if="selectedImageUrl" class="relative overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                <img :src="selectedImageUrl" alt="" class="h-44 w-full object-cover" />
                                <button type="button" class="absolute right-2 top-2 rounded-xl border border-slate-200 bg-white/90 p-2 text-slate-700" @click="removeImage">
                                    X
                                </button>
                            </div>
                            <button v-else type="button" class="w-full rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-10 text-sm font-semibold text-slate-700 hover:border-ed-primary/50 hover:text-ed-primary" @click="openMediaSelector('featured')">
                                Choisir une image
                            </button>
                        </div>
                    </section>
                </aside>
            </form>
        </div>

        <div v-if="showMediaSelector" class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-slate-900/40" @click="showMediaSelector = false"></div>
            <div class="relative w-full max-w-4xl rounded-2xl bg-white p-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">
                        {{ mediaSelectorType === 'featured' ? "Selectionner l'image principale" : 'Ajouter des images a la galerie' }}
                    </h3>
                    <button type="button" class="rounded-xl border border-slate-200 px-3 py-2 text-sm" @click="showMediaSelector = false">Fermer</button>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                    <button
                        v-for="mediaItem in media.data"
                        :key="mediaItem.id"
                        type="button"
                        class="group relative overflow-hidden rounded-xl border border-slate-200"
                        @click="selectImage(mediaItem)"
                    >
                        <img :src="mediaItem.url" class="h-28 w-full object-cover transition group-hover:opacity-90" />
                    </button>
                </div>
                <div class="mt-4 flex flex-wrap gap-2">
                    <button
                        v-for="link in media.links"
                        :key="link.label"
                        type="button"
                        class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs"
                        :class="link.active ? 'bg-ed-primary text-white border-ed-primary' : 'text-slate-600'"
                        :disabled="!link.url"
                        @click="changeMediaPage(link)"
                        v-html="link.label"
                    ></button>
                </div>
                <div class="mt-4 border-t border-slate-200 pt-4">
                    <Link :href="route('admin.media.upload')" target="_blank" class="text-sm font-semibold text-ed-primary hover:text-ed-secondary">
                        Uploader une nouvelle image
                    </Link>
                </div>
            </div>
        </div>

        <div class="sticky bottom-4 z-20 mt-8">
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white/95 px-4 py-3 shadow-lg backdrop-blur">
                <p class="text-xs text-slate-500">Assurez-vous de sauvegarder vos changements.</p>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.actualites.index')" class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                        Annuler
                    </Link>
                    <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2 text-xs font-semibold text-white hover:bg-ed-secondary" :disabled="form.processing" @click="submit">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                        Creer l'actualite
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
