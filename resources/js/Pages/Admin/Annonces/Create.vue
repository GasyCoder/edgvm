<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const props = defineProps({
    medias: Array,
});

const editorId = 'annonce-create-editor';

const form = useForm({
    titre: '',
    contenu_html: '',
    cible: 'both',
    media_id: null,
    media_file: null,
    est_publie: false,
    envoyer_email: false,
    email_cible: 'both',
});

const mediaMode = ref('library');
const mediaPreviewUrl = ref(null);
const mediaInput = ref(null);
const mediaDragging = ref(false);

const selectMedia = () => {
    if (!form.media_id) {
        mediaPreviewUrl.value = null;
        return;
    }
    const selected = props.medias?.find((media) => media.id === form.media_id);
    mediaPreviewUrl.value = selected?.url ?? null;
};

const handleMediaFile = (file) => {
    if (!file) {
        return;
    }

    form.media_file = file;
    form.media_id = null;
    mediaMode.value = 'upload';

    if (mediaPreviewUrl.value) {
        URL.revokeObjectURL(mediaPreviewUrl.value);
    }

    mediaPreviewUrl.value = file.type?.startsWith('image/') ? URL.createObjectURL(file) : null;
};

const onDrop = (event) => {
    event.preventDefault();
    mediaDragging.value = false;
    handleMediaFile(event.dataTransfer?.files?.[0] ?? null);
};

const clearMediaFile = () => {
    form.media_file = null;
    if (mediaPreviewUrl.value) {
        URL.revokeObjectURL(mediaPreviewUrl.value);
    }
    mediaPreviewUrl.value = null;
    if (mediaInput.value) {
        mediaInput.value.value = '';
    }
};

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
                editor.setContent(form.contenu_html || '');
            });
            editor.on('change', () => {
                form.contenu_html = editor.getContent();
            });
        },
    });
};

onMounted(() => initTinyMce());
onBeforeUnmount(() => {
    if (window.tinymce) {
        window.tinymce.remove();
    }
    if (mediaPreviewUrl.value) {
        URL.revokeObjectURL(mediaPreviewUrl.value);
    }
});

const submit = () => {
    form.post(route('admin.annonces.store'), {
        preserveScroll: true,
        forceFormData: Boolean(form.media_file),
    });
};
</script>

