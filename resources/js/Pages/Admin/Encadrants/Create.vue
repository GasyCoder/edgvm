<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Card from '@/Components/Admin/Card.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

defineProps({
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
            <h1 class="text-lg font-bold text-slate-700">Nouvel encadrant</h1>
        </template>

        <Head title="Nouvel encadrant" />
        <FlashMessage />

        <div class="space-y-6">
            <nav class="text-xs text-slate-400">
                <ol class="flex flex-wrap items-center gap-2">
                    <li><Link :href="route('admin.dashboard')" class="hover:text-ed-primary">Tableau de bord</Link></li>
                    <li>/</li>
                    <li><Link :href="route('admin.encadrants.index')" class="hover:text-ed-primary">Encadrants</Link></li>
                    <li>/</li>
                    <li class="font-semibold text-slate-600">Créer</li>
                </ol>
            </nav>

            <form class="grid gap-6 lg:grid-cols-3" @submit.prevent="submit">
                <div class="lg:col-span-2">
                    <Card title="Profil de l'encadrant">
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="text-xs font-medium text-slate-600">Nom <span class="text-red-500">*</span></label>
                                <input v-model="form.nom" type="text" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" :class="form.errors.nom && 'border-red-300'" />
                                <p v-if="form.errors.nom" class="mt-1 text-xs text-red-600">{{ form.errors.nom }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-slate-600">Prénoms</label>
                                <input v-model="form.prenoms" type="text" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" />
                                <p v-if="form.errors.prenoms" class="mt-1 text-xs text-red-600">{{ form.errors.prenoms }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-slate-600">Genre <span class="text-red-500">*</span></label>
                                <select v-model="form.genre" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary">
                                    <option value="homme">Homme</option>
                                    <option value="femme">Femme</option>
                                </select>
                                <p v-if="form.errors.genre" class="mt-1 text-xs text-red-600">{{ form.errors.genre }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-slate-600">Email <span class="text-red-500">*</span></label>
                                <input v-model="form.email" type="email" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" :class="form.errors.email && 'border-red-300'" />
                                <p v-if="form.errors.email" class="mt-1 text-xs text-red-600">{{ form.errors.email }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-slate-600">Téléphone</label>
                                <input v-model="form.phone" type="text" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" />
                                <p v-if="form.errors.phone" class="mt-1 text-xs text-red-600">{{ form.errors.phone }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-slate-600">Bureau</label>
                                <input v-model="form.bureau" type="text" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" />
                                <p v-if="form.errors.bureau" class="mt-1 text-xs text-red-600">{{ form.errors.bureau }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-slate-600">Grade</label>
                                <input v-model="form.grade" type="text" list="grades" placeholder="Professeur, MCF…" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" />
                                <datalist id="grades">
                                    <option v-for="item in grades" :key="item" :value="item"></option>
                                </datalist>
                                <p v-if="form.errors.grade" class="mt-1 text-xs text-red-600">{{ form.errors.grade }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-slate-600">Spécialité</label>
                                <input v-model="form.specialite" type="text" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" />
                                <p v-if="form.errors.specialite" class="mt-1 text-xs text-red-600">{{ form.errors.specialite }}</p>
                            </div>
                        </div>
                    </Card>
                </div>

                <aside>
                    <div class="rounded-md border border-gray-100 bg-gray-50 p-5 text-xs leading-relaxed text-slate-500">
                        Le grade et la spécialité servent au filtrage rapide et à l'affichage dans les fiches de thèse.
                    </div>
                </aside>
            </form>

            <div class="flex flex-wrap items-center justify-end gap-3 rounded-md border border-gray-200 bg-white px-4 py-3">
                <Link :href="route('admin.encadrants.index')" class="rounded-md border border-gray-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-gray-50">Annuler</Link>
                <button type="button" class="rounded-md bg-ed-primary px-5 py-2 text-sm font-semibold text-white hover:bg-ed-secondary disabled:opacity-60" :disabled="form.processing" @click="submit">
                    {{ form.processing ? 'Enregistrement…' : 'Enregistrer' }}
                </button>
            </div>
        </div>
    </AdminLayout>
</template>
