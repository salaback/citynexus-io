<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 3/17/17
 * Time: 4:01 PM
 */

namespace App\Services;


use App\Client;
use App\Jobs\UpgradeProperies;
use CityNexus\CityNexus\Property;
use Illuminate\Foundation\Bus\DispatchesJobs;

class Upgrade
{

    use DispatchesJobs;
    private $client;

    public function client($client)
    {
        $this->client = $client;

        config(['database.connections.tenant.schema' => $this->client->schema]);

        if($this->client->version_id === null)
        {
            $version = 'version_0';
        }
        else
        {
            $version = 'version_' . ($this->client->version + 1);
        }

        $this->$version();
    }

    private function version_0()
    {
        // Updates

        // Migrate properties table
        $properties = Property::pluck('id')->chunk(100);

        foreach($properties as $chunk)
        {
            $this->dispatch(new UpgradeProperies($this->client->id, $chunk));
        }

//        TODO: Add job which processes each property and changes the id of related fields.


        // Save client version
        $this->client->version = 0;
        $this->client->save();
    }

}