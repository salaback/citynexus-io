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
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
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
                'id' => 1,
                'created_at' => '2016-04-19 18:12:12',
                'data' => 'blarg',
            ],
            (object) [
                'id' => 1,
                'created_at' => '2016-02-19 18:12:12',
                'data' => 'blarg',
            ],
            (object) [
                'id' => 1,
                'created_at' => '2016-04-20 18:12:12',
                'data' => 'blarg',
            ],
            (object) [
                'id' => 1,
                'created_at' => '2016-04-19 19:12:12',
                'data' => 'blarg',
            ],
            (object) [
                'id' => 1,
                'created_at' => '2016-04-19 14:12:12',
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
