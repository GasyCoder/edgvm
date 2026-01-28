<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { onBeforeUnmount, ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const props = defineProps({
    message: Object,
});

const form = useForm({
    nom: props.message?.nom ?? '',
    fonction: props.message?.fonction ?? '',
    institution: props.message?.institution ?? '',
    telephone: props.message?.telephone ?? '',
    email: props.message?.email ?? '',
    citation: props.message?.citation ?? '',
    message_intro: props.message?.message_intro ?? '',
    visible: props.message?.visible ?? true,
    photo: null,
});

const photoPreviewUrl = ref(null);
const photoInput = ref(null);
const photoDragging = ref(false);

const handlePhoto = (file) => {
    if (!file || !file.type.startsWith('image/')) {
        return;
    }

    form.photo = file;

    if (photoPreviewUrl.value) {
        URL.revokeObjectURL(photoPreviewUrl.value);
    }

    photoPreviewUrl.value = URL.createObjectURL(file);
};

const setPhoto = (event) => handlePhoto(event.target.files?.[0] ?? null);

const onDrop = (event) => {
    event.preventDefault();
    photoDragging.value = false;
    handlePhoto(event.dataTransfer?.files?.[0] ?? null);
};

const clearPhoto = () => {
    form.photo = null;
    if (photoPreviewUrl.value) {
        URL.revokeObjectURL(photoPreviewUrl.value);
    }
    photoPreviewUrl.value = null;
    if (photoInput.value) {
        photoInput.value.value = '';
    }
};

onBeforeUnmount(() => {
    if (photoPreviewUrl.value) {
        URL.revokeObjectURL(photoPreviewUrl.value);
    }
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        visible: data.visible ? 1 : 0,
        photo: data.photo || undefined,
        _method: 'put',
    })).post(route('admin.message-directions.update', props.message.id), {
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
                    <h2 class="text-lg font-semibold text-slate-900 md:text-xl">Modifier le message</h2>
                    <p class="mt-1 text-xs text-slate-500 md:text-sm">Mettre a jour les informations.</p>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.message-directions.index')" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Retour</Link>
                    <button type="button" class="rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary" :disabled="form.processing" @click="submit">Enregistrer</button>
                </div>
            </div>
        </template>

        <Head title="Modifier message" />
        <FlashMessage />

        <div class="space-y-6">
            <nav class="text-xs text-slate-500">
                <ol class="flex flex-wrap items-center gap-2">
                    <li><Link :href="route('admin.dashboard')" class="hover:text-ed-primary">Dashboard</Link></li>
                    <li>/</li>
                    <li><Link :href="route('admin.message-directions.index')" class="hover:text-ed-primary">Message direction</Link></li>
                    <li>/</li>
                    <li class="font-semibold text-slate-900">Modifier</li>
                </ol>
            </nav>
            <form class="grid grid-cols-1 gap-6 lg:grid-cols-12" @submit.prevent="submit">
                <div class="space-y-6 lg:col-span-8">
                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Nom *</label>
                                <input v-model="form.nom" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Fonction</label>
                                <input v-model="form.fonction" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Institution</label>
                                <input v-model="form.institution" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Telephone</label>
                                <input v-model="form.telephone" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-slate-700">Email</label>
                                <input v-model="form.email" type="email" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="text-xs font-semibold text-slate-700">Citation</label>
                            <textarea v-model="form.citation" rows="3" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20"></textarea>
                        </div>
                        <div class="mt-4">
                            <label class="text-xs font-semibold text-slate-700">Message</label>
                            <textarea v-model="form.message_intro" rows="4" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20"></textarea>
                        </div>
                    </section>
                </div>

                <aside class="space-y-6 lg:col-span-4">
                    <section class="rounded-2xl border border-slate-100 bg-white p-6">
                        <h3 class="text-sm font-semibold text-slate-900">Visibilite</h3>
                        <div class="mt-4 space-y-3">
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
                        <h3 class="text-sm font-semibold text-slate-900">Photo</h3>
                        <div class="mt-4 space-y-3">
                            <div
                                class="relative rounded-xl border-2 border-dashed transition-all duration-200"
                                :class="photoDragging ? 'border-ed-primary bg-ed-primary/5' : 'border-slate-200 hover:border-slate-300'"
                                @dragover.prevent="photoDragging = true"
                                @dragleave.prevent="photoDragging = false"
                                @drop="onDrop"
                            >
                                <div v-if="photoPreviewUrl || message.photo_url" class="p-4">
                                    <div class="space-y-3">
                                        <div class="overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                            <img :src="photoPreviewUrl || message.photo_url" alt="" class="h-40 w-full object-cover" />
                                        </div>
                                        <div class="flex items-center justify-between gap-3">
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium text-slate-700 truncate">
                                                    {{ photoPreviewUrl ? 'Nouvelle photo selectionnee' : 'Photo actuelle' }}
                                                </p>
                                                <p class="text-xs text-slate-500">Cliquez ou deposez pour remplacer</p>
                                            </div>
                                            <button
                                                v-if="photoPreviewUrl"
                                                type="button"
                                                class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-red-50 hover:text-red-500"
                                                @click="clearPhoto"
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
                                    <p class="mt-2 text-sm font-medium text-slate-600">Deposez la photo ici</p>
                                    <p class="text-xs text-slate-400">PNG, JPG, WEBP · 2MB max</p>
                                </div>
                                <input
                                    ref="photoInput"
                                    type="file"
                                    accept="image/*"
                                    class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                                    @change="setPhoto"
                                />
                            </div>
                            <p v-if="form.errors.photo" class="text-xs text-red-600">{{ form.errors.photo }}</p>
                        </div>
                    </section>
                </aside>
            </form>
        </div>

        <div class="sticky bottom-4 z-20 mt-8">
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white/95 px-4 py-3 shadow-lg backdrop-blur">
                <p class="text-xs text-slate-500">Pensez a enregistrer vos modifications.</p>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.message-directions.index')" class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                        Annuler
                    </Link>
                    <button type="button" class="rounded-xl bg-ed-primary px-4 py-2 text-xs font-semibold text-white hover:bg-ed-secondary" :disabled="form.processing" @click="submit">
                        Mettre a jour
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
