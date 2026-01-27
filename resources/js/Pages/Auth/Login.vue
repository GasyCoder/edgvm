<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login.store'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthLayout>
        <Head title="Connexion" />

        <FlashMessage />

        <div class="mx-auto w-full max-w-md">
            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-wide text-ed-primary">Espace membre</p>
                    <h1 class="text-2xl font-semibold text-slate-900">Connexion</h1>
                    <p class="text-sm text-slate-500">Accédez à votre tableau de bord et gérez votre compte.</p>
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
                            placeholder="vous@edgvm.org"
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
                            autocomplete="current-password"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-ed-primary focus:ring-ed-primary/20"
                            placeholder="Votre mot de passe"
                            required
                        />
                        <p v-if="form.errors.password" class="text-xs text-red-600">{{ form.errors.password }}</p>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2 text-slate-600">
                            <input v-model="form.remember" type="checkbox" class="rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20" />
                            Se souvenir de moi
                        </label>
                        <Link :href="route('password.request')" class="text-ed-primary hover:text-ed-primary/80">
                            Mot de passe oublié ?
                        </Link>
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-ed-primary px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-ed-primary/90 disabled:cursor-not-allowed disabled:opacity-70"
                        :disabled="form.processing"
                    >
                        Se connecter
                    </button>
                </form>
            </div>

            <p class="mt-6 text-center text-sm text-slate-600">
                Pas encore de compte ?
                <Link :href="route('register')" class="font-semibold text-ed-primary hover:text-ed-primary/80">
                    Créer un compte
                </Link>
            </p>
        </div>
    </AuthLayout>
</template>