<template>
    <AdminLayout>
        <template #header>
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 md:text-xl">Nouvelle annonce</h2>
                    <p class="mt-1 text-xs text-slate-500 md:text-sm">Rédigez une annonce et planifiez l'envoi.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary" :disabled="form.processing" @click="submit">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                        </svg>
                        Creer
                    </button>
                </div>
            </div>
        </template>

        <Head title="Nouvelle annonce" />
        <FlashMessage />

        <div class="space-y-6">
            <nav class="flex flex-wrap items-center justify-between gap-3 text-xs text-slate-500">
                <ol class="flex flex-wrap items-center gap-2">
                    <li><Link :href="route('admin.dashboard')" class="hover:text-ed-primary">Dashboard</Link></li>
                    <li>/</li>
                    <li><Link :href="route('admin.annonces.index')" class="hover:text-ed-primary">Annonces</Link></li>
                    <li>/</li>
                    <li class="font-semibold text-slate-900">Nouvelle</li>
                </ol>
                <Link :href="route('admin.annonces.index')" class="rounded-xl border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                    Retour
                </Link>
            </nav>
            <form class="grid grid-cols-1 gap-6 lg:grid-cols-12" @submit.prevent="submit">
                <div class="space-y-6 lg:col-span-8">
                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Titre *</label>
                                <input v-model="form.titre" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20" placeholder="Titre de l'annonce" />
                                <p v-if="form.errors.titre" class="mt-2 text-xs text-red-600">{{ form.errors.titre }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Contenu</label>
                                <textarea :id="editorId" class="w-full"></textarea>
                                <p v-if="form.errors.contenu_html" class="mt-2 text-xs text-red-600">{{ form.errors.contenu_html }}</p>
                            </div>
                        </div>
                    </section>
                </div>

                <aside class="space-y-6 lg:col-span-4">
                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <h3 class="text-sm font-semibold text-slate-900">Cible</h3>
                        <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
                            <label class="group flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-ed-primary/40 hover:bg-ed-primary/5">
                                <input v-model="form.cible" type="radio" value="doctorant" class="peer sr-only" />
                                <span class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-500 transition group-hover:border-ed-primary/30 group-hover:text-ed-primary peer-checked:border-ed-primary peer-checked:bg-ed-primary/10 peer-checked:text-ed-primary">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0120 17.5c0 2.485-3.582 4.5-8 4.5s-8-2.015-8-4.5c0-2.02 2.315-3.76 5.84-4.578L12 14z" />
                                    </svg>
                                </span>
                                <span class="flex flex-col">
                                    <span class="text-sm font-semibold">Doctorants</span>
                                    <span class="text-xs text-slate-500">Tous les doctorants</span>
                                </span>
                            </label>
                            <label class="group flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-ed-primary/40 hover:bg-ed-primary/5">
                                <input v-model="form.cible" type="radio" value="encadrant" class="peer sr-only" />
                                <span class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-500 transition group-hover:border-ed-primary/30 group-hover:text-ed-primary peer-checked:border-ed-primary peer-checked:bg-ed-primary/10 peer-checked:text-ed-primary">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20H4v-2a4 4 0 014-4h1" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </span>
                                <span class="flex flex-col">
                                    <span class="text-sm font-semibold">Encadrants</span>
                                    <span class="text-xs text-slate-500">Tous les encadrants</span>
                                </span>
                            </label>
                            <label class="group flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-ed-primary/40 hover:bg-ed-primary/5">
                                <input v-model="form.cible" type="radio" value="both" class="peer sr-only" />
                                <span class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-500 transition group-hover:border-ed-primary/30 group-hover:text-ed-primary peer-checked:border-ed-primary peer-checked:bg-ed-primary/10 peer-checked:text-ed-primary">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
                                    </svg>
                                </span>
                                <span class="flex flex-col">
                                    <span class="text-sm font-semibold">Tous</span>
                                    <span class="text-xs text-slate-500">Doctorants et encadrants</span>
                                </span>
                            </label>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <h3 class="text-sm font-semibold text-slate-900">Media associe</h3>
                        <div class="mt-4 space-y-4">
                            <div class="inline-flex rounded-xl border border-slate-200 bg-slate-50 p-1 text-xs font-semibold text-slate-600">
                                <button
                                    type="button"
                                    class="rounded-lg px-3 py-1.5 transition"
                                    :class="mediaMode === 'library' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500'"
                                    @click="mediaMode = 'library'"
                                >
                                    Bibliotheque
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

                            <div v-if="mediaMode === 'library'">
                                <select v-model="form.media_id" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20" @change="selectMedia">
                                    <option :value="null">Aucun</option>
                                    <option v-for="media in medias" :key="media.id" :value="media.id">
                                        {{ media.nom }} ({{ media.type }})
                                    </option>
                                </select>
                                <div v-if="mediaPreviewUrl" class="mt-3 overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                    <img :src="mediaPreviewUrl" alt="" class="h-32 w-full object-cover" />
                                </div>
                            </div>

                            <div v-else>
                                <div
                                    class="relative rounded-xl border-2 border-dashed transition-all duration-200"
                                    :class="mediaDragging ? 'border-ed-primary bg-ed-primary/5' : 'border-slate-200 hover:border-slate-300'"
                                    @dragover.prevent="mediaDragging = true"
                                    @dragleave.prevent="mediaDragging = false"
                                    @drop="onDrop"
                                >
                                    <div class="p-4">
                                        <div v-if="form.media_file" class="space-y-3">
                                            <div v-if="mediaPreviewUrl" class="overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                                <img :src="mediaPreviewUrl" alt="" class="h-32 w-full object-cover" />
                                            </div>
                                            <div class="flex items-center justify-between gap-3">
                                                <div class="min-w-0">
                                                    <p class="text-sm font-medium text-slate-700 truncate">{{ form.media_file.name }}</p>
                                                    <p class="text-xs text-slate-500">Cliquez ou deposez pour remplacer</p>
                                                </div>
                                                <button
                                                    type="button"
                                                    class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-red-50 hover:text-red-500"
                                                    @click="clearMediaFile"
                                                >
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div v-else class="text-center">
                                            <svg class="mx-auto h-9 w-9 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="mt-2 text-sm font-medium text-slate-600">Deposez un fichier ici</p>
                                            <p class="text-xs text-slate-400">Images, documents ou video (10 MB max)</p>
                                        </div>
                                    </div>
                                    <input
                                        ref="mediaInput"
                                        type="file"
                                        class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                                        @change="(e) => handleMediaFile(e.target.files?.[0] ?? null)"
                                    />
                                </div>
                            </div>
                            <p v-if="form.errors.media_file" class="text-xs text-red-600">{{ form.errors.media_file }}</p>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <h3 class="text-sm font-semibold text-slate-900">Publication & email</h3>
                        <div class="mt-4 space-y-4">
                            <label class="flex items-start gap-3 text-sm">
                                <input v-model="form.est_publie" type="checkbox" class="mt-0.5 rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20" />
                                <span>
                                    <span class="font-semibold text-slate-800">Publier</span>
                                    <span class="block text-xs text-slate-500">Publier immediatement.</span>
                                </span>
                            </label>
                            <label class="flex items-start gap-3 text-sm">
                                <input v-model="form.envoyer_email" type="checkbox" class="mt-0.5 rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20" />
                                <span>
                                    <span class="font-semibold text-slate-800">Envoyer email</span>
                                    <span class="block text-xs text-slate-500">Declencher l'envoi.</span>
                                </span>
                            </label>
                            <div v-if="form.envoyer_email">
                                <label class="text-xs font-semibold text-slate-700">Email cible</label>
                                <select v-model="form.email_cible" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20">
                                    <option value="doctorant">Doctorants</option>
                                    <option value="encadrant">Encadrants</option>
                                    <option value="both">Tous</option>
                                </select>
                            </div>
                        </div>
                    </section>
                </aside>
            </form>
        </div>

    </AdminLayout>
</template>
