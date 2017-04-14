<?php

namespace Tests\Feature;

use App\Client;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ClientModelTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLogInAsClient()
    {
        $client = factory(Client::class)->create();

        $client->logInAsClient();

        $this->assertSame(config('database.connections.tenant.schema'), $client->schema);
        $this->assertTrue(config('client.test'));
    }
}
