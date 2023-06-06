<?php

namespace App\Jobs;

use App\Models\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PreventUpdateExamScores implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $examId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $examId)
    {
       $this->examId = $examId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Exam::where('id', $this->examId)->update(['can_edit_scores' => false]);
    }
}
