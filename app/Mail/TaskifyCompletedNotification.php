<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TaskifyCompletedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $completedCount;

    public function __construct($completedCount)
    {
        $this->completedCount = $completedCount;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('no-reply@yourdomain.com', 'Your Name'),
            subject: 'Taskify Completed Notification',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.task_completed',
        );
    }

    public function attachments(): array
    {
        return [];
    }

    public function build()
    {
        return $this->subject('Congratulations on Your Task Milestone!')
                    ->view('emails.task_completed', ['count' => $this->completedCount]);
    }
}
