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

const mediaForm = useForm({
    logo: null,
    favicon: null,
});

const logoPreviewUrl = ref(null);
const faviconPreviewUrl = ref(null);

const setLogo = (event) => {
    const file = event.target.files?.[0] ?? null;
    mediaForm.logo = file;

    if (logoPreviewUrl.value) {
        URL.revokeObjectURL(logoPreviewUrl.value);
    }

    logoPreviewUrl.value = file ? URL.createObjectURL(file) : null;
};

const setFavicon = (event) => {
    const file = event.target.files?.[0] ?? null;
    mediaForm.favicon = file;

    if (faviconPreviewUrl.value) {
        URL.revokeObjectURL(faviconPreviewUrl.value);
    }

    faviconPreviewUrl.value = file ? URL.createObjectURL(file) : null;
};

onBeforeUnmount(() => {
    if (logoPreviewUrl.value) {
        URL.revokeObjectURL(logoPreviewUrl.value);
    }
    if (faviconPreviewUrl.value) {
        URL.revokeObjectURL(faviconPreviewUrl.value);
    }
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

const submitMedia = () => {
    mediaForm.post(route('admin.settings.media'), {
        preserveScroll: true,
        forceFormData: true,
        method: 'put',
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

            <section class="rounded-2xl border border-slate-100 bg-white p-6">
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
                <div class="rounded-2xl border border-slate-100 bg-white p-6">
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

                <div class="rounded-2xl border border-slate-100 bg-white p-6">
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

            <section class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-100 bg-white p-6">
                    <h3 class="text-sm font-semibold text-slate-900">Logo & favicon</h3>
                    <form class="mt-4 space-y-4" @submit.prevent="submitMedia">
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Logo</label>
                            <div v-if="settings.logo_url" class="mt-2 overflow-hidden rounded-xl border border-slate-200">
                                <img :src="settings.logo_url" alt="" class="h-16 w-full object-contain bg-slate-50" />
                            </div>
                            <div v-if="logoPreviewUrl" class="mt-2 overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                <img :src="logoPreviewUrl" alt="" class="h-16 w-full object-contain" />
                            </div>
                            <input type="file" accept="image/*" class="mt-2 text-sm" @change="setLogo" />
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-700">Favicon</label>
                            <div v-if="settings.favicon_url" class="mt-2 overflow-hidden rounded-xl border border-slate-200">
                                <img :src="settings.favicon_url" alt="" class="h-12 w-full object-contain bg-slate-50" />
                            </div>
                            <div v-if="faviconPreviewUrl" class="mt-2 overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                <img :src="faviconPreviewUrl" alt="" class="h-12 w-full object-contain" />
                            </div>
                            <input type="file" accept="image/*" class="mt-2 text-sm" @change="setFavicon" />
                        </div>
                        <button type="submit" class="rounded-xl bg-ed-primary px-4 py-2 text-sm font-semibold text-white hover:bg-ed-secondary" :disabled="mediaForm.processing">Mettre a jour</button>
                    </form>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-6">
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

            <section class="rounded-2xl border border-slate-100 bg-white p-6">
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
