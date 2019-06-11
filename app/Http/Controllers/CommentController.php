<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Paper;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    /**
     * @param Request $request
     * @param Paper $paper
     * @throws ValidationException
     */
    public function create(Request $request, Paper $paper)
    {
        $this->validate($request, [
            'message' => 'required|string'
        ]);

        $paper->comments()->save(new Comment([
            'message' => $request->input('message'),
            'user_id' => Auth::id()
        ]));
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
    }

    /**
     * @param Comment $comment
     * @throws Exception
     */
    public function delete(Comment $comment)
    {
        $comment->delete();
    }

    public function getForPaper(Paper $paper)
    {
        return $paper->comments;
    }
}
