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
        for ($i = 1; $i <= 4; $i++) {
            $basePath = 'papers\\bulk\\ro\\comisia' . $i . '\\';
            $comisieFile = 'comisia' . $i . '.txt';
            $students = collect(explode("\n", \Storage::get($basePath . $comisieFile)))->filter()->mapWithKeys(function ($line) {
                $tmp = explode(",", $line);
                return [$tmp[0] => $tmp[1]];
            });


            $students->each(function ($paperPath, $name) use ($i, $basePath) {
                $text = $this->readerService->parseText(\Storage::path($basePath . $paperPath));
                if (!$text) {
                    \Log::error('cannot read file ' . $paperPath);
                    return true;
                }

                $student = User::query()->firstOrCreate([
                    'email' => $name . '@scs.ubbcluj.ro',
                    'name' => Str::title(Str::replaceFirst('_', ' ', $name)),
                ])->assignRole(Roles::STUDENT);



                $paperLanguage = 'ro';
                if (preg_match('/contents/i', $text)) {
                    $paperLanguage = 'en';
                }


                \Storage::put($basePath . $paperPath . '.keywords', implode("\n", $this->extractorService->extract($text, $paperLanguage)));

                $paper = $student->paper()->create([
                    'language' => $paperLanguage,
                    'exam_session_id' => 2,
                    'committee_id' => $i
                ]);




                $paper->revisions()->save(new PaperRevision([
                    'name' => "Diploma thesis",
                    'filepath' => $basePath . $paperPath
                ]));
            });
        }

    }
}
