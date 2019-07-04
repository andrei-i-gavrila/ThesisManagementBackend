<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\PaperMetrics;
use App\Models\PaperRevision;
use App\Services\KeywordExtractorService;
use App\Services\PdfReaderService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PaperRevisionController extends Controller
{
    /**
     * @param Request $request
     * @param Paper $paper
     * @param PdfReaderService $readerService
     * @param KeywordExtractorService $extractorService
     * @return false|Model
     * @throws ValidationException
     * @throws \Exception
     */
    public function create(Request $request, Paper $paper, PdfReaderService $readerService, KeywordExtractorService $extractorService)
    {
        $this->validate($request, [
            'paper' => 'file|mimes:pdf|required',
            'name' => 'nullable|string'
        ]);

        $studentEmail = Auth::user()->email;

        $revisionName = $request->input('name') ?? 'Revision' . $paper->revisions()->count();

        $date = now()->format('Y-m-d-His');

        $filepath = Storage::putFileAs("papers/$studentEmail", $request->file('paper'), "$date-$revisionName" . '.' . $request->file('paper')->extension());


        $text = $readerService->parse(Storage::path($filepath));
        if (!$text) {
            Storage::delete($filepath);
            abort(422, 'Pdf cannot be parsed. Try a different file (Sometimes converting it to another pdf version works. Try 1.4)');
        }


        Storage::put($filepath . '.keywords', implode("\n", $extractorService->extract($text->getText(), $paper->language)));


        $revision = $paper->revisions()->save(new PaperRevision([
            'name' => $revisionName,
            'filepath' => $filepath
        ]));

        if (!$revision) {
            Storage::delete($filepath);
            Storage::delete($filepath . '.keywords');
        }



        $char_count = strlen($text->getText());
        preg_match_all('/\b\w+\b/', $text->getText(), $matches);
        $word_count = count($matches[0]);
        $page_count = count($text->getPages());

        $revision->metrics()->save(new PaperMetrics(compact('char_count', 'page_count', 'word_count')));
        return $revision->fresh();
    }

    public function download(PaperRevision $paperRevision)
    {
        return Storage::download($paperRevision->filepath);
    }

}
