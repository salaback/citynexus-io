<?php

namespace App\DataStore\Jobs;

use App\Client;
use App\DataStore\Importer;
use App\DataStore\TableBuilder;
use App\Organization;
use Carbon\Carbon;
use App\DataStore\Model\Uploader;
use App\DataStore\Store;
use App\DataStore\Model\Upload;
use App\PropertyMgr\Sync;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class ProcessData implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $data;
    private $client_id;
    private $upload_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($upload_id)
    {
        $this->upload_id = $upload_id;
        $this->client_id = config('client.id');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // log in client
        Client::find($this->client_id)->logInAsClient();

        $upload = Upload::find($this->upload_id);

        $import = new Importer();

        $import->fromUpload($upload);

    }
}
