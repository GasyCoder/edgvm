<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
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
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 md:text-xl">Jury de these</h2>
                    <p class="mt-1 text-xs text-slate-500 md:text-sm">Configurez les membres du jury.</p>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.theses.show', these.id)" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Retour
                    </Link>
                    <button type="button" class="rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary" :disabled="form.processing" @click="submit">
                        Enregistrer
                    </button>
                </div>
            </div>
        </template>

        <Head title="Jury de these" />
        <FlashMessage />

        <nav class="text-xs text-slate-500">
            <ol class="flex flex-wrap items-center gap-2">
                <li><Link :href="route('admin.dashboard')" class="hover:text-ed-primary">Dashboard</Link></li>
                <li>/</li>
                <li><Link :href="route('admin.theses.index')" class="hover:text-ed-primary">Theses</Link></li>
                <li>/</li>
                <li class="font-semibold text-slate-900">Jury</li>
            </ol>
        </nav>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6">
                <p class="text-sm font-semibold text-slate-700">These</p>
                <p class="mt-1 text-slate-600">{{ these.sujet_these }}</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6">
                <h3 class="text-sm font-semibold text-slate-700">Ajouter un membre</h3>
                <div class="mt-4 flex flex-wrap items-end gap-3">
                    <div class="min-w-[220px] flex-1">
                        <label class="text-xs font-semibold text-slate-600">Membre</label>
                        <select v-model="selectedJuryId" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2">
                            <option value="">Selectionner</option>
                            <option v-for="jury in jurys" :key="jury.id" :value="jury.id">{{ jury.nom }} {{ jury.grade ? `(${jury.grade})` : '' }}</option>
                        </select>
                    </div>
                    <div class="min-w-[160px]">
                        <label class="text-xs font-semibold text-slate-600">Role</label>
                        <select v-model="selectedRole" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2">
                            <option v-for="role in roles" :key="role" :value="role">{{ role }}</option>
                        </select>
                    </div>
                    <div class="min-w-[120px]">
                        <label class="text-xs font-semibold text-slate-600">Ordre</label>
                        <input v-model="selectedOrdre" type="number" min="1" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" />
                    </div>
                    <button type="button" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white" @click="addJury">Ajouter</button>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6">
                <h3 class="text-sm font-semibold text-slate-700">Membres du jury</h3>
                <div class="mt-4 space-y-2 text-sm">
                    <div v-for="item in form.jurys" :key="item.id" class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-2">
                        <div>
                            <p class="font-semibold text-slate-900">{{ jurys.find((j) => j.id === item.id)?.nom || 'Membre' }}</p>
                            <p class="text-xs text-slate-500">{{ item.role || '-' }} {{ item.ordre ? `· ordre ${item.ordre}` : '' }}</p>
                        </div>
                        <button type="button" class="text-xs font-semibold text-red-600" @click="removeJury(item.id)">Retirer</button>
                    </div>
                    <p v-if="!form.jurys.length" class="text-xs text-slate-500">Aucun membre ajoute.</p>
                </div>
            </div>
            <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5 text-xs text-slate-600">
                Astuce: utilisez l'ordre pour l'affichage lors des soutenances.
            </div>
        </div>

        <div class="sticky bottom-4 z-20 mt-8">
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white/95 px-4 py-3 shadow-lg backdrop-blur">
                <p class="text-xs text-slate-500">Pensez a enregistrer vos modifications.</p>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.theses.show', these.id)" class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
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
