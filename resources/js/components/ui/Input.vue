<script setup lang="ts">
import { cn } from '@/lib/utils';
import { computed } from 'vue';

interface Props {
    modelValue?: string;
    type?: 'text' | 'email' | 'password' | 'number' | 'date';
    placeholder?: string;
    disabled?: boolean;
    error?: string;
    id?: string;
}

const props = withDefaults(defineProps<Props>(), {
    type: 'text',
    disabled: false,
});

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

const classes = computed(() => {
    return cn(
        'flex h-9 w-full rounded-md border border-gray-200 bg-transparent px-3 py-1 text-sm shadow-sm transition-colors',
        'file:border-0 file:bg-transparent file:text-sm file:font-medium',
        'placeholder:text-gray-500',
        'focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-gray-950',
        'disabled:cursor-not-allowed disabled:opacity-50',
        'dark:border-gray-800 dark:placeholder:text-gray-400 dark:focus-visible:ring-gray-300',
        props.error && 'border-red-500 focus-visible:ring-red-500',
    );
});

const handleInput = (event: Event) => {
    const target = event.target as HTMLInputElement;
    emit('update:modelValue', target.value);
};
</script>

<template>
    <div class="w-full">
        <input :id="id" :class="classes" :type="type" :value="modelValue" :placeholder="placeholder" :disabled="disabled" @input="handleInput" />
        <p v-if="error" class="mt-1 text-sm text-red-500">{{ error }}</p>
    </div>
</template>
