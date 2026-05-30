<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Card from '@/Components/Admin/Card.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const props = defineProps({
    doctorants: Array,
    eads: Array,
    specialites: Array,
    encadrants: Array,
    jurys: { type: Array, default: () => [] },
    juryRoles: { type: Array, default: () => [] },
    documents: Array,
    statuts: Array,
    defaults: Object,
});

const form = useForm({
    doctorant_id: '',
    sujet_these: '',
    specialite_id: '',
    ead_id: '',
    universite_soutenance: '',
    date_debut: '',
    date_prevue_fin: '',
    date_publication: '',
    statut: props.defaults?.statut || 'en_cours',
    media_id: '',
    slug: '',
    these_file: null,
    resume_these: '',
    mots_cles: '',
    encadrants: [],
    jurys: [],
});

const juryRoleLabels = {
    president: 'Président', rapporteur: 'Rapporteur', examinateur: 'Examinateur', invite: 'Invité',
};
const juryRoleLabel = (v) => juryRoleLabels[v] || v;

const selectedEncadrantId = ref('');
const selectedRole = ref('directeur');
const doctorantSearch = ref('');
const showDoctorantOptions = ref(false);
const activeDoctorantIndex = ref(0);
const editorId = 'these-resume-editor';

const statutLabels = {
    en_cours: 'En cours', soutenue: 'Soutenue', preparation: 'Préparation',
    redaction: 'Rédaction', suspendue: 'Suspendue', abandonnee: 'Abandonnée',
};
const statutLabel = (v) => statutLabels[v] || v;

const filteredSpecialites = computed(() => {
    if (!form.ead_id) {
        return props.specialites || [];
    }

    return (props.specialites || []).filter((item) => item.ead_id === form.ead_id);
});

const filteredDoctorants = computed(() => {
    const term = doctorantSearch.value.trim().toLowerCase();

    if (term.length < 1) {
        return [];
    }

    return (props.doctorants || []).filter((doctorant) => {
        const name = doctorant.name?.toLowerCase() ?? '';
        const matricule = doctorant.matricule?.toLowerCase() ?? '';

        return name.includes(term) || matricule.includes(term);
    });
});

const limitedDoctorants = computed(() => filteredDoctorants.value.slice(0, 20));

watch(limitedDoctorants, () => {
    activeDoctorantIndex.value = 0;
});

const selectDoctorant = (doctorant) => {
    form.doctorant_id = doctorant.id;
    doctorantSearch.value = `${doctorant.name} (${doctorant.matricule})`;
    showDoctorantOptions.value = false;
};

const handleDoctorantKeydown = (event) => {
    if (!showDoctorantOptions.value || doctorantSearch.value.trim().length < 1) {
        return;
    }

    if (!limitedDoctorants.value.length) {
        return;
    }

    if (event.key === 'ArrowDown') {
        event.preventDefault();
        activeDoctorantIndex.value = (activeDoctorantIndex.value + 1) % limitedDoctorants.value.length;
    } else if (event.key === 'ArrowUp') {
        event.preventDefault();
        activeDoctorantIndex.value =
            (activeDoctorantIndex.value - 1 + limitedDoctorants.value.length) % limitedDoctorants.value.length;
    } else if (event.key === 'Enter') {
        event.preventDefault();
        selectDoctorant(limitedDoctorants.value[activeDoctorantIndex.value]);
    } else if (event.key === 'Escape') {
        showDoctorantOptions.value = false;
    }
};

const closeDoctorantOptions = () => {
    setTimeout(() => {
        showDoctorantOptions.value = false;
    }, 150);
};

const initTinyMce = () => {
    if (!window.tinymce) return;
    window.tinymce.remove();
    window.tinymce.init({
        selector: `#${editorId}`,
        height: 320,
        menubar: false,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'table', 'help', 'wordcount',
        ],
        toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link | code | help',
        content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; }',
        setup: (editor) => {
            editor.on('init', () => {
                editor.setContent(form.resume_these || '');
            });
            editor.on('change', () => {
                form.resume_these = editor.getContent();
            });
        },
    });
};

onMounted(() => initTinyMce());
onBeforeUnmount(() => {
    if (window.tinymce) {
        window.tinymce.remove();
    }
});

const addEncadrant = () => {
    if (!selectedEncadrantId.value) return;

    if (form.encadrants.some((item) => String(item.id) === String(selectedEncadrantId.value))) {
        return;
    }

    form.encadrants.push({
        id: Number(selectedEncadrantId.value),
        role: selectedRole.value,
    });

    selectedEncadrantId.value = '';
    selectedRole.value = 'directeur';
};

