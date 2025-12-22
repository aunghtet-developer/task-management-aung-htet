<script setup lang="ts">
import { ref, watch, onMounted, onUnmounted } from 'vue';
import { cn } from '@/lib/utils';
import Button from './Button.vue';

interface Props {
    open: boolean;
    title?: string;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    'update:open': [value: boolean];
    confirm: [];
    cancel: [];
}>();

const dialogRef = ref<HTMLDivElement | null>(null);

const close = () => {
    emit('update:open', false);
    emit('cancel');
};

const confirm = () => {
    emit('confirm');
    emit('update:open', false);
};

const handleKeydown = (event: KeyboardEvent) => {
    if (event.key === 'Escape' && props.open) {
        close();
    }
};

onMounted(() => {
    document.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
    document.removeEventListener('keydown', handleKeydown);
});

watch(
    () => props.open,
    (isOpen) => {
        if (isOpen) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    },
);
</script>

<template>
    <Teleport to="body">
        <Transition name="fade">
            <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="close" />

                <!-- Dialog -->
                <div
                    ref="dialogRef"
                    :class="
                        cn(
                            'relative z-50 w-full max-w-md rounded-lg border border-gray-200 bg-white p-6 shadow-lg',
                            'dark:border-gray-800 dark:bg-gray-950',
                        )
                    "
                >
                    <h2 v-if="title" class="mb-4 text-lg font-semibold">{{ title }}</h2>

                    <div class="mb-6">
                        <slot />
                    </div>

                    <div class="flex justify-end gap-3">
                        <Button variant="outline" @click="close">Cancel</Button>
                        <Button variant="destructive" @click="confirm">
                            <slot name="confirm-text">Confirm</slot>
                        </Button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
