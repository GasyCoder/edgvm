<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: {
        type: Array,
        required: true,
    },
    class: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <nav
        v-if="links.length > 3"
        :class="['flex flex-wrap items-center justify-center gap-1', $props.class]"
        aria-label="Pagination"
    >
        <template v-for="(link, index) in links" :key="index">
            <!-- Previous button -->
            <Link
                v-if="index === 0"
                :href="link.url || '#'"
                :class="[
                    'relative inline-flex items-center justify-center min-w-[44px] min-h-[44px] px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200',
                    link.url
                        ? 'text-ed-gray hover:bg-teal-50 hover:text-ed-primary'
                        : 'text-gray-300 cursor-not-allowed pointer-events-none'
                ]"
                preserve-scroll
                :aria-disabled="!link.url"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span class="sr-only">Precedent</span>
            </Link>

            <!-- Next button -->
            <Link
                v-else-if="index === links.length - 1"
                :href="link.url || '#'"
                :class="[
                    'relative inline-flex items-center justify-center min-w-[44px] min-h-[44px] px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200',
                    link.url
                        ? 'text-ed-gray hover:bg-teal-50 hover:text-ed-primary'
                        : 'text-gray-300 cursor-not-allowed pointer-events-none'
                ]"
                preserve-scroll
                :aria-disabled="!link.url"
            >
                <span class="sr-only">Suivant</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </Link>

            <!-- Ellipsis -->
            <span
                v-else-if="link.label === '...'"
                class="relative hidden items-center px-2 py-2 text-sm font-medium text-gray-400 sm:inline-flex"
            >
                ...
            </span>

            <!-- Page numbers (hidden on mobile except current) -->
            <Link
                v-else
                :href="link.url || '#'"
                :class="[
                    'relative items-center justify-center min-w-[44px] min-h-[44px] px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200',
                    link.active
                        ? 'inline-flex bg-ed-primary text-white shadow-md'
                        : link.url
                            ? 'hidden sm:inline-flex text-ed-gray hover:bg-teal-50 hover:text-ed-primary'
                            : 'hidden sm:inline-flex text-gray-300 cursor-not-allowed pointer-events-none'
                ]"
                preserve-scroll
                :aria-current="link.active ? 'page' : undefined"
            >
                {{ link.label }}
            </Link>
        </template>
    </nav>
</template>
