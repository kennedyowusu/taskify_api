<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskifyCompletedNotification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SendTaskifyCompletionEmail implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $userId;
    protected $completedCount;

    public function __construct($userId, $completedCount)
    {
        $this->userId = $userId;
        $this->completedCount = $completedCount;
    }

    public function handle()
    {
        try {
            $user = User::find($this->userId);
            Log::info('SendTaskifyCompletionEmail job started for user: ' . $user->email);

            Mail::to($user->email)->send(new TaskifyCompletedNotification($this->completedCount));
            Log::info('Email sent successfully to: ' . $user->email);

        } catch (\Exception $e) {
            Log::error('Failed to send email: ' . $e->getMessage());
        }
    }
}
