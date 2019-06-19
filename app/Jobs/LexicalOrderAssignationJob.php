<?php

namespace App\Jobs;

use App\Models\ExamSession;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class LexicalOrderAssignationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $studentsGroups = $this->examSession->students->sortBy('name')->split($committees->count());

        $committees->zip($studentsGroups)->each(function ($pair) {
            $pair[0]->assignedStudents()->sync($pair[1]);
        });
    }
}
