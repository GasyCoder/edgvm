<script setup>
import { computed, onMounted, ref } from 'vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import Container from '@/Components/Frontend/UI/Container.vue';

const props = defineProps({
    subjectOptions: Object,
    captcha: Object,
});

const page = usePage();
const flash = computed(() => page.props.flash || {});
const appSettings = computed(() => page.props.appSettings || {});

const form = useForm({
    fullName: '',
    phone: '',
    email: '',
    subject: 'information_programme',
    messageContent: '',
    captchaAnswer: '',
});

const captchaChars = ref([]);

const buildCaptchaChars = () => {
    const text = `${props.captcha?.a ?? ''} + ${props.captcha?.b ?? ''} = ?`;

    captchaChars.value = text.split('').map((char) => {
        return {
            char,
            rotate: Math.floor(Math.random() * 37) - 18,
            translateY: Math.floor(Math.random() * 9) - 4,
            fontSize: Math.floor(Math.random() * 7) + 12,
            opacity: (Math.floor(Math.random() * 26) + 75) / 100,
        };
    });
};

const refreshCaptcha = () => {
    router.get(route('contact', { refresh: 1 }), {}, { preserveScroll: true, preserveState: false });
};

const submit = () => {
    form.post(route('contact.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('fullName', 'phone', 'email', 'subject', 'messageContent', 'captchaAnswer');
            form.subject = 'information_programme';
        },
    });
};

onMounted(() => {
    buildCaptchaChars();
});

defineOptions({
    layout: FrontendLayout,
});
</script>

