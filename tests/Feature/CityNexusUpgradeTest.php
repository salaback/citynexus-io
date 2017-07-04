<?php

namespace Tests\Feature;

use App\Client;
use App\Services\CityNexusUpgrade;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CityNexusUpgradeTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->upgrade = new CityNexusUpgrade();

        $credentials = [
            'driver'   => 'pgsql',
            'host'     => 'lawrence-mass-cn.ck0vyspcejra.us-west-2.rds.amazonaws.com',
            'database' => 'lawrence_ma_cn',
            'username' => 'lawrence_ma_cn',
            'password' => '5!{!V!vQtrL5>8mP(!5Vw{d:',
            'charset'  => 'utf8',
            'prefix'   => '',
            'schema'   => 'public',
        ];

        config(['database.connections.target' => $credentials]);
    }


    /**
     * A basic test example.
     * @group upgrade
     * @return void
     */
    public function testRun()
    {
        $this->upgrade->run(347);
    }
}
