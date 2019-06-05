<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use Illuminate\Contracts\Auth\Authenticatable;
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
        $paperName = $request->name;
        $date = now()->format('Y-m-d');

        $filepath = Storage::putFileAs("papers/$studentEmail", $request->file('paper'), "$date-$paperName");

        Paper::create([
            'filepath' => $filepath,
            'name' => $paperName,
        ]);
    }

    public function download(Paper $paper)
    {
        return Storage::response($paper->filepath);
    }

    public function getMine()
    {
        return $this->get(Auth::user());
    }

    /**
     * @param User|Authenticatable $user
     * @return mixed
     */
    public function get(User $user)
    {
        return $user->papers;
    }
}
