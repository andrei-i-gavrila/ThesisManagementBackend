<?php


namespace App\Events\Chat;


use App\Events\BaseEvent;
use App\Models\Comment;
use Illuminate\Broadcasting\PrivateChannel;

class CommentCreated extends BaseEvent
{

    /**
     * @var Comment
     */
    public $comment;

    public function __construct($comment)
    {

        $this->comment = $comment;
    }

    public function channel(): string
    {
        return 'chat.' . $this->comment->paper_revision_id;
    }
}