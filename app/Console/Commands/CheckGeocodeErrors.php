<?php

namespace App\Console\Commands;

use App\Client;
use App\Jobs\Geocode;
use App\PropertyMgr\Model\GeoCodingError;
use App\PropertyMgr\Model\Property;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckGeocodeErrors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'citynexus:geocode-errors {client_id?} {properties?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rerun Geocoding Errors';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        if($this->argument('client_id') == null)
        {
            $clients = Client::all();

            foreach($clients as $client)
            {
                $client->logInAsClient();

                if($this->argument('properties') == true)
                {
                    $property_ids = Property::whereNull('location')->pluck('id');

                    dd($property_ids);

                    foreach($property_ids as $property_id)
                    {
                        dispatch(new Geocode($property_id));
                    }
                }
                else
                {
                    $errors = GeoCodingError::all();

                    foreach($errors as $error)
                    {
                        switch ($error->model_type)
                        {
                            case '\\App\\PropertyMgr\\Model\\Property':
                                dispatch(new Geocode($error->model_id));
                                break;
                        }

                        $error->delete();
                    }
                }

            }
        }

        else
        {

            Client::find($this->argument('client_id'))->logInAsClient();

            if($this->argument('properties') == true)
            {
                $property_ids = Property::whereNull('location')->pluck('id');

                foreach($property_ids as $property_id)
                {
                    dispatch(new Geocode($property_id));
                }
            }
            else
            {
                $errors = GeoCodingError::all();

                foreach($errors as $error)
                {
                    switch ($error->model_type)
                    {
                        case '\\App\\PropertyMgr\\Model\\Property':
                            dispatch(new Geocode($error->model_id));
                            break;
                    }

                    $error->delete();
                }
            }
        }

    }

}
