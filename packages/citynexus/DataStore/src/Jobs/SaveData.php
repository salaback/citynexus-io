<?php

namespace App\Jobs;

use App\Client;
use CityNexus\DataStore\DataProcessor;
use CityNexus\DataStore\Helper\UploadHelper;
use CityNexus\DataStore\Uploader;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class SaveData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $uploader_id;
    private $uploadHelper;
    private $data;
    private $upload_id;
    private $client_id;

    /**
     *
     * Create a new job instance.
     *
     * @param int $client_id
     * @param Uploader $uploader
     * @param array $settings
     */
    public function __construct($client_id, $data, $uploader_id, $upload_id)
    {

        $this->client_id = $client_id;
        $this->data = $data;
        $this->uploader_id = $uploader_id;
        $this->upload_id = $upload_id;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Client::find($this->client_id)->logInAsClient();

        $uploader = Uploader::find($this->uploader_id);
        $uploadHelper = new UploadHelper();

        $uploadHelper->importData($this->data, $uploader, $this->upload_id);
    }
}
