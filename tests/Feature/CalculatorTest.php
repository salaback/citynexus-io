<?php

namespace Tests\Feature;

use App\Services\MultiTenant;
use CityNexus\AnalysisMgr\Calculator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CalculatorTest extends TestCase
{

    private $client;
    private $calculator;

    public function setUp()
    {
        parent::setUp();
        $this->calculator = new Calculator();
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

    public function testCreateNewColumnForATvalue()
    {

        $this->client->logInAsClient();

        $name = 'test_t_value';
        $type = 'tvalue';

        $this->calculator->addNewValue($name, $type);

        $this->assertTrue(Schema::hasColumn('cn_values', $name));
    }

    public function testTvalue()
    {

    }
}
