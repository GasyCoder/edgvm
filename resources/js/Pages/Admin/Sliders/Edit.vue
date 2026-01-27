<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const props = defineProps({
    slider: Object,
});

const form = useForm({
    nom: props.slider?.nom ?? '',
    position: props.slider?.position ?? 'homepage',
    visible: props.slider?.visible ?? true,
});

const submit = () => {
    form.put(route('admin.sliders.update', props.slider.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AdminLayout>
        <template #header>
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 md:text-xl">Modifier le slider</h2>
                    <p class="mt-1 text-xs text-slate-500 md:text-sm">Mettez a jour les informations.</p>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.sliders.index')" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Retour</Link>
                    <button type="button" class="rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary" :disabled="form.processing" @click="submit">Enregistrer</button>
                </div>
            </div>
        </template>

        <Head title="Modifier slider" />
        <FlashMessage />

        <div class="space-y-6">
            <form class="max-w-3xl space-y-6" @submit.prevent="submit">
                <section class="rounded-2xl border border-slate-100 bg-white p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Nom *</label>
                            <input v-model="form.nom" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                            <p v-if="form.errors.nom" class="mt-2 text-xs text-red-600">{{ form.errors.nom }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Position *</label>
                            <input v-model="form.position" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                            <p v-if="form.errors.position" class="mt-2 text-xs text-red-600">{{ form.errors.position }}</p>
                        </div>
                        <label class="flex items-start gap-3 text-sm">
                            <input v-model="form.visible" type="checkbox" class="mt-0.5 rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20" />
                            <span>
                                <span class="font-semibold text-slate-800">Visible</span>
                                <span class="block text-xs text-slate-500">Afficher le slider sur le site.</span>
                            </span>
                        </label>
                    </div>
                </section>
            </form>
        </div>
    </AdminLayout>
</template>
