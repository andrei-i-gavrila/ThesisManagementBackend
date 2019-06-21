<?php

namespace App\Http\Controllers;

use App\Models\ExamSession;
use App\Models\Paper;
use App\Models\PaperRevision;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Storage;

class PaperRevisionController extends Controller
{
    /**
     * @param Request $request
     * @param Paper $paper
     * @return false|Model
     * @throws ValidationException
     */
    public function create(Request $request, Paper $paper)
    {
        $this->validate($request, [
            'paper' => 'file|mimes:pdf|required',
            'name' => 'nullable|string'
        ]);

        $studentEmail = Auth::user()->email;

        $revisionName = $request->input('name') ?? 'Revision' . $paper->revisions()->count();

        $date = now()->format('Y-m-d-His');

        $filepath = Storage::putFileAs("papers/$studentEmail", $request->file('paper'), "$date-$revisionName" . '.' . $request->file('paper')->extension());

        $revision = $paper->revisions()->save(new PaperRevision([
            'name' => $revisionName,
            'filepath' => $filepath
        ]));

        if (!$revision) {
            Storage::delete($filepath);
        }

        return $revision;
    }

    public function download(PaperRevision $paperRevision)
    {
        return Storage::download($paperRevision->filepath);
    }

}
