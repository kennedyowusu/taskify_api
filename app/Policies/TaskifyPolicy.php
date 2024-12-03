<?php

namespace App\Policies;

use App\Models\Taskify;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class TaskifyPolicy
{
    public function view(User $user, Taskify $taskify): Response
    {
        return $user->id === $taskify->user_id
            ? Response::allow()
            : Response::deny('You do not own this task.');
    }

    public function update(User $user, Taskify $taskify): bool
    {
        Log::info("User ID: {$user->id}, Taskify Owner ID: {$taskify->user_id}");

        return $user->id === $taskify->user_id;
    }

    public function delete(User $user, Taskify $taskify): bool
    {
        return $user->id === $taskify->user_id;
    }
}
