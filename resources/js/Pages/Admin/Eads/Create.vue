<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const props = defineProps({
    encadrants: Array,
});

const slugTouched = ref(false);

const form = useForm({
    nom: '',
    slug: '',
    description: '',
    responsable_id: '',
    encadrants: [],
});
const selectedEncadrantId = ref('');

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
    form.post(route('admin.ead.store'));
};

const addEncadrant = () => {
    const value = Number(selectedEncadrantId.value);
    if (!value) return;
    if (form.encadrants.includes(value)) {
        selectedEncadrantId.value = '';
        return;
    }
    form.encadrants.push(value);
    selectedEncadrantId.value = '';
};

const removeEncadrant = (id) => {
    form.encadrants = form.encadrants.filter((item) => item !== id);
};
</script>

<template>
    <AdminLayout>
        <template #header>
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 md:text-xl">Creer un EAD</h2>
                    <p class="mt-1 text-xs text-slate-500 md:text-sm">Renseignez les informations principales de l'equipe.</p>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.ead.index')" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Retour
                    </Link>
                    <button type="button" class="rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary" :disabled="form.processing" @click="submit">
                        Enregistrer
                    </button>
                </div>
            </div>
        </template>

        <Head title="Creer un EAD" />
        <FlashMessage />

        <nav class="text-xs text-slate-500">
            <ol class="flex flex-wrap items-center gap-2">
                <li><Link :href="route('admin.dashboard')" class="hover:text-ed-primary">Dashboard</Link></li>
                <li>/</li>
                <li><Link :href="route('admin.ead.index')" class="hover:text-ed-primary">EAD</Link></li>
                <li>/</li>
                <li class="font-semibold text-slate-900">Creer</li>
            </ol>
        </nav>

        <form class="grid gap-6 lg:grid-cols-3" @submit.prevent="submit">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 lg:col-span-2">
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Nom *</label>
                        <input v-model="form.nom" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" @input="updateSlug" />
                        <p class="mt-1 text-xs text-slate-500">Le nom officiel de l'equipe.</p>
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
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Encadrants</label>
                        <div class="mt-2 flex flex-wrap items-end gap-3">
                            <div class="min-w-[240px] flex-1">
                                <select v-model="selectedEncadrantId" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                                    <option value="">Selectionner un encadrant</option>
                                    <option v-for="encadrant in encadrants" :key="encadrant.id" :value="encadrant.id">
                                        {{ encadrant.name }} {{ encadrant.grade ? `(${encadrant.grade})` : '' }}
                                    </option>
                                </select>
                            </div>
                            <button type="button" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white" @click="addEncadrant">
                                Ajouter
                            </button>
                        </div>
                        <p v-if="form.errors.encadrants" class="mt-2 text-xs text-red-600">{{ form.errors.encadrants }}</p>
                        <div v-if="form.encadrants.length" class="mt-3 flex flex-wrap gap-2">
                            <span v-for="id in form.encadrants" :key="id" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-700">
                                {{ encadrants.find((item) => item.id === id)?.name || 'Encadrant' }}
                                <button type="button" class="text-slate-400 hover:text-slate-600" @click="removeEncadrant(id)">×</button>
                            </span>
                        </div>
                        <p v-else class="mt-2 text-xs text-slate-500">Aucun encadrant associe pour le moment.</p>
                    </div>
                </div>
            </section>

            <aside class="space-y-6">
                <section class="rounded-2xl border border-slate-200 bg-white p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Responsable</label>
                            <select v-model="form.responsable_id" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2">
                                <option value="">Aucun</option>
                                <option v-for="encadrant in encadrants" :key="encadrant.id" :value="encadrant.id">
                                    {{ encadrant.name }} {{ encadrant.grade ? `(${encadrant.grade})` : '' }}
                                </option>
                            </select>
                            <p v-if="form.errors.responsable_id" class="mt-2 text-xs text-red-600">{{ form.errors.responsable_id }}</p>
                        </div>
                    </div>
                </section>
                <section class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5 text-xs text-slate-600">
                    Astuce: renseignez un responsable pour faciliter l'affichage sur les pages publiques.
                </section>
            </aside>
        </form>

        <div class="sticky bottom-4 z-20 mt-8">
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white/95 px-4 py-3 shadow-lg backdrop-blur">
                <p class="text-xs text-slate-500">Pensez a enregistrer vos modifications.</p>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.ead.index')" class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
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
