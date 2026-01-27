<script setup>
import { Head } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';

defineOptions({
    layout: AuthLayout,
});
</script>

<template>
    <Head title="API Tokens" />

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h1 class="text-lg font-semibold text-slate-900">API tokens</h1>
        <p class="mt-2 text-sm text-slate-600">
            Gestion des tokens API a migrer. Cette interface sera reconstruite via Inertia.
        </p>
    </div>
</template>
