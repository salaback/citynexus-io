<?php

namespace Tests\Feature;

use App\Client;
use App\Services\MultiTenant;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
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
    public function testAddMembershipsNewMembership()
    {
        $user = User::create([
            'first_name' => 'Firstname',
            'last_name' => 'Lastname',
            'email' => 'first.last@email.com',
            'password' => 'hashed'
        ]);

        $memberships = [
            "demo.citynexus.io" => ['something']
        ];

        $user->addMemberships($memberships);

        $this->assertTrue(isset($user->memberships['demo.citynexus.io']));
    }
}
