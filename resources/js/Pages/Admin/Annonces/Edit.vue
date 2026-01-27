<script setup>
import { onBeforeUnmount, onMounted } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const props = defineProps({
    annonce: Object,
    medias: Array,
});

const editorId = 'annonce-edit-editor';

const form = useForm({
    titre: props.annonce?.titre ?? '',
    contenu_html: props.annonce?.contenu_html ?? '',
    cible: props.annonce?.cible ?? 'both',
    media_id: props.annonce?.media_id ?? null,
    est_publie: props.annonce?.est_publie ?? false,
    envoyer_email: props.annonce?.envoyer_email ?? false,
    email_cible: props.annonce?.email_cible ?? 'both',
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
});

const submit = () => {
    form.put(route('admin.annonces.update', props.annonce.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AdminLayout>
        <template #header>
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 md:text-xl">Modifier l'annonce</h2>
                    <p class="mt-1 text-xs text-slate-500 md:text-sm">Mettre a jour le contenu et l'envoi.</p>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.annonces.index')" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Retour</Link>
                    <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary" :disabled="form.processing" @click="submit">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                        Enregistrer
                    </button>
                </div>
            </div>
        </template>

        <Head title="Modifier annonce" />
        <FlashMessage />

        <div class="space-y-6">
            <form class="grid grid-cols-1 gap-6 lg:grid-cols-12" @submit.prevent="submit">
                <div class="space-y-6 lg:col-span-8">
                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Titre *</label>
                                <input v-model="form.titre" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
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
                        <div class="mt-4 space-y-3">
                            <label class="text-sm"><input v-model="form.cible" type="radio" value="doctorant" class="mr-2" />Doctorants</label>
                            <label class="text-sm"><input v-model="form.cible" type="radio" value="encadrant" class="mr-2" />Encadrants</label>
                            <label class="text-sm"><input v-model="form.cible" type="radio" value="both" class="mr-2" />Tous</label>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <h3 class="text-sm font-semibold text-slate-900">Media associe</h3>
                        <div class="mt-4">
                            <select v-model="form.media_id" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20">
                                <option :value="null">Aucun</option>
                                <option v-for="media in medias" :key="media.id" :value="media.id">
                                    {{ media.nom }} ({{ media.type }})
                                </option>
                            </select>
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

        <div class="sticky bottom-4 z-20 mt-8">
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white/95 px-4 py-3 shadow-lg backdrop-blur">
                <p class="text-xs text-slate-500">Sauvegardez avant d'envoyer l'annonce.</p>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.annonces.index')" class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                        Annuler
                    </Link>
                    <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2 text-xs font-semibold text-white hover:bg-ed-secondary" :disabled="form.processing" @click="submit">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                        Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
