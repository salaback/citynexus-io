<?php

namespace Tests\Feature;

use App\Client;
use App\User;
use App\UserGroup;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ScoreTest extends TestCase
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
    public function testCreateNewScore()
    {
        $this->client->logInAsClient();
        $user = factory(User::class)->create();
        $user->addMembership($this->client->domain);
        $group = UserGroup::create(['name' => 'testGroup', 'permissions' => ['analytics' => ['score-create' => true]]]);
        DB::table('user_user_group')->insert(['user_id' => $user->id, 'user_group_id' => $group->id]);
        $this->be($user);

        $this->get('analytics/score/create')->assertSee('CityNexus | Create New Score');
    }
}
