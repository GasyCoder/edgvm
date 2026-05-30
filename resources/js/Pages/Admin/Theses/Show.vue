<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Card from '@/Components/Admin/Card.vue';

defineProps({
    these: Object,
});

const statutMeta = {
    en_cours: { label: 'En cours', class: 'bg-emerald-50 text-emerald-700 border-emerald-200' },
    soutenue: { label: 'Soutenue', class: 'bg-blue-50 text-blue-700 border-blue-200' },
    preparation: { label: 'Préparation', class: 'bg-amber-50 text-amber-700 border-amber-200' },
    redaction: { label: 'Rédaction', class: 'bg-indigo-50 text-indigo-700 border-indigo-200' },
    suspendue: { label: 'Suspendue', class: 'bg-orange-50 text-orange-700 border-orange-200' },
    abandonnee: { label: 'Abandonnée', class: 'bg-red-50 text-red-700 border-red-200' },
};
const statutOf = (v) => statutMeta[v] || { label: v, class: 'bg-gray-50 text-slate-600 border-gray-200' };

const roleLabels = {
    president: 'Président', rapporteur: 'Rapporteur', examinateur: 'Examinateur', invite: 'Invité',
    directeur: 'Directeur', codirecteur: 'Codirecteur',
};
const roleLabel = (v) => roleLabels[v] || v;
</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-bold text-slate-700">Fiche thèse</h1>
        </template>

        <Head title="Thèse" />

        <div class="space-y-6">
            <nav class="text-xs text-slate-400">
                <ol class="flex flex-wrap items-center gap-2">
                    <li><Link :href="route('admin.dashboard')" class="hover:text-ed-primary">Tableau de bord</Link></li>
                    <li>/</li>
                    <li><Link :href="route('admin.theses.index')" class="hover:text-ed-primary">Thèses</Link></li>
                    <li>/</li>
                    <li class="font-semibold text-slate-600">Détail</li>
                </ol>
            </nav>

            <div class="flex flex-wrap items-start justify-between gap-4 rounded-md border border-gray-200 bg-gray-50/60 p-6">
                <div class="min-w-0">
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Sujet de thèse</p>
                    <h2 class="mt-1 max-w-3xl text-lg font-bold text-slate-700">{{ these.sujet_these }}</h2>
                    <div class="mt-2 flex flex-wrap items-center gap-2 text-sm text-slate-500">
                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold" :class="statutOf(these.statut).class">{{ statutOf(these.statut).label }}</span>
                        <span>·</span>
                        <span>{{ these.doctorant?.name || '—' }}</span>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <Link :href="route('admin.theses.edit', these.id)" class="rounded-md bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary">Modifier</Link>
                    <Link :href="route('admin.theses.jury.edit', these.id)" class="rounded-md border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-gray-50">Jury</Link>
                    <Link :href="route('admin.theses.index')" class="rounded-md border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-gray-50">Retour</Link>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <section class="lg:col-span-2">
                    <Card title="Informations">
                        <dl class="grid gap-4 text-sm sm:grid-cols-2">
                            <div>
                                <dt class="text-xs text-slate-400">Doctorant</dt>
                                <dd class="font-semibold text-slate-700">{{ these.doctorant?.name || '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-400">Statut</dt>
                                <dd class="font-semibold text-slate-700">{{ statutOf(these.statut).label }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-400">Université de soutenance</dt>
                                <dd class="font-semibold text-slate-700">{{ these.universite_soutenance || '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-400">Date de début</dt>
                                <dd class="font-semibold text-slate-700">{{ these.date_debut || '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-400">Date de publication</dt>
                                <dd class="font-semibold text-slate-700">{{ these.date_publication || '—' }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-xs text-slate-400">Description</dt>
                                <dd class="text-slate-600">{{ these.description || 'Aucune description.' }}</dd>
                            </div>
                        </dl>
                    </Card>
                </section>

                <aside class="space-y-6">
                    <Card title="Encadrants">
                        <div class="space-y-2 text-sm">
                            <div v-for="encadrant in these.encadrants" :key="encadrant.id" class="rounded-md border border-gray-100 bg-gray-50 p-3">
                                <p class="font-semibold text-slate-700">{{ encadrant.name }}</p>
                                <p class="text-xs capitalize text-slate-400">{{ roleLabel(encadrant.role || 'directeur') }}</p>
                            </div>
                            <p v-if="!these.encadrants?.length" class="text-xs text-slate-400">Aucun encadrant.</p>
                        </div>
                    </Card>
                    <Card title="Jury">
                        <div class="space-y-2 text-sm">
                            <div v-for="jury in these.jurys" :key="jury.id" class="flex items-center gap-3 rounded-md border border-gray-100 bg-gray-50 p-3">
                                <span v-if="jury.ordre" class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-ed-primary/10 text-xs font-bold text-ed-teal-dark">{{ jury.ordre }}</span>
                                <div>
                                    <p class="font-semibold text-slate-700">{{ jury.nom }}</p>
                                    <p class="text-xs text-slate-400">{{ roleLabel(jury.role) || '—' }}</p>
                                </div>
                            </div>
                            <p v-if="!these.jurys?.length" class="text-xs text-slate-400">Aucun membre de jury.</p>
                        </div>
                    </Card>
                </aside>
            </div>
        </div>
    </AdminLayout>
</template>
