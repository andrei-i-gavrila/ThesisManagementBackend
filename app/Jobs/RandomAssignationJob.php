<?php

namespace App\Jobs;

use App\Models\ExamSession;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RandomAssignationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var ExamSession
     */
    private $examSession;

    /**
     * Create a new job instance.
     *
     * @param ExamSession $examSession
     */
    public function __construct(ExamSession $examSession)
    {
        $this->examSession = $examSession;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $committees = $this->examSession->committees;
        $studentsGroups = $this->examSession->students->shuffle()->split($committees->count());

        $committees->zip($studentsGroups)->each(function ($pair) {
            $pair[0]->assignedStudents()->sync($pair[1]);
        });
    }
}
