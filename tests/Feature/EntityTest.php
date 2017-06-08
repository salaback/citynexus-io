<?php

namespace Tests\Feature;

use App\Client;
use App\DataStore\Model\DataSet;
use App\DataStore\TableBuilder;
use App\PropertyMgr\Model\Address;
use App\PropertyMgr\Model\Entity;
use App\PropertyMgr\Model\Property;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EntityTest extends TestCase
{

    use DatabaseTransactions;

    protected $client;

    protected  $connectionsToTransact = [
        'public',
        'tenant'
    ];

    public function setUp()
    {
        parent::setUp();
        $this->client = Client::where('domain', 'testclient.citynexus-io.app:8000')->first();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testDatasetsAttribute()
    {
        $this->client->logInAsClient();

        // Create property attach an entity
        $property = factory(Property::class)->create();
        $entity = factory(Entity::class)->create();
        $entity->properties()->attach($property->id, ['upload_id' => 1, 'role' => 'owner']);

        // create a dataset
        $dataSet = factory(DataSet::class)->create();
        $tableBuilder = new TableBuilder();
        $tableBuilder->createTable($dataSet);

        // insert record to dataset belonging to property
        DB::table($dataSet->table_name)->insert(['upload_id' => 1, 'property_id' => $property->id]);

        $result = $entity->datasets;

        $this->assertTrue(isset($result[$dataSet->id]), "Data Set array key is set");
        $this->assertTrue(count($result[$dataSet->id]) == 1, 'Only one item in array');
        $this->assertTrue(count($result) == 1, 'Only one dataset provided');

    }
}