const removeEncadrant = (id) => {
    form.encadrants = form.encadrants.filter((item) => item.id !== id);
};

const selectedJuryId = ref('');
const selectedJuryRole = ref('examinateur');

const addJury = () => {
    if (!selectedJuryId.value) return;

    if (form.jurys.some((item) => String(item.id) === String(selectedJuryId.value))) {
        return;
    }

    form.jurys.push({
        id: Number(selectedJuryId.value),
        role: selectedJuryRole.value,
    });

    selectedJuryId.value = '';
    selectedJuryRole.value = 'examinateur';
};

const removeJury = (id) => {
    form.jurys = form.jurys.filter((item) => item.id !== id);
};

const slugify = (value) => {
    return value
        .toLowerCase()
        .normalize('NFD')
        .replace(/[̀-ͯ]/g, '')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/(^-|-$)/g, '');
};

watch(() => form.these_file, (file) => {
    if (!file) {
        form.slug = '';
        return;
    }

    const baseName = file.name.replace(/\.[^/.]+$/, '');
    form.slug = slugify(baseName);
});

const submit = () => {
    form.post(route('admin.theses.store'), {
        forceFormData: true,
    });
};
</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-bold text-slate-700">Nouvelle thèse</h1>
        </template>

        <Head title="Nouvelle thèse" />
        <FlashMessage />

        <div class="space-y-6">
            <nav class="text-xs text-slate-400">
                <ol class="flex flex-wrap items-center gap-2">
                    <li><Link :href="route('admin.dashboard')" class="hover:text-ed-primary">Tableau de bord</Link></li>
                    <li>/</li>
                    <li><Link :href="route('admin.theses.index')" class="hover:text-ed-primary">Thèses</Link></li>
                    <li>/</li>
                    <li class="font-semibold text-slate-600">Créer</li>
                </ol>
            </nav>

            <form class="grid gap-6 lg:grid-cols-3" @submit.prevent="submit">
                <section class="space-y-6 lg:col-span-2">
                    <Card title="Informations générales" subtitle="Sujet, rattachement et calendrier.">
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label class="text-xs font-medium text-slate-600">Doctorant <span class="text-red-500">*</span></label>
                                <div class="relative mt-1.5">
                                    <input
                                        v-model="doctorantSearch"
                                        type="text"
                                        placeholder="Rechercher un doctorant…"
                                        class="w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary"
                                        @focus="showDoctorantOptions = true"
                                        @blur="closeDoctorantOptions"
                                        @keydown="handleDoctorantKeydown"
                                    />
                                    <div v-if="showDoctorantOptions" class="absolute z-10 mt-2 max-h-64 w-full overflow-auto rounded-md border border-gray-200 bg-white text-sm shadow-lg">
                                        <div v-if="doctorantSearch.trim().length < 1" class="px-3 py-2 text-xs text-slate-400">
                                            Commencez à taper pour rechercher un doctorant.
                                        </div>
                                        <template v-else>
                                            <button
                                                v-for="(doctorant, index) in limitedDoctorants"
                                                :key="doctorant.id"
                                                type="button"
                                                class="flex w-full items-center justify-between gap-2 px-3 py-2 text-left hover:bg-gray-50"
                                                :class="activeDoctorantIndex === index ? 'bg-gray-50' : ''"
                                                @mousedown.prevent="selectDoctorant(doctorant)"
                                            >
                                                <span class="font-semibold text-slate-700">{{ doctorant.name }}</span>
                                                <span class="text-xs text-slate-400">{{ doctorant.matricule }}</span>
                                            </button>
                                            <div v-if="!filteredDoctorants.length" class="px-3 py-2 text-xs text-slate-400">Aucun résultat</div>
                                            <div v-else-if="filteredDoctorants.length > 20" class="px-3 py-2 text-xs text-slate-400">
                                                Affichage limité à 20 résultats. Continuez à taper pour affiner.
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                <p v-if="form.errors.doctorant_id" class="mt-1 text-xs text-red-600">{{ form.errors.doctorant_id }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-xs font-medium text-slate-600">Sujet de thèse <span class="text-red-500">*</span></label>
                                <textarea v-model="form.sujet_these" rows="3" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary"></textarea>
                                <p v-if="form.errors.sujet_these" class="mt-1 text-xs text-red-600">{{ form.errors.sujet_these }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-xs font-medium text-slate-600">Résumé</label>
                                <div class="mt-1.5 overflow-hidden rounded-md border border-gray-200">
                                    <textarea :id="editorId" class="w-full"></textarea>
                                </div>
                                <p v-if="form.errors.resume_these" class="mt-1 text-xs text-red-600">{{ form.errors.resume_these }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-xs font-medium text-slate-600">Équipe d'accueil</label>
                                <select v-model="form.ead_id" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary">
                                    <option value="">Sélectionner une équipe</option>
                                    <option v-for="item in eads" :key="item.id" :value="item.id">{{ item.sigle ? `${item.sigle} — ${item.nom}` : item.nom }}</option>
                                </select>
                                <p v-if="form.errors.ead_id" class="mt-1 text-xs text-red-600">{{ form.errors.ead_id }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-slate-600">Spécialité</label>
                                <select v-model="form.specialite_id" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary">
                                    <option value="">Sélectionner une spécialité</option>
                                    <option v-for="item in filteredSpecialites" :key="item.id" :value="item.id">{{ item.nom }}</option>
                                </select>
                                <p v-if="form.errors.specialite_id" class="mt-1 text-xs text-red-600">{{ form.errors.specialite_id }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-slate-600">Statut <span class="text-red-500">*</span></label>
                                <select v-model="form.statut" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary">
                                    <option v-for="item in statuts" :key="item" :value="item">{{ statutLabel(item) }}</option>
                                </select>
                                <p v-if="form.errors.statut" class="mt-1 text-xs text-red-600">{{ form.errors.statut }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-slate-600">Université de soutenance</label>
                                <input v-model="form.universite_soutenance" type="text" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" />
                                <p v-if="form.errors.universite_soutenance" class="mt-1 text-xs text-red-600">{{ form.errors.universite_soutenance }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-slate-600">Date de début</label>
                                <input v-model="form.date_debut" type="date" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" />
                                <p v-if="form.errors.date_debut" class="mt-1 text-xs text-red-600">{{ form.errors.date_debut }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-slate-600">Date prévue de fin</label>
                                <input v-model="form.date_prevue_fin" type="date" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" />
                                <p v-if="form.errors.date_prevue_fin" class="mt-1 text-xs text-red-600">{{ form.errors.date_prevue_fin }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-slate-600">Date de publication</label>
                                <input v-model="form.date_publication" type="date" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" />
                                <p v-if="form.errors.date_publication" class="mt-1 text-xs text-red-600">{{ form.errors.date_publication }}</p>
                            </div>
                        </div>
                    </Card>
                </section>

                <aside class="space-y-6">
                    <Card title="Encadrants" subtitle="Ajoutez le directeur et/ou le codirecteur.">
                        <div class="flex flex-wrap items-end gap-3">
                            <div class="min-w-[220px] flex-1">
                                <label class="text-xs font-medium text-slate-600">Encadrant</label>
                                <select v-model="selectedEncadrantId" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary">
                                    <option value="">Sélectionner</option>
                                    <option v-for="item in encadrants" :key="item.id" :value="item.id">{{ item.name }} {{ item.grade ? `(${item.grade})` : '' }}</option>
                                </select>
                            </div>
                            <div class="min-w-[160px]">
                                <label class="text-xs font-medium text-slate-600">Rôle</label>
                                <select v-model="selectedRole" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary">
                                    <option value="directeur">Directeur</option>
                                    <option value="codirecteur">Codirecteur</option>
                                </select>
                            </div>
                            <button type="button" class="rounded-md bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary" @click="addEncadrant">Ajouter</button>
                        </div>

                        <div class="mt-4 space-y-2">
                            <div v-for="item in form.encadrants" :key="item.id" class="flex items-center justify-between rounded-md border border-gray-100 bg-gray-50 px-4 py-2 text-sm">
                                <div>
                                    <p class="font-semibold text-slate-700">{{ encadrants.find((e) => e.id === item.id)?.name || 'Encadrant' }}</p>
                                    <p class="text-xs capitalize text-slate-400">{{ item.role }}</p>
                                </div>
                                <button type="button" class="text-xs font-semibold text-red-600 hover:text-red-700" @click="removeEncadrant(item.id)">Retirer</button>
                            </div>
                            <p v-if="!form.encadrants.length" class="text-xs text-slate-400">Aucun encadrant associé.</p>
                        </div>
                    </Card>

                    <Card title="Membres du jury" subtitle="Optionnel — peut aussi être renseigné plus tard.">
                        <div v-if="jurys.length" class="flex flex-wrap items-end gap-3">
                            <div class="min-w-[220px] flex-1">
                                <label class="text-xs font-medium text-slate-600">Membre du jury</label>
                                <select v-model="selectedJuryId" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary">
                                    <option value="">Sélectionner</option>
                                    <option v-for="item in jurys" :key="item.id" :value="item.id">{{ item.nom }} {{ item.grade ? `(${item.grade})` : '' }}</option>
                                </select>
                            </div>
                            <div class="min-w-[160px]">
                                <label class="text-xs font-medium text-slate-600">Rôle</label>
                                <select v-model="selectedJuryRole" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary">
                                    <option v-for="role in juryRoles" :key="role" :value="role">{{ juryRoleLabel(role) }}</option>
                                </select>
                            </div>
                            <button type="button" class="rounded-md bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary" @click="addJury">Ajouter</button>
                        </div>
                        <p v-else class="text-xs text-slate-400">
                            Aucun membre de jury enregistré.
                            <Link :href="route('admin.jurys.create')" class="font-semibold text-ed-primary hover:text-ed-secondary">En créer un</Link>.
                        </p>

                        <div v-if="form.jurys.length" class="mt-4 space-y-2">
                            <div v-for="item in form.jurys" :key="item.id" class="flex items-center justify-between rounded-md border border-gray-100 bg-gray-50 px-4 py-2 text-sm">
                                <div>
                                    <p class="font-semibold text-slate-700">{{ jurys.find((j) => j.id === item.id)?.nom || 'Membre' }}</p>
                                    <p class="text-xs text-slate-400">{{ juryRoleLabel(item.role) }}</p>
                                </div>
                                <button type="button" class="text-xs font-semibold text-red-600 hover:text-red-700" @click="removeJury(item.id)">Retirer</button>
                            </div>
                        </div>
                    </Card>

                    <Card title="Document de thèse (PDF)">
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-medium text-slate-600">Téléverser depuis l'ordinateur</label>
                                <input type="file" accept="application/pdf" class="mt-1.5 w-full rounded-md border border-gray-200 px-3 py-2 text-sm file:mr-3 file:rounded file:border-0 file:bg-ed-primary/10 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-ed-teal-dark" @change="(e) => (form.these_file = e.target.files?.[0] || null)" />
                                <p v-if="form.errors.these_file" class="mt-1 text-xs text-red-600">{{ form.errors.these_file }}</p>
                                <p v-if="form.slug" class="mt-1 text-xs text-slate-400">Slug généré : {{ form.slug }}</p>
                                <p v-if="form.errors.slug" class="mt-1 text-xs text-red-600">{{ form.errors.slug }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-slate-600">Ou choisir un fichier existant</label>
                                <select v-model="form.media_id" class="mt-1.5 w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary">
                                    <option value="">Aucun</option>
                                    <option v-for="doc in documents" :key="doc.id" :value="doc.id">{{ doc.name }}</option>
                                </select>
                                <p v-if="form.errors.media_id" class="mt-1 text-xs text-red-600">{{ form.errors.media_id }}</p>
                            </div>
                        </div>
                    </Card>

                    <Card title="Mots-clés">
                        <input v-model="form.mots_cles" type="text" class="w-full rounded-md border-gray-200 text-sm focus:border-ed-primary focus:ring-ed-primary" placeholder="mot1, mot2, mot3" />
                        <p v-if="form.errors.mots_cles" class="mt-1 text-xs text-red-600">{{ form.errors.mots_cles }}</p>
                        <p class="mt-2 text-xs text-slate-400">Séparez les mots-clés par des virgules pour faciliter la recherche.</p>
                    </Card>
                </aside>
            </form>

            <div class="sticky bottom-4 z-20 flex flex-wrap items-center justify-between gap-3 rounded-md border border-gray-200 bg-white px-4 py-3 shadow-sm">
                <p class="text-xs text-slate-400">Vérifiez les informations avant d'enregistrer.</p>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.theses.index')" class="rounded-md border border-gray-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-gray-50">Annuler</Link>
                    <button type="button" class="rounded-md bg-ed-primary px-5 py-2 text-sm font-semibold text-white hover:bg-ed-secondary disabled:opacity-60" :disabled="form.processing" @click="submit">
                        {{ form.processing ? 'Enregistrement…' : 'Enregistrer la thèse' }}
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
