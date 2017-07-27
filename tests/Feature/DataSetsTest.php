<?php

namespace Tests\Feature;

use App\Client;
use App\DataStore\Model\DataSet;
use App\User;
use App\UserGroup;
use Illuminate\Database\Concerns\ManagesTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DataSetsTest extends TestCase
{
    use DatabaseTransactions;

    protected  $connectionsToTransact = [
        'public',
        'tenant'
    ];

    /**
     * View datasets
     *
     * @group datasets
     *
     * @return void
     */
    public function testViewDataset()
    {
        $this->client->loginAsClient();
        $user = factory(User::class)->create();
        $user->addMembership($this->client->domain);
        $group = UserGroup::create(['name' => 'testGroup', 'permissions' => ['datasets' => ['view' => true]]]);
        DB::table('user_user_group')->insert(['user_id' => $user->id, 'user_group_id' => $group->id]);
        $this->be($user);

        $this->get('/dataset')->assertSee('CityNexus | All Data Sets')->assertDontSee('Create New Data Set');
    }

    /**
     * Authorized User Can See Create Data Set
     *
     * @group datasets
     *
     * @return void
     */
    public function testUserCanSeeCreateDataset()
    {
        $this->client->loginAsClient();
        $user = factory(User::class)->create();
        $user->addMembership($this->client->domain);
        $group = UserGroup::create(['name' => 'testGroup', 'permissions' => ['datasets' => ['view' => true, 'create' => 'true']]]);
        DB::table('user_user_group')->insert(['user_id' => $user->id, 'user_group_id' => $group->id]);
        $this->be($user);

        $this->get('/dataset')->assertSee('CityNexus | All Data Sets')->assertSee('Create New Data Set');
    }

    /**
     * Authorized User Can Access Create New Dataset
     *
     * @group datasets
     *
     * @return void
     */
    public function testUserCanCreateDataset()
    {
        $this->client->loginAsClient();
        $user = factory(User::class)->create();
        $user->addMembership($this->client->domain);
        $group = UserGroup::create(['name' => 'testGroup', 'permissions' => ['datasets' => ['view' => true, 'create' => 'true']]]);
        DB::table('user_user_group')->insert(['user_id' => $user->id, 'user_group_id' => $group->id]);
        $this->be($user);

        $this->get('/dataset/create')->assertSee('CityNexus | Create New Data Set');
    }

    /**
     * Authorized User Can Access Create New Dataset
     *
     * @group datasets
     *
     * @return void
     */
    public function testUserCanSeeDatasetOverview()
    {
        $this->client->loginAsClient();
        $user = factory(User::class)->create();
        $user->addMembership($this->client->domain);
        $group = UserGroup::create(['name' => 'testGroup', 'permissions' => ['datasets' => ['view' => true, 'create' => 'true']]]);
        DB::table('user_user_group')->insert(['user_id' => $user->id, 'user_group_id' => $group->id]);
        $this->be($user);

        $dataset = factory(DataSet::class)->create(['name' => 'Test Data Set', 'type' => 'updating']);

        $this->get('/dataset/' . $dataset->id)->assertSee('CityNexus | Test Data Set Overview');
    }

}
