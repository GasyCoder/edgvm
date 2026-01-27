<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const flash = computed(() => page.props.flash);
const visible = ref(false);
const currentMessage = ref(null);
const currentType = ref('success');

const types = {
    success: {
        bg: 'bg-green-50',
        border: 'border-green-400',
        text: 'text-green-800',
        icon: 'text-green-400',
    },
    error: {
        bg: 'bg-red-50',
        border: 'border-red-400',
        text: 'text-red-800',
        icon: 'text-red-400',
    },
    warning: {
        bg: 'bg-yellow-50',
        border: 'border-yellow-400',
        text: 'text-yellow-800',
        icon: 'text-yellow-400',
    },
    info: {
        bg: 'bg-blue-50',
        border: 'border-blue-400',
        text: 'text-blue-800',
        icon: 'text-blue-400',
    },
};

const classes = computed(() => types[currentType.value] || types.success);

const showFlash = (message, type) => {
    currentMessage.value = message;
    currentType.value = type;
    visible.value = true;

    setTimeout(() => {
        visible.value = false;
    }, 5000);
};

const close = () => {
    visible.value = false;
};

watch(flash, (newFlash) => {
    if (newFlash?.success) {
        showFlash(newFlash.success, 'success');
    } else if (newFlash?.error) {
        showFlash(newFlash.error, 'error');
    } else if (newFlash?.warning) {
        showFlash(newFlash.warning, 'warning');
    } else if (newFlash?.info) {
        showFlash(newFlash.info, 'info');
    }
}, { immediate: true, deep: true });
</script>

<template>
    <Transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0 translate-y-4"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-4"
    >
        <div
            v-if="visible && currentMessage"
            class="fixed bottom-6 right-6 z-50 w-full max-w-sm px-4"
        >
            <div
                :class="[classes.bg, classes.border, classes.text]"
                class="rounded-lg border p-4 shadow-lg"
            >
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <!-- Success Icon -->
                        <svg
                            v-if="currentType === 'success'"
                            :class="classes.icon"
                            class="h-5 w-5"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <!-- Error Icon -->
                        <svg
                            v-else-if="currentType === 'error'"
                            :class="classes.icon"
                            class="h-5 w-5"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <!-- Warning Icon -->
                        <svg
                            v-else-if="currentType === 'warning'"
                            :class="classes.icon"
                            class="h-5 w-5"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <!-- Info Icon -->
                        <svg
                            v-else
                            :class="classes.icon"
                            class="h-5 w-5"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium">{{ currentMessage }}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0">
                        <button
                            @click="close"
                            :class="classes.text"
                            class="inline-flex rounded-md hover:opacity-75 focus:outline-none focus:ring-2 focus:ring-offset-2"
                        >
                            <span class="sr-only">Fermer</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>
