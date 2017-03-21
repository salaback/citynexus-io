<?php

namespace App\Jobs;

use App\Client;
use CityNexus\CityNexus\Property;
use CityNexus\PropertyMgr\PropertySync;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpgradeProperies implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $client;
    protected $chunk;
    protected $pSync;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($client_id, $chunk)
    {
        $this->client = Client::find($client_id);
        $this->chunk = $chunk;
        $this->pSync = new PropertySync();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        config(['database.connections.tenant.schema' => $this->client->schema]);

        $properties = Property::findMany($this->chunk);

        foreach ($properties as $property)
        {
            $id = $this->pSync->getPropertyId($property->full_address . ' ' .
                $this->client->settings['city'] . ', ' .
                $this->client->settings['state']);

            dd($id);
        }

    }
}
