<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm.store'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthLayout>
        <Head title="Confirmer le mot de passe" />

        <FlashMessage />

        <div class="mx-auto w-full max-w-md">
            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-wide text-ed-primary">Sécurité</p>
                    <h1 class="text-2xl font-semibold text-slate-900">Confirmez votre mot de passe</h1>
                    <p class="text-sm text-slate-500">Pour continuer, veuillez confirmer votre mot de passe.</p>
                </div>

                <form class="mt-8 space-y-6" @submit.prevent="submit">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700" for="password">Mot de passe</label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            autocomplete="current-password"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-ed-primary focus:ring-ed-primary/20"
                            placeholder="Votre mot de passe"
                            required
                        />
                        <p v-if="form.errors.password" class="text-xs text-red-600">{{ form.errors.password }}</p>
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-ed-primary px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-ed-primary/90 disabled:cursor-not-allowed disabled:opacity-70"
                        :disabled="form.processing"
                    >
                        Confirmer
                    </button>
                </form>
            </div>

            <p class="mt-6 text-center text-sm text-slate-600">
                Retour au
                <Link :href="route('dashboard')" class="font-semibold text-ed-primary hover:text-ed-primary/80">
                    tableau de bord
                </Link>
            </p>
        </div>
    </AuthLayout>
</template>
