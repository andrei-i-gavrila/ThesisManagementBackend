<?php

namespace App\Console\Commands;

use App\Jobs\ProfessorImporter;
use App\Jobs\RandomAssignationJob;
use App\Jobs\StudentImporter;
use App\Models\ExamSession;
use App\Services\KeywordExtractorService;
use App\Services\PdfReaderService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InitDevEnv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reinits the app with dev settings';

    public function handle()
    {
//        Artisan::call('migrate:fresh', ['--seed' => true]);
//        Artisan::call('roles:update');
//        Artisan::call('ide-helper:models', ["-W" => true]);

        dispatch_now(new ProfessorImporter());
        dispatch_now(new StudentImporter(new PdfReaderService(), new KeywordExtractorService()));

//        dispatch_now(new RandomAssignationJob(ExamSession::first()));
    }
}
