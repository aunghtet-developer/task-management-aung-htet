<script setup lang="ts">
import { cn } from '@/lib/utils';
import { computed } from 'vue';

interface Props {
    variant?: 'default' | 'destructive' | 'outline' | 'secondary' | 'ghost' | 'link';
    size?: 'default' | 'sm' | 'lg' | 'icon';
    disabled?: boolean;
    type?: 'button' | 'submit' | 'reset';
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'default',
    size: 'default',
    disabled: false,
    type: 'button',
});

const emit = defineEmits<{
    click: [event: MouseEvent];
}>();

const classes = computed(() => {
    const baseClasses =
        'inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-gray-950 disabled:pointer-events-none disabled:opacity-50';

    const variantClasses = {
        default: 'bg-gray-900 text-gray-50 shadow hover:bg-gray-900/90 dark:bg-gray-50 dark:text-gray-900 dark:hover:bg-gray-50/90',
        destructive: 'bg-red-500 text-gray-50 shadow-sm hover:bg-red-500/90 dark:bg-red-900 dark:text-gray-50 dark:hover:bg-red-900/90',
        outline:
            'border border-gray-300 bg-gray-600 text-white shadow-sm hover:bg-gray-700 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600',
        secondary: 'bg-gray-100 text-gray-900 shadow-sm hover:bg-gray-100/80 dark:bg-gray-800 dark:text-gray-50 dark:hover:bg-gray-800/80',
        ghost: 'hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-800 dark:hover:text-gray-50',
        link: 'text-gray-900 underline-offset-4 hover:underline dark:text-gray-50',
    };

    const sizeClasses = {
        default: 'h-9 px-4 py-2',
        sm: 'h-8 rounded-md px-3 text-xs',
        lg: 'h-10 rounded-md px-8',
        icon: 'h-9 w-9',
    };

    return cn(baseClasses, variantClasses[props.variant], sizeClasses[props.size]);
});

const handleClick = (event: MouseEvent) => {
    if (!props.disabled) {
        emit('click', event);
    }
};
</script>

<template>
    <button :class="classes" :disabled="disabled" :type="type" @click="handleClick">
        <slot />
    </button>
</template>
