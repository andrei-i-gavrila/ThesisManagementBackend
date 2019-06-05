<?php

namespace App\Jobs;

use App\Models\ProfessorDetails;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProfessorDetailImporter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var User
     */
    private $professor;

    public function __construct(User $professor)
    {
        $this->professor = $professor;
    }

    public function handle()
    {
        $pageData = file_get_contents("http://www.cs.ubbcluj.ro/about-the-faculty/departments/department-of-computer-science/");
        $emailRegex = str_replace(['@', '.'], ['\\[at\\]', '\\.'], $this->professor->email);
        $re = '/src=[\'"](.*?)[\'"](?:.*\n){2,3}.*?' . $emailRegex . '(?:.*\n){2,3}.*?interest:\s(.*?)</m';

        Log::info($re);

        preg_match($re, $pageData, $matches);

        if (empty($matches)) {
            return;
        }

        ProfessorDetails::updateOrCreate([
            'professor_id' => $this->professor->id
        ], [
            'image_url' => $matches[1],
            'interest_domains' => $matches[2]
        ]);
    }
}
