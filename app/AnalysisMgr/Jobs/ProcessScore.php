<?php

namespace App\AnalysisMgr\Jobs;

use App\AnalysisMgr\ScoreProcessor;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessScore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $score_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->score_id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ScoreProcessor $processor)
    {
        $processor->processScore($this->score_id);
    }
}
