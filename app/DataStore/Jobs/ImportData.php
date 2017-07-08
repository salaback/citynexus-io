<?php

namespace App\DataStore\Jobs;

use App\Client;
use App\DataStore\Importer;
use App\DataStore\Store;
use App\DataStore\Model\Upload;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportData implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $upload_id;
    private $client_id;

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

        $importer = new Importer();

        $importer->fromUpload(Upload::find($this->upload_id));

    }
}
