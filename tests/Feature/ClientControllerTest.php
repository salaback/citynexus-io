<?php

namespace Tests\Feature;

use App\Client;
use App\Events\UserCreated;
use App\Http\Controllers\Admin\ClientController;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ClientControllerTest extends TestCase
{

    use DatabaseTransactions;

    protected  $connectionsToTransact = [
        'public',
        'tenant'
    ];

    protected $controller;

    public function __construct()
    {
        $this->controller = new ClientController();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateOwnerUser()
    {
        Event::fake();

        $client = Client::where('domain', 'testclient.citynexus-io.app:8000')->first();

        $user = [
            'first_name' => 'FirstName',
            'last_name' => 'LastName',
            'email' => 'Email@email.com'
        ];

        $user = $this->controller->createOwnerUser($client, $user);

        $this->assertTrue(isset($user->memberships['testclient.citynexus-io.app:8000']));
        Event::assertDispatched(UserCreated::class);
    }
}
