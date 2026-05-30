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
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div class="min-w-0">
            <p v-if="badge" class="text-xs font-semibold uppercase tracking-[0.18em] text-ed-primary">
                {{ badge }}
            </p>
            <h1 class="mt-1 text-xl font-bold tracking-tight text-gray-900 sm:text-2xl">{{ title }}</h1>
            <p v-if="description" class="mt-1 text-sm text-gray-500">{{ description }}</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <slot name="actions" />
        </div>
    </div>
</template>
