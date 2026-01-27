<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const props = defineProps({
    page: Object,
    menus: Array,
    menuState: Object,
    images: Array,
});

const editorId = 'page-edit-editor';
const showDeleteSectionModal = ref(false);
const sectionToDelete = ref(null);
const showEditSection = ref(false);

const pageForm = useForm({
    titre: props.page?.titre ?? '',
    slug: props.page?.slug ?? '',
    contenu: props.page?.contenu ?? '',
    visible: props.page?.visible ?? true,
    ordre: props.page?.ordre ?? 0,
    attach_to_menu: props.menuState?.attach_to_menu ?? false,
    menu_id: props.menuState?.menu_id ?? null,
    menu_label: props.menuState?.menu_label ?? '',
});

const newSectionForm = useForm({
    titre: '',
    contenu: '',
    image_id: null,
    ordre: props.page?.sections?.length ?? 0,
});

const editSectionForm = useForm({
    id: null,
    titre: '',
    contenu: '',
    image_id: null,
    ordre: 0,
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
                editor.setContent(pageForm.contenu || '');
            });
            editor.on('change', () => {
                pageForm.contenu = editor.getContent();
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
    pageForm.put(route('admin.pages.update', props.page.id), {
        preserveScroll: true,
    });
};

const addSection = () => {
    newSectionForm.post(route('admin.pages.sections.store', props.page.id), {
        preserveScroll: true,
        onSuccess: () => {
            newSectionForm.reset();
            newSectionForm.ordre = props.page?.sections?.length ?? 0;
        },
    });
};

const editSection = (section) => {
    editSectionForm.id = section.id;
    editSectionForm.titre = section.titre ?? '';
    editSectionForm.contenu = section.contenu ?? '';
    editSectionForm.image_id = section.image_id ?? null;
    editSectionForm.ordre = section.ordre ?? 0;
    showEditSection.value = true;
};

const updateSection = () => {
    if (!editSectionForm.id) return;

    editSectionForm.put(route('admin.pages.sections.update', {
        page: props.page.id,
        section: editSectionForm.id,
    }), {
        preserveScroll: true,
        onSuccess: () => {
            showEditSection.value = false;
        },
    });
};

const confirmDeleteSection = (section) => {
    sectionToDelete.value = section;
    showDeleteSectionModal.value = true;
};

const deleteSection = () => {
    if (!sectionToDelete.value) return;

    editSectionForm.delete(route('admin.pages.sections.destroy', {
        page: props.page.id,
        section: sectionToDelete.value.id,
    }), {
        preserveScroll: true,
        onFinish: () => {
            showDeleteSectionModal.value = false;
            sectionToDelete.value = null;
        },
    });
};
</script>

<template>
    <AdminLayout>
        <template #header>
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 md:text-xl">Modifier la page</h2>
                    <p class="mt-1 text-xs text-slate-500 md:text-sm">
                        Mettre a jour le contenu principal et les sections.
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
                        :disabled="pageForm.processing"
                        @click="submit"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                        Enregistrer
                    </button>
                </div>
            </div>
        </template>

        <Head title="Modifier page" />
        <FlashMessage />

        <div class="space-y-6">
            <nav class="text-xs text-slate-500">
                <ol class="flex flex-wrap items-center gap-2">
                    <li><Link :href="route('admin.dashboard')" class="hover:text-ed-primary">Dashboard</Link></li>
                    <li>/</li>
                    <li><Link :href="route('admin.pages.index')" class="hover:text-ed-primary">Pages</Link></li>
                    <li>/</li>
                    <li class="font-semibold text-slate-900">Edition</li>
                </ol>
            </nav>

            <form class="grid grid-cols-1 gap-6 lg:grid-cols-12" @submit.prevent="submit">
                <div class="space-y-6 lg:col-span-8">
                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Titre et URL</h3>
                                <p class="mt-1 text-xs text-slate-500">Le slug peut etre modifie manuellement.</p>
                            </div>
                            <span class="text-xs text-slate-400">Obligatoire</span>
                        </div>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Titre *</label>
                                <input
                                    v-model="pageForm.titre"
                                    type="text"
                                    class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                                />
                                <p v-if="pageForm.errors.titre" class="mt-2 text-xs text-red-600">{{ pageForm.errors.titre }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Slug *</label>
                                <input
                                    v-model="pageForm.slug"
                                    type="text"
                                    class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                                />
                                <p v-if="pageForm.errors.slug" class="mt-2 text-xs text-red-600">{{ pageForm.errors.slug }}</p>
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
                            <p v-if="pageForm.errors.contenu" class="mt-2 text-xs text-red-600">{{ pageForm.errors.contenu }}</p>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Sections</h3>
                                <p class="mt-1 text-xs text-slate-500">Ajoutez des blocs secondaires.</p>
                            </div>
                        </div>
                        <div class="mt-4 space-y-4">
                            <div class="grid grid-cols-1 gap-3">
                                <div class="space-y-2">
                                    <label class="text-xs font-semibold text-slate-700">Titre section</label>
                                    <input
                                        v-model="newSectionForm.titre"
                                        type="text"
                                        class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                                        placeholder="Titre optionnel"
                                    />
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-semibold text-slate-700">Contenu</label>
                                    <textarea
                                        v-model="newSectionForm.contenu"
                                        rows="3"
                                        class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                                    ></textarea>
                                </div>
                                <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                    <div>
                                        <label class="text-xs font-semibold text-slate-700">Image</label>
                                        <select v-model="newSectionForm.image_id" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20">
                                            <option :value="null">Aucune</option>
                                            <option v-for="image in images" :key="image.id" :value="image.id">
                                                {{ image.nom }}
                                            </option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs font-semibold text-slate-700">Ordre</label>
                                        <input v-model="newSectionForm.ordre" type="number" min="0" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    class="mt-2 w-full rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary"
                                    :disabled="newSectionForm.processing"
                                    @click="addSection"
                                >
                                    Ajouter la section
                                </button>
                            </div>

                            <div class="space-y-3">
                                <div
                                    v-for="section in page.sections"
                                    :key="section.id"
                                    class="rounded-xl border border-slate-200 p-4"
                                >
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900">{{ section.titre || 'Section sans titre' }}</p>
                                            <p class="mt-1 text-xs text-slate-500">Ordre {{ section.ordre }}</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button
                                                type="button"
                                                class="rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50"
                                                @click="editSection(section)"
                                            >
                                                Modifier
                                            </button>
                                            <button
                                                type="button"
                                                class="rounded-lg border border-red-200 px-2.5 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50"
                                                @click="confirmDeleteSection(section)"
                                            >
                                                Supprimer
                                            </button>
                                        </div>
                                    </div>
                                    <p v-if="section.contenu" class="mt-2 text-xs text-slate-500 line-clamp-2">
                                        {{ section.contenu }}
                                    </p>
                                </div>
                                <p v-if="!page.sections.length" class="text-sm text-slate-500">Aucune section pour le moment.</p>
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
                                <input
                                    v-model="pageForm.ordre"
                                    type="number"
                                    min="0"
                                    class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                                />
                            </div>
                            <label class="flex items-start gap-3 text-sm">
                                <input v-model="pageForm.visible" type="checkbox" class="mt-0.5 rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20" />
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
                                <input v-model="pageForm.attach_to_menu" type="checkbox" class="mt-0.5 rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20" />
                                <span>
                                    <span class="font-semibold text-slate-800">Afficher dans un menu</span>
                                    <span class="block text-xs text-slate-500">Synchronise le lien dans les menus.</span>
                                </span>
                            </label>

                            <div v-if="pageForm.attach_to_menu" class="space-y-3">
                                <div>
                                    <label class="text-xs font-semibold text-slate-700">Menu *</label>
                                    <select v-model="pageForm.menu_id" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20">
                                        <option :value="null">Selectionner</option>
                                        <option v-for="menu in menus" :key="menu.id" :value="menu.id">
                                            {{ menu.nom }}
                                        </option>
                                    </select>
                                    <p v-if="pageForm.errors.menu_id" class="mt-2 text-xs text-red-600">{{ pageForm.errors.menu_id }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-slate-700">Libelle du menu</label>
                                    <input
                                        v-model="pageForm.menu_label"
                                        type="text"
                                        class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                                    />
                                </div>
                            </div>
                        </div>
                    </section>
                </aside>
            </form>
        </div>

        <div v-if="showEditSection" class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-slate-900/40" @click="showEditSection = false"></div>
            <div class="relative w-full max-w-lg rounded-2xl bg-white p-6">
                <h3 class="text-lg font-semibold text-slate-900">Modifier la section</h3>
                <div class="mt-4 space-y-3">
                    <div>
                        <label class="text-xs font-semibold text-slate-700">Titre</label>
                        <input v-model="editSectionForm.titre" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-700">Contenu</label>
                        <textarea v-model="editSectionForm.contenu" rows="4" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20"></textarea>
                    </div>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Image</label>
                            <select v-model="editSectionForm.image_id" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20">
                                <option :value="null">Aucune</option>
                                <option v-for="image in images" :key="image.id" :value="image.id">
                                    {{ image.nom }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Ordre</label>
                            <input v-model="editSectionForm.ordre" type="number" min="0" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700" @click="showEditSection = false">Annuler</button>
                    <button type="button" class="rounded-xl bg-ed-primary px-4 py-2 text-sm font-semibold text-white hover:bg-ed-secondary" @click="updateSection">Enregistrer</button>
                </div>
            </div>
        </div>

        <div class="sticky bottom-4 z-20 mt-8">
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white/95 px-4 py-3 shadow-lg backdrop-blur">
                <p class="text-xs text-slate-500">Sauvegardez la page et ses sections.</p>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.pages.index')" class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                        Annuler
                    </Link>
                    <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2 text-xs font-semibold text-white hover:bg-ed-secondary" :disabled="pageForm.processing" @click="submit">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                        Enregistrer
                    </button>
                </div>
            </div>
        </div>

        <div v-if="showDeleteSectionModal" class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-slate-900/40" @click="showDeleteSectionModal = false"></div>
            <div class="relative w-full max-w-md rounded-2xl bg-white p-6">
                <h3 class="text-lg font-semibold text-slate-900">Supprimer la section</h3>
                <p class="mt-2 text-sm text-slate-600">Cette section sera supprimee definitivement.</p>
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700" @click="showDeleteSectionModal = false">Annuler</button>
                    <button type="button" class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700" @click="deleteSection">Supprimer</button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
