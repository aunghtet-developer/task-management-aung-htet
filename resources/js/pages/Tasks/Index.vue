<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import type { Task, TaskFilters, StatusOptions, PriorityOptions } from '@/types';

import Button from '@/components/ui/Button.vue';
import Select from '@/components/ui/Select.vue';
import Badge from '@/components/ui/Badge.vue';
import Card from '@/components/ui/Card.vue';
import CardHeader from '@/components/ui/CardHeader.vue';
import CardTitle from '@/components/ui/CardTitle.vue';
import CardContent from '@/components/ui/CardContent.vue';
import ConfirmDialog from '@/components/ui/ConfirmDialog.vue';
import Toast from '@/components/ui/Toast.vue';
import Spinner from '@/components/ui/Spinner.vue';

interface Props {
    tasks: Task[];
    filters: TaskFilters;
    statusOptions: StatusOptions;
    priorityOptions: PriorityOptions;
}

const props = defineProps<Props>();

const page = usePage();

// State
const isLoading = ref(false);
const deleteDialogOpen = ref(false);
const taskToDelete = ref<Task | null>(null);
const showToast = ref(false);
const toastMessage = ref('');
const toastType = ref<'success' | 'error'>('success');

// Filters
const statusFilter = ref(props.filters.status || '');
const priorityFilter = ref(props.filters.priority || '');
const sortField = ref(props.filters.sort || 'created_at');
const sortDirection = ref(props.filters.direction || 'desc');

// Flash messages
const flash = computed(() => (page.props as any).flash || {});

watch(
    flash,
    (newFlash) => {
        if (newFlash?.success) {
            toastMessage.value = newFlash.success;
            toastType.value = 'success';
            showToast.value = true;
        } else if (newFlash?.error) {
            toastMessage.value = newFlash.error;
            toastType.value = 'error';
            showToast.value = true;
        }
    },
    { immediate: true },
);

// Apply filters
const applyFilters = () => {
    isLoading.value = true;
    router.get(
        '/tasks',
        {
            status: statusFilter.value || undefined,
            priority: priorityFilter.value || undefined,
            sort: sortField.value,
            direction: sortDirection.value,
        },
        {
            preserveState: true,
            onFinish: () => {
                isLoading.value = false;
            },
        },
    );
};

// Reset filters
const resetFilters = () => {
    statusFilter.value = '';
    priorityFilter.value = '';
    sortField.value = 'created_at';
    sortDirection.value = 'desc';
    applyFilters();
};

// Delete task
const openDeleteDialog = (task: Task) => {
    taskToDelete.value = task;
    deleteDialogOpen.value = true;
};

const confirmDelete = () => {
    if (taskToDelete.value) {
        isLoading.value = true;
        router.delete(`/tasks/${taskToDelete.value.id}`, {
            onFinish: () => {
                isLoading.value = false;
                taskToDelete.value = null;
            },
        });
    }
};

// Quick status change
const updateStatus = (task: Task, newStatus: string) => {
    isLoading.value = true;
    router.patch(
        `/tasks/${task.id}/status`,
        { status: newStatus },
        {
            preserveState: true,
            onFinish: () => {
                isLoading.value = false;
            },
        },
    );
};

// Status badge styling
const getStatusBadgeClass = (status: string) => {
    switch (status) {
        case 'todo':
            return 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100';
        case 'in_progress':
            return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100';
        case 'done':
            return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100';
        default:
            return '';
    }
};

// Priority badge styling
const getPriorityBadgeClass = (priority: string) => {
    switch (priority) {
        case 'low':
            return 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-100';
        case 'medium':
            return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100';
        case 'high':
            return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100';
        default:
            return '';
    }
};

// Format date (handles date-only strings properly)
const formatDate = (dateString: string | null) => {
    if (!dateString) return '-';
    // For date-only strings (YYYY-MM-DD), append time to avoid timezone issues
    const dateStr = dateString.includes('T') ? dateString : dateString + 'T00:00:00';
    return new Date(dateStr).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};

// Format datetime for created_at/updated_at
const formatDateTime = (dateString: string | null) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};
</script>

