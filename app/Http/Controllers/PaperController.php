<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PaperController extends Controller
{

    /**
     * @param Request $request
     * @throws ValidationException
     */
    public function updateDetails(Request $request)
    {
        $attributes = $this->validate($request, [
            'name' => 'string|required',
            'link' => 'nullable|string'
        ]);

        Paper::updateOrCreate([
            'student_id' => Auth::id(),
        ], $attributes);
    }


    public function getWithRevisions(User $user)
    {
        $paper = $user->paper;
        if (!$paper) {
            $paper = $user->paper()->save(new Paper());
        }
        return $paper->load('revisions');
    }
}
