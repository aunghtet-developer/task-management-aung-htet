<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     */
    public function index(Request $request): Response
    {
        $query = Task::query();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Sort
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        
        $allowedSortFields = ['title', 'status', 'priority', 'due_date', 'created_at'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }

        $tasks = $query->get();

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
            'filters' => [
                'status' => $request->status,
                'priority' => $request->priority,
                'sort' => $sortField,
                'direction' => $sortDirection,
            ],
            'statusOptions' => Task::getStatusOptions(),
            'priorityOptions' => Task::getPriorityOptions(),
        ]);
    }

    /**
     * Show the form for creating a new task.
     */
    public function create(): Response
    {
        return Inertia::render('Tasks/Create', [
            'statusOptions' => Task::getStatusOptions(),
            'priorityOptions' => Task::getPriorityOptions(),
        ]);
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['todo', 'in_progress', 'done'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
            'due_date' => ['nullable', 'date'],
        ]);

        $task = Task::create($validated);

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task): Response
    {
        return Inertia::render('Tasks/Show', [
            'task' => $task,
            'statusOptions' => Task::getStatusOptions(),
            'priorityOptions' => Task::getPriorityOptions(),
        ]);
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Task $task): Response
    {
        return Inertia::render('Tasks/Edit', [
            'task' => $task,
            'statusOptions' => Task::getStatusOptions(),
            'priorityOptions' => Task::getPriorityOptions(),
        ]);
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['todo', 'in_progress', 'done'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
            'due_date' => ['nullable', 'date'],
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }

    /**
     * Quick status update for a task.
     */
    public function updateStatus(Request $request, Task $task)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['todo', 'in_progress', 'done'])],
        ]);

        $task->update($validated);

        return redirect()->back()
            ->with('success', 'Task status updated successfully.');
    }
}
