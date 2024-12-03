<?php

namespace App\Http\Controllers\api\v1;

use App\Helpers\ApiResponse;
use App\Models\Taskify;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\v1\StoreTaskifyRequest;
use App\Http\Requests\v1\UpdateTaskifyRequest;
use App\Http\Resources\v1\TaskifyResource;
use App\Services\TaskifyService;
use Illuminate\Http\Request;


class TaskifyController extends Controller
{
    protected $taskifyService;

    public function __construct(TaskifyService $taskifyService)
    {
        $this->taskifyService = $taskifyService;
    }

    public function index()
    {
        $perPage = request('per_page', 10);
        $tasks = Auth::user()->tasks()->paginate($perPage);

        return ApiResponse::success(TaskifyResource::collection($tasks), 'Tasks retrieved successfully.');
    }

    public function store(StoreTaskifyRequest $request)
    {
        $task = Auth::user()->tasks()->create($request->validated());

        return ApiResponse::success(new TaskifyResource($task), 'Task created successfully.', 201);
    }

    public function show(Taskify $taskify, Request $request)
    {
        if ($request->user()->cannot('view', $taskify)) {
            return ApiResponse::error('You do not own this task.', 403);
        }

        return ApiResponse::success(new TaskifyResource($taskify), 'Task retrieved successfully.');
    }

    public function update(UpdateTaskifyRequest $request, Taskify $taskify)
    {
        if ($request->user()->cannot('update', $taskify))
        {
            return ApiResponse::error('You do not own this task.', 403);
        }

        $this->taskifyService->updateTask($request->user(), $taskify, $request->validated());

        return ApiResponse::success(new TaskifyResource($taskify), 'Task updated successfully.');
    }

    public function destroy(Taskify $taskify, Request $request)
    {
        if ($request->user()->cannot('delete', $taskify)) {
            return ApiResponse::error('You do not own this task.', 403);
        }

        $taskify->delete();

        return ApiResponse::success(null, 'Task deleted successfully.', 204);
    }

    public function complete(Taskify $taskify, Request $request)
    {
        if ($request->user()->cannot('update', $taskify)) {
            return ApiResponse::error('You do not own this task.', 403);
        }

        // Update the task status
        $taskify->complete();

        return ApiResponse::success(
            [
                'task' => new TaskifyResource($taskify),
                'message' => 'Task marked as completed successfully!',
            ],
            'Task completion successful.'
        );
    }
}
