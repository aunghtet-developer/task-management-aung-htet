<script setup lang="ts">
import { cn } from '@/lib/utils';
import { computed } from 'vue';

interface Props {
    modelValue?: string;
    placeholder?: string;
    disabled?: boolean;
    error?: string;
    rows?: number;
    id?: string;
}

const props = withDefaults(defineProps<Props>(), {
    disabled: false,
    rows: 4,
});

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

const classes = computed(() => {
    return cn(
        'flex min-h-[60px] w-full rounded-md border border-gray-200 bg-transparent px-3 py-2 text-sm shadow-sm',
        'placeholder:text-gray-500',
        'focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-gray-950',
        'disabled:cursor-not-allowed disabled:opacity-50',
        'dark:border-gray-800 dark:placeholder:text-gray-400 dark:focus-visible:ring-gray-300',
        props.error && 'border-red-500 focus-visible:ring-red-500',
    );
});

const handleInput = (event: Event) => {
    const target = event.target as HTMLTextAreaElement;
    emit('update:modelValue', target.value);
};
</script>

<template>
    <div class="w-full">
        <textarea :id="id" :class="classes" :value="modelValue" :placeholder="placeholder" :disabled="disabled" :rows="rows" @input="handleInput" />
        <p v-if="error" class="mt-1 text-sm text-red-500">{{ error }}</p>
    </div>
</template>
