<script setup lang="ts">
import { ref, watch, onMounted, onUnmounted, computed } from 'vue';
import { cn } from '@/lib/utils';

interface Props {
    message: string;
    type?: 'success' | 'error' | 'warning' | 'info';
    duration?: number;
    show: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    type: 'info',
    duration: 5000,
});

const emit = defineEmits<{
    'update:show': [value: boolean];
}>();

let timeoutId: ReturnType<typeof setTimeout> | null = null;

const close = () => {
    emit('update:show', false);
};

const iconClasses = computed(() => {
    const baseClasses = 'h-5 w-5';
    const colorClasses = {
        success: 'text-green-500',
        error: 'text-red-500',
        warning: 'text-yellow-500',
        info: 'text-blue-500',
    };
    return cn(baseClasses, colorClasses[props.type]);
});

const containerClasses = computed(() => {
    const baseClasses = 'flex items-center gap-3 rounded-lg border p-4 shadow-lg';
    const typeClasses = {
        success: 'border-green-200 bg-green-50 dark:border-green-800 dark:bg-green-950',
        error: 'border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-950',
        warning: 'border-yellow-200 bg-yellow-50 dark:border-yellow-800 dark:bg-yellow-950',
        info: 'border-blue-200 bg-blue-50 dark:border-blue-800 dark:bg-blue-950',
    };
    return cn(baseClasses, typeClasses[props.type]);
});

watch(
    () => props.show,
    (isVisible) => {
        if (isVisible && props.duration > 0) {
            if (timeoutId) clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                close();
            }, props.duration);
        }
    },
    { immediate: true },
);

onUnmounted(() => {
    if (timeoutId) clearTimeout(timeoutId);
});
</script>

<template>
    <Teleport to="body">
        <Transition name="slide">
            <div v-if="show" class="fixed right-4 top-4 z-50 max-w-sm">
                <div :class="containerClasses">
                    <!-- Icon -->
                    <svg v-if="type === 'success'" :class="iconClasses" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <svg v-else-if="type === 'error'" :class="iconClasses" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <svg v-else-if="type === 'warning'" :class="iconClasses" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                        />
                    </svg>
                    <svg v-else :class="iconClasses" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>

                    <p class="flex-1 text-sm font-medium text-gray-900 dark:text-gray-100">{{ message }}</p>

                    <button class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" @click="close">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.slide-enter-active,
.slide-leave-active {
    transition: all 0.3s ease;
}

.slide-enter-from,
.slide-leave-to {
    opacity: 0;
    transform: translateX(100%);
}
</style>
