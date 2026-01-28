<script setup>
import { onBeforeUnmount, ref, watch } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const props = defineProps({
    types: Object,
    documents: Array,
    filters: Object,
    defaults: Object,
});

const pdfSearch = ref(props.filters?.pdfSearch ?? '');
const imagePreviewUrl = ref(null);
const coverInput = ref(null);
const coverDragging = ref(false);
const selectedDocument = ref(null);

const form = useForm({
    titre: '',
    description: '',
    date_debut: props.defaults?.date_debut ?? '',
    heure_debut: '',
    date_fin: '',
    heure_fin: '',
    lieu: '',
    type: props.defaults?.type ?? 'autre',
    est_important: props.defaults?.est_important ?? false,
    lien_inscription: '',
    est_publie: props.defaults?.est_publie ?? true,
    cover_image: null,
    document_media_id: null,
    notify_all: false,
    notify_encadrants: false,
    notify_doctorants: false,
});

const applyPdfSearch = () => {
    router.get(route('admin.evenements.create'), {
        pdfSearch: pdfSearch.value || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        only: ['documents', 'filters'],
    });
};

let searchTimeout = null;
watch(pdfSearch, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyPdfSearch();
    }, 300);
});

const handleCoverFile = (file) => {
    if (!file) {
        return;
    }
    form.cover_image = file;
    if (imagePreviewUrl.value) {
        URL.revokeObjectURL(imagePreviewUrl.value);
    }
    imagePreviewUrl.value = URL.createObjectURL(file);
};

const setCoverImage = (event) => {
    handleCoverFile(event.target.files?.[0] ?? null);
};

const onCoverDrop = (event) => {
    event.preventDefault();
    coverDragging.value = false;
    handleCoverFile(event.dataTransfer?.files?.[0] ?? null);
};

const removeCoverImage = () => {
    if (imagePreviewUrl.value) {
        URL.revokeObjectURL(imagePreviewUrl.value);
    }

    imagePreviewUrl.value = null;
    form.cover_image = null;

    if (coverInput.value) {
        coverInput.value.value = '';
    }
};

const selectDocument = (document) => {
    form.document_media_id = document.id;
    selectedDocument.value = document;
};

const clearDocument = () => {
    form.document_media_id = null;
    selectedDocument.value = null;
};

