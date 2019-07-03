<?php

namespace App\Jobs;

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
        ]);


        $allowedMails->each(function ($mail) {
            $prof = User::firstOrCreate(['email' => $mail]);
            dispatch_now(new ProfessorDetailImporter($prof));
        });


    }
}
