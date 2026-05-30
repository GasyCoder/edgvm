<script setup>
import { computed, ref } from 'vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';
import ConfirmDialog from '@/Components/Common/ConfirmDialog.vue';

const props = defineProps({
    doctorant: Object,
    theses: Array,
    paiements: { type: Array, default: () => [] },
    finances: {
        type: Object,
        default: () => ({ total_du: 0, total_paye: 0, total_reste: 0, series: [] }),
    },
    anneeUniversitaireDefaut: { type: String, default: '' },
    parcours: {
        type: Object,
        default: () => ({ niveau: 'D1', annee: null, etat: '', actions: {}, reinscriptions: [] }),
    },
});

const DROIT_INSCRIPTION = 700000;

const page = usePage();
const can = (ability) => Boolean(page.props.auth?.can?.[ability]);

const niveaux = ['D1', 'D2', 'D3'];

const form = useForm({
    niveau: props.doctorant.niveau || 'D1',
    annee_universitaire: props.anneeUniversitaireDefaut,
    montant_du: DROIT_INSCRIPTION,
    montant_paye: '',
    date_paiement: '',
    mode: 'Virement bancaire',
    reference: '',
    notes: '',
    justificatif: null,
});

const showForm = ref(false);

const submitPaiement = () => {
    form.post(route('admin.doctorants.paiements.store', props.doctorant.id), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            form.reset('montant_paye', 'date_paiement', 'reference', 'notes', 'justificatif');
            form.montant_du = DROIT_INSCRIPTION;
            showForm.value = false;
        },
    });
};

const onFile = (event) => {
    form.justificatif = event.target.files[0] ?? null;
};

const notifyForm = useForm({});
const showNotifyConfirm = ref(false);

const notifyFinance = () => {
    showNotifyConfirm.value = true;
};

const confirmNotify = () => {
    notifyForm.post(route('admin.doctorants.finances.notify', props.doctorant.id), {
        preserveScroll: true,
        onFinish: () => {
            showNotifyConfirm.value = false;
        },
    });
};

const notifyMessage = computed(() => {
    const cible = props.doctorant.email || "l'adresse du doctorant";

    return `Un e-mail récapitulant la situation financière sera envoyé à ${cible}.`;
});

const paiementToDelete = ref(null);
const deletingPaiement = ref(false);

const deleteMessage = computed(() => {
    if (!paiementToDelete.value) {
        return '';
    }

    return `Le paiement ${paiementToDelete.value.niveau} · ${paiementToDelete.value.annee_universitaire} sera définitivement supprimé.`;
});

const deletePaiement = (paiement) => {
    paiementToDelete.value = paiement;
};

const confirmDeletePaiement = () => {
    if (!paiementToDelete.value) {
        return;
    }

    router.delete(route('admin.doctorants.paiements.destroy', [props.doctorant.id, paiementToDelete.value.id]), {
        preserveScroll: true,
        onStart: () => {
            deletingPaiement.value = true;
        },
        onFinish: () => {
            deletingPaiement.value = false;
            paiementToDelete.value = null;
        },
    });
};

const formatMontant = (value) => {
    const number = Number(value || 0);

    return new Intl.NumberFormat('fr-FR', { maximumFractionDigits: 0 }).format(number) + ' Ar';
};

const initials = computed(() => {
    const name = `${props.doctorant.nom || ''} ${props.doctorant.prenoms || ''}`.trim();

    if (!name) return '–';

    return name
        .split(' ')
        .filter(Boolean)
        .slice(0, 2)
        .map((part) => part.charAt(0).toUpperCase())
        .join('');
});

const statutBadge = (statut) => {
    switch (statut) {
        case 'actif':
            return 'bg-emerald-50 text-emerald-700 border-emerald-200';
        case 'diplome':
            return 'bg-blue-50 text-blue-700 border-blue-200';
        case 'suspendu':
            return 'bg-amber-50 text-amber-700 border-amber-200';
        case 'abandonne':
            return 'bg-red-50 text-red-700 border-red-200';
        default:
            return 'bg-gray-50 text-gray-600 border-gray-200';
    }
};

const statutLabels = {
    paye: 'Soldé',
    partiel: 'Partiel',
    impaye: 'Impayé',
};

const statutClasses = {
    paye: 'bg-ed-primary/10 text-ed-teal-dark',
    partiel: 'bg-ed-yellow/20 text-amber-800',
    impaye: 'bg-red-50 text-red-700',
};

