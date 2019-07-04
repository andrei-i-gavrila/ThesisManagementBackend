<?php

namespace App\Jobs;

use App\Enums\Roles;
use App\Models\PaperRevision;
use App\Models\ProfessorDetails;
use App\Models\User;
use App\Services\KeywordExtractorService;
use App\Services\PdfReaderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudentImporter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $fileToImport;
    /**
     * @var PdfReaderService
     */
    private $readerService;
    /**
     * @var KeywordExtractorService
     */
    private $extractorService;

    /**
     * Create a new job instance.
     *
     * @param $fileToImport
     * @param PdfReaderService $readerService
     * @param KeywordExtractorService $extractorService
     */
    public function __construct(PdfReaderService $readerService, KeywordExtractorService $extractorService)
    {
        $this->readerService = $readerService;
        $this->extractorService = $extractorService;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        for ($i = 5; $i < 9; $i++) {
            $basePath = 'papers\\bulk\\comisia' . $i . '\\';
            $comisieFile = 'comisia' . $i . '.txt';
            $students = collect(explode("\n", \Storage::get($basePath . $comisieFile)))->filter()->mapWithKeys(function ($line) {
                $tmp = explode(",", $line);
                return [$tmp[0] => $tmp[1]];
            });


            $students->each(function ($paperPath, $name) use ($i, $basePath) {
                if (!\Storage::exists($basePath . $paperPath . '.keywords')) {
                    return true;
                }
                $student = User::query()->firstOrCreate([
                    'email' => $name . '@scs.ubbcluj.ro',
                    'name' => Str::title(Str::replaceFirst('_', ' ', $name)),
                ])->assignRole(Roles::STUDENT);

                $paper = $student->paper()->create([
                    'language' => 'en',
                    'exam_session_id' => 2,
                    'committee_id' => $i - 3
                ]);

//                $text = $this->readerService->parse(\Storage::path($basePath . $paperPath));
//                if (!$text) {
//                    \Log::error('cannot read file ' . $paperPath);
//                    return true;
//                }
//
//
//                \Storage::put($basePath . $paperPath . '.keywords', implode("\n", $this->extractorService->extract($text, $paper->language)));


                $paper->revisions()->save(new PaperRevision([
                    'name' => "Test diploma",
                    'filepath' => $basePath . $paperPath
                ]));
            });
        }

    }
}
