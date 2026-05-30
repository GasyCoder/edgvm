<script setup>
defineProps({
    badge: {
        type: String,
        default: '',
    },
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div class="flex flex-wrap items-end justify-between gap-4 border-b border-gray-200 pb-4">
        <div class="min-w-0">
            <p v-if="badge" class="mb-1 text-[11px] font-bold uppercase tracking-[0.14em] text-ed-primary">
                {{ badge }}
            </p>
            <h1 class="text-xl font-bold leading-tight tracking-tight text-slate-700 sm:text-2xl">{{ title }}</h1>
            <p v-if="description" class="mt-1 text-sm text-slate-400">{{ description }}</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <slot name="actions" />
        </div>
    </div>
</template>
