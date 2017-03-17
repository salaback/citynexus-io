<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 3/17/17
 * Time: 4:01 PM
 */

namespace App\Services;


use App\Client;

class Upgrade
{

    private $client;

    public function client($id)
    {
        $this->client = Client::find($id);

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


        // Save client version
        $this->client->version = 0;
        $this->client->save();
    }

}