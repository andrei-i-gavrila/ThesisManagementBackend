<?php

namespace App\Jobs;

use App\Enums\Roles;
use App\Models\Committee;
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
        $allowedMails = collect([
            "grigo",
            "camelia",
            "radu.dragos",

            "gabis",
            "mihai-suciu",
            "dana",

            "lauras",
            "sanda",
            "cretu",

            "istvanc",
            "sabina",
            "ilazar",

        ]);
        //        $allowedMails = collect([
        //            "hfpop",
        //            "dan",
        //            "rgaceanu",
        //
        //            "avescan",
        //            "rlupsa",
        //            "arthur",
        //
        //            "craciunf",
        //            "lupea",
        //            "forest",
        //
        //            "vniculescu",
        //            "vancea",
        //            "mihoct",
        //        ]);


        $allowedMails->each(function ($mail) {
            $prof = User::query()->firstOrCreate(['email' => $mail . '@cs.ubbcluj.ro'])->assignRole(Roles::PROFESSOR);
            dispatch_now(new ProfessorDetailImporter($prof));
        });

        User::professor()->orderBy('id')->chunk(3, function ($profs) {
            Committee::create([
                'leader_id' => $profs[0]->id,
                'member1_id' => $profs[1]->id,
                'member2_id' => $profs[2]->id,
                'exam_session_id' => 2,
            ]);
        });


    }
}
