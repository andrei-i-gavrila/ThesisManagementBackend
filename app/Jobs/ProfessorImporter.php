<?php

namespace App\Jobs;

use App\Enums\Roles;
use App\Models\ProfessorDetails;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProfessorImporter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pageData = file_get_contents("http://www.cs.ubbcluj.ro/about-the-faculty/departments/department-of-computer-science/");
        $re = '/src=[\'"](.*?)[\'"](?:.*\n){2,3}.*?:\s?(.*?)\[at\](.*?)<(?:.*\n){2,3}.*?interest:\s?(.*?)</m';

        preg_match_all($re, $pageData, $matches, PREG_SET_ORDER);

        $allowedMails = [
            "hfpop@cs.ubbcluj.ro",
            "dan@cs.ubbcluj.ro",
            "rgaceanu@cs.ubbcluj.ro",
            "avescan@cs.ubbcluj.ro",
            "rlupsa@cs.ubbcluj.ro",
            "arthur@cs.ubbcluj.ro",
            "craciunf@cs.ubbcluj.ro",
            "lupea@cs.ubbcluj.ro",
            "forest@cs.ubbcluj.ro",
            "vniculescu@cs.ubbcluj.ro",
            "vancea@cs.ubbcluj.ro",
            "mihoct@cs.ubbcluj.ro",
        ];

        foreach ($matches as $match) {
//            if (empty($match[4])) {
//                continue;
//            }


            $email = $match[2] . "@" . $match[3];
            //            if (!in_array($email, $allowedMails)) {
            //                continue;
            //            }
            $professor = User::firstOrNew(['email' => $email])->assignRole(Roles::PROFESSOR);
            $professor->save();
            $professor->professorDetails()->save(new ProfessorDetails([
                'image_url' => $match[1],
                'interest_domains' => $match[4]
            ]));
        }
    }
}
