<?php

namespace Tests\Feature;

use App\Client;
use App\DataStore\Model\DataSet;
use App\DataStore\TableBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TableBuilderTest extends TestCase
{
    use DatabaseTransactions;

    protected $client;
    protected $tableBuilder;
    protected  $connectionsToTransact = [
        'public',
        'tenant'
    ];

    public function setUp()
    {
        parent::setUp();
        $this->client = Client::where('domain', 'testclient.citynexus-io.app:8000')->first();
        $this->tableBuilder = new TableBuilder();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateTable()
    {
        $this->client->logInAsClient();
        $dataset = factory(DataSet::class)->create();

        $this->assertTrue(DB::statement('SELECT * FROM ' . $dataset->table_name));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAddNewToTable()
    {
        $this->client->logInAsClient();
        $dataset = factory(DataSet::class)->create();

        $this->tableBuilder->createTable($dataset);

        $fields = [
          'test' => [
              'type' => 'string',
              'key' => 'test'
          ]
        ];

        $this->tableBuilder->addToTable($dataset, $fields);

        $this->assertTrue(DB::statement('SELECT test FROM ' . $dataset->table_name));
    }

    /**
     * A basic test example.
     *
     * @expectedException \Exception
     * @return void
     */
    public function testAddExistingToTable()
    {
        $this->client->logInAsClient();
        $dataset = DataSet::create([
            'name' => 'Test Data Set',
            'type' => 'updating'
        ]);

        $this->tableBuilder->createTable($dataset);

        $fields = [
            'test' => [
                'type' => 'string',
                'key' => 'test'
            ]
        ];

        $this->tableBuilder->addToTable($dataset, $fields);

        $this->tableBuilder->addToTable($dataset, $fields);

        $this->expectExceptionCode(500);

    }

}
