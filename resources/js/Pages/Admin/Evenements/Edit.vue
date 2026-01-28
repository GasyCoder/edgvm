<script setup>
import { onBeforeUnmount, ref, watch } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const props = defineProps({
    evenement: Object,
    types: Object,
    documents: Array,
    selectedDocument: Object,
    filters: Object,
});

const pdfSearch = ref(props.filters?.pdfSearch ?? '');
const imagePreviewUrl = ref(null);
const coverInput = ref(null);
const selectedDocument = ref(props.selectedDocument ?? null);
const slugTouched = ref(Boolean(props.evenement?.slug));

const form = useForm({
    titre: props.evenement?.titre ?? '',
    slug: props.evenement?.slug ?? '',
    description: props.evenement?.description ?? '',
    date_debut: props.evenement?.date_debut ?? '',
    heure_debut: props.evenement?.heure_debut ?? '',
    date_fin: props.evenement?.date_fin ?? '',
    heure_fin: props.evenement?.heure_fin ?? '',
    lieu: props.evenement?.lieu ?? '',
    type: props.evenement?.type ?? 'autre',
    est_important: props.evenement?.est_important ?? false,
    lien_inscription: props.evenement?.lien_inscription ?? '',
    est_publie: props.evenement?.est_publie ?? true,
    cover_image: null,
    document_media_id: props.evenement?.document_media_id ?? null,
});

const applyPdfSearch = () => {
    router.get(route('admin.evenements.edit', props.evenement.id), {
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

const setCoverImage = (event) => {
    const file = event.target.files?.[0] ?? null;
    form.cover_image = file;

    if (imagePreviewUrl.value) {
        URL.revokeObjectURL(imagePreviewUrl.value);
    }

    imagePreviewUrl.value = file ? URL.createObjectURL(file) : null;
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
    form.put(route('admin.evenements.update', props.evenement.id), {
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
                    <h2 class="text-lg font-semibold text-slate-900 md:text-xl">Modifier l'evenement</h2>
                    <p class="mt-1 text-xs text-slate-500 md:text-sm">
                        Mettez a jour les details et documents associes.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <Link
                        :href="route('admin.evenements.index')"
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                        Enregistrer
                    </button>
                </div>
            </div>
        </template>

        <Head title="Modifier evenement" />
        <FlashMessage />

        <div class="space-y-6">
            <nav class="text-xs text-slate-500">
                <ol class="flex flex-wrap items-center gap-2">
                    <li><Link :href="route('admin.dashboard')" class="hover:text-ed-primary">Dashboard</Link></li>
                    <li>/</li>
                    <li><Link :href="route('admin.evenements.index')" class="hover:text-ed-primary">Evenements</Link></li>
                    <li>/</li>
                    <li class="font-semibold text-slate-900">Edition</li>
                </ol>
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
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Slug</label>
                                <input
                                    v-model="form.slug"
                                    type="text"
                                    class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20"
                                    placeholder="slug-evenement"
                                    @input="onSlugInput"
                                />
                                <p class="mt-1 text-xs text-slate-500">Laisse vide pour generer automatiquement.</p>
                                <p v-if="form.errors.slug" class="mt-2 text-xs text-red-600">{{ form.errors.slug }}</p>
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
                                <p class="mt-1 text-xs text-slate-500">Ajouter une nouvelle image si besoin.</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div v-if="imagePreviewUrl" class="relative overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                <img :src="imagePreviewUrl" alt="" class="h-48 w-full object-cover" />
                                <button type="button" class="absolute right-2 top-2 rounded-xl border border-slate-200 bg-white/90 p-2 text-slate-700" @click="removeCoverImage">
                                    Retirer
                                </button>
                            </div>
                            <div v-else-if="evenement.image_url" class="relative overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                <img :src="evenement.image_url" alt="" class="h-48 w-full object-cover" />
                            </div>
                            <div class="mt-4 flex items-center justify-between gap-4 rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-6">
                                <div>
                                    <p class="text-sm font-semibold text-slate-700">Choisir une nouvelle image</p>
                                    <p class="mt-1 text-xs text-slate-500">PNG, JPG jusqu'a 2MB.</p>
                                </div>
                                <input ref="coverInput" type="file" accept="image/*" class="text-sm" @change="setCoverImage" />
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

                            <div class="max-h-56 space-y-2 overflow-y-auto rounded-xl border border-slate-200 p-2 text-sm">
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
                                <p v-if="!documents.length" class="py-6 text-center text-xs text-slate-400">Aucun document disponible.</p>
                            </div>
                            <p v-if="form.errors.document_media_id" class="text-xs text-red-600">{{ form.errors.document_media_id }}</p>
                        </div>
                    </section>
                </aside>
            </form>
        </div>

        <div class="sticky bottom-4 z-20 mt-8">
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white/95 px-4 py-3 shadow-lg backdrop-blur">
                <p class="text-xs text-slate-500">Sauvegardez avant de quitter cette page.</p>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.evenements.index')" class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
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
