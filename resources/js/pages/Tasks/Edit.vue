<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import type { Task, StatusOptions, PriorityOptions } from '@/types';

import Button from '@/components/ui/Button.vue';
import Input from '@/components/ui/Input.vue';
import Textarea from '@/components/ui/Textarea.vue';
import Select from '@/components/ui/Select.vue';
import Label from '@/components/ui/Label.vue';
import DatePicker from '@/components/ui/DatePicker.vue';
import Card from '@/components/ui/Card.vue';
import CardHeader from '@/components/ui/CardHeader.vue';
import CardTitle from '@/components/ui/CardTitle.vue';
import CardContent from '@/components/ui/CardContent.vue';
import Spinner from '@/components/ui/Spinner.vue';

interface Props {
    task: Task;
    statusOptions: StatusOptions;
    priorityOptions: PriorityOptions;
}

const props = defineProps<Props>();

// Format due_date to YYYY-MM-DD if it exists
const formatDueDate = (date: string | null): string => {
    if (!date) return '';
    // If already in YYYY-MM-DD format, return as is
    if (/^\d{4}-\d{2}-\d{2}$/.test(date)) return date;
    // Otherwise, parse and format
    const d = new Date(date);
    if (isNaN(d.getTime())) return '';
    return d.toISOString().split('T')[0];
};

const form = useForm({
    title: props.task.title,
    description: props.task.description || '',
    status: props.task.status,
    priority: props.task.priority,
    due_date: formatDueDate(props.task.due_date),
});

const submit = () => {
    form.put(`/tasks/${props.task.id}`);
};
</script>

<template>
    <Head :title="`Edit: ${task.title}`" />

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
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Task</h1>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:px-8">
            <Card>
                <CardHeader>
                    <CardTitle>Edit Task</CardTitle>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Title -->
                        <div>
                            <Label for="title" :required="true">Title</Label>
                            <div class="mt-1">
                                <Input
                                    id="title"
                                    v-model="form.title"
                                    placeholder="Enter task title"
                                    :error="form.errors.title"
                                />
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <Label for="description">Description</Label>
                            <div class="mt-1">
                                <Textarea
                                    id="description"
                                    v-model="form.description"
                                    placeholder="Enter task description"
                                    :rows="4"
                                    :error="form.errors.description"
                                />
                            </div>
                        </div>

                        <div class="grid gap-6 sm:grid-cols-2">
                            <!-- Status -->
                            <div>
                                <Label for="status" :required="true">Status</Label>
                                <div class="mt-1">
                                    <Select id="status" v-model="form.status" :error="form.errors.status">
                                        <option v-for="(label, value) in statusOptions" :key="value" :value="value">
                                            {{ label }}
                                        </option>
                                    </Select>
                                </div>
                            </div>

                            <!-- Priority -->
                            <div>
                                <Label for="priority" :required="true">Priority</Label>
                                <div class="mt-1">
                                    <Select id="priority" v-model="form.priority" :error="form.errors.priority">
                                        <option v-for="(label, value) in priorityOptions" :key="value" :value="value">
                                            {{ label }}
                                        </option>
                                    </Select>
                                </div>
                            </div>
                        </div>

                        <!-- Due Date -->
                        <div>
                            <Label for="due_date">Due Date</Label>
                            <div class="mt-1">
                                <DatePicker
                                    id="due_date"
                                    v-model="form.due_date"
                                    placeholder="Select due date"
                                    :error="form.errors.due_date"
                                />
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end gap-3 pt-4">
                            <Link href="/tasks">
                                <Button type="button" variant="outline">Cancel</Button>
                            </Link>
                            <Button type="submit" :disabled="form.processing">
                                <Spinner v-if="form.processing" size="sm" class="mr-2" />
                                Update Task
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </main>
    </div>
</template>
