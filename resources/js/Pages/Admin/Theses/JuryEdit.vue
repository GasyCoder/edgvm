<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Card from '@/Components/Admin/Card.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const props = defineProps({
    these: Object,
    jurys: Array,
    selected: Array,
    roles: Array,
});

const form = useForm({
    jurys: props.selected || [],
});

const selectedJuryId = ref('');
const selectedRole = ref(props.roles?.[0] || 'president');
const selectedOrdre = ref('');

const roleLabels = {
    president: 'Président', rapporteur: 'Rapporteur', examinateur: 'Examinateur', invite: 'Invité',
};
const roleLabel = (v) => roleLabels[v] || v;

const addJury = () => {
    if (!selectedJuryId.value) return;

    if (form.jurys.some((item) => String(item.id) === String(selectedJuryId.value))) {
        return;
    }

    form.jurys.push({
        id: Number(selectedJuryId.value),
        role: selectedRole.value,
        ordre: selectedOrdre.value ? Number(selectedOrdre.value) : null,
    });

    selectedJuryId.value = '';
    selectedRole.value = props.roles?.[0] || 'president';
    selectedOrdre.value = '';
};

const removeJury = (id) => {
    form.jurys = form.jurys.filter((item) => item.id !== id);
};

const submit = () => {
    form.put(route('admin.theses.jury.update', props.these.id));
};
</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-bold text-slate-700">Jury de thèse</h1>
        </template>

        <Head title="Jury de thèse" />
        <FlashMessage />

        <div class="space-y-6">
            <nav class="text-xs text-slate-400">
                <ol class="flex flex-wrap items-center gap-2">
                    <li><Link :href="route('admin.dashboard')" class="hover:text-ed-primary">Tableau de bord</Link></li>
                    <li>/</li>
                    <li><Link :href="route('admin.theses.index')" class="hover:text-ed-primary">Thèses</Link></li>
                    <li>/</li>
                    <li class="font-semibold text-slate-600">Jury</li>
                </ol>
            </nav>

            <div class="grid gap-6 lg:grid-cols-3">
                <section class="space-y-6 lg:col-span-2">
                    <Card title="Ajouter un membre du jury">
                        <div class="flex flex-wrap items-end gap-3">
                            <div class="min-w-[200px] flex-1">
                                <label class="text-xs font-medium text-slate-600">Membre</label>
                                <select v-model="selectedJuryId" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary">
                                    <option value="">Sélectionner</option>
                                    <option v-for="jury in jurys" :key="jury.id" :value="jury.id">{{ jury.nom }} {{ jury.grade ? `(${jury.grade})` : '' }}</option>
                                </select>
                            </div>
                            <div class="min-w-[150px]">
                                <label class="text-xs font-medium text-slate-600">Rôle</label>
                                <select v-model="selectedRole" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary">
                                    <option v-for="role in roles" :key="role" :value="role">{{ roleLabel(role) }}</option>
                                </select>
                            </div>
                            <div class="min-w-[100px]">
                                <label class="text-xs font-medium text-slate-600">Ordre</label>
                                <input v-model="selectedOrdre" type="number" min="1" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" />
                            </div>
                            <button type="button" class="rounded-md bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary" @click="addJury">Ajouter</button>
                        </div>
                        <p v-if="!jurys.length" class="mt-3 text-xs text-slate-400">
                            Aucun membre de jury enregistré.
                            <Link :href="route('admin.jurys.create')" class="font-semibold text-ed-primary hover:text-ed-secondary">En créer un</Link>.
                        </p>
                    </Card>

                    <Card title="Composition du jury" :subtitle="`${form.jurys.length} membre(s)`">
                        <div class="space-y-2 text-sm">
                            <div v-for="item in form.jurys" :key="item.id" class="flex items-center justify-between rounded-md border border-gray-100 bg-gray-50 px-4 py-2.5">
                                <div class="flex items-center gap-3">
                                    <span v-if="item.ordre" class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-ed-primary/10 text-xs font-bold text-ed-teal-dark">{{ item.ordre }}</span>
                                    <div>
                                        <p class="font-semibold text-slate-700">{{ jurys.find((j) => j.id === item.id)?.nom || 'Membre' }}</p>
                                        <p class="text-xs text-slate-400">{{ roleLabel(item.role) || '—' }}</p>
                                    </div>
                                </div>
                                <button type="button" class="text-xs font-semibold text-red-600 hover:text-red-700" @click="removeJury(item.id)">Retirer</button>
                            </div>
                            <p v-if="!form.jurys.length" class="py-4 text-center text-xs text-slate-400">Aucun membre ajouté pour le moment.</p>
                        </div>
                    </Card>
                </section>

                <aside class="space-y-6">
                    <Card title="Thèse concernée">
                        <p class="text-sm font-medium leading-relaxed text-slate-700">{{ these.sujet_these }}</p>
                        <Link :href="route('admin.theses.show', these.id)" class="mt-3 inline-flex items-center gap-1.5 text-sm font-semibold text-ed-primary hover:text-ed-secondary">
                            Voir la fiche
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </Link>
                    </Card>
                    <div class="rounded-md border border-gray-100 bg-gray-50 p-5 text-xs leading-relaxed text-slate-500">
                        L'ordre détermine l'affichage des membres lors des soutenances et sur les procès-verbaux.
                    </div>
                </aside>
            </div>

            <div class="sticky bottom-4 z-20 flex flex-wrap items-center justify-end gap-3 rounded-md border border-gray-200 bg-white px-4 py-3 shadow-sm">
                <Link :href="route('admin.theses.show', these.id)" class="rounded-md border border-gray-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-gray-50">Annuler</Link>
                <button type="button" class="rounded-md bg-ed-primary px-5 py-2 text-sm font-semibold text-white hover:bg-ed-secondary disabled:opacity-60" :disabled="form.processing" @click="submit">
                    {{ form.processing ? 'Enregistrement…' : 'Enregistrer le jury' }}
                </button>
            </div>
        </div>
    </AdminLayout>
</template>
