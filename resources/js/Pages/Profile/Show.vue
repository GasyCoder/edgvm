<script setup>
import { Head } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';

defineOptions({
    layout: AuthLayout,
});
</script>

<template>
    <Head title="Profil" />

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h1 class="text-lg font-semibold text-slate-900">Profil utilisateur</h1>
        <p class="mt-2 text-sm text-slate-600">
            Cette page est en cours de migration vers Inertia. Les formulaires precedents ont ete retires.
        </p>
    </div>
</template>
