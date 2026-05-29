import { onMounted, onUnmounted, ref } from 'vue';

/**
 * Reactive helper exposing the user's "prefers-reduced-motion" preference.
 * Used to disable auto-playing / decorative motion for accessibility (WCAG 2.3.3).
 *
 * @returns {{ reduced: import('vue').Ref<boolean> }}
 */
export function useReducedMotion() {
    const reduced = ref(false);
    let mediaQuery = null;

    const update = () => {
        reduced.value = mediaQuery?.matches ?? false;
    };

    onMounted(() => {
        if (typeof window === 'undefined' || typeof window.matchMedia !== 'function') {
            return;
        }

        mediaQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
        update();
        mediaQuery.addEventListener?.('change', update);
    });

    onUnmounted(() => {
        mediaQuery?.removeEventListener?.('change', update);
    });

    return { reduced };
}