<template>
    <Head title="Tasks" />

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Header -->
        <header class="border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-950">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-4">
                    <Link href="/">
                        <Button variant="ghost" size="icon">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </Button>
                    </Link>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Task Management</h1>
                </div>
                <Link href="/tasks/create">
                    <Button>
                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        New Task
                    </Button>
                </Link>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Filters -->
            <Card class="mb-6">
                <CardContent class="pt-6">
                    <div class="flex flex-wrap items-end gap-4">
                        <div class="min-w-[150px]">
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <Select v-model="statusFilter">
                                <option value="">All Statuses</option>
                                <option v-for="(label, value) in statusOptions" :key="value" :value="value">
                                    {{ label }}
                                </option>
                            </Select>
                        </div>

                        <div class="min-w-[150px]">
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Priority</label>
                            <Select v-model="priorityFilter">
                                <option value="">All Priorities</option>
                                <option v-for="(label, value) in priorityOptions" :key="value" :value="value">
                                    {{ label }}
                                </option>
                            </Select>
                        </div>

                        <div class="min-w-[150px]">
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Sort By</label>
                            <Select v-model="sortField">
                                <option value="created_at">Created Date</option>
                                <option value="title">Title</option>
                                <option value="status">Status</option>
                                <option value="priority">Priority</option>
                                <option value="due_date">Due Date</option>
                            </Select>
                        </div>

                        <div class="min-w-[120px]">
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Direction</label>
                            <Select v-model="sortDirection">
                                <option value="asc">Ascending</option>
                                <option value="desc">Descending</option>
                            </Select>
                        </div>

                        <div class="flex gap-2">
                            <Button @click="applyFilters" :disabled="isLoading">
                                <Spinner v-if="isLoading" size="sm" class="mr-2" />
                                Apply
                            </Button>
                            <Button variant="outline" @click="resetFilters" :disabled="isLoading"> Reset </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Tasks Table -->
            <Card>
                <CardHeader>
                    <CardTitle>Tasks ({{ tasks.length }})</CardTitle>
                </CardHeader>
                <CardContent>
                    <div v-if="isLoading" class="flex items-center justify-center py-12">
                        <Spinner size="lg" />
                    </div>

                    <div v-else-if="tasks.length === 0" class="py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                            />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No tasks</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new task.</p>
                        <div class="mt-6">
                            <Link href="/tasks/create">
                                <Button>
                                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    New Task
                                </Button>
                            </Link>
                        </div>
                    </div>

                    <div v-else class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Title</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Status</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Priority</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Due Date</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Created</th>
                                    <th class="px-4 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-400">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="task in tasks"
                                    :key="task.id"
                                    class="border-b border-gray-100 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800/50"
                                >
                                    <td class="px-4 py-4">
                                        <Link :href="`/tasks/${task.id}`" class="font-medium text-gray-900 hover:text-blue-600 dark:text-white dark:hover:text-blue-400">
                                            {{ task.title }}
                                        </Link>
                                        <p v-if="task.description" class="mt-1 line-clamp-1 text-sm text-gray-500 dark:text-gray-400">
                                            {{ task.description }}
                                        </p>
                                    </td>
                                    <td class="px-4 py-4">
                                        <Select
                                            :model-value="task.status"
                                            @update:model-value="(value) => updateStatus(task, value)"
                                            class="w-32"
                                        >
                                            <option v-for="(label, value) in statusOptions" :key="value" :value="value">
                                                {{ label }}
                                            </option>
                                        </Select>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span :class="['inline-flex rounded-full px-2 py-1 text-xs font-semibold', getPriorityBadgeClass(task.priority)]">
                                            {{ priorityOptions[task.priority] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ formatDate(task.due_date) }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ formatDateTime(task.created_at) }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex justify-end gap-2">
                                            <Link :href="`/tasks/${task.id}/edit`">
                                                <Button variant="ghost" size="icon">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                                        />
                                                    </svg>
                                                </Button>
                                            </Link>
                                            <Button variant="ghost" size="icon" @click="openDeleteDialog(task)">
                                                <svg class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                    />
                                                </svg>
                                            </Button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </CardContent>
            </Card>
        </main>
    </div>

    <!-- Delete Confirmation Dialog -->
    <ConfirmDialog v-model:open="deleteDialogOpen" title="Delete Task" @confirm="confirmDelete">
        <p class="text-gray-600 dark:text-gray-300">
            Are you sure you want to delete "<strong>{{ taskToDelete?.title }}</strong>"? This action cannot be undone.
        </p>
        <template #confirm-text>Delete</template>
    </ConfirmDialog>

    <!-- Toast Notification -->
    <Toast v-model:show="showToast" :message="toastMessage" :type="toastType" />
</template>
