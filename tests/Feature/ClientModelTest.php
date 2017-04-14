<?php

namespace Tests\Feature;

use App\Client;
use App\Services\MultiTenant;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ClientModelTest extends TestCase
{
    use DatabaseTransactions;

    private $client;

    public function setUp()
    {
        parent::setUp();
        $multiTenant = new MultiTenant();
        $this->client = $multiTenant->createClient('Test Client', 'testclient');
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->client->delete();
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

}
