<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssigned;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Create a new TaskController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the tasks.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {

        if (auth()->user()->is_admin) {
            $tasks = Task::all();
            return response()->json([
                'data' => $tasks
            ]);
        }

        $tasks = Task::where('created_by', auth()->id())
            ->orWhere('responsible', auth()->id())
            ->get();

        return response()->json([
            'data' => $tasks
        ]);
    }

    /**
     * Store a newly created task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'nullable|string|in:pending,in_progress,completed',
                'due_date' => 'nullable|date',
                'priority' => 'nullable|string|in:low,medium,high',
                'responsible' => 'nullable|exists:users,id',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->getMessage(),
            ], 422);
        }

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? 'pending',
            'due_date' => $request->due_date,
            'priority' => $request->priority ?? 'medium',
            'responsible' => $request->responsible,
            'created_by' => auth()->id()
        ]);

        if ($task->responsible) {
            $responsibleUser = User::find($task->responsible);
            if ($responsibleUser) {
                $responsibleUser->notify(new TaskAssigned($task));
            }
        }

        return response()->json([
            'message' => 'Task created successfully',
            'data' => $task
        ], 201);
    }

    /**
     * Display the specified task.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {

        $task = Task::where('created_by', auth()->id())->find($id)
            ?? Task::where('responsible', auth()->id())->find($id);

        if (!$task) {
            return response()->json([
                'message' => 'Task not found'
            ], 404);
        }

        return response()->json([
            'data' => $task
        ]);
    }

    /**
     * Update the specified task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:pending,in_progress,completed',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|string|in:low,medium,high',
            'responsible' => 'nullable|exists:users,id',
        ]);

        if (auth()->user()->is_admin) {
            $task = Task::find($id);
        } else {
            $task = Task::where('created_by', auth()->id())->find($id);
        }

        if (!$task) {
            return response()->json([
                'message' => 'Task not found'
            ], 404);
        }

        $task->update($request->all());

        return response()->json([
            'message' => 'Task updated successfully',
            'data' => $task
        ]);
    }

    /**
     * Remove the specified task from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $task = Task::find($id);

        if (!auth()->user()->is_admin && $task->created_by != auth()->id()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        };

        if (!$task) {
            return response()->json([
                'message' => 'Task not found'
            ], 404);
        }

        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully'
        ]);
    }
}
