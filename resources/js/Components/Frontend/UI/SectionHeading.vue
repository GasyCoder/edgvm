<script setup>
defineProps({
    eyebrow: {
        type: String,
        default: null,
    },
    title: {
        type: String,
        default: null,
    },
    subtitle: {
        type: String,
        default: null,
    },
    align: {
        type: String,
        default: 'left',
        validator: (value) => ['left', 'center'].includes(value),
    },
});
</script>

<template>
    <div :class="['max-w-2xl', align === 'center' ? 'mx-auto text-center' : '']">
        <p
            v-if="eyebrow"
            class="text-xs font-semibold uppercase tracking-[0.2em] text-ed-primary"
        >
            {{ eyebrow }}
        </p>

        <h2
            v-if="title || $slots.title"
            class="mt-3 text-2xl font-bold leading-tight tracking-tight text-gray-900 sm:text-3xl"
        >
            <slot name="title">{{ title }}</slot>
        </h2>

        <p
            v-if="subtitle || $slots.subtitle"
            class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-[15px]"
        >
            <slot name="subtitle">{{ subtitle }}</slot>
        </p>

        <slot />
    </div>
</template>
