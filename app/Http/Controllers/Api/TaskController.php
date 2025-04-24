<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tags;
use App\Models\Tasks;
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
            $tasks = Tasks::all();

            if ($tasks->isEmpty()) {
                return response()->json([
                    'message' => 'No tasks found'
                ], 404);
            }

            $tasks->each(function ($task) {
                $taskTags = $this->getTaskTags($task->id);
                $task->tags = $taskTags;
            });

            return response()->json([
                'data' => $tasks
            ]);
        }

        $tasks = Tasks::where('created_by', auth()->id())
            ->orWhere('responsible', auth()->id())
            ->get();

        if ($tasks->isEmpty()) {
            return response()->json([
                'message' => 'No tasks found'
            ], 404);
        }

        $tasks->each(function ($task) {
            $taskTags = $this->getTaskTags($task->id);
            $task->tags = $taskTags;
        });

        return response()->json([
            'data' => $tasks
        ]);
    }

    /**
     * Store a newly created tasks in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'string|max:255',
                'description' => 'nullable|string',
                'status' => 'string|in:pending,in_progress,completed',
                'due_date' => 'nullable|date',
                'priority' => 'string|in:low,medium,high',
                'responsible' => 'exists:users,id',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->validator->errors()
            ], 422);
        }

        $task = Tasks::create([
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
            'message' => 'Tasks created successfully',
            'data' => $task
        ], 201);
    }

    /**
     * Display the specified tasks.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {

        $task = Tasks::where('created_by', auth()->id())->find($id)
            ?? Tasks::where('responsible', auth()->id())->find($id);

        if (!$task) {
            return response()->json([
                'message' => 'Tasks not found'
            ], 404);
        }

        $taskTags = $this->getTaskTags($task->id);
        $task->tags = $taskTags;

        return response()->json([
            'data' => $task
        ]);
    }

    /**
     * Update the specified tasks in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'string|max:255',
                'description' => 'nullable|string',
                'status' => 'string|in:pending,in_progress,completed',
                'due_date' => 'date',
                'priority' => 'string|in:low,medium,high',
                'responsible' => 'exists:users,id'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->validator->errors()
            ], 422);
        }

        if (auth()->user()->is_admin) {
            $task = Tasks::find($id);
        } else {
            $task = Tasks::where('responsible', auth()->id())->find($id);
        }

        if (!$task) {
            return response()->json([
                'message' => 'Tasks not found'
            ], 404);
        }

        $task->update($request->all());

        return response()->json([
            'message' => 'Tasks updated successfully',
            'data' => $task
        ]);
    }

    /**
     * Remove the specified tasks from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $task = Tasks::find($id);

        if (!auth()->user()->is_admin && $task->responsible != auth()->id()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        };

        if (!$task) {
            return response()->json([
                'message' => 'Tasks not found'
            ], 404);
        }

        $task->delete();

        return response()->json([
            'message' => 'Tasks deleted successfully'
        ]);
    }

    protected function getTaskTags($taskId)
    {
        $tags = Tags::join('tags_tasks', 'tags.id', '=', 'tags_tasks.tag_id')
            ->where('tags_tasks.task_id', $taskId)
            ->select('tags.*')
            ->get();

        return $tags->map(function ($tag) {
            return $tag;
        });
    }
}
