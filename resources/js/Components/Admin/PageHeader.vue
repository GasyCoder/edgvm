<script setup>
defineProps({
    badge: {
        type: String,
        required: true,
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
    <div class="rounded-2xl border border-slate-100 bg-white p-4 sm:p-6">
        <div class="flex flex-wrap items-start justify-between gap-3 sm:gap-4">
            <div>
                <div class="mb-2 inline-flex items-center gap-2 rounded-full bg-ed-primary/10 px-3 py-1 sm:mb-3">
                    <span class="h-2 w-2 rounded-full bg-ed-primary"></span>
                    <span class="text-xs font-semibold uppercase tracking-wider text-ed-primary">
                        {{ badge }}
                    </span>
                </div>
                <h1 class="text-lg font-bold text-slate-900 sm:text-xl">{{ title }}</h1>
                <p v-if="description" class="mt-1 text-sm text-slate-500">{{ description }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <slot name="actions" />
            </div>
        </div>
    </div>
</template>
