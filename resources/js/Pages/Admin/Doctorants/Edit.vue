<script setup>
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
</script>

<template>
    <AdminLayout>
        <template #header>
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 md:text-xl">Modifier le doctorant</h2>
                    <p class="mt-1 text-xs text-slate-500 md:text-sm">Mise a jour des informations administratives.</p>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.doctorants.index')" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Retour
                    </Link>
                    <button type="button" class="rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary" :disabled="form.processing" @click="submit">
                        Mettre a jour
                    </button>
                </div>
            </div>
        </template>

        <Head title="Modifier le doctorant" />
        <FlashMessage />

        <nav class="text-xs text-slate-500">
            <ol class="flex flex-wrap items-center gap-2">
                <li><Link :href="route('admin.dashboard')" class="hover:text-ed-primary">Dashboard</Link></li>
                <li>/</li>
                <li><Link :href="route('admin.doctorants.index')" class="hover:text-ed-primary">Doctorants</Link></li>
                <li>/</li>
                <li class="font-semibold text-slate-900">Modifier</li>
            </ol>
        </nav>

        <form class="grid gap-6 lg:grid-cols-3" @submit.prevent="submit">
            <section class="space-y-6 lg:col-span-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h3 class="text-sm font-semibold text-slate-700">Identite</h3>
                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Nom *</label>
                            <input v-model="form.nom" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" />
                            <p v-if="form.errors.nom" class="mt-2 text-xs text-red-600">{{ form.errors.nom }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Prenoms</label>
                            <input v-model="form.prenoms" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" />
                            <p v-if="form.errors.prenoms" class="mt-2 text-xs text-red-600">{{ form.errors.prenoms }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Genre *</label>
                            <select v-model="form.genre" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2">
                                <option value="homme">Homme</option>
                                <option value="femme">Femme</option>
                            </select>
                            <p v-if="form.errors.genre" class="mt-2 text-xs text-red-600">{{ form.errors.genre }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Matricule *</label>
                            <input v-model="form.matricule" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" />
                            <p v-if="form.errors.matricule" class="mt-2 text-xs text-red-600">{{ form.errors.matricule }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Niveau *</label>
                            <select v-model="form.niveau" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2">
                                <option v-for="niveau in niveaux" :key="niveau" :value="niveau">{{ niveau }}</option>
                            </select>
                            <p v-if="form.errors.niveau" class="mt-2 text-xs text-red-600">{{ form.errors.niveau }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Equipe d'accueil</label>
                            <select v-model="form.ead_id" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2">
                                <option value="">Selectionner une equipe</option>
                                <option v-for="item in eads" :key="item.id" :value="item.id">{{ item.nom }}</option>
                            </select>
                            <p v-if="form.errors.ead_id" class="mt-2 text-xs text-red-600">{{ form.errors.ead_id }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h3 class="text-sm font-semibold text-slate-700">Informations personnelles</h3>
                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Email *</label>
                            <input v-model="form.email" type="email" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" />
                            <p v-if="form.errors.email" class="mt-2 text-xs text-red-600">{{ form.errors.email }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Telephone</label>
                            <input v-model="form.phone" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" />
                            <p v-if="form.errors.phone" class="mt-2 text-xs text-red-600">{{ form.errors.phone }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Date de naissance</label>
                            <input v-model="form.date_naissance" type="date" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" />
                            <p v-if="form.errors.date_naissance" class="mt-2 text-xs text-red-600">{{ form.errors.date_naissance }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Lieu de naissance</label>
                            <input v-model="form.lieu_naissance" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" />
                            <p v-if="form.errors.lieu_naissance" class="mt-2 text-xs text-red-600">{{ form.errors.lieu_naissance }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-slate-700">Adresse</label>
                            <input v-model="form.adresse" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" />
                            <p v-if="form.errors.adresse" class="mt-2 text-xs text-red-600">{{ form.errors.adresse }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <aside class="space-y-6">
                <section class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h3 class="text-sm font-semibold text-slate-700">Statut & inscription</h3>
                    <div class="mt-4 space-y-4">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Date d'inscription *</label>
                            <input v-model="form.date_inscription" type="date" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" />
                            <p v-if="form.errors.date_inscription" class="mt-2 text-xs text-red-600">{{ form.errors.date_inscription }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Statut *</label>
                            <select v-model="form.statut" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2">
                                <option v-for="item in statuts" :key="item" :value="item">{{ item }}</option>
                            </select>
                            <p v-if="form.errors.statut" class="mt-2 text-xs text-red-600">{{ form.errors.statut }}</p>
                        </div>
                    </div>
                </section>
                <section class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5 text-xs text-slate-600">
                    Astuce: gardez le statut a jour pour les tableaux de bord.
                </section>
            </aside>
        </form>

        <div class="sticky bottom-4 z-20 mt-8">
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white/95 px-4 py-3 shadow-lg backdrop-blur">
                <p class="text-xs text-slate-500">Pensez a enregistrer vos modifications.</p>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.doctorants.index')" class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
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
