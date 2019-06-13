<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Storage;

class PaperController extends Controller
{

    /**
     * @param Request $request
     * @throws ValidationException
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'paper' => 'file|mimes:pdf|required',
            'name' => 'required'
        ]);

        $studentEmail = Auth::user()->email;
        $paperName = $request->name ;
        $date = now()->format('Y-m-d-His');

        $filepath = Storage::putFileAs("papers/$studentEmail", $request->file('paper' ), "$date-$paperName" . '.' . $request->file('paper')->extension());

        $paper = Paper::create([
            'filepath' => $filepath,
            'name' => $paperName,
            'student_id' => Auth::id()
        ]);

        if (!$paper) {
            Storage::delete($filepath);
        }
    }

    public function download(Paper $paper)
    {
        return Storage::download($paper->filepath);
    }

    public function getMine()
    {
        return Auth::user()->papers;
    }

    /**
     * @param User|Authenticatable $user
     * @return mixed
     */
    public function get(User $user)
    {
        return $user->papers;
    }

    public function getFinalizedPapers() {
        return Paper::whereHas('review', function (Builder $query) {
            return $query->where('final', true);
        });
    }
}
