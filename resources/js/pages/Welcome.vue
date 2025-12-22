<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Welcome" />

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
        <!-- Navigation -->
        <nav class="border-b border-gray-200 bg-white/80 backdrop-blur-sm dark:border-gray-700 dark:bg-gray-900/80">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"
                            />
                        </svg>
                        <span class="text-xl font-bold text-gray-900 dark:text-white">TaskFlow</span>
                    </div>
                    <Link
                        href="/tasks"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700"
                    >
                        Go to Tasks
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <main class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
            <div class="grid items-center gap-12 lg:grid-cols-2">
                <!-- Left Content -->
                <div class="text-center lg:text-left">
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl lg:text-6xl dark:text-white">
                        Manage your tasks
                        <span class="text-blue-600">effortlessly</span>
                    </h1>
                    <p class="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-300">
                        A simple and intuitive task management system to help you stay organized, track progress, and achieve your goals.
                    </p>
                    <div class="mt-10 flex flex-col gap-4 sm:flex-row sm:justify-center lg:justify-start">
                        <Link
                            href="/tasks"
                            class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-6 py-3 text-base font-semibold text-white shadow-sm transition-all hover:bg-blue-700 hover:shadow-md"
                        >
                            Get Started
                            <svg class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </Link>
                        <Link
                            href="/tasks/create"
                            class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-6 py-3 text-base font-semibold text-gray-700 shadow-sm transition-all hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                        >
                            Create Task
                        </Link>
                    </div>
                </div>

                <!-- Right Content - Feature Cards -->
                <div class="grid gap-4 sm:grid-cols-2">
                    <!-- Feature 1 -->
                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition-shadow hover:shadow-md dark:border-gray-700 dark:bg-gray-800">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900">
                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <h3 class="mb-2 font-semibold text-gray-900 dark:text-white">Create Tasks</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Quickly add new tasks with title, description, priority, and due date.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition-shadow hover:shadow-md dark:border-gray-700 dark:bg-gray-800">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="mb-2 font-semibold text-gray-900 dark:text-white">Track Progress</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Monitor task status from Todo to In Progress to Done.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition-shadow hover:shadow-md dark:border-gray-700 dark:bg-gray-800">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-yellow-100 dark:bg-yellow-900">
                            <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"
                                />
                            </svg>
                        </div>
                        <h3 class="mb-2 font-semibold text-gray-900 dark:text-white">Filter & Sort</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Easily filter by status, priority, and sort by various fields.</p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition-shadow hover:shadow-md dark:border-gray-700 dark:bg-gray-800">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900">
                            <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"
                                />
                            </svg>
                        </div>
                        <h3 class="mb-2 font-semibold text-gray-900 dark:text-white">Quick Actions</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Change status instantly with quick action buttons.</p>
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="mt-20 rounded-2xl border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="grid gap-8 text-center sm:grid-cols-3">
                    <div>
                        <div class="text-3xl font-bold text-blue-600">Easy</div>
                        <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">to use interface</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-green-600">Fast</div>
                        <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">task management</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-purple-600">RESTful</div>
                        <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">API included</div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="border-t border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900">
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                    Task Management System &copy; {{ new Date().getFullYear() }}. Built with Laravel, Vue.js & Tailwind CSS.
                </p>
            </div>
        </footer>
    </div>
</template>
