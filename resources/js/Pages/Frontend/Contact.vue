<script setup>
import { computed, onMounted, ref } from 'vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';

const props = defineProps({
    subjectOptions: Object,
    captcha: Object,
});

const page = usePage();
const flash = computed(() => page.props.flash || {});

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

    <div class="mt-20 lg:mt-24">
        <section class="relative pt-20 pb-10 bg-white border-b border-gray-100 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                    <div class="space-y-4">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-ed-primary/5 border border-ed-primary/15">
                            <span class="w-2 h-2 rounded-full bg-ed-primary"></span>
                            <span class="text-[11px] font-semibold uppercase tracking-[0.16em] text-ed-primary">
                                Contact EDGVM
                            </span>
                        </div>

                        <h1 class="text-xl sm:text-2xl font-semibold leading-snug text-gray-900">
                            Une question sur l'EDGVM ?<br>
                            <span class="text-ed-primary font-semibold">Ecrivez-nous simplement.</span>
                        </h1>

                        <p class="text-sm sm:text-base text-gray-600 max-w-xl">
                            Pour toute demande liee a la formation doctorale, aux projets de recherche
                            ou aux partenariats, l'equipe EDGVM reste a votre ecoute.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-gray-50 py-14">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-10">
                    <main class="lg:col-span-2">
                        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm px-5 py-7 md:px-8 md:py-8">
                            <div class="mt-6 mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h2 class="text-lg md:text-xl font-semibold text-gray-900">
                                        Envoyer un message a l'EDGVM
                                    </h2>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Les champs marques d'un <span class="text-red-500">*</span> sont obligatoires.
                                    </p>
                                </div>
                                <div class="flex items-center gap-2 text-[11px] text-gray-500">
                                    <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>Reponse en general sous 2-3 jours ouvres</span>
                                </div>
                            </div>

                            <div v-if="flash.success" class="mb-5 flex gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p>{{ flash.success }}</p>
                            </div>

                            <div v-if="flash.error" class="mb-5 flex gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8v4m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"/>
                                </svg>
                                <p>{{ flash.error }}</p>
                            </div>

                            <form class="space-y-5" @submit.prevent="submit">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Nom complet <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.fullName"
                                        type="text"
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-sm text-gray-900 focus:bg-white focus:border-ed-primary focus:ring-1 focus:ring-ed-primary/60"
                                        placeholder="Ex. : RABE Jean Michel"
                                        autocomplete="name"
                                    >
                                    <p v-if="form.errors.fullName" class="mt-1 text-xs text-red-500">{{ form.errors.fullName }}</p>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Telephone</label>
                                        <input
                                            v-model="form.phone"
                                            type="text"
                                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-sm text-gray-900 focus:bg-white focus:border-ed-primary focus:ring-1 focus:ring-ed-primary/60"
                                            placeholder="+261 ..."
                                            autocomplete="tel"
                                        >
                                        <p v-if="form.errors.phone" class="mt-1 text-xs text-red-500">{{ form.errors.phone }}</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            E-mail <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            v-model="form.email"
                                            type="email"
                                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-sm text-gray-900 focus:bg-white focus:border-ed-primary focus:ring-1 focus:ring-ed-primary/60"
                                            placeholder="vous@example.com"
                                            autocomplete="email"
                                        >
                                        <p v-if="form.errors.email" class="mt-1 text-xs text-red-500">{{ form.errors.email }}</p>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Objet de la demande <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        v-model="form.subject"
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-sm text-gray-900 focus:bg-white focus:border-ed-primary focus:ring-1 focus:ring-ed-primary/60"
                                    >
                                        <option value="">Selectionnez un objet</option>
                                        <option v-for="(label, value) in subjectOptions" :key="value" :value="value">
                                            {{ label }}
                                        </option>
                                    </select>
                                    <p v-if="form.errors.subject" class="mt-1 text-xs text-red-500">{{ form.errors.subject }}</p>
                                </div>

                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Message <span class="text-red-500">*</span>
                                        </label>
                                        <span class="text-[11px] text-gray-400">
                                            {{ form.messageContent.length }}/250
                                        </span>
                                    </div>
                                    <textarea
                                        v-model="form.messageContent"
                                        rows="4"
                                        maxlength="250"
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-sm text-gray-900 focus:bg-white focus:border-ed-primary focus:ring-1 focus:ring-ed-primary/60 resize-none"
                                        placeholder="Merci de decrire clairement votre demande (entre 10 et 250 caracteres)."
                                    ></textarea>
                                    <p v-if="form.errors.messageContent" class="mt-1 text-xs text-red-500">{{ form.errors.messageContent }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Verification anti-robot <span class="text-red-500">*</span>
                                    </label>

                                    <div class="flex flex-wrap items-center gap-3">
                                        <div class="relative inline-flex items-center justify-center px-4 py-2 rounded-xl border border-ed-primary/40 bg-slate-900 text-amber-200 font-mono text-sm shadow-inner overflow-hidden">
                                            <div class="pointer-events-none absolute inset-0 opacity-25 bg-[radial-gradient(circle_at_1px_1px,#ffffff_0.6px,transparent_0)] [background-size:6px_6px]"></div>
                                            <div class="pointer-events-none absolute inset-0 opacity-30 mix-blend-soft-light bg-[repeating-linear-gradient(135deg,rgba(255,255,255,0.35)_0,rgba(255,255,255,0.35)_1px,transparent_1px,transparent_5px)]"></div>

                                            <div class="relative z-10 flex items-center">
                                                <span
                                                    v-for="(item, index) in captchaChars"
                                                    :key="index"
                                                    class="inline-block mx-[2px] select-none"
                                                    :style="{
                                                        transform: `translateY(${item.translateY}px) rotate(${item.rotate}deg)`,
                                                        fontSize: `${item.fontSize}px`,
                                                        opacity: item.opacity,
                                                    }"
                                                >
                                                    {{ item.char }}
                                                </span>
                                            </div>
                                        </div>

                                        <input
                                            v-model="form.captchaAnswer"
                                            type="number"
                                            class="rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-900 focus:bg-white focus:border-ed-primary focus:ring-1 focus:ring-ed-primary/60"
                                            placeholder="Reponse"
                                        >

                                        <button
                                            type="button"
                                            class="inline-flex items-center gap-1 rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs font-medium text-gray-600 hover:border-ed-primary/60 hover:text-ed-primary transition-colors"
                                            @click="refreshCaptcha"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M4 4v6h6M20 20v-6h-6M5 13a7 7 0 0112-5M19 11a7 7 0 01-12 5"/>
                                            </svg>
                                            <span>Rafraichir</span>
                                        </button>
                                    </div>

                                    <p v-if="form.errors.captchaAnswer" class="mt-1 text-xs text-red-500">{{ form.errors.captchaAnswer }}</p>
                                </div>

                                <div class="pt-1 flex justify-end">
                                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-ed-primary text-white font-semibold hover:bg-ed-secondary shadow-sm" :disabled="form.processing">
                                        Envoyer le message
                                    </button>
                                </div>
                            </form>
                        </div>
                    </main>

                    <aside class="lg:col-span-1 space-y-6">
                        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">
                            <h3 class="text-sm font-semibold text-gray-900 mb-3">Besoin d'une reponse rapide ?</h3>
                            <p class="text-xs text-gray-500 mb-4">
                                Vous pouvez aussi nous joindre par email ou telephone.
                            </p>
                            <Link :href="route('pages.show', 'a-propos')" class="inline-flex items-center gap-2 text-sm text-ed-primary hover:text-ed-secondary">
                                En savoir plus sur l'EDGVM
                            </Link>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
    </div>
</template>
