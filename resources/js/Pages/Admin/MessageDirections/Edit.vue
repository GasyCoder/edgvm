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

const setPhoto = (event) => {
    const file = event.target.files?.[0] ?? null;
    form.photo = file;

    if (photoPreviewUrl.value) {
        URL.revokeObjectURL(photoPreviewUrl.value);
    }

    photoPreviewUrl.value = file ? URL.createObjectURL(file) : null;
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
                            <div v-if="message.photo_url" class="overflow-hidden rounded-xl border border-slate-200">
                                <img :src="message.photo_url" alt="" class="h-40 w-full object-cover" />
                            </div>
                            <div v-if="photoPreviewUrl" class="overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                <img :src="photoPreviewUrl" alt="" class="h-40 w-full object-cover" />
                            </div>
                            <input type="file" accept="image/*" class="text-sm" @change="setPhoto" />
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
