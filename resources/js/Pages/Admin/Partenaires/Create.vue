<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { onBeforeUnmount, ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const props = defineProps({
    defaults: Object,
});

const form = useForm({
    nom: '',
    description: '',
    url: '',
    ordre: props.defaults?.ordre ?? 1,
    visible: props.defaults?.visible ?? true,
    logo: null,
});

const logoPreviewUrl = ref(null);
const logoInput = ref(null);
const logoDragging = ref(false);

const handleLogo = (file) => {
    if (!file || !file.type.startsWith('image/')) {
        return;
    }

    form.logo = file;

    if (logoPreviewUrl.value) {
        URL.revokeObjectURL(logoPreviewUrl.value);
    }

    logoPreviewUrl.value = URL.createObjectURL(file);
};

const setLogo = (event) => handleLogo(event.target.files?.[0] ?? null);

const onDrop = (event) => {
    event.preventDefault();
    logoDragging.value = false;
    handleLogo(event.dataTransfer?.files?.[0] ?? null);
};

const clearLogo = () => {
    form.logo = null;
    if (logoPreviewUrl.value) {
        URL.revokeObjectURL(logoPreviewUrl.value);
    }
    logoPreviewUrl.value = null;
    if (logoInput.value) {
        logoInput.value.value = '';
    }
};

onBeforeUnmount(() => {
    if (logoPreviewUrl.value) {
        URL.revokeObjectURL(logoPreviewUrl.value);
    }
});

const submit = () => {
    form.post(route('admin.partenaires.store'), {
        preserveScroll: true,
        forceFormData: true,
    });
};
</script>

<template>
    <AdminLayout>
        <template #header>
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 md:text-xl">Nouveau partenaire</h2>
                    <p class="mt-1 text-xs text-slate-500 md:text-sm">Ajouter un partenaire.</p>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.partenaires.index')" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Retour</Link>
                    <button type="button" class="rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary" :disabled="form.processing" @click="submit">Creer</button>
                </div>
            </div>
        </template>

        <Head title="Nouveau partenaire" />
        <FlashMessage />

        <div class="space-y-6">
            <form class="grid grid-cols-1 gap-6 lg:grid-cols-12" @submit.prevent="submit">
                <div class="space-y-6 lg:col-span-8">
                    <section class="rounded-2xl border border-slate-100 bg-white p-6 space-y-4">
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Nom *</label>
                            <input v-model="form.nom" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                            <p v-if="form.errors.nom" class="mt-2 text-xs text-red-600">{{ form.errors.nom }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Description</label>
                            <textarea v-model="form.description" rows="4" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20"></textarea>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Site web</label>
                            <input v-model="form.url" type="url" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20" placeholder="https://..." />
                            <p v-if="form.errors.url" class="mt-2 text-xs text-red-600">{{ form.errors.url }}</p>
                        </div>
                    </section>
                </div>

                <aside class="space-y-6 lg:col-span-4">
                    <section class="rounded-2xl border border-slate-100 bg-white p-6 space-y-4">
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Ordre</label>
                            <input v-model="form.ordre" type="number" min="1" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                        </div>
                        <label class="flex items-start gap-3 text-sm">
                            <input v-model="form.visible" type="checkbox" class="mt-0.5 rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20" />
                            <span>
                                <span class="font-semibold text-slate-800">Visible</span>
                                <span class="block text-xs text-slate-500">Afficher sur le site.</span>
                            </span>
                        </label>
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Logo</label>
                            <p class="mt-1 text-xs text-slate-500">Ajoutez un logo pour l'affichage public.</p>
                            <div
                                class="mt-3 relative rounded-xl border-2 border-dashed transition-all duration-200"
                                :class="logoDragging ? 'border-ed-primary bg-ed-primary/5' : 'border-slate-200 hover:border-slate-300'"
                                @dragover.prevent="logoDragging = true"
                                @dragleave.prevent="logoDragging = false"
                                @drop="onDrop"
                            >
                                <div v-if="logoPreviewUrl" class="p-4">
                                    <div class="space-y-3">
                                        <div class="overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                            <img :src="logoPreviewUrl" alt="" class="h-32 w-full object-contain" />
                                        </div>
                                        <div class="flex items-center justify-between gap-3">
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium text-slate-700 truncate">Logo selectionne</p>
                                                <p class="text-xs text-slate-500">Cliquez ou deposez pour remplacer</p>
                                            </div>
                                            <button
                                                type="button"
                                                class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-red-50 hover:text-red-500"
                                                @click="clearLogo"
                                            >
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="p-6 text-center">
                                    <svg class="mx-auto h-10 w-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="mt-2 text-sm font-medium text-slate-600">Deposez votre logo ici</p>
                                    <p class="text-xs text-slate-400">PNG, JPG, WEBP · 2MB max</p>
                                </div>
                                <input
                                    ref="logoInput"
                                    type="file"
                                    accept="image/*"
                                    class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                                    @change="setLogo"
                                />
                            </div>
                            <p v-if="form.errors.logo" class="mt-2 text-xs text-red-600">{{ form.errors.logo }}</p>
                        </div>
                    </section>
                </aside>
            </form>
        </div>

        <div class="sticky bottom-4 z-20 mt-8">
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white/95 px-4 py-3 shadow-lg backdrop-blur">
                <p class="text-xs text-slate-500">Pensez a enregistrer vos modifications.</p>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.partenaires.index')" class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                        Annuler
                    </Link>
                    <button type="button" class="rounded-xl bg-ed-primary px-4 py-2 text-xs font-semibold text-white hover:bg-ed-secondary" :disabled="form.processing" @click="submit">
                        Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
