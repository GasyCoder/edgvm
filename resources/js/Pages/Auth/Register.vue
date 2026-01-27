<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthLayout>
        <Head title="Créer un compte" />

        <FlashMessage />

        <div class="mx-auto w-full max-w-md">
            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-wide text-ed-primary">Espace membre</p>
                    <h1 class="text-2xl font-semibold text-slate-900">Créer un compte</h1>
                    <p class="text-sm text-slate-500">Rejoignez la communauté EDGVM pour accéder à votre espace.</p>
                </div>

                <form class="mt-8 space-y-6" @submit.prevent="submit">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700" for="name">Nom complet</label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            autocomplete="name"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-ed-primary focus:ring-ed-primary/20"
                            placeholder="Votre nom"
                            required
                        />
                        <p v-if="form.errors.name" class="text-xs text-red-600">{{ form.errors.name }}</p>
                    </div>

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
                            autocomplete="new-password"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-ed-primary focus:ring-ed-primary/20"
                            placeholder="Créer un mot de passe"
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
                        Créer mon compte
                    </button>
                </form>
            </div>

            <p class="mt-6 text-center text-sm text-slate-600">
                Déjà inscrit ?
                <Link :href="route('login')" class="font-semibold text-ed-primary hover:text-ed-primary/80">
                    Se connecter
                </Link>
            </p>
        </div>
    </AuthLayout>
</template>
