<script setup>
import { computed } from 'vue';

const props = defineProps({
    show: { type: Boolean, default: false },
    title: { type: String, default: 'Confirmer l\'action' },
    message: { type: String, default: '' },
    confirmLabel: { type: String, default: 'Confirmer' },
    cancelLabel: { type: String, default: 'Annuler' },
    variant: { type: String, default: 'primary' }, // 'primary' | 'danger'
    processing: { type: Boolean, default: false },
});

const emit = defineEmits(['confirm', 'cancel']);

const isDanger = computed(() => props.variant === 'danger');

const confirmClasses = computed(() =>
    isDanger.value
        ? 'bg-red-600 hover:bg-red-700'
        : 'bg-ed-primary hover:bg-ed-secondary',
);

const iconWrapClasses = computed(() =>
    isDanger.value
        ? 'bg-red-50 text-red-600'
        : 'bg-ed-primary/10 text-ed-teal-dark',
);
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-150 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-100 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="!processing && emit('cancel')"></div>

                <Transition
                    enter-active-class="transition duration-150 ease-out"
                    enter-from-class="opacity-0 translate-y-2 scale-95"
                    enter-to-class="opacity-100 translate-y-0 scale-100"
                    leave-active-class="transition duration-100 ease-in"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-95"
                >
                    <div v-if="show" class="relative w-full max-w-md rounded-2xl border border-gray-200 bg-white p-6 shadow-xl" role="dialog" aria-modal="true">
                        <div class="flex items-start gap-4">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full" :class="iconWrapClasses">
                                <svg v-if="isDanger" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                                </svg>
                                <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-base font-semibold text-gray-900">{{ title }}</h3>
                                <p v-if="message" class="mt-1 text-sm leading-relaxed text-gray-500">{{ message }}</p>
                                <slot />
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-3">
                            <button
                                type="button"
                                class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 disabled:opacity-60"
                                :disabled="processing"
                                @click="emit('cancel')"
                            >
                                {{ cancelLabel }}
                            </button>
                            <button
                                type="button"
                                class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold text-white transition disabled:opacity-60"
                                :class="confirmClasses"
                                :disabled="processing"
                                @click="emit('confirm')"
                            >
                                <svg v-if="processing" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                </svg>
                                {{ confirmLabel }}
                            </button>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>
