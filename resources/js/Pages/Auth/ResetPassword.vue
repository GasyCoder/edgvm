<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const props = defineProps({
    token: {
        type: String,
        required: true,
    },
    email: {
        type: String,
        default: '',
    },
});

const form = useForm({
    token: props.token,
    email: props.email || '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.update'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthLayout>
        <Head title="Réinitialiser le mot de passe" />

        <FlashMessage />

        <div class="mx-auto w-full max-w-md">
            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-wide text-ed-primary">Sécurité</p>
                    <h1 class="text-2xl font-semibold text-slate-900">Nouveau mot de passe</h1>
                    <p class="text-sm text-slate-500">Choisissez un mot de passe sécurisé pour votre compte.</p>
                </div>

                <form class="mt-8 space-y-6" @submit.prevent="submit">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700" for="email">Email</label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            autocomplete="username"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-ed-primary focus:ring-ed-primary/20"
                            required
                        />
                        <p v-if="form.errors.email" class="text-xs text-red-600">{{ form.errors.email }}</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700" for="password">Mot de passe</label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            autocomplete="new-password"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-ed-primary focus:ring-ed-primary/20"
                            placeholder="Nouveau mot de passe"
                            required
                        />
                        <p v-if="form.errors.password" class="text-xs text-red-600">{{ form.errors.password }}</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700" for="password_confirmation">Confirmer le mot de passe</label>
                        <input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-ed-primary focus:ring-ed-primary/20"
                            placeholder="Confirmer le mot de passe"
                            required
                        />
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-ed-primary px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-ed-primary/90 disabled:cursor-not-allowed disabled:opacity-70"
                        :disabled="form.processing"
                    >
                        Mettre à jour le mot de passe
                    </button>
                </form>
            </div>

            <p class="mt-6 text-center text-sm text-slate-600">
                Revenir à la
                <Link :href="route('login')" class="font-semibold text-ed-primary hover:text-ed-primary/80">
                    connexion
                </Link>
            </p>
        </div>
    </AuthLayout>
</template>
