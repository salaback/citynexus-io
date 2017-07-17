<?php

namespace App\DataStore\Jobs;

use App\Client;
use App\DataStore\Importer;
use App\DataStore\Store;
use App\DataStore\Model\Upload;
use App\Mail\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportChunk implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels, DispatchesJobs;

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

        $upload = Upload::find($this->upload_id);
        $parts = $upload->parts;

        if(count($parts) == 0)
        {

        }
        else
        {
            $path = array_shift($parts);

            // get the json file
            $data = Storage::disk('s3')->get($path);

            // decode to an array
            $data = json_decode($data, true);

            // store the data
            $importer->storeData($data, $upload);

            // delete the temp file from s3
            Storage::disk('s3')->delete($path);

            // update the parts list
            $upload->parts = $parts;
            $upload->save();

            // dispatch the next chunk
            $this->dispatch(new ImportChunk($upload->id));
        }
    }
}
