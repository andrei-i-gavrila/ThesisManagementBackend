<?php

namespace App\Jobs;

use App\Models\ProfessorDetails;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProfessorDetailImporter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var User
     */
    private $professor;

    /**
     * Create a new job instance.
     *
     * @param User $professor
     */
    public function __construct(User $professor)
    {
        //
        $this->professor = $professor;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pageData = file_get_contents("http://www.cs.ubbcluj.ro/about-the-faculty/departments/department-of-computer-science/");
        $emailRegex = str_replace(['@', '.'], ['\\[at\\]', '\\.'], $this->professor->email);

        $re = '/src=\'(.*?)\'.*\n(?:.*\n){2}.*?' . $emailRegex . '(?:.*\n){3}.*?:\s(.*?)</m';

        preg_match($re, $pageData, $matches);

        if (empty($matches)) {
            return;
        }

        $image_url = $matches[1];
        $interest_domains = $matches[2];
        $professor_id = $this->professor->id;

        ProfessorDetails::create(compact('image_url', 'interest_domains', 'professor_id'));
    }
}
