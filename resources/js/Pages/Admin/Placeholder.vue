<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    section: String,
    action: String,
    resourceId: [String, Number, null],
});

const title = `${props.section || 'section'} - ${props.action || 'index'}`;

</script>

<template>
    <AdminLayout>
        <template #header>
        <div>
            <h2 class="text-lg font-semibold text-slate-900 md:text-xl">Administration - {{ section }}</h2>
            <p class="mt-0.5 text-xs text-slate-500 md:text-sm">
                Vue Inertia en cours de migration.
            </p>
        </div>
        </template>

        <Head :title="title" />

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm font-semibold text-slate-900">Section: {{ section }}</p>
                <p class="mt-1 text-xs text-slate-500">Action: {{ action }}</p>
                <p v-if="resourceId" class="mt-1 text-xs text-slate-500">ID: {{ resourceId }}</p>
            </div>
            <Link
                :href="route('admin.dashboard')"
                class="inline-flex items-center rounded-full bg-ed-primary px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-ed-secondary"
            >
                Retour dashboard
            </Link>
        </div>

        <div class="mt-6 rounded-xl border border-dashed border-slate-300 bg-slate-50 p-6 text-sm text-slate-600">
            Cette page est une etape de migration. La prochaine iteration remplacera ce placeholder par le formulaire ou
            la liste complete.
        </div>
    </div>
    </AdminLayout>
</template>
