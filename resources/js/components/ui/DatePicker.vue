<script setup lang="ts">
import { cn } from '@/lib/utils';
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';

interface Props {
    modelValue?: string;
    placeholder?: string;
    disabled?: boolean;
    error?: string;
    id?: string;
    min?: string;
}

const props = withDefaults(defineProps<Props>(), {
    disabled: false,
    placeholder: 'Select date',
});

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

const isOpen = ref(false);
const inputRef = ref<HTMLDivElement | null>(null);
const currentMonth = ref(new Date());

// Initialize current month from model value
onMounted(() => {
    if (props.modelValue) {
        currentMonth.value = new Date(props.modelValue + 'T00:00:00');
    }
});

// Days of week
const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

// Get calendar days for current month
const calendarDays = computed(() => {
    const year = currentMonth.value.getFullYear();
    const month = currentMonth.value.getMonth();
    
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    
    const days: { date: Date; isCurrentMonth: boolean; isToday: boolean; isSelected: boolean }[] = [];
    
    // Add days from previous month
    const startPadding = firstDay.getDay();
    for (let i = startPadding - 1; i >= 0; i--) {
        const date = new Date(year, month, -i);
        days.push({
            date,
            isCurrentMonth: false,
            isToday: isToday(date),
            isSelected: isSelected(date),
        });
    }
    
    // Add days from current month
    for (let i = 1; i <= lastDay.getDate(); i++) {
        const date = new Date(year, month, i);
        days.push({
            date,
            isCurrentMonth: true,
            isToday: isToday(date),
            isSelected: isSelected(date),
        });
    }
    
    // Add days from next month to complete the grid
    const endPadding = 42 - days.length; // 6 rows * 7 days
    for (let i = 1; i <= endPadding; i++) {
        const date = new Date(year, month + 1, i);
        days.push({
            date,
            isCurrentMonth: false,
            isToday: isToday(date),
            isSelected: isSelected(date),
        });
    }
    
    return days;
});

const monthYearDisplay = computed(() => {
    return currentMonth.value.toLocaleDateString('en-US', {
        month: 'long',
        year: 'numeric',
    });
});

const displayValue = computed(() => {
    if (!props.modelValue) return '';
    const date = new Date(props.modelValue + 'T00:00:00');
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
});

function isToday(date: Date): boolean {
    const today = new Date();
    return (
        date.getDate() === today.getDate() &&
        date.getMonth() === today.getMonth() &&
        date.getFullYear() === today.getFullYear()
    );
}

function isSelected(date: Date): boolean {
    if (!props.modelValue) return false;
    const selected = new Date(props.modelValue + 'T00:00:00');
    return (
        date.getDate() === selected.getDate() &&
        date.getMonth() === selected.getMonth() &&
        date.getFullYear() === selected.getFullYear()
    );
}

function selectDate(date: Date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    emit('update:modelValue', `${year}-${month}-${day}`);
    isOpen.value = false;
}

function previousMonth() {
    currentMonth.value = new Date(
        currentMonth.value.getFullYear(),
        currentMonth.value.getMonth() - 1,
        1
    );
}

function nextMonth() {
    currentMonth.value = new Date(
        currentMonth.value.getFullYear(),
        currentMonth.value.getMonth() + 1,
        1
    );
}

function clearDate() {
    emit('update:modelValue', '');
    isOpen.value = false;
}

function goToToday() {
    const today = new Date();
    currentMonth.value = new Date(today.getFullYear(), today.getMonth(), 1);
    selectDate(today);
}

// Close on click outside
function handleClickOutside(event: MouseEvent) {
    if (inputRef.value && !inputRef.value.contains(event.target as Node)) {
        isOpen.value = false;
    }
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});

const inputClasses = computed(() => {
    return cn(
        'flex h-9 w-full items-center justify-between rounded-md border border-gray-200 bg-white px-3 py-1 text-sm shadow-sm cursor-pointer',
        'hover:bg-gray-50',
        'focus:outline-none focus:ring-1 focus:ring-gray-950',
        'disabled:cursor-not-allowed disabled:opacity-50',
        'dark:border-gray-800 dark:bg-gray-950 dark:hover:bg-gray-900 dark:focus:ring-gray-300',
        props.error && 'border-red-500 focus:ring-red-500',
    );
});
</script>

<template>
    <div class="relative w-full" ref="inputRef">
        <!-- Input Display -->
        <div
            :id="id"
            :class="inputClasses"
            tabindex="0"
            @click="!disabled && (isOpen = !isOpen)"
            @keydown.enter="!disabled && (isOpen = !isOpen)"
            @keydown.space.prevent="!disabled && (isOpen = !isOpen)"
        >
            <span :class="displayValue ? 'text-gray-900 dark:text-gray-100' : 'text-gray-500 dark:text-gray-400'">
                {{ displayValue || placeholder }}
            </span>
            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>

        <!-- Calendar Dropdown -->
        <Transition name="dropdown">
            <div
                v-if="isOpen"
                class="absolute z-50 mt-1 w-72 rounded-lg border border-gray-200 bg-white p-3 shadow-lg dark:border-gray-700 dark:bg-gray-800"
            >
                <!-- Header -->
                <div class="mb-2 flex items-center justify-between">
                    <button
                        type="button"
                        class="rounded p-1 hover:bg-gray-100 dark:hover:bg-gray-700"
                        @click="previousMonth"
                    >
                        <svg class="h-5 w-5 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <span class="font-medium text-gray-900 dark:text-white">{{ monthYearDisplay }}</span>
                    <button
                        type="button"
                        class="rounded p-1 hover:bg-gray-100 dark:hover:bg-gray-700"
                        @click="nextMonth"
                    >
                        <svg class="h-5 w-5 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                <!-- Days of Week -->
                <div class="mb-1 grid grid-cols-7 gap-1">
                    <div
                        v-for="day in daysOfWeek"
                        :key="day"
                        class="py-1 text-center text-xs font-medium text-gray-500 dark:text-gray-400"
                    >
                        {{ day }}
                    </div>
                </div>

                <!-- Calendar Days -->
                <div class="grid grid-cols-7 gap-1">
                    <button
                        v-for="(day, index) in calendarDays"
                        :key="index"
                        type="button"
                        :class="[
                            'h-8 w-8 rounded text-sm transition-colors',
                            day.isCurrentMonth ? 'text-gray-900 dark:text-gray-100' : 'text-gray-400 dark:text-gray-600',
                            day.isToday && !day.isSelected && 'border border-blue-500',
                            day.isSelected && 'bg-blue-600 text-white hover:bg-blue-700',
                            !day.isSelected && 'hover:bg-gray-100 dark:hover:bg-gray-700',
                        ]"
                        @click="selectDate(day.date)"
                    >
                        {{ day.date.getDate() }}
                    </button>
                </div>

                <!-- Footer -->
                <div class="mt-3 flex items-center justify-between border-t border-gray-200 pt-3 dark:border-gray-700">
                    <button
                        type="button"
                        class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300"
                        @click="goToToday"
                    >
                        Today
                    </button>
                    <button
                        v-if="modelValue"
                        type="button"
                        class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                        @click="clearDate"
                    >
                        Clear
                    </button>
                </div>
            </div>
        </Transition>

        <p v-if="error" class="mt-1 text-sm text-red-500">{{ error }}</p>
    </div>
</template>

<style scoped>
.dropdown-enter-active,
.dropdown-leave-active {
    transition: all 0.15s ease;
}

.dropdown-enter-from,
.dropdown-leave-to {
    opacity: 0;
    transform: translateY(-4px);
}
</style>
