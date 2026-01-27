<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const props = defineProps({
    doctorants: Array,
    eads: Array,
    specialites: Array,
    encadrants: Array,
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
});

const selectedEncadrantId = ref('');
const selectedRole = ref('directeur');
const doctorantSearch = ref('');
const showDoctorantOptions = ref(false);
const activeDoctorantIndex = ref(0);
const editorId = 'these-resume-editor';

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

const slugify = (value) => {
    return value
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
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
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 md:text-xl">Nouvelle these</h2>
                    <p class="mt-1 text-xs text-slate-500 md:text-sm">Renseignez les informations principales de la these.</p>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.theses.index')" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Retour
                    </Link>
                    <button type="button" class="rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary" :disabled="form.processing" @click="submit">
                        Enregistrer
                    </button>
                </div>
            </div>
        </template>

        <Head title="Nouvelle these" />
        <FlashMessage />

        <nav class="text-xs text-slate-500">
            <ol class="flex flex-wrap items-center gap-2">
                <li><Link :href="route('admin.dashboard')" class="hover:text-ed-primary">Dashboard</Link></li>
                <li>/</li>
                <li><Link :href="route('admin.theses.index')" class="hover:text-ed-primary">Theses</Link></li>
                <li>/</li>
                <li class="font-semibold text-slate-900">Creer</li>
            </ol>
        </nav>

        <form class="grid gap-6 lg:grid-cols-3" @submit.prevent="submit">
            <section class="space-y-6 lg:col-span-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h3 class="text-sm font-semibold text-slate-700">Informations generales</h3>
                    <p class="mt-1 text-xs text-slate-500">Sujet, rattachement et calendrier.</p>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-slate-700">Doctorant *</label>
                            <div class="relative mt-2">
                                <input
                                    v-model="doctorantSearch"
                                    type="text"
                                    placeholder="Rechercher un doctorant..."
                                    class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm"
                                    @focus="showDoctorantOptions = true"
                                    @blur="closeDoctorantOptions"
                                    @keydown="handleDoctorantKeydown"
                                />
                                <div
                                    v-if="showDoctorantOptions"
                                    class="absolute z-10 mt-2 max-h-64 w-full overflow-auto rounded-xl border border-slate-200 bg-white text-sm shadow-lg"
                                >
                                    <div v-if="doctorantSearch.trim().length < 1" class="px-3 py-2 text-xs text-slate-500">
                                        Commencez a taper pour rechercher un doctorant.
                                    </div>
                                    <template v-else>
                                        <button
                                            v-for="(doctorant, index) in limitedDoctorants"
                                            :key="doctorant.id"
                                            type="button"
                                            class="flex w-full items-center justify-between gap-2 px-3 py-2 text-left hover:bg-slate-50"
                                            :class="activeDoctorantIndex === index ? 'bg-slate-50' : ''"
                                            @mousedown.prevent="selectDoctorant(doctorant)"
                                        >
                                            <span class="font-semibold text-slate-900">{{ doctorant.name }}</span>
                                            <span class="text-xs text-slate-500">{{ doctorant.matricule }}</span>
                                        </button>
                                        <div v-if="!filteredDoctorants.length" class="px-3 py-2 text-xs text-slate-500">Aucun resultat</div>
                                        <div v-else-if="filteredDoctorants.length > 20" class="px-3 py-2 text-xs text-slate-400">
                                            Affichage limite a 20 resultats. Continuez a taper pour affiner.
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <p v-if="form.errors.doctorant_id" class="mt-2 text-xs text-red-600">{{ form.errors.doctorant_id }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-slate-700">Sujet de these *</label>
                            <textarea v-model="form.sujet_these" rows="3" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2"></textarea>
                            <p v-if="form.errors.sujet_these" class="mt-2 text-xs text-red-600">{{ form.errors.sujet_these }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-slate-700">Resume (Markdown ou texte enrichi)</label>
                            <div class="mt-2 rounded-xl border border-slate-200 bg-white p-2">
                                <textarea :id="editorId" class="w-full"></textarea>
                            </div>
                            <p class="mt-2 text-xs text-slate-500">Editeur riche avec alignement et mise en forme.</p>
                            <p v-if="form.errors.resume_these" class="mt-2 text-xs text-red-600">{{ form.errors.resume_these }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-slate-700">EAD</label>
                            <select v-model="form.ead_id" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2">
                                <option value="">Selectionner un EAD</option>
                                <option v-for="item in eads" :key="item.id" :value="item.id">{{ item.nom }}</option>
                            </select>
                            <p v-if="form.errors.ead_id" class="mt-2 text-xs text-red-600">{{ form.errors.ead_id }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Specialite</label>
                            <select v-model="form.specialite_id" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2">
                                <option value="">Selectionner une specialite</option>
                                <option v-for="item in filteredSpecialites" :key="item.id" :value="item.id">{{ item.nom }}</option>
                            </select>
                            <p v-if="form.errors.specialite_id" class="mt-2 text-xs text-red-600">{{ form.errors.specialite_id }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Statut *</label>
                            <select v-model="form.statut" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2">
                                <option v-for="item in statuts" :key="item" :value="item">{{ item }}</option>
                            </select>
                            <p v-if="form.errors.statut" class="mt-2 text-xs text-red-600">{{ form.errors.statut }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Universite soutenance</label>
                            <input v-model="form.universite_soutenance" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" />
                            <p v-if="form.errors.universite_soutenance" class="mt-2 text-xs text-red-600">{{ form.errors.universite_soutenance }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Date debut</label>
                            <input v-model="form.date_debut" type="date" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" />
                            <p v-if="form.errors.date_debut" class="mt-2 text-xs text-red-600">{{ form.errors.date_debut }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Date prevue fin</label>
                            <input v-model="form.date_prevue_fin" type="date" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" />
                            <p v-if="form.errors.date_prevue_fin" class="mt-2 text-xs text-red-600">{{ form.errors.date_prevue_fin }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Date publication</label>
                            <input v-model="form.date_publication" type="date" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" />
                            <p v-if="form.errors.date_publication" class="mt-2 text-xs text-red-600">{{ form.errors.date_publication }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h3 class="text-sm font-semibold text-slate-700">Encadrants</h3>
                    <p class="mt-1 text-xs text-slate-500">Ajoutez le directeur et/ou le codirecteur.</p>
                    <div class="mt-4 flex flex-wrap items-end gap-3">
                        <div class="min-w-[220px] flex-1">
                            <label class="text-xs font-semibold text-slate-600">Encadrant</label>
                            <select v-model="selectedEncadrantId" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2">
                                <option value="">Selectionner</option>
                                <option v-for="item in encadrants" :key="item.id" :value="item.id">{{ item.name }} {{ item.grade ? `(${item.grade})` : '' }}</option>
                            </select>
                        </div>
                        <div class="min-w-[160px]">
                            <label class="text-xs font-semibold text-slate-600">Role</label>
                            <select v-model="selectedRole" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2">
                                <option value="directeur">Directeur</option>
                                <option value="codirecteur">Codirecteur</option>
                            </select>
                        </div>
                        <button type="button" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white" @click="addEncadrant">Ajouter</button>
                    </div>

                    <div class="mt-4 space-y-2">
                        <div v-for="item in form.encadrants" :key="item.id" class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-2 text-sm">
                            <div>
                                <p class="font-semibold text-slate-900">
                                    {{ encadrants.find((e) => e.id === item.id)?.name || 'Encadrant' }}
                                </p>
                                <p class="text-xs text-slate-500">{{ item.role }}</p>
                            </div>
                            <button type="button" class="text-xs font-semibold text-red-600" @click="removeEncadrant(item.id)">Retirer</button>
                        </div>
                        <p v-if="!form.encadrants.length" class="text-xs text-slate-500">Aucun encadrant associe.</p>
                    </div>
                </div>
            </section>

            <aside class="space-y-6">
                <section class="rounded-2xl border border-slate-200 bg-white p-6">
                    <label class="text-sm font-semibold text-slate-700">Fichier PDF</label>
                    <div class="mt-2 space-y-3">
                        <div>
                            <label class="text-xs font-semibold text-slate-600">Uploader depuis le PC</label>
                            <input
                                type="file"
                                accept="application/pdf"
                                class="mt-2 w-full text-sm"
                                @change="(e) => (form.these_file = e.target.files?.[0] || null)"
                            />
                            <p v-if="form.errors.these_file" class="mt-1 text-xs text-red-600">{{ form.errors.these_file }}</p>
                            <p v-if="form.slug" class="mt-1 text-xs text-slate-500">Slug genere: {{ form.slug }}</p>
                            <p v-if="form.errors.slug" class="mt-1 text-xs text-red-600">{{ form.errors.slug }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-600">Ou choisir un fichier existant</label>
                            <select v-model="form.media_id" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                                <option value="">Aucun</option>
                                <option v-for="doc in documents" :key="doc.id" :value="doc.id">{{ doc.name }}</option>
                            </select>
                            <p v-if="form.errors.media_id" class="mt-1 text-xs text-red-600">{{ form.errors.media_id }}</p>
                        </div>
                    </div>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-6">
                    <label class="text-sm font-semibold text-slate-700">Mots cles</label>
                    <input v-model="form.mots_cles" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2" placeholder="mot1, mot2, mot3" />
                    <p v-if="form.errors.mots_cles" class="mt-2 text-xs text-red-600">{{ form.errors.mots_cles }}</p>
                </section>
                <section class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5 text-xs text-slate-600">
                    Astuce: renseignez un resume clair pour faciliter la recherche.
                </section>
            </aside>
        </form>

        <div class="sticky bottom-4 z-20 mt-8">
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white/95 px-4 py-3 shadow-lg backdrop-blur">
                <p class="text-xs text-slate-500">Pensez a enregistrer vos modifications.</p>
                <div class="flex items-center gap-2">
                    <Link :href="route('admin.theses.index')" class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
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
