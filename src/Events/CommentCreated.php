<?php

namespace PanicHD\PanicHD\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use PanicHD\PanicHD\Models\Comment;

class CommentCreated
{
    use InteractsWithSockets, SerializesModels;
    public $comment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Comment $comment, Request $request)
    {
        $this->comment = $comment;
        $this->request = $request;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
