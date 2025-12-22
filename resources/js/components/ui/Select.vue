<script setup lang="ts">
import { cn } from '@/lib/utils';
import { computed } from 'vue';

interface Props {
    modelValue?: string;
    disabled?: boolean;
    error?: string;
    id?: string;
}

const props = withDefaults(defineProps<Props>(), {
    disabled: false,
});

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

const classes = computed(() => {
    return cn(
        'flex h-9 w-full items-center justify-between whitespace-nowrap rounded-md border border-gray-200 bg-transparent px-3 py-2 text-sm shadow-sm',
        'ring-offset-white text-gray-900',
        'focus:outline-none focus:ring-1 focus:ring-gray-950',
        'disabled:cursor-not-allowed disabled:opacity-50',
        'dark:border-gray-800 dark:ring-offset-gray-950 dark:focus:ring-gray-300 dark:text-white dark:bg-gray-800',
        props.error && 'border-red-500 focus:ring-red-500',
    );
});

const handleChange = (event: Event) => {
    const target = event.target as HTMLSelectElement;
    emit('update:modelValue', target.value);
};
</script>

<template>
    <div class="w-full">
        <select :id="id" :class="classes" :value="modelValue" :disabled="disabled" @change="handleChange">
            <slot />
        </select>
        <p v-if="error" class="mt-1 text-sm text-red-500">{{ error }}</p>
    </div>
</template>
