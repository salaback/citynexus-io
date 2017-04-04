<?php

namespace App\Jobs;

use CityNexus\DataStore\Uploader;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportProcessData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;
    private $uploader;
    private $uploadHelper;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $upload_id)
    {
        $this->data = $data;
        $this->uploader = Uploader::find($upload_id);
        $this->uploadHelper = new \CityNexus\DataStore\Helper\Uploader();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->uploadHelper->importData($this->data, $this->uploader);
    }
}
