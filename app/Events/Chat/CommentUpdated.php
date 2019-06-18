<?php


namespace App\Events\Chat;


use App\Events\BaseEvent;
use App\Models\Comment;
use Illuminate\Broadcasting\PrivateChannel;

class CommentUpdated extends BaseEvent
{

    /**
     * @var Comment
     */
    public $comment;

    public function __construct(Comment $comment)
    {

        $this->comment = $comment;
    }


    public function broadcastAs()
    {
        return 'CommentUpdated\\' . $this->comment->id;
    }

    public function channel(): string
    {
        return 'chat.' . $this->comment->paper_revision_id;
    }
}