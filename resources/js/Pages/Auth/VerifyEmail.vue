<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';

const page = usePage();

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};
</script>

<template>
    <AuthLayout>
        <Head title="Verifier votre email" />

        <FlashMessage />

        <div class="mx-auto w-full max-w-md">
            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-wide text-ed-primary">Verification email</p>
                    <h1 class="text-2xl font-semibold text-slate-900">Confirmez votre adresse</h1>
                    <p class="text-sm text-slate-500">
                        Nous vous avons envoye un lien de verification. Cliquez sur le lien recu par email pour activer
                        votre compte.
                    </p>
                </div>

                <div v-if="page.props.status" class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ page.props.status }}
                </div>

                <form class="mt-6" @submit.prevent="submit">
                    <button
                        type="submit"
                        class="w-full rounded-xl bg-ed-primary px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-ed-primary/90 disabled:cursor-not-allowed disabled:opacity-70"
                        :disabled="form.processing"
                    >
                        Renvoyer le lien de verification
                    </button>
                </form>

                <div class="mt-6 flex flex-wrap items-center justify-between gap-3 text-sm text-slate-600">
                    <Link :href="route('dashboard')" class="text-ed-primary hover:text-ed-primary/80">
                        Aller au tableau de bord
                    </Link>
                    <Link :href="route('logout')" method="post" as="button" class="text-slate-500 hover:text-slate-700">
                        Se deconnecter
                    </Link>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>
