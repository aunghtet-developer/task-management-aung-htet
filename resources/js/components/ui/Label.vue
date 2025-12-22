<script setup lang="ts">
import { cn } from '@/lib/utils';
import { computed } from 'vue';

interface Props {
    for?: string;
    required?: boolean;
}

const props = defineProps<Props>();

const classes = computed(() => {
    return cn('text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70');
});
</script>

<template>
    <label :for="props.for" :class="classes">
        <slot />
        <span v-if="required" class="ml-1 text-red-500">*</span>
    </label>
</template>
