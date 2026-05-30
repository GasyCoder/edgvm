<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const props = defineProps({
    encadrants: Array,
});

const form = useForm({
    nom: '',
    sigle: '',
    description: '',
    responsable_id: '',
    encadrants: [],
});
const selectedEncadrantId = ref('');

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
            <h2 class="text-lg font-semibold text-slate-900 md:text-xl">Nouvelle équipe d'accueil</h2>
        </template>

        <Head title="Nouvelle équipe d'accueil" />
        <FlashMessage />

        <div class="mx-auto max-w-screen-2xl space-y-6">
            <nav class="flex flex-wrap items-center gap-2 text-xs text-gray-500">
                <Link :href="route('admin.dashboard')" class="hover:text-ed-primary">Tableau de bord</Link>
                <span aria-hidden="true">/</span>
                <Link :href="route('admin.ead.index')" class="hover:text-ed-primary">Équipes d'accueil</Link>
                <span aria-hidden="true">/</span>
                <span class="font-semibold text-gray-900">Nouvelle</span>
            </nav>

            <form class="grid gap-6 lg:grid-cols-3" @submit.prevent="submit">
                <section class="space-y-5 rounded-xl border border-gray-200 bg-white p-6 lg:col-span-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Nom de l'équipe <span class="text-red-500">*</span></label>
                        <input v-model="form.nom" type="text" placeholder="Ex. : Sciences du Vivant et Modélisation" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm placeholder:text-gray-400 focus:border-transparent focus:ring-2 focus:ring-ed-primary/40">
                        <p class="mt-1 text-xs text-gray-500">Le lien public (slug) est généré automatiquement à partir du nom.</p>
                        <p v-if="form.errors.nom" class="mt-1 text-xs text-red-600">{{ form.errors.nom }}</p>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Sigle</label>
                        <input v-model="form.sigle" type="text" placeholder="Ex. : SVM" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm uppercase placeholder:normal-case placeholder:text-gray-400 focus:border-transparent focus:ring-2 focus:ring-ed-primary/40">
                        <p class="mt-1 text-xs text-gray-500">Abréviation courte de l'équipe (facultatif).</p>
                        <p v-if="form.errors.sigle" class="mt-1 text-xs text-red-600">{{ form.errors.sigle }}</p>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Description</label>
                        <textarea v-model="form.description" rows="5" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm placeholder:text-gray-400 focus:border-transparent focus:ring-2 focus:ring-ed-primary/40"></textarea>
                        <p v-if="form.errors.description" class="mt-1 text-xs text-red-600">{{ form.errors.description }}</p>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Encadrants rattachés</label>
                        <div class="flex flex-wrap items-end gap-3">
                            <select v-model="selectedEncadrantId" class="min-w-[240px] flex-1 rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm focus:border-transparent focus:ring-2 focus:ring-ed-primary/40">
                                <option value="">Sélectionner un encadrant…</option>
                                <option v-for="encadrant in encadrants" :key="encadrant.id" :value="encadrant.id">
                                    {{ encadrant.name }} {{ encadrant.grade ? `(${encadrant.grade})` : '' }}
                                </option>
                            </select>
                            <button type="button" class="rounded-lg bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-ed-secondary" @click="addEncadrant">
                                Ajouter
                            </button>
                        </div>
                        <p v-if="form.errors.encadrants" class="mt-1 text-xs text-red-600">{{ form.errors.encadrants }}</p>
                        <div v-if="form.encadrants.length" class="mt-3 flex flex-wrap gap-2">
                            <span v-for="id in form.encadrants" :key="id" class="inline-flex items-center gap-2 rounded-full bg-ed-primary/10 px-3 py-1 text-xs font-medium text-ed-primary">
                                {{ encadrants.find((item) => item.id === id)?.name || 'Encadrant' }}
                                <button type="button" class="text-ed-primary/60 hover:text-ed-primary" @click="removeEncadrant(id)" aria-label="Retirer">×</button>
                            </span>
                        </div>
                        <p v-else class="mt-2 text-xs text-gray-500">Aucun encadrant rattaché pour le moment.</p>
                    </div>
                </section>

                <aside class="space-y-5">
                    <section class="rounded-xl border border-gray-200 bg-white p-6">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Responsable</label>
                        <select v-model="form.responsable_id" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm focus:border-transparent focus:ring-2 focus:ring-ed-primary/40">
                            <option value="">Aucun</option>
                            <option v-for="encadrant in encadrants" :key="encadrant.id" :value="encadrant.id">
                                {{ encadrant.name }} {{ encadrant.grade ? `(${encadrant.grade})` : '' }}
                            </option>
                        </select>
                        <p v-if="form.errors.responsable_id" class="mt-1 text-xs text-red-600">{{ form.errors.responsable_id }}</p>
                        <p class="mt-3 text-xs leading-relaxed text-gray-500">Le responsable apparaît sur la fiche publique de l'équipe.</p>
                    </section>

                    <section class="rounded-xl border border-gray-200 bg-white p-6">
                        <div class="flex items-center gap-2">
                            <button type="button" class="flex-1 rounded-lg bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-ed-secondary disabled:opacity-60" :disabled="form.processing" @click="submit">
                                {{ form.processing ? 'Enregistrement…' : 'Enregistrer' }}
                            </button>
                            <Link :href="route('admin.ead.index')" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                                Annuler
                            </Link>
                        </div>
                    </section>
                </aside>
            </form>
        </div>
    </AdminLayout>
</template>
