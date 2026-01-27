<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    doctorant: Object,
    theses: Array,
});
</script>

<template>
    <AdminLayout>
        <template #header>
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 md:text-xl">Doctorant</h2>
                    <p class="mt-1 text-xs text-slate-500 md:text-sm">Detail du profil doctorant.</p>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.doctorants.edit', doctorant.id)" class="rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary">
                        Modifier
                    </Link>
                    <Link :href="route('admin.doctorants.index')" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Retour
                    </Link>
                </div>
            </div>
        </template>

        <Head title="Doctorant" />

        <div class="grid gap-6 lg:grid-cols-3">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 lg:col-span-2">
                <h3 class="text-sm font-semibold text-slate-700">Informations</h3>
                <div class="mt-4 grid gap-4 sm:grid-cols-2 text-sm">
                    <div>
                        <p class="text-xs text-slate-500">Nom</p>
                        <p class="font-semibold text-slate-900">{{ doctorant.nom || '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Prenoms</p>
                        <p class="font-semibold text-slate-900">{{ doctorant.prenoms || '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Genre</p>
                        <p class="font-semibold text-slate-900">{{ doctorant.genre || '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Email</p>
                        <p class="font-semibold text-slate-900">{{ doctorant.email || '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Equipe d'accueil</p>
                        <p class="font-semibold text-slate-900">{{ doctorant.ead?.nom || '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Matricule</p>
                        <p class="font-semibold text-slate-900">{{ doctorant.matricule }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Statut</p>
                        <p class="font-semibold text-slate-900">{{ doctorant.statut }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Niveau</p>
                        <p class="font-semibold text-slate-900">{{ doctorant.niveau }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Date d'inscription</p>
                        <p class="font-semibold text-slate-900">{{ doctorant.date_inscription || '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Telephone</p>
                        <p class="font-semibold text-slate-900">{{ doctorant.phone || '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Adresse</p>
                        <p class="font-semibold text-slate-900">{{ doctorant.adresse || '-' }}</p>
                    </div>
                </div>
            </section>

            <aside class="rounded-2xl border border-slate-200 bg-white p-6">
                <h3 class="text-sm font-semibold text-slate-700">Theses</h3>
                <div class="mt-4 space-y-3 text-sm">
                    <div v-for="these in theses" :key="these.id" class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                        <p class="font-semibold text-slate-900">{{ these.sujet_these }}</p>
                        <p class="text-xs text-slate-500">{{ these.statut }}</p>
                        <p class="text-xs text-slate-400">{{ these.ead?.nom || '-' }} · {{ these.specialite?.nom || '-' }}</p>
                    </div>
                    <p v-if="!theses.length" class="text-xs text-slate-500">Aucune these associee.</p>
                </div>
            </aside>
        </div>
    </AdminLayout>
</template>
