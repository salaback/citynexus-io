<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class Geocode implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;


    private $property_id;
    private $geocode;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($property_id)
    {
        $this->property_id = $property_id;
        $this->geocode = new \CityNexus\PropertyMgr\Geocode();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(config('citynexus.geocoding') == true)
        {
            $this->geocode->property($this->property_id);
        }
    }
}
