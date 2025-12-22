<script setup lang="ts">
import { cn } from '@/lib/utils';
import { computed } from 'vue';

interface Props {
    size?: 'sm' | 'md' | 'lg';
}

const props = withDefaults(defineProps<Props>(), {
    size: 'md',
});

const classes = computed(() => {
    const sizeClasses = {
        sm: 'h-4 w-4 border-2',
        md: 'h-6 w-6 border-2',
        lg: 'h-8 w-8 border-3',
    };
    return cn('animate-spin rounded-full border-gray-300 border-t-gray-900 dark:border-gray-700 dark:border-t-gray-100', sizeClasses[props.size]);
});
</script>

<template>
    <div :class="classes" role="status" aria-label="Loading">
        <span class="sr-only">Loading...</span>
    </div>
</template>
