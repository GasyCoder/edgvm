<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const usingRecoveryCode = ref(false);

const form = useForm({
    code: '',
    recovery_code: '',
});

const submit = () => {
    form.post(route('two-factor.login.store'), {
        onFinish: () => form.reset('code', 'recovery_code'),
    });
};
</script>

<template>
    <AuthLayout>
        <Head title="Validation double facteur" />

        <FlashMessage />

        <div class="mx-auto w-full max-w-md">
            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-wide text-ed-primary">Sécurité</p>
                    <h1 class="text-2xl font-semibold text-slate-900">Validation double facteur</h1>
                    <p class="text-sm text-slate-500">
                        {{ usingRecoveryCode ? 'Entrez un code de récupération pour continuer.' : 'Entrez le code généré par votre application d’authentification.' }}
                    </p>
                </div>

                <form class="mt-8 space-y-6" @submit.prevent="submit">
                    <div v-if="!usingRecoveryCode" class="space-y-2">
                        <label class="text-sm font-medium text-slate-700" for="code">Code</label>
                        <input
                            id="code"
                            v-model="form.code"
                            type="text"
                            inputmode="numeric"
                            autocomplete="one-time-code"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-ed-primary focus:ring-ed-primary/20"
                            placeholder="123456"
                            required
                        />
                        <p v-if="form.errors.code" class="text-xs text-red-600">{{ form.errors.code }}</p>
                    </div>

                    <div v-else class="space-y-2">
                        <label class="text-sm font-medium text-slate-700" for="recovery_code">Code de récupération</label>
                        <input
                            id="recovery_code"
                            v-model="form.recovery_code"
                            type="text"
                            autocomplete="one-time-code"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-ed-primary focus:ring-ed-primary/20"
                            placeholder="XXXX-XXXX"
                            required
                        />
                        <p v-if="form.errors.recovery_code" class="text-xs text-red-600">{{ form.errors.recovery_code }}</p>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-3 text-sm">
                        <button
                            type="button"
                            class="text-ed-primary hover:text-ed-primary/80"
                            @click="usingRecoveryCode = !usingRecoveryCode"
                        >
                            {{ usingRecoveryCode ? 'Utiliser un code d’authentification' : 'Utiliser un code de récupération' }}
                        </button>
                        <Link :href="route('login')" class="text-slate-500 hover:text-slate-700">Retour à la connexion</Link>
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-ed-primary px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-ed-primary/90 disabled:cursor-not-allowed disabled:opacity-70"
                        :disabled="form.processing"
                    >
                        Valider
                    </button>
                </form>
            </div>
        </div>
    </AuthLayout>
</template>
