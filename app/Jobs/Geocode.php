<?php

namespace App\Jobs;

use App\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Session\Session;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class Geocode implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;


    private $property_id;
    private $geocode;
    private $client_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($property_id)
    {
        $this->property_id = $property_id;
        $this->client_id = config('client.id');

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(session('client.id') != $this->client_id)  Client::find($this->client_id)->logInAsClient();

        $geocode = new \CityNexus\PropertyMgr\GeocodeHelper();
        $geocode->property($this->property_id);

    }
}
