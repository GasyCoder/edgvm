<script setup>
import { computed, ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const form = useForm({
    files: [],
});

const fileInput = ref(null);
const dragging = ref(false);
const localError = ref('');

const filesCount = computed(() => form.files.length);

const handleFiles = (fileList) => {
    const incoming = Array.from(fileList);
    const merged = [...form.files, ...incoming];
    if (merged.length > 20) {
        localError.value = 'Maximum 20 fichiers autorises.';
        form.files = merged.slice(0, 20);
        return;
    }
    localError.value = '';
    form.files = merged;
};

const onFileChange = (event) => {
    handleFiles(event.target.files || []);
};

const onDrop = (event) => {
    dragging.value = false;
    handleFiles(event.dataTransfer.files || []);
};

const triggerBrowse = () => {
    fileInput.value?.click();
};

const removeFile = (index) => {
    form.files.splice(index, 1);
};

const submit = () => {
    localError.value = '';
    form.post(route('admin.media.store'), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

const filePreview = (file) => {
    if (!file || !file.type) return null;
    if (!file.type.startsWith('image/')) return null;
    return URL.createObjectURL(file);
};

</script>

<template>
    <AdminLayout>
        <template #header>
        <div>
            <h2 class="text-lg font-semibold text-slate-900 md:text-xl">Upload media</h2>
            <p class="mt-1 text-xs text-slate-500 md:text-sm">Glisser-deposer ou selectionner des fichiers.</p>
        </div>
        </template>

        <Head title="Upload media" />
        <FlashMessage />

    <div class="space-y-6">
        <nav class="text-xs text-slate-500">
            <ol class="flex flex-wrap items-center gap-2">
                <li><Link :href="route('admin.dashboard')" class="hover:text-ed-primary">Dashboard</Link></li>
                <li>/</li>
                <li><Link :href="route('admin.media.index')" class="hover:text-ed-primary">Mediatheque</Link></li>
                <li>/</li>
                <li class="font-semibold text-slate-900">Upload</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-slate-900">Selectionner des fichiers</h3>

                    <div
                        class="mt-4 rounded-2xl border-2 border-dashed border-slate-300 px-6 py-10 text-center transition"
                        :class="dragging ? 'border-ed-primary bg-ed-primary/5' : ''"
                        @dragover.prevent="dragging = true"
                        @dragleave.prevent="dragging = false"
                        @drop.prevent="onDrop"
                        @click="triggerBrowse"
                    >
                        <p class="text-sm text-slate-600">
                            <span class="font-semibold text-ed-primary">Cliquez pour parcourir</span>
                            ou glissez vos fichiers ici.
                        </p>
                        <p class="mt-1 text-xs text-slate-400">PNG, JPG, GIF, PDF, DOC, XLS, PPT jusqu'a 10MB.</p>
                        <input ref="fileInput" type="file" multiple class="sr-only" @change="onFileChange" />
                    </div>

                    <p v-if="localError" class="mt-3 text-sm text-red-600">{{ localError }}</p>
                    <p v-if="form.errors.files" class="mt-3 text-sm text-red-600">{{ form.errors.files }}</p>
                    <p v-if="form.errors['files.*']" class="mt-3 text-sm text-red-600">{{ form.errors['files.*'] }}</p>
                </div>

                <div v-if="filesCount" class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-slate-900">Fichiers selectionnes ({{ filesCount }})</h3>
                    <div class="mt-4 space-y-3">
                        <div
                            v-for="(file, index) in form.files"
                            :key="index"
                            class="flex items-center gap-4 rounded-xl border border-slate-200 p-3"
                        >
                            <div class="h-14 w-14 flex-shrink-0 overflow-hidden rounded-xl bg-slate-100">
                                <img v-if="filePreview(file)" :src="filePreview(file)" class="h-full w-full object-cover" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-slate-900">{{ file.name }}</p>
                                <p class="text-xs text-slate-500">{{ (file.size / 1024).toFixed(2) }} KB</p>
                            </div>
                            <button type="button" class="inline-flex items-center gap-1 text-xs font-semibold text-red-600 hover:text-red-700" @click="removeFile(index)">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 011-1h4a1 1 0 011 1m-7 0h8" />
                                </svg>
                                Supprimer
                            </button>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <button
                            type="button"
                            class="flex-1 rounded-xl bg-ed-primary px-6 py-3 text-sm font-semibold text-white hover:bg-ed-secondary disabled:cursor-not-allowed disabled:opacity-60"
                            :disabled="form.processing"
                            @click="submit"
                        >
                            <span v-if="!form.processing">Uploader {{ filesCount }} fichier(s)</span>
                            <span v-else>Upload en cours...</span>
                        </button>
                        <Link
                            :href="route('admin.media.index')"
                            class="rounded-xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                        >
                            Annuler
                        </Link>
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-slate-900">Formats acceptes</h3>
                    <ul class="mt-3 space-y-2 text-xs text-slate-500">
                        <li>Images: JPG, PNG, GIF, SVG</li>
                        <li>Documents: PDF, DOC, XLS, PPT</li>
                        <li>Videos: MP4, WebM</li>
                    </ul>
                </div>
                <div class="rounded-2xl border border-blue-200 bg-blue-50 p-6 text-xs text-blue-800">
                    Astuce: optimisez vos images avant upload pour un chargement plus rapide.
                </div>
            </aside>
        </div>
    </div>
    </AdminLayout>
</template>
