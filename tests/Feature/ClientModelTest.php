<?php

namespace Tests\Feature;

use App\Client;
use App\Services\MultiTenant;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ClientModelTest extends TestCase
{
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


    /**
     * Add a member to the model's client org
     *
     */
    public function testAddMember()
    {
        $this->client->logInAsClient();

        $user = User::create([
            'first_name' => 'Tester',
            'last_name' => 'McTester-Butt',
            'email' => str_random(10) . '_test@test.com',
            'password' => str_random(10),
        ]);

        $options = [
            'title' => 'Title',
            'department' => 'Department'
        ];
        $this->client->addUser($user, $options);

        $this->assertTrue(isset($user->memberships[$this->client->domain]));
        $this->assertSame($user->info->title, 'Title');
        $this->assertSame($user->info->department, 'Department');
    }

}
