<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { onBeforeUnmount, ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const props = defineProps({
    settings: Object,
    security: Object,
});

const generalForm = useForm({
    site_name: props.settings?.site_name ?? '',
    site_baseline: props.settings?.site_baseline ?? '',
    contact_email: props.settings?.contact_email ?? '',
    contact_phone: props.settings?.contact_phone ?? '',
    contact_address: props.settings?.contact_address ?? '',
    social_facebook: props.settings?.social_facebook ?? '',
    social_twitter: props.settings?.social_twitter ?? '',
    social_linkedin: props.settings?.social_linkedin ?? '',
    social_youtube: props.settings?.social_youtube ?? '',
    social_instagram: props.settings?.social_instagram ?? '',
});

const seoForm = useForm({
    meta_title: props.settings?.meta_title ?? '',
    meta_description: props.settings?.meta_description ?? '',
    meta_keywords: props.settings?.meta_keywords ?? '',
    sitemap_url: props.settings?.sitemap_url ?? '',
});

const maintenanceForm = useForm({
    maintenance_enabled: props.settings?.maintenance_enabled ?? false,
    maintenance_message: props.settings?.maintenance_message ?? '',
});

const statsForm = useForm({
    message_direction_doctorants: props.settings?.message_direction_doctorants ?? 0,
    message_direction_equipes: props.settings?.message_direction_equipes ?? 0,
    message_direction_theses: props.settings?.message_direction_theses ?? 0,
});

const mediaForm = useForm({
    logo: null,
    favicon: null,
});

const logoPreviewUrl = ref(null);
const faviconPreviewUrl = ref(null);
const logoInput = ref(null);
const faviconInput = ref(null);
const logoDragging = ref(false);
const faviconDragging = ref(false);

const handleFile = (file, type) => {
    if (!file || !file.type.startsWith('image/')) return;

    if (type === 'logo') {
        mediaForm.logo = file;
        if (logoPreviewUrl.value) URL.revokeObjectURL(logoPreviewUrl.value);
        logoPreviewUrl.value = URL.createObjectURL(file);
    } else {
        mediaForm.favicon = file;
        if (faviconPreviewUrl.value) URL.revokeObjectURL(faviconPreviewUrl.value);
        faviconPreviewUrl.value = URL.createObjectURL(file);
    }
};

const setLogo = (event) => handleFile(event.target.files?.[0], 'logo');
const setFavicon = (event) => handleFile(event.target.files?.[0], 'favicon');

const onDrop = (event, type) => {
    event.preventDefault();
    if (type === 'logo') logoDragging.value = false;
    else faviconDragging.value = false;
    const file = event.dataTransfer?.files?.[0];
    handleFile(file, type);
};

const clearLogo = () => {
    mediaForm.logo = null;
    if (logoPreviewUrl.value) URL.revokeObjectURL(logoPreviewUrl.value);
    logoPreviewUrl.value = null;
    if (logoInput.value) logoInput.value.value = '';
};

const clearFavicon = () => {
    mediaForm.favicon = null;
    if (faviconPreviewUrl.value) URL.revokeObjectURL(faviconPreviewUrl.value);
    faviconPreviewUrl.value = null;
    if (faviconInput.value) faviconInput.value.value = '';
};

onBeforeUnmount(() => {
    if (logoPreviewUrl.value) URL.revokeObjectURL(logoPreviewUrl.value);
    if (faviconPreviewUrl.value) URL.revokeObjectURL(faviconPreviewUrl.value);
});

const securityForm = useForm({
    current_password: '',
    new_password: '',
    new_password_confirmation: '',
});

const secretaireForm = useForm({
    name: props.security?.secretaire?.name ?? '',
    email: props.security?.secretaire?.email ?? '',
    password: '',
});

const submitGeneral = () => {
    generalForm.put(route('admin.settings.general'), { preserveScroll: true });
};

const submitSeo = () => {
    seoForm.put(route('admin.settings.seo'), { preserveScroll: true });
};

const submitMaintenance = () => {
    maintenanceForm.put(route('admin.settings.maintenance'), { preserveScroll: true });
};

const submitStats = () => {
    statsForm.put(route('admin.settings.statistics'), { preserveScroll: true });
};

const submitMedia = () => {
    mediaForm.post(route('admin.settings.media'), {
        preserveScroll: true,
        forceFormData: true,
    });
};

const submitSecurity = () => {
    securityForm.put(route('admin.settings.security'), {
        preserveScroll: true,
        onSuccess: () => securityForm.reset(),
    });
};

const submitSecretaire = () => {
    secretaireForm.put(route('admin.settings.secretaire'), { preserveScroll: true });
};
</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-slate-900">Parametres</h1>
        </template>

        <Head title="Parametres" />
        <FlashMessage />

        <div class="space-y-8">
            <PageHeader
                badge="Site"
                title="Parametres"
                description="Configuration globale du site."
            />

            <section class="rounded-2xl border border-slate-100 bg-white p-4 sm:p-6">
                <h3 class="text-sm font-semibold text-slate-900">Informations generales</h3>
                <form class="mt-4 space-y-4" @submit.prevent="submitGeneral">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Nom du site *</label>
                            <input v-model="generalForm.site_name" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Baseline</label>
                            <input v-model="generalForm.site_baseline" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Email contact</label>
                            <input v-model="generalForm.contact_email" type="email" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Telephone</label>
                            <input v-model="generalForm.contact_phone" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-700">Adresse</label>
                        <textarea v-model="generalForm.contact_address" rows="3" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20"></textarea>
                    </div>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Facebook</label>
                            <input v-model="generalForm.social_facebook" type="url" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Twitter</label>
                            <input v-model="generalForm.social_twitter" type="url" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-700">LinkedIn</label>
                            <input v-model="generalForm.social_linkedin" type="url" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-700">YouTube</label>
                            <input v-model="generalForm.social_youtube" type="url" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Instagram</label>
                            <input v-model="generalForm.social_instagram" type="url" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                        </div>
                    </div>
                    <button type="submit" class="rounded-xl bg-ed-primary px-4 py-2 text-sm font-semibold text-white hover:bg-ed-secondary" :disabled="generalForm.processing">Enregistrer</button>
                </form>
            </section>

            <section class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-100 bg-white p-4 sm:p-6">
                    <h3 class="text-sm font-semibold text-slate-900">SEO</h3>
                    <form class="mt-4 space-y-4" @submit.prevent="submitSeo">
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Meta title</label>
                            <input v-model="seoForm.meta_title" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Meta description</label>
                            <textarea v-model="seoForm.meta_description" rows="3" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20"></textarea>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Meta keywords</label>
                            <textarea v-model="seoForm.meta_keywords" rows="2" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20"></textarea>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Sitemap URL</label>
                            <input v-model="seoForm.sitemap_url" type="url" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                        </div>
                        <button type="submit" class="rounded-xl bg-ed-primary px-4 py-2 text-sm font-semibold text-white hover:bg-ed-secondary" :disabled="seoForm.processing">Enregistrer</button>
                    </form>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-4 sm:p-6">
                    <h3 class="text-sm font-semibold text-slate-900">Maintenance</h3>
                    <form class="mt-4 space-y-4" @submit.prevent="submitMaintenance">
                        <label class="flex items-start gap-3 text-sm">
                            <input v-model="maintenanceForm.maintenance_enabled" type="checkbox" class="mt-0.5 rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20" />
                            <span>
                                <span class="font-semibold text-slate-800">Mode maintenance</span>
                                <span class="block text-xs text-slate-500">Activer le mode maintenance.</span>
                            </span>
                        </label>
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Message maintenance</label>
                            <textarea v-model="maintenanceForm.maintenance_message" rows="3" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20"></textarea>
                        </div>
                        <button type="submit" class="rounded-xl bg-ed-primary px-4 py-2 text-sm font-semibold text-white hover:bg-ed-secondary" :disabled="maintenanceForm.processing">Enregistrer</button>
                    </form>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-100 bg-white p-4 sm:p-6">
                <h3 class="text-sm font-semibold text-slate-900">Statistiques Message Direction</h3>
                <form class="mt-4 space-y-4" @submit.prevent="submitStats">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Doctorants</label>
                            <input v-model="statsForm.message_direction_doctorants" type="number" min="0" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                            <p v-if="statsForm.errors.message_direction_doctorants" class="mt-1 text-xs text-red-600">{{ statsForm.errors.message_direction_doctorants }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Equipes</label>
                            <input v-model="statsForm.message_direction_equipes" type="number" min="0" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                            <p v-if="statsForm.errors.message_direction_equipes" class="mt-1 text-xs text-red-600">{{ statsForm.errors.message_direction_equipes }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Theses soutenues</label>
                            <input v-model="statsForm.message_direction_theses" type="number" min="0" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                            <p v-if="statsForm.errors.message_direction_theses" class="mt-1 text-xs text-red-600">{{ statsForm.errors.message_direction_theses }}</p>
                        </div>
                    </div>
                    <button type="submit" class="rounded-xl bg-ed-primary px-4 py-2 text-sm font-semibold text-white hover:bg-ed-secondary" :disabled="statsForm.processing">Enregistrer</button>
                </form>
            </section>

            <section class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-100 bg-white p-4 sm:p-6">
                    <h3 class="text-sm font-semibold text-slate-900">Logo & favicon</h3>
                    <form class="mt-4 space-y-6" @submit.prevent="submitMedia">
                        <!-- Logo Upload -->
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Logo</label>
                            <div
                                class="mt-2 relative rounded-xl border-2 border-dashed transition-all duration-200"
                                :class="logoDragging ? 'border-ed-primary bg-ed-primary/5' : 'border-slate-200 hover:border-slate-300'"
                                @dragover.prevent="logoDragging = true"
                                @dragleave.prevent="logoDragging = false"
                                @drop="onDrop($event, 'logo')"
                            >
                                <!-- Preview -->
                                <div v-if="logoPreviewUrl || settings.logo_url" class="p-4">
                                    <div class="flex items-center gap-4">
                                        <div class="h-16 w-24 flex-shrink-0 overflow-hidden rounded-lg border border-slate-200 bg-slate-50">
                                            <img :src="logoPreviewUrl || settings.logo_url" alt="Logo" class="h-full w-full object-contain" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-slate-700 truncate">
                                                {{ logoPreviewUrl ? 'Nouveau logo selectionne' : 'Logo actuel' }}
                                            </p>
                                            <p class="text-xs text-slate-500">Cliquez ou deposez pour remplacer</p>
                                        </div>
                                        <button
                                            v-if="logoPreviewUrl"
                                            type="button"
                                            class="flex-shrink-0 h-8 w-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition"
                                            @click="clearLogo"
                                        >
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <!-- Empty State -->
                                <div v-else class="p-6 text-center">
                                    <svg class="mx-auto h-10 w-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="mt-2 text-sm font-medium text-slate-600">Deposez votre logo ici</p>
                                    <p class="text-xs text-slate-400">ou cliquez pour parcourir</p>
                                </div>
                                <input
                                    ref="logoInput"
                                    type="file"
                                    accept="image/*"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    @change="setLogo"
                                />
                            </div>
                        </div>

                        <!-- Favicon Upload -->
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Favicon</label>
                            <div
                                class="mt-2 relative rounded-xl border-2 border-dashed transition-all duration-200"
                                :class="faviconDragging ? 'border-ed-primary bg-ed-primary/5' : 'border-slate-200 hover:border-slate-300'"
                                @dragover.prevent="faviconDragging = true"
                                @dragleave.prevent="faviconDragging = false"
                                @drop="onDrop($event, 'favicon')"
                            >
                                <!-- Preview -->
                                <div v-if="faviconPreviewUrl || settings.favicon_url" class="p-4">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-lg border border-slate-200 bg-slate-50 flex items-center justify-center">
                                            <img :src="faviconPreviewUrl || settings.favicon_url" alt="Favicon" class="h-8 w-8 object-contain" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-slate-700 truncate">
                                                {{ faviconPreviewUrl ? 'Nouveau favicon selectionne' : 'Favicon actuel' }}
                                            </p>
                                            <p class="text-xs text-slate-500">Cliquez ou deposez pour remplacer</p>
                                        </div>
                                        <button
                                            v-if="faviconPreviewUrl"
                                            type="button"
                                            class="flex-shrink-0 h-8 w-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition"
                                            @click="clearFavicon"
                                        >
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <!-- Empty State -->
                                <div v-else class="p-6 text-center">
                                    <svg class="mx-auto h-10 w-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <p class="mt-2 text-sm font-medium text-slate-600">Deposez votre favicon ici</p>
                                    <p class="text-xs text-slate-400">Format recommande: 32x32 ou 64x64 px</p>
                                </div>
                                <input
                                    ref="faviconInput"
                                    type="file"
                                    accept="image/*"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    @change="setFavicon"
                                />
                            </div>
                        </div>

                        <button
                            type="submit"
                            class="w-full rounded-xl bg-ed-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-ed-secondary disabled:opacity-50 disabled:cursor-not-allowed transition"
                            :disabled="mediaForm.processing || (!mediaForm.logo && !mediaForm.favicon)"
                        >
                            <span v-if="mediaForm.processing" class="flex items-center justify-center gap-2">
                                <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Envoi en cours...
                            </span>
                            <span v-else>Mettre a jour</span>
                        </button>
                    </form>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-4 sm:p-6">
                    <h3 class="text-sm font-semibold text-slate-900">Securite admin</h3>
                    <form class="mt-4 space-y-4" @submit.prevent="submitSecurity">
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Mot de passe actuel</label>
                            <input v-model="securityForm.current_password" type="password" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Nouveau mot de passe</label>
                            <input v-model="securityForm.new_password" type="password" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Confirmation</label>
                            <input v-model="securityForm.new_password_confirmation" type="password" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                        </div>
                        <button type="submit" class="rounded-xl bg-ed-primary px-4 py-2 text-sm font-semibold text-white hover:bg-ed-secondary" :disabled="securityForm.processing">Mettre a jour</button>
                    </form>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-100 bg-white p-4 sm:p-6">
                <h3 class="text-sm font-semibold text-slate-900">Compte secretaire</h3>
                <form class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-3" @submit.prevent="submitSecretaire">
                    <div>
                        <label class="text-xs font-semibold text-slate-700">Nom</label>
                        <input v-model="secretaireForm.name" type="text" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-700">Email</label>
                        <input v-model="secretaireForm.email" type="email" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-700">Mot de passe</label>
                        <input v-model="secretaireForm.password" type="password" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ed-primary focus:ring-ed-primary/20" />
                    </div>
                    <div class="md:col-span-3">
                        <button type="submit" class="rounded-xl bg-ed-primary px-4 py-2 text-sm font-semibold text-white hover:bg-ed-secondary" :disabled="secretaireForm.processing">Enregistrer</button>
                    </div>
                </form>
            </section>
        </div>
    </AdminLayout>
</template>
