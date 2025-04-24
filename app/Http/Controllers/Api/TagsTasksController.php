<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tags;
use App\Models\TagsTasks;
use App\Models\Tasks;
use App\Models\User;
use Illuminate\Http\Request;

class TagsTasksController extends Controller
{
    /**
     * Create a new TagsTasksController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Update a newly tag-task relationship in storage.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        try {

            $request->validate([
                'tag_id' => 'required|array',
                'tag_id.*' => 'exists:tags,id',
                'task_id' => 'required|exists:tasks,id',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->validator->errors()
            ], 422);
        }

        $tags = Tags::find($request->tag_id);
        $task = Tasks::find($request->task_id);

        if ($task->created_by != auth()->id() && $task->responsible != auth()->id() && !auth()->user()->is_admin) {
            return response()->json([
                'message' => 'You do not have permission to update tags for this task'
            ], 403);
        }

        if (!$tags || !$task) {
            return response()->json([
                'message' => 'Tag or Task not found'
            ], 404);
        }

        $this->updateTagsTasks($task, $tags);

        return response()->json([
            'message' => 'Tags updated to task successfully'
        ]);
    }

    public function destroy(Request $request)
    {
        try {
            $request->validate([
                'tag_id' => 'required|array',
                'tag_id.*' => 'exists:tags,id',
                'task_id' => 'required|exists:tasks,id',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->validator->errors()
            ], 422);
        }

        $tags = Tags::find($request->tag_id);
        $task = Tasks::find($request->task_id);

        if ($task->created_by != auth()->id() && $task->responsible != auth()->id() && !auth()->user()->is_admin) {
            return response()->json([
                'message' => 'You do not have permission to delete tags for this task'
            ], 403);
        }

        if (!$tags || !$task) {
            return response()->json([
                'message' => 'Tag or Task not found'
            ], 404);
        }

        $this->deleteTagsTasks($task, $tags);

        return response()->json([
            'message' => 'Tags deleted from task successfully'
        ]);
    }

    /**
     * Remove the specified tag-task relationship from storage.
     * 
     * @return void
     */
    protected function updateTagsTasks($task, $tags)
    {
        $this->deleteTagsTasks($task, $tags);
        $this->createTagsTasks($task, $tags);
    }

    /**
     * Remove the specified tag-task relationship from storage.
     * 
     * @return void
     */
    protected function createTagsTasks($task, $tags)
    {
        foreach ($tags as $tag) {
            TagsTasks::create([
                'task_id' => $task->id,
                'tag_id' => $tag->id,
            ]);
        }
    }

    /**
     * Remove the specified tag-task relationship from storage.
     * 
     * @return void
     */
    protected function deleteTagsTasks($task, $tags)
    {
        foreach ($tags as $tag) {
            TagsTasks::where('task_id', $task->id)
                ->where('tag_id', $tag->id)
                ->delete();
        }
    }
}
