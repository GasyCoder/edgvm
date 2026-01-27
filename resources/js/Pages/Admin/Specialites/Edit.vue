<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const props = defineProps({
    specialite: Object,
    eads: Array,
});

const slugTouched = ref(false);

const form = useForm({
    nom: props.specialite?.nom || '',
    slug: props.specialite?.slug || '',
    description: props.specialite?.description || '',
    ead_id: props.specialite?.ead_id || '',
});

const slugify = (value) => {
    return value
        .toLowerCase()
        .normalize('NFD')
        .replace(/[^\w\s-]/g, '')
        .trim()
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');
};

const updateSlug = () => {
    if (!slugTouched.value) {
        form.slug = slugify(form.nom);
    }
};

const markSlugTouched = () => {
    slugTouched.value = true;
};

const submit = () => {
    form.put(route('admin.specialites.update', props.specialite.slug));
};
</script>

<template>
    <AdminLayout>
        <template #header>
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 md:text-xl">Modifier la specialite</h2>
                    <p class="mt-1 text-xs text-slate-500 md:text-sm">Mettez a jour les informations de la specialite.</p>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.specialites.index')" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Retour
                    </Link>
                    <button type="button" class="rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary" :disabled="form.processing" @click="submit">
                        Mettre a jour
                    </button>
                </div>
            </div>
        </template>

        <Head title="Modifier la specialite" />
        <FlashMessage />

        <nav class="text-xs text-slate-500">
            <ol class="flex flex-wrap items-center gap-2">
                <li><Link :href="route('admin.dashboard')" class="hover:text-ed-primary">Dashboard</Link></li>
                <li>/</li>
                <li><Link :href="route('admin.specialites.index')" class="hover:text-ed-primary">Specialites</Link></li>
                <li>/</li>
                <li class="font-semibold text-slate-900">Modifier</li>
            </ol>
        </nav>

        <form class="grid gap-6 lg:grid-cols-3" @submit.prevent="submit">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 lg:col-span-2">
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Nom *</label>
                        <input v-model="form.nom" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" @input="updateSlug" />
                        <p class="mt-1 text-xs text-slate-500">Le nom de la specialite.</p>
                        <p v-if="form.errors.nom" class="mt-2 text-xs text-red-600">{{ form.errors.nom }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Slug *</label>
                        <input v-model="form.slug" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" @input="markSlugTouched" />
                        <p class="mt-1 text-xs text-slate-500">Utilise pour les URLs publiques.</p>
                        <p v-if="form.errors.slug" class="mt-2 text-xs text-red-600">{{ form.errors.slug }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Description</label>
                        <textarea v-model="form.description" rows="5" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2"></textarea>
                        <p v-if="form.errors.description" class="mt-2 text-xs text-red-600">{{ form.errors.description }}</p>
                    </div>
                </div>
            </section>

            <aside class="space-y-6">
                <section class="rounded-2xl border border-slate-200 bg-white p-6">
                    <label class="text-sm font-semibold text-slate-700">EAD *</label>
                    <select v-model="form.ead_id" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2">
                        <option value="">Selectionner un EAD</option>
                        <option v-for="item in eads" :key="item.id" :value="item.id">{{ item.nom }}</option>
                    </select>
                    <p v-if="form.errors.ead_id" class="mt-2 text-xs text-red-600">{{ form.errors.ead_id }}</p>
                </section>
                <section class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5 text-xs text-slate-600">
                    Astuce: l'EAD choisi conditionne l'affichage sur le site public.
                </section>
            </aside>
        </form>

        <div class="sticky bottom-4 z-20 mt-8">
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white/95 px-4 py-3 shadow-lg backdrop-blur">
                <p class="text-xs text-slate-500">Pensez a enregistrer vos modifications.</p>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.specialites.index')" class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
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
