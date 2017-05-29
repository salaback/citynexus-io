<?php

namespace Tests\Feature;

use App\Client;
use App\Services\MultiTenant;
use App\User;
use App\UserGroup;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrganizationManagementTest extends TestCase
{
    protected $client;
    protected $user;
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
     * Test access to main organization page
     *
     * @group organization
     *
     * @return void
     */
    public function testOrganizationSettingsIndex()
    {
        $this->client->loginAsClient();
        $user = factory(User::class)->create();
        $user->addMembership($this->client->domain);
        $group = UserGroup::create(['name' => 'testGroup', 'permissions' => ['org-admin' => ['view' => true]]]);
        DB::table('user_user_group')->insert(['user_id' => $user->id, 'user_group_id' => $group->id]);
        $this->be($user);

        $this->get('/organization')->assertSee('CityNexus | Organization Settings');
    }

    /**
     * Test access to user admin
     *
     * @group organization
     *
     * @return void
     */
    public function testManageUser()
    {
        $this->client->loginAsClient();
        $user = factory(User::class)->create();
        $user->addMembership($this->client->domain);
        $group = UserGroup::create(['name' => 'testGroup', 'permissions' => ['org-admin' => ['edit-users' => true]]]);
        DB::table('user_user_group')->insert(['user_id' => $user->id, 'user_group_id' => $group->id]);
        $this->be($user);

        $this->get('/organization/users/' . $user->id . '/edit')->assertSee('CityNexus | ' . $user->fullname . ' Settings');
    }

}