const submit = () => {
    form.post(route('admin.evenements.store'), {
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
                    <h2 class="text-lg font-semibold text-slate-900 md:text-xl">Nouvel evenement</h2>
                    <p class="mt-1 text-xs text-slate-500 md:text-sm">
                        Planifiez un rendez-vous et partagez-le avec la communaute.
                    </p>
                </div>
                <div></div>
            </div>
        </template>

        <Head title="Nouvel evenement" />
        <FlashMessage />

        <div class="space-y-6">
            <nav class="flex flex-wrap items-center justify-between gap-3 text-xs text-slate-500">
                <ol class="flex flex-wrap items-center gap-2">
                    <li><Link :href="route('admin.dashboard')" class="hover:text-ed-primary">Dashboard</Link></li>
                    <li>/</li>
                    <li><Link :href="route('admin.evenements.index')" class="hover:text-ed-primary">Evenements</Link></li>
                    <li>/</li>
                    <li class="font-semibold text-slate-900">Nouveau</li>
                </ol>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.evenements.index')" class="rounded-xl border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                        Retour
                    </Link>
                    <button
                        type="button"
                        class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-3 py-1.5 text-xs font-semibold text-white hover:bg-ed-secondary disabled:cursor-not-allowed disabled:opacity-60"
                        :disabled="form.processing"
                        @click="submit"
                    >
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                        </svg>
                        Creer
                    </button>
                </div>
            </nav>

            <form class="grid grid-cols-1 gap-6 lg:grid-cols-12" @submit.prevent="submit">
                <div class="space-y-6 lg:col-span-8">
                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Informations principales</h3>
                                <p class="mt-1 text-xs text-slate-500">Titre, type et description.</p>
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
                                    placeholder="Titre de l'evenement"
                                />
                                <p v-if="form.errors.titre" class="mt-2 text-xs text-red-600">{{ form.errors.titre }}</p>
                            </div>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="text-xs font-semibold text-slate-700">Type *</label>
                                    <select v-model="form.type" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20">
                                        <option v-for="(label, value) in types" :key="value" :value="value">
                                            {{ label }}
                                        </option>
                                    </select>
                                    <p v-if="form.errors.type" class="mt-2 text-xs text-red-600">{{ form.errors.type }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-slate-700">Lieu</label>
                                    <input
                                        v-model="form.lieu"
                                        type="text"
                                        class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                                        placeholder="Lieu ou visioconference"
                                    />
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Description</label>
                                <textarea
                                    v-model="form.description"
                                    rows="5"
                                    class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                                    placeholder="Resume de l'evenement"
                                ></textarea>
                                <p v-if="form.errors.description" class="mt-2 text-xs text-red-600">{{ form.errors.description }}</p>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Calendrier</h3>
                                <p class="mt-1 text-xs text-slate-500">Dates et horaires.</p>
                            </div>
                        </div>
                        <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Date de debut *</label>
                                <input v-model="form.date_debut" type="date" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                                <p v-if="form.errors.date_debut" class="mt-2 text-xs text-red-600">{{ form.errors.date_debut }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Heure debut</label>
                                <input v-model="form.heure_debut" type="time" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Date de fin</label>
                                <input v-model="form.date_fin" type="date" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                                <p v-if="form.errors.date_fin" class="mt-2 text-xs text-red-600">{{ form.errors.date_fin }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Heure fin</label>
                                <input v-model="form.heure_fin" type="time" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                            </div>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Image de couverture</h3>
                                <p class="mt-1 text-xs text-slate-500">Optionnel, format image.</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div
                                class="relative rounded-xl border-2 border-dashed transition-all duration-200"
                                :class="coverDragging ? 'border-ed-primary bg-ed-primary/5' : 'border-slate-200 hover:border-slate-300'"
                                @dragover.prevent="coverDragging = true"
                                @dragleave.prevent="coverDragging = false"
                                @drop="onCoverDrop"
                            >
                                <div class="p-4">
                                    <div v-if="imagePreviewUrl" class="space-y-3">
                                        <div class="overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                            <img :src="imagePreviewUrl" alt="" class="h-48 w-full object-cover" />
                                        </div>
                                        <div class="flex items-center justify-between gap-3">
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium text-slate-700 truncate">{{ form.cover_image?.name }}</p>
                                                <p class="text-xs text-slate-500">Cliquez ou deposez pour remplacer</p>
                                            </div>
                                            <button
                                                type="button"
                                                class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-red-50 hover:text-red-500"
                                                @click="removeCoverImage"
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
                                        <p class="mt-2 text-sm font-medium text-slate-600">Deposez une image ici</p>
                                        <p class="text-xs text-slate-400">PNG, JPG jusqu'a 2MB</p>
                                    </div>
                                </div>
                                <input
                                    ref="coverInput"
                                    type="file"
                                    accept="image/*"
                                    class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                                    @change="setCoverImage"
                                />
                            </div>
                            <p v-if="form.errors.cover_image" class="mt-2 text-xs text-red-600">{{ form.errors.cover_image }}</p>
                        </div>
                    </section>
                </div>

                <aside class="space-y-6 lg:col-span-4">
                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <h3 class="text-sm font-semibold text-slate-900">Publication</h3>
                        <div class="mt-4 space-y-4">
                            <label class="flex items-start gap-3 text-sm">
                                <input v-model="form.est_publie" type="checkbox" class="mt-0.5 rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20" />
                                <span>
                                    <span class="font-semibold text-slate-800">Publier immediatement</span>
                                    <span class="block text-xs text-slate-500">Visible sur le site public.</span>
                                </span>
                            </label>
                            <label class="flex items-start gap-3 text-sm">
                                <input v-model="form.est_important" type="checkbox" class="mt-0.5 rounded border-slate-300 text-red-600 focus:ring-red-600/20" />
                                <span>
                                    <span class="font-semibold text-slate-800">Evenement important</span>
                                    <span class="block text-xs text-slate-500">Mise en avant.</span>
                                </span>
                            </label>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <h3 class="text-sm font-semibold text-slate-900">Lien d'inscription</h3>
                        <div class="mt-4">
                            <input
                                v-model="form.lien_inscription"
                                type="url"
                                class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                                placeholder="https://..."
                            />
                            <p v-if="form.errors.lien_inscription" class="mt-2 text-xs text-red-600">{{ form.errors.lien_inscription }}</p>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Document PDF</h3>
                                <p class="mt-1 text-xs text-slate-500">Associer un fichier existant.</p>
                            </div>
                        </div>
                        <div class="mt-4 space-y-4">
                            <input
                                v-model="pdfSearch"
                                type="text"
                                class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                                placeholder="Rechercher un document..."
                            />

                            <div v-if="selectedDocument" class="rounded-xl border border-emerald-200 bg-emerald-50 p-3">
                                <p class="text-xs font-semibold text-emerald-700">Document selectionne</p>
                                <p class="mt-1 text-sm text-emerald-800">{{ selectedDocument.nom }}</p>
                                <button type="button" class="mt-2 text-xs font-semibold text-emerald-700" @click="clearDocument">Retirer</button>
                            </div>

                            <div v-if="pdfSearch && !selectedDocument" class="max-h-56 space-y-2 overflow-y-auto rounded-xl border border-slate-200 p-2 text-sm">
                                <button
                                    v-for="document in documents"
                                    :key="document.id"
                                    type="button"
                                    class="flex w-full items-center justify-between rounded-lg px-2.5 py-2 text-left hover:bg-slate-50"
                                    @click="selectDocument(document)"
                                >
                                    <span class="text-slate-700">{{ document.nom }}</span>
                                    <span class="text-xs text-slate-400">PDF</span>
                                </button>
                                <p v-if="!documents.length" class="py-6 text-center text-xs text-slate-400">Aucun resultat.</p>
                            </div>
                            <div v-else class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-6 text-center text-xs text-slate-500">
                                Tapez pour rechercher un fichier PDF.
                            </div>
                            <p v-if="form.errors.document_media_id" class="text-xs text-red-600">{{ form.errors.document_media_id }}</p>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <h3 class="text-sm font-semibold text-slate-900">Notification newsletter</h3>
                        <div class="mt-4 space-y-3 text-sm">
                            <label class="flex items-start gap-3">
                                <input v-model="form.notify_all" type="checkbox" class="mt-0.5 rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20" />
                                <span>
                                    <span class="font-semibold text-slate-800">Tous les abonnes</span>
                                    <span class="block text-xs text-slate-500">Envoi global.</span>
                                </span>
                            </label>
                            <label class="flex items-start gap-3">
                                <input v-model="form.notify_encadrants" type="checkbox" class="mt-0.5 rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20" :disabled="form.notify_all" />
                                <span>
                                    <span class="font-semibold text-slate-800">Encadrants</span>
                                    <span class="block text-xs text-slate-500">Uniquement encadrants.</span>
                                </span>
                            </label>
                            <label class="flex items-start gap-3">
                                <input v-model="form.notify_doctorants" type="checkbox" class="mt-0.5 rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20" :disabled="form.notify_all" />
                                <span>
                                    <span class="font-semibold text-slate-800">Doctorants</span>
                                    <span class="block text-xs text-slate-500">Uniquement doctorants.</span>
                                </span>
                            </label>
                        </div>
                    </section>
                </aside>
            </form>
        </div>

    </AdminLayout>
</template>
