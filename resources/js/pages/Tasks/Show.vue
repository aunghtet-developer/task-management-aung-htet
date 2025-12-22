<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import type { Task, StatusOptions, PriorityOptions } from '@/types';

import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import CardHeader from '@/components/ui/CardHeader.vue';
import CardTitle from '@/components/ui/CardTitle.vue';
import CardContent from '@/components/ui/CardContent.vue';
import ConfirmDialog from '@/components/ui/ConfirmDialog.vue';

interface Props {
    task: Task;
    statusOptions: StatusOptions;
    priorityOptions: PriorityOptions;
}

const props = defineProps<Props>();

const deleteDialogOpen = ref(false);

const confirmDelete = () => {
    router.delete(`/tasks/${props.task.id}`);
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

// Format date
const formatDate = (dateString: string | null) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};

const formatDateTime = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<template>
    <Head :title="task.title" />

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Header -->
        <header class="border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-950">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-4">
                    <Link href="/tasks">
                        <Button variant="ghost" size="icon">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </Button>
                    </Link>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Task Details</h1>
                </div>
                <div class="flex gap-2">
                    <Link :href="`/tasks/${task.id}/edit`">
                        <Button variant="outline">
                            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                />
                            </svg>
                            Edit
                        </Button>
                    </Link>
                    <Button variant="destructive" @click="deleteDialogOpen = true">
                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                            />
                        </svg>
                        Delete
                    </Button>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
            <Card>
                <CardHeader>
                    <div class="flex items-start justify-between">
                        <CardTitle class="text-xl">{{ task.title }}</CardTitle>
                        <div class="flex gap-2">
                            <span :class="['inline-flex rounded-full px-3 py-1 text-sm font-semibold', getStatusBadgeClass(task.status)]">
                                {{ statusOptions[task.status] }}
                            </span>
                            <span :class="['inline-flex rounded-full px-3 py-1 text-sm font-semibold', getPriorityBadgeClass(task.priority)]">
                                {{ priorityOptions[task.priority] }}
                            </span>
                        </div>
                    </div>
                </CardHeader>
                <CardContent>
                    <div class="space-y-6">
                        <!-- Description -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</h3>
                            <p class="mt-1 whitespace-pre-wrap text-gray-900 dark:text-white">
                                {{ task.description || 'No description provided.' }}
                            </p>
                        </div>

                        <!-- Details Grid -->
                        <div class="grid gap-6 border-t border-gray-200 pt-6 sm:grid-cols-2 dark:border-gray-700">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Due Date</h3>
                                <p class="mt-1 text-gray-900 dark:text-white">{{ formatDate(task.due_date) }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h3>
                                <p class="mt-1 text-gray-900 dark:text-white">{{ statusOptions[task.status] }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Priority</h3>
                                <p class="mt-1 text-gray-900 dark:text-white">{{ priorityOptions[task.priority] }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</h3>
                                <p class="mt-1 text-gray-900 dark:text-white">{{ formatDateTime(task.created_at) }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</h3>
                                <p class="mt-1 text-gray-900 dark:text-white">{{ formatDateTime(task.updated_at) }}</p>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </main>
    </div>

    <!-- Delete Confirmation Dialog -->
    <ConfirmDialog v-model:open="deleteDialogOpen" title="Delete Task" @confirm="confirmDelete">
        <p class="text-gray-600 dark:text-gray-300">
            Are you sure you want to delete "<strong>{{ task.title }}</strong>"? This action cannot be undone.
        </p>
        <template #confirm-text>Delete</template>
    </ConfirmDialog>
</template>
