<?php

namespace App\AnalysisMgr\Jobs;

use App\AnalysisMgr\ScoreProcessor;
use App\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessScore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $score_id;
    protected $client_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->score_id = $id;
        $this->client_id = config('client.id');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ScoreProcessor $processor)
    {
        Client::find($this->client_id)->logInAsClient();

        $processor->processScore($this->score_id);
    }
}
