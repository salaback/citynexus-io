<?php

namespace Tests\Feature;

use App\DataStore\Store;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StoreTest extends TestCase
{

    protected $client;
    protected $store;

    use DatabaseTransactions;

    protected  $connectionsToTransact = [
        'public',
        'tenant'
    ];

    public function setUp()
    {
        parent::setUp();
        $this->client->logInAsClient();
        $this->store = new Store();
    }


    public function testAnalyzeExcelFile()
    {
        $result = $this->store->analyzeFile('/test_data/exceltest.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $this->assertSame('integer', $result['id']['type']);
        $this->assertSame('string', $result['string']['type']);
        $this->assertSame('boolean', $result['boolean']['type']);
        $this->assertSame('text', $result['text']['type']);
        $this->assertSame('float', $result['float']['type']);
        $this->assertSame('integer', $result['int']['type']);
        $this->assertSame(count($result), 6);

    }

    public function testCSVFile()
    {
        $result = $this->store->analyzeFile('test_data/exceltest.csv', 'text/csv');
        $this->assertSame('integer', $result['id']['type']);
        $this->assertSame('string', $result['string']['type']);
        $this->assertSame('boolean', $result['boolean']['type']);
        $this->assertSame('text', $result['text']['type']);
        $this->assertSame('float', $result['float']['type']);
        $this->assertSame('integer', $result['int']['type']);
        $this->assertSame(count($result), 6);
    }
}
