<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Card from '@/Components/Admin/Card.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const props = defineProps({
    jury: Object,
});

const form = useForm({
    nom: props.jury?.nom || '',
    grade: props.jury?.grade || '',
    universite: props.jury?.universite || '',
    email: props.jury?.email || '',
    phone: props.jury?.phone || '',
});

const submit = () => {
    form.put(route('admin.jurys.update', props.jury.id));
};
</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-bold text-slate-700">Modifier le membre de jury</h1>
        </template>

        <Head title="Modifier le membre de jury" />
        <FlashMessage />

        <div class="space-y-6">
            <nav class="text-xs text-slate-400">
                <ol class="flex flex-wrap items-center gap-2">
                    <li><Link :href="route('admin.dashboard')" class="hover:text-ed-primary">Tableau de bord</Link></li>
                    <li>/</li>
                    <li><Link :href="route('admin.jurys.index')" class="hover:text-ed-primary">Jurys</Link></li>
                    <li>/</li>
                    <li class="font-semibold text-slate-600">{{ form.nom }}</li>
                </ol>
            </nav>

            <form class="grid gap-6 lg:grid-cols-3" @submit.prevent="submit">
                <div class="lg:col-span-2">
                    <Card title="Informations du membre">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label class="text-xs font-medium text-slate-600">Nom <span class="text-red-500">*</span></label>
                                <input v-model="form.nom" type="text" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" :class="form.errors.nom && 'border-red-300'" />
                                <p v-if="form.errors.nom" class="mt-1 text-xs text-red-600">{{ form.errors.nom }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-slate-600">Grade</label>
                                <input v-model="form.grade" type="text" placeholder="Professeur, MCF…" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" />
                                <p v-if="form.errors.grade" class="mt-1 text-xs text-red-600">{{ form.errors.grade }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-slate-600">Université</label>
                                <input v-model="form.universite" type="text" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" />
                                <p v-if="form.errors.universite" class="mt-1 text-xs text-red-600">{{ form.errors.universite }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-slate-600">Email</label>
                                <input v-model="form.email" type="email" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" />
                                <p v-if="form.errors.email" class="mt-1 text-xs text-red-600">{{ form.errors.email }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-slate-600">Téléphone</label>
                                <input v-model="form.phone" type="text" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" />
                                <p v-if="form.errors.phone" class="mt-1 text-xs text-red-600">{{ form.errors.phone }}</p>
                            </div>
                        </div>
                    </Card>
                </div>

                <aside>
                    <div class="rounded-md border border-gray-100 bg-gray-50 p-5 text-xs leading-relaxed text-slate-500">
                        L'université et le grade apparaissent sur les procès-verbaux et rapports de soutenance. Un e-mail valide facilite les notifications.
                    </div>
                </aside>
            </form>

            <div class="flex flex-wrap items-center justify-end gap-3 rounded-md border border-gray-200 bg-white px-4 py-3">
                <Link :href="route('admin.jurys.index')" class="rounded-md border border-gray-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-gray-50">Annuler</Link>
                <button type="button" class="rounded-md bg-ed-primary px-5 py-2 text-sm font-semibold text-white hover:bg-ed-secondary disabled:opacity-60" :disabled="form.processing" @click="submit">
                    {{ form.processing ? 'Mise à jour…' : 'Mettre à jour' }}
                </button>
            </div>
        </div>
    </AdminLayout>
</template>