const tauxRecouvrement = computed(() => {
    if (!props.finances.total_du) {
        return 0;
    }

    return Math.min(100, Math.round((props.finances.total_paye / props.finances.total_du) * 100));
});

const ring = computed(() => {
    const radius = 52;
    const circumference = 2 * Math.PI * radius;
    const offset = circumference * (1 - tauxRecouvrement.value / 100);

    return {
        radius,
        circumference: circumference.toFixed(2),
        offset: offset.toFixed(2),
    };
});

// Parcours doctoral
const parcoursForm = useForm({ action: '', decision: '' });
const parcoursAction = ref(null);

const parcoursMeta = computed(() => ({
    promouvoir: { title: 'Admettre au niveau suivant', message: `Le doctorant passera en ${props.parcours.niveau_suivant || 'niveau suivant'} (nouvelle réinscription pour la prochaine année).`, confirm: 'Admettre', variant: 'primary' },
    ajourner: { title: 'Ajourner (redoublement)', message: `Le doctorant redoublera le niveau ${props.parcours.niveau} l'année prochaine.`, confirm: 'Ajourner', variant: 'primary' },
    valider: { title: 'Valider pour la soutenance', message: 'La thèse sera validée — le doctorant passera « en attente de soutenance ».', confirm: 'Valider', variant: 'primary' },
    diplomer: { title: 'Enregistrer la soutenance', message: 'Le doctorant sera diplômé et déplacé dans les archives.', confirm: 'Diplômer', variant: 'primary' },
    abandonner: { title: 'Enregistrer un abandon', message: 'Le doctorant sera marqué « abandon » et déplacé dans les archives.', confirm: 'Confirmer l\'abandon', variant: 'danger' },
}));

const currentParcoursMeta = computed(() => (parcoursAction.value ? parcoursMeta.value[parcoursAction.value] : null));

const askParcours = (action) => {
    parcoursAction.value = action;
    parcoursForm.clearErrors();
    parcoursForm.decision = '';
};

const runParcours = () => {
    parcoursForm.transform((data) => ({ ...data, action: parcoursAction.value })).post(route('admin.doctorants.parcours.store', props.doctorant.id), {
        preserveScroll: true,
        onSuccess: () => {
            parcoursAction.value = null;
        },
    });
};

