<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        default: null,
    },
    external: {
        type: Boolean,
        default: false,
    },
    variant: {
        type: String,
        default: 'primary',
        validator: (value) => ['primary', 'yellow', 'outline', 'ghost'].includes(value),
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg'].includes(value),
    },
    type: {
        type: String,
        default: 'button',
    },
});

const variantClasses = {
    primary: 'bg-ed-primary text-white shadow-md hover:bg-ed-secondary hover:shadow-lg focus-visible:ring-ed-primary/50',
    yellow: 'bg-ed-yellow text-gray-900 shadow-md hover:bg-ed-yellow-dark hover:shadow-lg focus-visible:ring-ed-yellow/60',
    outline: 'border border-gray-200 bg-white text-gray-700 shadow-sm hover:border-ed-primary/50 hover:text-ed-primary hover:shadow-md focus-visible:ring-ed-primary/40',
    ghost: 'text-ed-primary hover:text-ed-secondary focus-visible:ring-ed-primary/40',
};

const sizeClasses = {
    sm: 'px-3.5 py-2 text-xs',
    md: 'px-5 py-2.5 text-sm',
    lg: 'px-6 py-3 text-sm',
};

const classes = computed(() => [
    'group inline-flex items-center justify-center gap-2 rounded-xl font-semibold transition duration-300',
    'hover:-translate-y-px focus:outline-none focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2',
    'motion-reduce:transform-none motion-reduce:transition-none',
    variantClasses[props.variant],
    sizeClasses[props.size],
]);

const tag = computed(() => {
    if (!props.href) {
        return 'button';
    }

    return props.external ? 'a' : Link;
});
</script>

<template>
    <component
        :is="tag"
        :href="href || undefined"
        :type="href ? undefined : type"
        :target="external ? '_blank' : undefined"
        :rel="external ? 'noopener noreferrer' : undefined"
        :class="classes"
    >
        <slot />
    </component>
</template>
