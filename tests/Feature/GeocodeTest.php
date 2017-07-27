<?php

namespace Tests\Feature;

use App\Jobs\Geocode;
use App\PropertyMgr\Model\Property;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GeocodeTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->client->logInAsClient();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGeocodeErrorPickup()
    {
        DB::table('geo_coding_errors')->truncate();

        DB::table('geo_coding_errors')->insert([
            'model_type' => '\App\PropertyMgr\Model\Property',
            'model_id' => 1
        ]);

        $this->expectsJobs(Geocode::class);

        Artisan::call('citynexus:geocode-errors', ['client_id' => $this->client->id]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGeocodeErrorPickupWithoutErrors()
    {
        DB::table('geo_coding_errors')->truncate();

        $this->doesntExpectJobs(Geocode::class);

        Artisan::call('citynexus:geocode-errors', ['client_id' => $this->client->id]);
    }

    public function testGeocodeOfProperties()
    {
        DB::table('cn_properties')->truncate();

        factory(Property::class)->create();

        $this->expectsJobs(Geocode::class);

        Artisan::call('citynexus:geocode-errors', ['client_id' => $this->client->id, 'properties' => true]);

    }
}
