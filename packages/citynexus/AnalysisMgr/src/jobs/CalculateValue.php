<?php

namespace App\Jobs;

use App\Client;
use CityNexus\AnalysisMgr\Calculator;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CalculateValue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $id;
    private $client_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($client_id, $id)
    {
        $this->client_id = $client_id;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @param Calculator $calculator
     */
    public function handle(Calculator $calculator)
    {
        Client::find($this->client_id)->logInAsClient();

        $calculator->calculateValue($this->id);
    }
}
