<?php

namespace App\Jobs;

use App\Client;
use CityNexus\AnalysisMgr\Calculator;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class StoreCalculateValue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $client_id;
    private $type;
    private $data;
    private $field;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($client_id, $type, $field, $data)
    {
        $this->client_id = $client_id;
        $this->type = $type;
        $this->data = $data;
        $this->field = $field;
    }

    /**
     * Execute the job.
     *
     * @param Calculator $calculator
     */
    public function handle(Calculator $calculator)
    {

        Client::find($this->client_id)->logInAsClient();

        $calculator->storeValueData($this->type, $this->field, $this->data);

    }
}
