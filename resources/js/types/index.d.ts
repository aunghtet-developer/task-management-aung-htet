export interface Auth {
    user: User;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type TaskStatus = 'todo' | 'in_progress' | 'done';
export type TaskPriority = 'low' | 'medium' | 'high';

export interface Task {
    id: number;
    title: string;
    description: string | null;
    status: TaskStatus;
    priority: TaskPriority;
    due_date: string | null;
    created_at: string;
    updated_at: string;
}

export interface TaskFilters {
    status?: TaskStatus | null;
    priority?: TaskPriority | null;
    sort: string;
    direction: 'asc' | 'desc';
}

export interface TaskFormData {
    title: string;
    description: string;
    status: TaskStatus;
    priority: TaskPriority;
    due_date: string;
    [key: string]: string;
}

export type StatusOptions = Record<TaskStatus, string>;
export type PriorityOptions = Record<TaskPriority, string>;
