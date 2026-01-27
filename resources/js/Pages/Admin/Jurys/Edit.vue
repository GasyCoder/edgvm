<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
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
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 md:text-xl">Modifier le jury</h2>
                    <p class="mt-1 text-xs text-slate-500 md:text-sm">Mettez a jour les informations du membre.</p>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.jurys.index')" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Retour
                    </Link>
                    <button type="button" class="rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary" :disabled="form.processing" @click="submit">
                        Mettre a jour
                    </button>
                </div>
            </div>
        </template>

        <Head title="Modifier le jury" />
        <FlashMessage />

        <nav class="text-xs text-slate-500">
            <ol class="flex flex-wrap items-center gap-2">
                <li><Link :href="route('admin.dashboard')" class="hover:text-ed-primary">Dashboard</Link></li>
                <li>/</li>
                <li><Link :href="route('admin.jurys.index')" class="hover:text-ed-primary">Jurys</Link></li>
                <li>/</li>
                <li class="font-semibold text-slate-900">Modifier</li>
            </ol>
        </nav>

        <form class="grid gap-6 lg:grid-cols-2" @submit.prevent="submit">
            <section class="rounded-2xl border border-slate-200 bg-white p-6">
                <div class="grid gap-4">
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Nom *</label>
                        <input v-model="form.nom" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" />
                        <p v-if="form.errors.nom" class="mt-2 text-xs text-red-600">{{ form.errors.nom }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Grade</label>
                        <input v-model="form.grade" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" />
                        <p v-if="form.errors.grade" class="mt-2 text-xs text-red-600">{{ form.errors.grade }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Universite</label>
                        <input v-model="form.universite" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" />
                        <p v-if="form.errors.universite" class="mt-2 text-xs text-red-600">{{ form.errors.universite }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Email</label>
                        <input v-model="form.email" type="email" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" />
                        <p v-if="form.errors.email" class="mt-2 text-xs text-red-600">{{ form.errors.email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Telephone</label>
                        <input v-model="form.phone" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" />
                        <p v-if="form.errors.phone" class="mt-2 text-xs text-red-600">{{ form.errors.phone }}</p>
                    </div>
                </div>
            </section>
            <section class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5 text-xs text-slate-600">
                Astuce: ajoutez un email valide pour faciliter les notifications.
            </section>
        </form>

        <div class="sticky bottom-4 z-20 mt-8">
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white/95 px-4 py-3 shadow-lg backdrop-blur">
                <p class="text-xs text-slate-500">Pensez a enregistrer vos modifications.</p>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.jurys.index')" class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
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
