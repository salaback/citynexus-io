<?php

namespace App\Jobs;

use App\Client;
use CityNexus\DataStore\Helper\UploadHelper;
use CityNexus\DataStore\Uploader;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StartImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $uploader_id;
    private $client_id;
    private $user_id;

    /**
     *
     * Create a new job instance.
     *
     * @param int $client_id
     * @param $uploader_id
     */
    public function __construct($client_id, $uploader_id)
    {
        $this->client_id = $client_id;
        $this->uploader_id = $uploader_id;
        $this->user_id = Auth::id();

    }

    /**
     * Execute the job.
     *
     * @param UploadHelper $uploadHelper
     */
    public function handle(UploadHelper $uploadHelper)
    {
        Client::find($this->client_id)->logInAsClient();

        $uploader = Uploader::find($this->uploader_id);

        switch ($uploader->type)
        {
            case 'sql';
                $uploadHelper->sqlUpload($this->uploader_id, ['user_id' => $this->user_id]);
                break;

        }
    }
}
