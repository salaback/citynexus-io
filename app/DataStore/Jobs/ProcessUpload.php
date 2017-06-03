<?php

namespace App\DataStore\Jobs;

use App\Client;
use App\DataStore\DataProcessor;
use App\DataStore\processSql;
use App\DataStore\Model\Upload;
use App\DataStore\Store;
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

    private $upload;
    private $settings;
    private $client_id;

    /**
     * Create a new job instance.
     *
     * @param int $client_id
     * @param Uploader $uploader
     * @param array $settings
     */
    public function __construct($client_id, Upload $upload, $settings = [])
    {
        $this->client_id = $client_id;
        $this->upload = $upload;
        $this->settings = $settings;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Client::find($this->client_id)->logInAsClient();

        $store = new Store();

        switch ($this->upload->uploader->type)
        {
            case 'sql':
                $data = $store->processSQL->sql($this->upload->uploader);
                $data = array_chunk($data, 50);
                foreach($data as $i)
                {

                }
                break;

            case 'csv':
                $store->processCSV($this->upload);
        }

    }
}
