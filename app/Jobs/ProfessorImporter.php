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

            "hfpop",
            "dan",
            "rgaceanu",

            "avescan",
            "rlupsa",
            "arthur",

            "craciunf",
            "lupea",
            "forest",

            "vniculescu",
            "vancea",
            "mihoct",
        ]);


        $allowedMails->each(function ($mail) {
            $prof = User::query()->firstOrCreate(['email' => $mail . '@cs.ubbcluj.ro'])->assignRole(Roles::PROFESSOR);
            dispatch_now(new ProfessorDetailImporter($prof));
        });


    }
}
