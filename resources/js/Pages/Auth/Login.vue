<script setup>
import { Head, useForm } from '@inertiajs/vue3';
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
    <AuthLayout :show-header="false" main-class="max-w-none min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-10 py-10">
        <Head title="Connexion" />

        <div class="mx-auto w-full max-w-5xl">
            <div class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-lg">
                <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-sky-50"></div>
                <div class="relative grid gap-8 p-6 sm:p-8 lg:grid-cols-2 lg:gap-10 lg:p-12">
                    <div class="flex flex-col justify-between gap-8">
                        <div class="space-y-4">
                            <div class="inline-flex items-center gap-2 rounded-full bg-ed-primary/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-ed-primary">
                                Espace membre
                            </div>
                            <h1 class="text-xl sm:text-2xl font-semibold text-slate-900">Connexion</h1>
                            <p class="text-sm text-slate-600">
                                Accédez à votre tableau de bord et gérez vos dossiers en toute sécurité.
                            </p>
                        </div>

                        <div class="space-y-3 rounded-2xl border border-slate-200 bg-white/80 p-4 text-sm text-slate-600">
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                                Authentification réservée aux membres autorisés.
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-blue-400"></span>
                                Vos données sont chiffrées et protégées.
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                                Contactez l’administration en cas d’accès.
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <FlashMessage />

                        <form class="mt-4 space-y-5" @submit.prevent="submit">
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

                            <div class="flex flex-wrap items-center justify-between gap-3 text-sm">
                                <label class="flex items-center gap-2 text-slate-600">
                                    <input v-model="form.remember" type="checkbox" class="rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20" />
                                    Se souvenir de moi
                                </label>
                            </div>

                            <button
                                type="submit"
                                class="w-full rounded-xl bg-ed-primary px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-ed-primary/90 disabled:cursor-not-allowed disabled:opacity-70"
                                :disabled="form.processing"
                            >
                                Se connecter
                            </button>
                        </form>

                        <p class="mt-4 text-xs text-slate-500">
                            Besoin d’un accès ? Merci de contacter l’équipe administrative.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>
