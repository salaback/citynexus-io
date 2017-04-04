<?php

namespace App\Jobs;

use CityNexus\DataStore\Uploader;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class SqlImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $upload_id;
    private $settings;

    /**
     * Create a new job instance.
     *
     * @param $upload_id
     * @param null $settings
     */
    public function __construct($client_schema, $upload_id, $settings = null)
    {
        $this->upload_id = $upload_id;
        $this->settings = $settings;

        config(['database.connections.tenant.schema' => $client_schema]);
    }

    /**
     * Execute the job.
     *
     * @param \CityNexus\DataStore\Helper\Uploader $uploaderHelper
     * @internal param Uploader $uploader
     */
    public function handle(\CityNexus\DataStore\Helper\Uploader $uploaderHelper)
    {
        $uploader = Uploader::find($this->upload_id);

        $uploaderHelper->sqlUpload($this->settings, $uploader);
    }
}
