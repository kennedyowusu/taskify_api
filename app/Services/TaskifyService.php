<?php

namespace App\Services;

use App\Models\Taskify;
use App\Jobs\SendTaskifyCompletionEmail;
use Illuminate\Support\Facades\Log;

class TaskifyService
{
    public function updateTask($user, Taskify $taskify, array $data)
    {
        $taskify->update($data);

        if ($data['is_completed'] ?? false) {
            Log::info('Task marked as completed');
            $completedCount = $user->tasks()->where('is_completed', true)->count();
            Log::info("Total completed tasks: {$completedCount}");

            if ($completedCount % 5 === 0) {
                Log::info("Milestone reached with {$completedCount} completed tasks");
                SendTaskifyCompletionEmail::dispatch($user->id, $completedCount);
            }
        }
    }
}
