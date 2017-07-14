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
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportChunk implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $upload_id;
    private $client_id;
    private $path;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($upload_id, $path)
    {
        $this->upload_id = $upload_id;
        $this->path = $path;
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

        $file = $importer->localFile($this->path);
        $upload = Upload::find($this->upload_id);

        Excel::load($file, function($reader) use($upload, $importer) {
            $data = $reader->toArray()[0];
            $importer->storeData($data, $upload);
            Storage::disk('s3')->delete($this->path);
        });

    }
}
