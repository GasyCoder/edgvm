<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const props = defineProps({
    grades: Array,
});

const form = useForm({
    nom: '',
    prenoms: '',
    email: '',
    genre: 'homme',
    grade: '',
    specialite: '',
    phone: '',
    bureau: '',
});

const submit = () => {
    form.post(route('admin.encadrants.store'));
};
</script>

<template>
    <AdminLayout>
        <template #header>
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 md:text-xl">Nouvel encadrant</h2>
                    <p class="mt-1 text-xs text-slate-500 md:text-sm">Renseignez les informations de l'encadrant.</p>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.encadrants.index')" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Retour
                    </Link>
                    <button type="button" class="rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary" :disabled="form.processing" @click="submit">
                        Enregistrer
                    </button>
                </div>
            </div>
        </template>

        <Head title="Nouvel encadrant" />
        <FlashMessage />

        <nav class="text-xs text-slate-500">
            <ol class="flex flex-wrap items-center gap-2">
                <li><Link :href="route('admin.dashboard')" class="hover:text-ed-primary">Dashboard</Link></li>
                <li>/</li>
                <li><Link :href="route('admin.encadrants.index')" class="hover:text-ed-primary">Encadrants</Link></li>
                <li>/</li>
                <li class="font-semibold text-slate-900">Creer</li>
            </ol>
        </nav>

        <form class="grid gap-6 lg:grid-cols-3" @submit.prevent="submit">
            <section class="space-y-6 lg:col-span-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h3 class="text-sm font-semibold text-slate-700">Profil encadrant</h3>
                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Nom *</label>
                            <input v-model="form.nom" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" />
                            <p v-if="form.errors.nom" class="mt-2 text-xs text-red-600">{{ form.errors.nom }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Prenoms</label>
                            <input v-model="form.prenoms" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" />
                            <p class="mt-1 text-xs text-slate-500">Optionnel.</p>
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
                            <label class="text-sm font-semibold text-slate-700">Bureau</label>
                            <input v-model="form.bureau" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" />
                            <p v-if="form.errors.bureau" class="mt-2 text-xs text-red-600">{{ form.errors.bureau }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Grade</label>
                            <input v-model="form.grade" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" list="grades" />
                            <datalist id="grades">
                                <option v-for="item in grades" :key="item" :value="item"></option>
                            </datalist>
                            <p v-if="form.errors.grade" class="mt-2 text-xs text-red-600">{{ form.errors.grade }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Specialite</label>
                            <input v-model="form.specialite" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" />
                            <p v-if="form.errors.specialite" class="mt-2 text-xs text-red-600">{{ form.errors.specialite }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <aside class="space-y-6">
                <section class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5 text-xs text-slate-600">
                    Astuce: utilisez le grade pour filtrer rapidement dans l'administration.
                </section>
            </aside>
        </form>

        <div class="sticky bottom-4 z-20 mt-8">
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white/95 px-4 py-3 shadow-lg backdrop-blur">
                <p class="text-xs text-slate-500">Pensez a enregistrer vos modifications.</p>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.encadrants.index')" class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
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
