<?php

namespace App\Notifications;

use App\Models\Thread;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReplySubmitted extends Notification
{
    use Queueable;

    /**
     * @var Thread
     */
    public $thread;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Thread $thread)
    {
        //
        $this->thread = $thread;
    }
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'thread_title' => $this->thread->title,
            'url' => route('threads.show', $this->thread),
            'time' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
