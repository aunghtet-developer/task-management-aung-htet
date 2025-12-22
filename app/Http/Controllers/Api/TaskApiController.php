<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskApiController extends Controller
{
    /**
     * Display a listing of the tasks.
     * GET /api/tasks
     * 
     * Query Parameters:
     * - status: Filter by status (todo, in_progress, done)
     * - priority: Filter by priority (low, medium, high)
     * - sort: Sort field (title, status, priority, due_date, created_at)
     * - direction: Sort direction (asc, desc)
     */
    public function index(Request $request): JsonResponse
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

        return response()->json([
            'success' => true,
            'data' => $tasks,
            'meta' => [
                'total' => $tasks->count(),
                'filters' => [
                    'status' => $request->status,
                    'priority' => $request->priority,
                ],
                'sort' => [
                    'field' => $sortField,
                    'direction' => $sortDirection,
                ],
            ],
        ]);
    }

    /**
     * Store a newly created task in storage.
     * POST /api/tasks
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['todo', 'in_progress', 'done'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
            'due_date' => ['nullable', 'date'],
        ]);

        $task = Task::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully.',
            'data' => $task,
        ], 201);
    }

    /**
     * Display the specified task.
     * GET /api/tasks/{id}
     */
    public function show(Task $task): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $task,
        ]);
    }

    /**
     * Update the specified task in storage.
     * PUT /api/tasks/{id}
     */
    public function update(Request $request, Task $task): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['todo', 'in_progress', 'done'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
            'due_date' => ['nullable', 'date'],
        ]);

        $task->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully.',
            'data' => $task,
        ]);
    }

    /**
     * Remove the specified task from storage.
     * DELETE /api/tasks/{id}
     */
    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully.',
        ]);
    }

    /**
     * Quick status update for a task.
     * PATCH /api/tasks/{id}/status
     */
    public function updateStatus(Request $request, Task $task): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['todo', 'in_progress', 'done'])],
        ]);

        $task->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Task status updated successfully.',
            'data' => $task,
        ]);
    }
}
