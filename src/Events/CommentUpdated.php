<?php

namespace PanicHD\PanicHD\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use PanicHD\PanicHD\Models\Comment;

class CommentUpdated
{
    use InteractsWithSockets, SerializesModels;
    public $original;
    public $modified;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Comment $original, Comment $modified)
    {
        $this->original = $original;
        $this->modified = $modified;
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
