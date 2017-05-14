<?php

namespace App\Jobs;

use App\Client;
use CityNexus\DataStore\DataProcessor;
use CityNexus\DataStore\processSql;
use CityNexus\DataStore\Uploader;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class ProcessUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $uploader;
    private $settings;
    private $client_id;

    /**
     * Create a new job instance.
     *
     * @param int $client_id
     * @param Uploader $uploader
     * @param array $settings
     */
    public function __construct($client_id, Uploader $uploader, $settings = [])
    {
        $this->client_id = $client_id;
        $this->uploader = $uploader;
        $this->settings = $settings;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Client::find($this->id)->logInAsClient();

        switch ($this->uploader->type)
        {
            case 'sql':
                $sql = new processSql();
                $data = $sql->sql($this->uploader);
                $data = array_chunk($data, 50);
                foreach($data as $i)
                {

                }
                break;
        }

    }
}
