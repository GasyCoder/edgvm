<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const props = defineProps({
    doctorant: Object,
    eads: Array,
    niveaux: Array,
    statuts: Array,
});

const form = useForm({
    nom: props.doctorant?.nom || '',
    prenoms: props.doctorant?.prenoms || '',
    genre: props.doctorant?.genre || 'homme',
    email: props.doctorant?.email || '',
    ead_id: props.doctorant?.ead_id || '',
    matricule: props.doctorant?.matricule || '',
    niveau: props.doctorant?.niveau || 'D1',
    phone: props.doctorant?.phone || '',
    adresse: props.doctorant?.adresse || '',
    date_inscription: props.doctorant?.date_inscription || '',
    date_naissance: props.doctorant?.date_naissance || '',
    lieu_naissance: props.doctorant?.lieu_naissance || '',
    statut: props.doctorant?.statut || 'actif',
});

const submit = () => {
    form.put(route('admin.doctorants.update', props.doctorant.id));
};

const initials = computed(() => {
    const name = `${form.nom} ${form.prenoms}`.trim();

    if (!name) return '–';

    return name
        .split(' ')
        .filter(Boolean)
        .slice(0, 2)
        .map((part) => part.charAt(0).toUpperCase())
        .join('');
});

const statutBadge = (statut) => {
    switch (statut) {
        case 'actif':
            return 'bg-emerald-50 text-emerald-700 border-emerald-200';
        case 'diplome':
            return 'bg-blue-50 text-blue-700 border-blue-200';
        case 'suspendu':
            return 'bg-amber-50 text-amber-700 border-amber-200';
        case 'abandonne':
            return 'bg-red-50 text-red-700 border-red-200';
        default:
            return 'bg-gray-50 text-gray-600 border-gray-200';
    }
};
</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-gray-900">Modifier le doctorant</h1>
        </template>

        <Head title="Modifier le doctorant" />
        <FlashMessage />

        <div class="space-y-6">
            <nav class="text-xs text-gray-500">
                <ol class="flex flex-wrap items-center gap-2">
                    <li><Link :href="route('admin.dashboard')" class="hover:text-ed-primary">Tableau de bord</Link></li>
                    <li>/</li>
                    <li><Link :href="route('admin.doctorants.index')" class="hover:text-ed-primary">Doctorants</Link></li>
                    <li>/</li>
                    <li class="font-semibold text-gray-900">Modifier</li>
                </ol>
            </nav>

            <section class="rounded-xl border border-gray-200 bg-gray-50/60 p-6">
                <div class="flex flex-wrap items-center justify-between gap-5">
                    <div class="flex items-center gap-4">
                        <span class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-ed-primary/10 text-xl font-bold text-ed-teal-dark">{{ initials }}</span>
                        <div>
                            <h2 class="text-xl font-bold tracking-tight text-gray-900">{{ form.nom }} {{ form.prenoms }}</h2>
                            <p class="mt-0.5 text-sm text-gray-500">Matricule {{ form.matricule || '—' }}</p>
                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center rounded-full bg-ed-primary/10 px-2.5 py-0.5 text-xs font-semibold text-ed-teal-dark">Niveau {{ form.niveau }}</span>
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold capitalize" :class="statutBadge(form.statut)">{{ form.statut }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <Link :href="route('admin.doctorants.show', doctorant.id)" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Voir la fiche
                        </Link>
                        <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary disabled:opacity-60" :disabled="form.processing" @click="submit">
                            {{ form.processing ? 'Mise à jour…' : 'Mettre à jour' }}
                        </button>
                    </div>
                </div>
            </section>

            <form class="grid gap-6 lg:grid-cols-3" @submit.prevent="submit">
                <section class="space-y-6 lg:col-span-2">
                    <div class="rounded-xl border border-gray-200 bg-white p-6">
                        <h3 class="text-sm font-semibold text-gray-700">Informations personnelles</h3>
                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Nom <span class="text-red-500">*</span></label>
                                <input v-model="form.nom" type="text" class="mt-2 w-full rounded-xl border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" :class="form.errors.nom && 'border-red-300'" />
                                <p v-if="form.errors.nom" class="mt-2 text-xs text-red-600">{{ form.errors.nom }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Prénoms</label>
                                <input v-model="form.prenoms" type="text" class="mt-2 w-full rounded-xl border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" :class="form.errors.prenoms && 'border-red-300'" />
                                <p v-if="form.errors.prenoms" class="mt-2 text-xs text-red-600">{{ form.errors.prenoms }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Genre <span class="text-red-500">*</span></label>
                                <select v-model="form.genre" class="mt-2 w-full rounded-xl border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary">
                                    <option value="homme">Homme</option>
                                    <option value="femme">Femme</option>
                                </select>
                                <p v-if="form.errors.genre" class="mt-2 text-xs text-red-600">{{ form.errors.genre }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                                <input v-model="form.email" type="email" class="mt-2 w-full rounded-xl border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" :class="form.errors.email && 'border-red-300'" />
                                <p v-if="form.errors.email" class="mt-2 text-xs text-red-600">{{ form.errors.email }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Téléphone</label>
                                <input v-model="form.phone" type="text" class="mt-2 w-full rounded-xl border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" />
                                <p v-if="form.errors.phone" class="mt-2 text-xs text-red-600">{{ form.errors.phone }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Date de naissance</label>
                                <input v-model="form.date_naissance" type="date" class="mt-2 w-full rounded-xl border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" />
                                <p v-if="form.errors.date_naissance" class="mt-2 text-xs text-red-600">{{ form.errors.date_naissance }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Lieu de naissance</label>
                                <input v-model="form.lieu_naissance" type="text" class="mt-2 w-full rounded-xl border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" />
                                <p v-if="form.errors.lieu_naissance" class="mt-2 text-xs text-red-600">{{ form.errors.lieu_naissance }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-sm font-medium text-gray-700">Adresse</label>
                                <input v-model="form.adresse" type="text" class="mt-2 w-full rounded-xl border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" />
                                <p v-if="form.errors.adresse" class="mt-2 text-xs text-red-600">{{ form.errors.adresse }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-6">
                        <h3 class="text-sm font-semibold text-gray-700">Informations académiques</h3>
                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Matricule <span class="text-red-500">*</span></label>
                                <input v-model="form.matricule" type="text" class="mt-2 w-full rounded-xl border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" :class="form.errors.matricule && 'border-red-300'" />
                                <p v-if="form.errors.matricule" class="mt-2 text-xs text-red-600">{{ form.errors.matricule }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Niveau <span class="text-red-500">*</span></label>
                                <select v-model="form.niveau" class="mt-2 w-full rounded-xl border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary">
                                    <option v-for="niveau in niveaux" :key="niveau" :value="niveau">{{ niveau }}</option>
                                </select>
                                <p v-if="form.errors.niveau" class="mt-2 text-xs text-red-600">{{ form.errors.niveau }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-sm font-medium text-gray-700">Équipe d'accueil</label>
                                <select v-model="form.ead_id" class="mt-2 w-full rounded-xl border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary">
                                    <option value="">Sélectionner une équipe</option>
                                    <option v-for="item in eads" :key="item.id" :value="item.id">{{ item.sigle || item.nom }}</option>
                                </select>
                                <p v-if="form.errors.ead_id" class="mt-2 text-xs text-red-600">{{ form.errors.ead_id }}</p>
                            </div>
                        </div>
                    </div>
                </section>

                <aside class="space-y-6">
                    <section class="rounded-xl border border-gray-200 bg-white p-6">
                        <h3 class="text-sm font-semibold text-gray-700">Inscription</h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Date d'inscription <span class="text-red-500">*</span></label>
                                <input v-model="form.date_inscription" type="date" class="mt-2 w-full rounded-xl border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" :class="form.errors.date_inscription && 'border-red-300'" />
                                <p v-if="form.errors.date_inscription" class="mt-2 text-xs text-red-600">{{ form.errors.date_inscription }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Statut <span class="text-red-500">*</span></label>
                                <select v-model="form.statut" class="mt-2 w-full rounded-xl border-gray-200 text-sm capitalize focus:border-ed-primary focus:ring-ed-primary">
                                    <option v-for="item in statuts" :key="item" :value="item">{{ item }}</option>
                                </select>
                                <p v-if="form.errors.statut" class="mt-2 text-xs text-red-600">{{ form.errors.statut }}</p>
                            </div>
                        </div>
                    </section>
                    <section class="rounded-xl border border-gray-100 bg-gray-50 p-5 text-xs leading-relaxed text-gray-600">
                        Gardez le statut à jour : il alimente les tableaux de bord et les rapports.
                    </section>
                </aside>
            </form>

            <div class="flex flex-wrap items-center justify-end gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3">
                <Link :href="route('admin.doctorants.index')" class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Annuler
                </Link>
                <button type="button" class="rounded-xl bg-ed-primary px-4 py-2 text-sm font-semibold text-white hover:bg-ed-secondary disabled:opacity-60" :disabled="form.processing" @click="submit">
                    {{ form.processing ? 'Mise à jour…' : 'Mettre à jour' }}
                </button>
            </div>
        </div>
    </AdminLayout>
</template>
