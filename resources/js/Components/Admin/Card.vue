<script setup>
defineProps({
    title: { type: String, default: '' },
    subtitle: { type: String, default: '' },
    noPadding: { type: Boolean, default: false },
});
</script>

<template>
    <div class="rounded-md border border-gray-200 bg-white">
        <div v-if="title || $slots.header || $slots.actions" class="flex items-center gap-3 border-b border-gray-100 px-5 py-3.5">
            <slot name="header">
                <div v-if="title" class="min-w-0">
                    <h3 class="truncate text-sm font-bold text-slate-700">{{ title }}</h3>
                    <p v-if="subtitle" class="mt-0.5 text-xs text-slate-400">{{ subtitle }}</p>
                </div>
            </slot>
            <div v-if="$slots.actions" class="ml-auto flex items-center gap-2">
                <slot name="actions" />
            </div>
        </div>
        <div :class="noPadding ? '' : 'p-5'">
            <slot />
        </div>
    </div>
</template>
