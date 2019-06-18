<?php

namespace App\Http\Controllers;

use App\Events\Chat\CommentCreated;
use App\Events\Chat\CommentDeleted;
use App\Events\Chat\CommentUpdated;
use App\Models\Comment;
use App\Models\Paper;
use App\Models\PaperRevision;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CommentController extends Controller
{
    /**
     * @param Request $request
     * @param Paper $paperRevision
     * @throws ValidationException
     */
    public function create(Request $request, PaperRevision $paperRevision)
    {
        $this->validate($request, [
            'message' => 'required|string'
        ]);

        $comment = $paperRevision->comments()->save(new Comment([
            'message' => $request->input('message'),
            'user_id' => Auth::id()
        ]))->load('user:id,name,email');
        if ($comment) {
            broadcast(new CommentCreated($comment))->toOthers();
            return $comment;
        } else {
            throw new UnprocessableEntityHttpException("Cannot save");
        }
    }

    /**
     * @param Request $request
     * @param Comment $comment
     * @throws ValidationException
     */
    public function update(Request $request, Comment $comment)
    {
        $this->validate($request, [
            'message' => 'required|string'
        ]);

        $comment->update([
            'message' => $request->input('message'),
        ]);


        broadcast(new CommentUpdated($comment))->toOthers();
    }

    /**
     * @param Comment $comment
     * @throws Exception
     */
    public function delete(Comment $comment)
    {
        broadcast(new CommentDeleted($comment));
        $comment->delete();
    }

    public function getForPaper(PaperRevision $paperRevision)
    {
        return $paperRevision->comments()->with('user:id,email,name')->get();
    }
}
