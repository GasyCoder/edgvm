<script setup>
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const props = defineProps({
    menus: Array,
});

const editorId = 'page-create-editor';
const slugTouched = ref(false);

const form = useForm({
    titre: '',
    slug: '',
    contenu: '',
    visible: true,
    ordre: 0,
    attach_to_menu: false,
    menu_id: null,
    menu_label: '',
});

const slugify = (value) => {
    return value
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/(^-|-$)+/g, '');
};

watch(() => form.titre, (value) => {
    if (!slugTouched.value) {
        form.slug = slugify(value || '');
    }
});

const onSlugInput = () => {
    slugTouched.value = true;
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

const submit = () => {
    form.post(route('admin.pages.store'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AdminLayout>
        <template #header>
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 md:text-xl">Nouvelle page</h2>
                    <p class="mt-1 text-xs text-slate-500 md:text-sm">
                        Configurez le titre, l'URL et le contenu principal.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <Link
                        :href="route('admin.pages.index')"
                        class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                    >
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

        <Head title="Nouvelle page" />
        <FlashMessage />

        <div class="space-y-6">
            <nav class="text-xs text-slate-500">
                <ol class="flex flex-wrap items-center gap-2">
                    <li><Link :href="route('admin.dashboard')" class="hover:text-ed-primary">Dashboard</Link></li>
                    <li>/</li>
                    <li><Link :href="route('admin.pages.index')" class="hover:text-ed-primary">Pages</Link></li>
                    <li>/</li>
                    <li class="font-semibold text-slate-900">Nouvelle</li>
                </ol>
            </nav>

            <form class="grid grid-cols-1 gap-6 lg:grid-cols-12" @submit.prevent="submit">
                <div class="space-y-6 lg:col-span-8">
                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Titre et URL</h3>
                                <p class="mt-1 text-xs text-slate-500">Le slug determine l'adresse de la page.</p>
                            </div>
                            <span class="text-xs text-slate-400">Obligatoire</span>
                        </div>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Titre *</label>
                                <input
                                    v-model="form.titre"
                                    type="text"
                                    class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                                    placeholder="Titre de la page"
                                />
                                <p v-if="form.errors.titre" class="mt-2 text-xs text-red-600">{{ form.errors.titre }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Slug *</label>
                                <input
                                    v-model="form.slug"
                                    type="text"
                                    class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                                    placeholder="ex: a-propos"
                                    @input="onSlugInput"
                                />
                                <p v-if="form.errors.slug" class="mt-2 text-xs text-red-600">{{ form.errors.slug }}</p>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Contenu principal</h3>
                                <p class="mt-1 text-xs text-slate-500">Editeur TinyMCE pour la page.</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <textarea :id="editorId" class="w-full"></textarea>
                            <p v-if="form.errors.contenu" class="mt-2 text-xs text-red-600">{{ form.errors.contenu }}</p>
                        </div>
                    </section>
                </div>

                <aside class="space-y-6 lg:col-span-4">
                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <h3 class="text-sm font-semibold text-slate-900">Parametres</h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Ordre</label>
                                <input
                                    v-model="form.ordre"
                                    type="number"
                                    min="0"
                                    class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                                />
                            </div>
                            <label class="flex items-start gap-3 text-sm">
                                <input v-model="form.visible" type="checkbox" class="mt-0.5 rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20" />
                                <span>
                                    <span class="font-semibold text-slate-800">Visible</span>
                                    <span class="block text-xs text-slate-500">Afficher la page sur le site.</span>
                                </span>
                            </label>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <h3 class="text-sm font-semibold text-slate-900">Menu</h3>
                        <div class="mt-4 space-y-4">
                            <label class="flex items-start gap-3 text-sm">
                                <input v-model="form.attach_to_menu" type="checkbox" class="mt-0.5 rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20" />
                                <span>
                                    <span class="font-semibold text-slate-800">Afficher dans un menu</span>
                                    <span class="block text-xs text-slate-500">Ajoute un lien automatique.</span>
                                </span>
                            </label>

                            <div v-if="form.attach_to_menu" class="space-y-3">
                                <div>
                                    <label class="text-xs font-semibold text-slate-700">Menu *</label>
                                    <select v-model="form.menu_id" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20">
                                        <option :value="null">Selectionner</option>
                                        <option v-for="menu in menus" :key="menu.id" :value="menu.id">
                                            {{ menu.nom }}
                                        </option>
                                    </select>
                                    <p v-if="form.errors.menu_id" class="mt-2 text-xs text-red-600">{{ form.errors.menu_id }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-slate-700">Libelle du menu</label>
                                    <input
                                        v-model="form.menu_label"
                                        type="text"
                                        class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                                        placeholder="Nom du lien"
                                    />
                                </div>
                            </div>
                        </div>
                    </section>
                </aside>
            </form>
        </div>

        <div class="sticky bottom-4 z-20 mt-8">
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white/95 px-4 py-3 shadow-lg backdrop-blur">
                <p class="text-xs text-slate-500">Finalisez la page avant publication.</p>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.pages.index')" class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                        Annuler
                    </Link>
                    <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2 text-xs font-semibold text-white hover:bg-ed-secondary" :disabled="form.processing" @click="submit">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                        Creer la page
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
