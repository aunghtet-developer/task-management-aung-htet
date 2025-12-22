<script setup lang="ts">
import { cn } from '@/lib/utils';

interface Props {
    class?: string;
}

const props = defineProps<Props>();
</script>

<template>
    <div :class="cn('rounded-xl border border-gray-200 bg-white text-gray-950 shadow dark:border-gray-800 dark:bg-gray-950 dark:text-gray-50', props.class)">
        <slot />
    </div>
</template>
