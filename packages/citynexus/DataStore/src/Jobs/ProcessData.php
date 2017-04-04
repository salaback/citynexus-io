<?php

namespace App\Jobs;

use App\Client;
use CityNexus\DataStore\DataProcessor;
use CityNexus\DataStore\Uploader;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class ProcessData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $uploader;
    private $settings;
    private $client_id;
    private $data;
    private $dataProcessor;

    /**
     * Create a new job instance.
     *
     * @param int $client_id
     * @param Uploader $uploader
     * @param array $settings
     */
    public function __construct($client_id, Uploader $uploader, $data, $settings = [])
    {
        $this->client_id = $client_id;
        $this->uploader = $uploader;
        $this->data = $data;
        $this->settings = $settings;
        $this->dataProcessor = new DataProcessor();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = Client::find($this->id);
        config(['database.connections.tenant.schema' => $client->schema]);
        DB::reconnect();

        $this->dataProcessor->processData($this->data, $this->uploader->id);
    }
}
