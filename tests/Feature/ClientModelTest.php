<?php

namespace Tests\Feature;

use App\Client;
use App\Services\MultiTenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ClientModelTest extends TestCase
{
    use DatabaseTransactions;

    private $client;
    private $multiTenant;

    use DatabaseTransactions;

    protected  $connectionsToTransact = [
        'public',
        'tenant'
    ];

    public function setUp()
    {
        parent::setUp();
        $this->multiTenant = new MultiTenant();
        $this->client = Client::where('domain', 'testclient.citynexus-io.app:8000')->first();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLogInAsClient()
    {
        $this->client->settings = ['test' => true];
        $this->client->logInAsClient();
        $this->assertSame(config('database.connections.tenant.schema'), $this->client->schema);
        $this->assertTrue(config('client.test'));
    }

    public function testSchemaMigration()
    {
        $this->assertSame(DB::table('information_schema.schemata')->where('schema_name', $this->client->schema)->count(), 1);
    }

}
