<?php

namespace Tests\Feature;

use App\Client;
use App\DataStore\DataInformation;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DataInformationTest extends TestCase
{

    use DatabaseTransactions;
    protected $dInfo;

    protected  $connectionsToTransact = [
        'public',
        'tenant'
    ];

    public function setUp()
    {
        parent::setUp();
        $this->dInfo = new DataInformation();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetDataRange()
    {
        $data = [
            (object) [
                '__id' => 1,
                '__created_at' => '2016-04-19 18:12:12',
                'data' => 'blarg',
            ],
            (object) [
                '__id' => 1,
                '__created_at' => '2016-02-19 18:12:12',
                'data' => 'blarg',
            ],
            (object) [
                '__id' => 1,
                '__created_at' => '2016-04-20 18:12:12',
                'data' => 'blarg',
            ],
            (object) [
                '__id' => 1,
                '__created_at' => '2016-04-19 19:12:12',
                'data' => 'blarg',
            ],
            (object) [
                '__id' => 1,
                '__created_at' => '2016-04-19 14:12:12',
                'data' => 'blarg',
            ]
        ];

        $results = $this->invokeMethod($this->dInfo, 'getDateRange', ['data' => $data]);


        $expected = [
            'start' => "2016-02-19 18:12:12",
            'end' => "2016-04-20 18:12:12"
        ];

        $this->assertSame($results, $expected);
    }

    public function testGetFrequency()
    {
        $data = [1, 2, 3, 2, 3, 3,];

        $results = $this->invokeMethod($this->dInfo, 'getFrequency', ['data' => $data, 'key' => 'data']);
        $expected = [
            1 => 1,
            2 => 2,
            3 => 3
        ];

        $this->assertSame($results, $expected);
    }
}