const reinscriptionBadge = (statut) => {
    switch (statut) {
        case 'en_cours': return 'bg-ed-primary/10 text-ed-teal-dark';
        case 'admis': return 'bg-emerald-50 text-emerald-700';
        case 'ajourne': return 'bg-amber-50 text-amber-700';
        case 'valide': return 'bg-blue-50 text-blue-700';
        case 'abandon': return 'bg-red-50 text-red-700';
        default: return 'bg-gray-100 text-gray-600';
    }
};
</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-gray-900">Fiche doctorant</h1>
        </template>

        <Head :title="`${doctorant.nom} ${doctorant.prenoms}`" />
        <FlashMessage />

        <div class="space-y-6">
            <nav class="text-xs text-gray-500">
                <ol class="flex flex-wrap items-center gap-2">
                    <li><Link :href="route('admin.dashboard')" class="hover:text-ed-primary">Tableau de bord</Link></li>
                    <li>/</li>
                    <li><Link :href="route('admin.doctorants.index')" class="hover:text-ed-primary">Doctorants</Link></li>
                    <li>/</li>
                    <li class="font-semibold text-gray-900">{{ doctorant.nom }} {{ doctorant.prenoms }}</li>
                </ol>
            </nav>

            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white">
                <div class="border-b border-gray-100 bg-gray-50/60 p-6">
                    <div class="flex flex-wrap items-start justify-between gap-5">
                        <div class="flex items-center gap-4">
                            <span class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-ed-primary/10 text-xl font-bold text-ed-teal-dark">{{ initials }}</span>
                            <div>
                                <h2 class="text-xl font-bold tracking-tight text-gray-900">{{ doctorant.nom }} {{ doctorant.prenoms }}</h2>
                                <p class="mt-0.5 text-sm text-gray-500">Matricule {{ doctorant.matricule }}</p>
                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center rounded-full bg-ed-primary/10 px-2.5 py-0.5 text-xs font-semibold text-ed-teal-dark">Niveau {{ doctorant.niveau }}</span>
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold capitalize" :class="statutBadge(doctorant.statut)">{{ doctorant.statut }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <Link :href="route('admin.doctorants.edit', doctorant.id)" class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary">
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.232-6.232a2.5 2.5 0 113.536 3.536L12.536 16.536A4 4 0 0110 17H7v-3a4 4 0 011-2.536z" />
                                </svg>
                                Modifier
                            </Link>
                            <button v-if="can('finances.access')" type="button" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-white disabled:opacity-60" :disabled="notifyForm.processing" @click="notifyFinance">
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ notifyForm.processing ? 'Envoi…' : 'Notifier par e-mail' }}
                            </button>
                            <Link :href="route('admin.doctorants.index')" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-white">
                                Retour
                            </Link>
                        </div>
                    </div>
                </div>

                <dl class="grid gap-px bg-gray-100 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="bg-white p-4">
                        <dt class="text-xs font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 truncate text-sm font-semibold text-gray-900">{{ doctorant.email || '—' }}</dd>
                    </div>
                    <div class="bg-white p-4">
                        <dt class="text-xs font-medium text-gray-500">Téléphone</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ doctorant.phone || '—' }}</dd>
                    </div>
                    <div class="bg-white p-4">
                        <dt class="text-xs font-medium text-gray-500">Équipe d'accueil</dt>
                        <dd class="mt-1 truncate text-sm font-semibold text-gray-900">{{ doctorant.ead?.nom || '—' }}</dd>
                    </div>
                    <div class="bg-white p-4">
                        <dt class="text-xs font-medium text-gray-500">Inscrit le</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ doctorant.date_inscription || '—' }}</dd>
                    </div>
                </dl>
            </section>

            <div class="grid gap-6 lg:grid-cols-3">
                <section class="rounded-xl border border-gray-200 bg-white p-6 lg:col-span-2">
                    <h3 class="text-sm font-semibold text-gray-700">Informations personnelles</h3>
                    <dl class="mt-4 grid gap-4 text-sm sm:grid-cols-2">
                        <div>
                            <dt class="text-xs text-gray-500">Nom</dt>
                            <dd class="font-semibold text-gray-900">{{ doctorant.nom || '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500">Prénoms</dt>
                            <dd class="font-semibold text-gray-900">{{ doctorant.prenoms || '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500">Genre</dt>
                            <dd class="font-semibold capitalize text-gray-900">{{ doctorant.genre || '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500">Date de naissance</dt>
                            <dd class="font-semibold text-gray-900">{{ doctorant.date_naissance || '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500">Lieu de naissance</dt>
                            <dd class="font-semibold text-gray-900">{{ doctorant.lieu_naissance || '—' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-xs text-gray-500">Adresse</dt>
                            <dd class="font-semibold text-gray-900">{{ doctorant.adresse || '—' }}</dd>
                        </div>
                    </dl>

                    <h3 class="mt-8 text-sm font-semibold text-gray-700">Informations académiques</h3>
                    <dl class="mt-4 grid gap-4 text-sm sm:grid-cols-2">
                        <div>
                            <dt class="text-xs text-gray-500">Matricule</dt>
                            <dd class="font-semibold text-gray-900">{{ doctorant.matricule }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500">Niveau</dt>
                            <dd class="font-semibold text-gray-900">{{ doctorant.niveau }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500">Statut</dt>
                            <dd class="font-semibold capitalize text-gray-900">{{ doctorant.statut }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500">Équipe d'accueil</dt>
                            <dd class="font-semibold text-gray-900">{{ doctorant.ead?.nom || '—' }}</dd>
                        </div>
                    </dl>
                </section>

                <aside class="rounded-xl border border-gray-200 bg-white p-6">
                    <h3 class="text-sm font-semibold text-gray-700">Thèses</h3>
                    <div class="mt-4 space-y-3 text-sm">
                        <div v-for="these in theses" :key="these.id" class="rounded-xl border border-gray-100 bg-gray-50 p-3">
                            <p class="font-semibold text-gray-900">{{ these.sujet_these }}</p>
                            <p class="mt-0.5 text-xs capitalize text-gray-500">{{ these.statut }}</p>
                            <p class="mt-1 text-xs text-gray-400">{{ these.ead?.nom || '—' }} · {{ these.specialite?.nom || '—' }}</p>
                        </div>
                        <p v-if="!theses.length" class="text-xs text-gray-500">Aucune thèse associée.</p>
                    </div>
                </aside>
            </div>

            <section v-if="can('gestion.access')" class="space-y-4">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Parcours doctoral</h3>
                    <p class="mt-1 text-xs text-gray-500">Admission et avancement année par année (D1 → D2 → D3 → soutenance). D4/D5 sur dérogation.</p>
                </div>

                <div class="rounded-md border border-gray-200 bg-white p-5">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-ed-primary/10 text-sm font-bold text-ed-teal-dark">{{ parcours.niveau }}</span>
                            <div>
                                <p class="text-sm font-semibold text-slate-700">{{ parcours.etat }}</p>
                                <p v-if="parcours.annee" class="text-xs text-slate-400">Année universitaire {{ parcours.annee }}</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <button v-if="parcours.actions?.promouvoir" type="button" class="rounded-md bg-ed-primary px-3.5 py-2 text-sm font-semibold text-white hover:bg-ed-secondary" @click="askParcours('promouvoir')">Admettre en {{ parcours.niveau_suivant }}</button>
                            <button v-if="parcours.actions?.valider" type="button" class="rounded-md bg-blue-600 px-3.5 py-2 text-sm font-semibold text-white hover:bg-blue-700" @click="askParcours('valider')">Valider pour soutenance</button>
                            <button v-if="parcours.actions?.diplomer" type="button" class="rounded-md bg-emerald-600 px-3.5 py-2 text-sm font-semibold text-white hover:bg-emerald-700" @click="askParcours('diplomer')">Enregistrer la soutenance</button>
                            <button v-if="parcours.actions?.ajourner" type="button" class="rounded-md border border-gray-200 px-3.5 py-2 text-sm font-semibold text-slate-600 hover:bg-gray-50" @click="askParcours('ajourner')">Ajourner</button>
                            <button v-if="parcours.actions?.abandonner" type="button" class="rounded-md border border-red-200 px-3.5 py-2 text-sm font-semibold text-red-600 hover:bg-red-50" @click="askParcours('abandonner')">Abandon</button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-md border border-gray-200 bg-white">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                            <tr>
                                <th class="px-4 py-3">Année</th>
                                <th class="px-4 py-3">Niveau</th>
                                <th class="px-4 py-3">Statut</th>
                                <th class="px-4 py-3">Décision</th>
                                <th class="px-4 py-3">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="r in parcours.reinscriptions" :key="r.id" class="text-slate-600">
                                <td class="px-4 py-3 font-semibold text-slate-700">{{ r.annee_universitaire }}</td>
                                <td class="px-4 py-3">{{ r.niveau }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold" :class="reinscriptionBadge(r.statut_annee)">{{ r.statut_label }}</span>
                                </td>
                                <td class="px-4 py-3 max-w-xs truncate" :title="r.decision">{{ r.decision || '—' }}</td>
                                <td class="px-4 py-3 text-slate-400">{{ r.date_decision || '—' }}</td>
                            </tr>
                            <tr v-if="!parcours.reinscriptions?.length">
                                <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-400">Aucune réinscription enregistrée.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section v-if="can('finances.access')" class="space-y-6">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Finances</h3>
                        <p class="mt-1 text-xs text-gray-500">Droit d'inscription par niveau (D1, D2, D3) — réglé par virement bancaire.</p>
                    </div>
                    <button
                        type="button"
                        class="rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary"
                        @click="showForm = !showForm"
                    >
                        {{ showForm ? 'Fermer' : 'Enregistrer un paiement' }}
                    </button>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="rounded-xl border border-gray-200 bg-white p-5">
                        <p class="text-xs font-medium text-gray-500">Total dû</p>
                        <p class="mt-2 text-xl font-semibold tabular-nums text-gray-900">{{ formatMontant(finances.total_du) }}</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-white p-5">
                        <p class="text-xs font-medium text-gray-500">Encaissé</p>
                        <p class="mt-2 text-xl font-semibold tabular-nums text-ed-teal-dark">{{ formatMontant(finances.total_paye) }}</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-white p-5">
                        <p class="text-xs font-medium text-gray-500">Reste à payer</p>
                        <p class="mt-2 text-xl font-semibold tabular-nums" :class="finances.total_reste > 0 ? 'text-red-700' : 'text-gray-900'">
                            {{ formatMontant(finances.total_reste) }}
                        </p>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6">
                    <h4 class="text-sm font-semibold text-gray-700">Taux de recouvrement</h4>
                    <p class="mt-0.5 text-xs text-gray-400">Part des droits d'inscription déjà encaissés.</p>

                    <div class="mt-4 flex flex-wrap items-center gap-x-8 gap-y-5">
                        <div class="relative h-32 w-32 shrink-0">
                            <svg viewBox="0 0 120 120" class="h-full w-full -rotate-90">
                                <circle cx="60" cy="60" :r="ring.radius" fill="none" stroke="#f1f5f9" stroke-width="12" />
                                <circle
                                    cx="60"
                                    cy="60"
                                    :r="ring.radius"
                                    fill="none"
                                    stroke="#16826A"
                                    stroke-width="12"
                                    stroke-linecap="round"
                                    :stroke-dasharray="ring.circumference"
                                    :stroke-dashoffset="ring.offset"
                                    class="transition-all duration-500"
                                />
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-2xl font-bold tabular-nums text-gray-900">{{ tauxRecouvrement }}%</span>
                                <span class="text-[11px] text-gray-400">recouvré</span>
                            </div>
                        </div>

                        <div class="min-w-[200px] flex-1 space-y-3 text-sm">
                            <div class="flex items-center gap-2">
                                <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-ed-primary"></span>
                                <span class="text-gray-500">Encaissé</span>
                                <span class="ml-auto font-semibold tabular-nums text-gray-900">{{ formatMontant(finances.total_paye) }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-gray-200"></span>
                                <span class="text-gray-500">Reste à payer</span>
                                <span class="ml-auto font-semibold tabular-nums" :class="finances.total_reste > 0 ? 'text-red-700' : 'text-gray-900'">{{ formatMontant(finances.total_reste) }}</span>
                            </div>
                            <div class="flex items-center gap-2 border-t border-gray-100 pt-3">
                                <span class="text-gray-500">Total dû</span>
                                <span class="ml-auto font-semibold tabular-nums text-gray-900">{{ formatMontant(finances.total_du) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <form v-if="showForm" class="rounded-xl border border-gray-200 bg-white p-6" @submit.prevent="submitPaiement">
                    <h4 class="text-sm font-semibold text-gray-700">Nouveau paiement</h4>
                    <p class="mt-1 text-xs text-gray-500">Un seul enregistrement par niveau et année universitaire — réenregistrer met à jour la ligne existante.</p>

                    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Niveau</label>
                            <select v-model="form.niveau" class="mt-1 w-full rounded-xl border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary">
                                <option v-for="niveau in niveaux" :key="niveau" :value="niveau">{{ niveau }}</option>
                            </select>
                            <p v-if="form.errors.niveau" class="mt-1 text-xs text-red-600">{{ form.errors.niveau }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Année universitaire</label>
                            <input v-model="form.annee_universitaire" type="text" placeholder="2025-2026" class="mt-1 w-full rounded-xl border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" />
                            <p v-if="form.errors.annee_universitaire" class="mt-1 text-xs text-red-600">{{ form.errors.annee_universitaire }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Date du paiement</label>
                            <input v-model="form.date_paiement" type="date" class="mt-1 w-full rounded-xl border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" />
                            <p v-if="form.errors.date_paiement" class="mt-1 text-xs text-red-600">{{ form.errors.date_paiement }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Droit d'inscription dû (Ar)</label>
                            <input v-model="form.montant_du" type="number" min="0" step="any" class="mt-1 w-full rounded-xl border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" />
                            <p class="mt-1 text-xs text-gray-400">Montant standard : 700 000 Ar (modifiable).</p>
                            <p v-if="form.errors.montant_du" class="mt-1 text-xs text-red-600">{{ form.errors.montant_du }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Montant payé (Ar)</label>
                            <input v-model="form.montant_paye" type="number" min="0" step="any" class="mt-1 w-full rounded-xl border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" />
                            <p v-if="form.errors.montant_paye" class="mt-1 text-xs text-red-600">{{ form.errors.montant_paye }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Mode de paiement</label>
                            <div class="mt-1 flex items-center rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm font-medium text-gray-700">
                                Virement bancaire
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Référence du virement</label>
                            <input v-model="form.reference" type="text" placeholder="N° de transaction" class="mt-1 w-full rounded-xl border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" />
                            <p v-if="form.errors.reference" class="mt-1 text-xs text-red-600">{{ form.errors.reference }}</p>
                        </div>
                        <div class="lg:col-span-2">
                            <label class="block text-xs font-medium text-gray-600">Justificatif (PDF ou image, 5 Mo max)</label>
                            <input type="file" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 w-full rounded-xl border border-gray-200 px-3 py-2 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-ed-primary/10 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-ed-teal-dark" @change="onFile" />
                            <p v-if="form.errors.justificatif" class="mt-1 text-xs text-red-600">{{ form.errors.justificatif }}</p>
                        </div>
                        <div class="sm:col-span-2 lg:col-span-3">
                            <label class="block text-xs font-medium text-gray-600">Notes</label>
                            <textarea v-model="form.notes" rows="2" class="mt-1 w-full rounded-xl border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary"></textarea>
                            <p v-if="form.errors.notes" class="mt-1 text-xs text-red-600">{{ form.errors.notes }}</p>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center gap-3">
                        <button type="submit" :disabled="form.processing" class="rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary disabled:opacity-60">
                            {{ form.processing ? 'Enregistrement…' : 'Enregistrer' }}
                        </button>
                        <button type="button" class="text-sm font-medium text-gray-500 hover:text-gray-700" @click="showForm = false">Annuler</button>
                    </div>
                </form>

                <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <tr>
                                <th class="px-4 py-3">Niveau</th>
                                <th class="px-4 py-3">Année</th>
                                <th class="px-4 py-3 text-right">Dû</th>
                                <th class="px-4 py-3 text-right">Payé</th>
                                <th class="px-4 py-3 text-right">Reste</th>
                                <th class="px-4 py-3">Statut</th>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Justificatif</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="paiement in paiements" :key="paiement.id" class="text-gray-700">
                                <td class="px-4 py-3 font-semibold text-gray-900">{{ paiement.niveau }}</td>
                                <td class="px-4 py-3">{{ paiement.annee_universitaire }}</td>
                                <td class="px-4 py-3 text-right tabular-nums">{{ formatMontant(paiement.montant_du) }}</td>
                                <td class="px-4 py-3 text-right tabular-nums">{{ formatMontant(paiement.montant_paye) }}</td>
                                <td class="px-4 py-3 text-right tabular-nums" :class="paiement.reste > 0 ? 'text-red-700' : 'text-gray-500'">{{ formatMontant(paiement.reste) }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold" :class="statutClasses[paiement.statut]">
                                        {{ statutLabels[paiement.statut] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-500">{{ paiement.date_paiement_human || '—' }}</td>
                                <td class="px-4 py-3">
                                    <a v-if="paiement.justificatif_url" :href="paiement.justificatif_url" target="_blank" rel="noopener" class="font-medium text-ed-primary hover:text-ed-secondary">
                                        Voir
                                    </a>
                                    <span v-else class="text-gray-400">—</span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <button v-if="can('records.delete')" type="button" class="text-xs font-medium text-red-600 hover:text-red-700" @click="deletePaiement(paiement)">
                                        Supprimer
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="!paiements.length">
                                <td colspan="9" class="px-4 py-8 text-center text-sm text-gray-500">Aucun paiement enregistré pour ce doctorant.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <ConfirmDialog
            :show="showNotifyConfirm"
            title="Envoyer le rapport financier"
            :message="notifyMessage"
            confirm-label="Envoyer l'e-mail"
            :processing="notifyForm.processing"
            @confirm="confirmNotify"
            @cancel="showNotifyConfirm = false"
        />

        <ConfirmDialog
            :show="paiementToDelete !== null"
            title="Supprimer le paiement"
            :message="deleteMessage"
            confirm-label="Supprimer"
            variant="danger"
            :processing="deletingPaiement"
            @confirm="confirmDeletePaiement"
            @cancel="paiementToDelete = null"
        />

        <ConfirmDialog
            :show="parcoursAction !== null"
            :title="currentParcoursMeta?.title || ''"
            :message="currentParcoursMeta?.message || ''"
            :confirm-label="currentParcoursMeta?.confirm || 'Confirmer'"
            :variant="currentParcoursMeta?.variant || 'primary'"
            :processing="parcoursForm.processing"
            @confirm="runParcours"
            @cancel="parcoursAction = null"
        >
            <div class="mt-3">
                <label class="mb-1 block text-xs font-medium text-gray-600">Décision / observation (optionnel)</label>
                <textarea v-model="parcoursForm.decision" rows="2" class="w-full rounded-xl border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" placeholder="Avis du conseil, mention…"></textarea>
                <p v-if="parcoursForm.errors.parcours" class="mt-1 text-xs text-red-600">{{ parcoursForm.errors.parcours }}</p>
            </div>
        </ConfirmDialog>
    </AdminLayout>
</template>