<template>
    <Head title="Contact" />

    <!-- Hero -->
    <section class="mt-16 bg-ed-teal-dark py-12 text-white sm:mt-20 sm:py-16">
        <Container>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-ed-yellow">Contact EDGVM</p>
            <h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Contactez l'École Doctorale</h1>
            <p class="mt-3 max-w-2xl text-sm leading-relaxed text-white/85 sm:text-base">
                Pour toute demande liée à la formation doctorale, aux projets de recherche ou aux partenariats, l'équipe de l'EDGVM reste à votre écoute.
            </p>

            <nav class="mt-5 flex items-center gap-2 text-sm text-white/70" aria-label="Fil d'Ariane">
                <Link :href="route('home')" class="transition-colors hover:text-ed-yellow">Accueil</Link>
                <span aria-hidden="true">/</span>
                <span class="font-semibold text-white">Contact</span>
            </nav>
        </Container>
    </section>

    <section class="bg-gray-50 py-12 md:py-14">
        <Container>
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 lg:gap-8">
                <!-- Formulaire -->
                <main class="lg:col-span-2">
                    <div class="rounded-xl border border-gray-200 bg-white p-5 sm:p-7 md:p-8">
                        <div class="mb-6 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 md:text-xl">Envoyer un message</h2>
                                <p class="mt-1 text-xs text-gray-500">Les champs marqués d'un <span class="text-red-500">*</span> sont obligatoires.</p>
                            </div>
                            <span class="text-xs text-gray-500">Réponse sous 2 à 3 jours ouvrés</span>
                        </div>

                        <div v-if="flash.success" class="mb-5 flex gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <p>{{ flash.success }}</p>
                        </div>

                        <div v-if="flash.error" class="mb-5 flex gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" /></svg>
                            <p>{{ flash.error }}</p>
                        </div>

                        <form class="space-y-5" @submit.prevent="submit">
                            <div>
                                <label for="c-name" class="mb-1 block text-sm font-medium text-gray-700">Nom complet <span class="text-red-500">*</span></label>
                                <input id="c-name" v-model="form.fullName" type="text" autocomplete="name" placeholder="Ex. : RABE Jean Michel" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-transparent focus:ring-2 focus:ring-ed-primary/40">
                                <p v-if="form.errors.fullName" class="mt-1 text-xs text-red-500">{{ form.errors.fullName }}</p>
                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="c-phone" class="mb-1 block text-sm font-medium text-gray-700">Téléphone</label>
                                    <input id="c-phone" v-model="form.phone" type="text" autocomplete="tel" placeholder="+261 …" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-transparent focus:ring-2 focus:ring-ed-primary/40">
                                    <p v-if="form.errors.phone" class="mt-1 text-xs text-red-500">{{ form.errors.phone }}</p>
                                </div>
                                <div>
                                    <label for="c-email" class="mb-1 block text-sm font-medium text-gray-700">E-mail <span class="text-red-500">*</span></label>
                                    <input id="c-email" v-model="form.email" type="email" autocomplete="email" placeholder="vous@example.com" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-transparent focus:ring-2 focus:ring-ed-primary/40">
                                    <p v-if="form.errors.email" class="mt-1 text-xs text-red-500">{{ form.errors.email }}</p>
                                </div>
                            </div>

                            <div>
                                <label for="c-subject" class="mb-1 block text-sm font-medium text-gray-700">Objet de la demande <span class="text-red-500">*</span></label>
                                <select id="c-subject" v-model="form.subject" class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 focus:border-transparent focus:ring-2 focus:ring-ed-primary/40">
                                    <option value="">Sélectionnez un objet</option>
                                    <option v-for="(label, value) in subjectOptions" :key="value" :value="value">{{ label }}</option>
                                </select>
                                <p v-if="form.errors.subject" class="mt-1 text-xs text-red-500">{{ form.errors.subject }}</p>
                            </div>

                            <div>
                                <div class="mb-1 flex items-center justify-between">
                                    <label for="c-message" class="block text-sm font-medium text-gray-700">Message <span class="text-red-500">*</span></label>
                                    <span class="text-[11px] text-gray-400">{{ form.messageContent.length }}/250</span>
                                </div>
                                <textarea id="c-message" v-model="form.messageContent" rows="4" maxlength="250" placeholder="Merci de décrire clairement votre demande (entre 10 et 250 caractères)." class="w-full resize-none rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-transparent focus:ring-2 focus:ring-ed-primary/40"></textarea>
                                <p v-if="form.errors.messageContent" class="mt-1 text-xs text-red-500">{{ form.errors.messageContent }}</p>
                            </div>

                            <div>
                                <label for="c-captcha" class="mb-1 block text-sm font-medium text-gray-700">Vérification anti-robot <span class="text-red-500">*</span></label>
                                <div class="flex flex-wrap items-center gap-3">
                                    <div class="relative inline-flex select-none items-center justify-center overflow-hidden rounded-lg border border-gray-300 bg-slate-800 px-4 py-2 font-mono text-sm text-amber-200">
                                        <div class="pointer-events-none absolute inset-0 opacity-20 bg-[repeating-linear-gradient(135deg,rgba(255,255,255,0.35)_0,rgba(255,255,255,0.35)_1px,transparent_1px,transparent_5px)]"></div>
                                        <div class="relative z-10 flex items-center">
                                            <span v-for="(item, index) in captchaChars" :key="index" class="mx-[2px] inline-block" :style="{ transform: `translateY(${item.translateY}px) rotate(${item.rotate}deg)`, fontSize: `${item.fontSize}px`, opacity: item.opacity }">{{ item.char }}</span>
                                        </div>
                                    </div>

                                    <input id="c-captcha" v-model="form.captchaAnswer" type="number" placeholder="Réponse" class="w-24 rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-transparent focus:ring-2 focus:ring-ed-primary/40">

                                    <button type="button" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-2.5 text-xs font-medium text-gray-600 transition hover:border-ed-primary/50 hover:text-ed-primary" @click="refreshCaptcha">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v6h6M20 20v-6h-6M5 13a7 7 0 0112-5M19 11a7 7 0 01-12 5" /></svg>
                                        Rafraîchir
                                    </button>
                                </div>
                                <p v-if="form.errors.captchaAnswer" class="mt-1 text-xs text-red-500">{{ form.errors.captchaAnswer }}</p>
                            </div>

                            <div class="flex justify-end pt-2">
                                <button type="submit" :disabled="form.processing" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-ed-primary px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-ed-secondary disabled:opacity-60 sm:w-auto">
                                    {{ form.processing ? 'Envoi…' : 'Envoyer le message' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </main>

                <!-- Coordonnées -->
                <aside class="lg:col-span-1">
                    <div class="space-y-5 lg:sticky lg:top-24">
                        <div class="rounded-xl border border-gray-200 bg-white p-6">
                            <h3 class="mb-4 text-sm font-semibold text-gray-900">Coordonnées</h3>
                            <ul class="space-y-4 text-sm">
                                <li class="flex items-start gap-3">
                                    <svg class="mt-0.5 h-5 w-5 flex-none text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    <span class="text-gray-700">{{ appSettings?.site_address || 'Université de Mahajanga' }}<br><span class="text-gray-500">Mahajanga, Madagascar</span></span>
                                </li>
                                <li v-if="appSettings?.site_email" class="flex items-center gap-3">
                                    <svg class="h-5 w-5 flex-none text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                    <a :href="'mailto:' + appSettings.site_email" class="text-gray-700 transition-colors hover:text-ed-primary">{{ appSettings.site_email }}</a>
                                </li>
                                <li v-if="appSettings?.site_phone" class="flex items-center gap-3">
                                    <svg class="h-5 w-5 flex-none text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498A1 1 0 0121 19.25V21a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 8.999V7a2 2 0 012-2z" /></svg>
                                    <a :href="'tel:' + appSettings.site_phone" class="text-gray-700 transition-colors hover:text-ed-primary">{{ appSettings.site_phone }}</a>
                                </li>
                            </ul>
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white p-6">
                            <h3 class="text-sm font-semibold text-gray-900">Besoin d'en savoir plus ?</h3>
                            <p class="mt-1 text-xs leading-relaxed text-gray-500">Découvrez la présentation, l'organisation et les missions de l'École Doctorale.</p>
                            <Link :href="route('pages.show', 'a-propos')" class="mt-3 inline-flex items-center gap-1.5 text-sm font-semibold text-ed-primary transition-colors hover:text-ed-secondary">
                                À propos de l'EDGVM
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </Link>
                        </div>
                    </div>
                </aside>
            </div>
        </Container>
    </section>
</template>
