<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Card from '@/Components/Admin/Card.vue';

const props = defineProps({
    encadrant: Object,
    theses: Array,
});

const fullName = computed(() => `${props.encadrant.nom || ''} ${props.encadrant.prenoms || ''}`.trim() || '—');

const initials = computed(() => {
    const name = fullName.value;
    if (name === '—') return '–';
    return name.split(' ').filter(Boolean).slice(0, 2).map((p) => p.charAt(0).toUpperCase()).join('');
});

const statutLabels = {
    en_cours: 'En cours', soutenue: 'Soutenue', preparation: 'Préparation',
    redaction: 'Rédaction', suspendue: 'Suspendue', abandonnee: 'Abandonnée',
};
const statutLabel = (v) => statutLabels[v] || v;
</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-bold text-slate-700">Fiche encadrant</h1>
        </template>

        <Head :title="fullName" />

        <div class="space-y-6">
            <nav class="text-xs text-slate-400">
                <ol class="flex flex-wrap items-center gap-2">
                    <li><Link :href="route('admin.dashboard')" class="hover:text-ed-primary">Tableau de bord</Link></li>
                    <li>/</li>
                    <li><Link :href="route('admin.encadrants.index')" class="hover:text-ed-primary">Encadrants</Link></li>
                    <li>/</li>
                    <li class="font-semibold text-slate-600">{{ fullName }}</li>
                </ol>
            </nav>

            <section class="overflow-hidden rounded-md border border-gray-200 bg-white">
                <div class="flex flex-wrap items-center justify-between gap-5 border-b border-gray-100 bg-gray-50/60 p-6">
                    <div class="flex items-center gap-4">
                        <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-ed-primary/10 text-lg font-bold text-ed-teal-dark">{{ initials }}</span>
                        <div>
                            <h2 class="text-xl font-bold text-slate-700">{{ fullName }}</h2>
                            <p class="mt-0.5 text-sm text-slate-400">{{ encadrant.email || '—' }}</p>
                            <span v-if="encadrant.grade" class="mt-2 inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">{{ encadrant.grade }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <Link :href="route('admin.encadrants.edit', encadrant.id)" class="rounded-md bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary">Modifier</Link>
                        <Link :href="route('admin.encadrants.index')" class="rounded-md border border-gray-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-white">Retour</Link>
                    </div>
                </div>

                <dl class="grid gap-px bg-gray-100 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="bg-white p-4">
                        <dt class="text-xs font-medium text-slate-400">Genre</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-700">{{ encadrant.genre === 'homme' ? 'Homme' : encadrant.genre === 'femme' ? 'Femme' : '—' }}</dd>
                    </div>
                    <div class="bg-white p-4">
                        <dt class="text-xs font-medium text-slate-400">Spécialité</dt>
                        <dd class="mt-1 truncate text-sm font-semibold text-slate-700">{{ encadrant.specialite || '—' }}</dd>
                    </div>
                    <div class="bg-white p-4">
                        <dt class="text-xs font-medium text-slate-400">Téléphone</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-700">{{ encadrant.phone || '—' }}</dd>
                    </div>
                    <div class="bg-white p-4">
                        <dt class="text-xs font-medium text-slate-400">Bureau</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-700">{{ encadrant.bureau || '—' }}</dd>
                    </div>
                </dl>
            </section>

            <Card :title="`Thèses encadrées (${theses.length})`">
                <div class="space-y-3 text-sm">
                    <div v-for="these in theses" :key="these.id" class="rounded-md border border-gray-100 bg-gray-50 p-3">
                        <p class="font-semibold text-slate-700">{{ these.sujet_these }}</p>
                        <p class="mt-0.5 text-xs text-slate-400">{{ statutLabel(these.statut) }} · {{ these.doctorant?.name || '—' }}</p>
                    </div>
                    <p v-if="!theses.length" class="text-xs text-slate-400">Aucune thèse associée.</p>
                </div>
            </Card>
        </div>
    </AdminLayout>
</template>
