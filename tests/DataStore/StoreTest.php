<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ScoreTest extends TestCase
{

    public function __construct()
    {
        $this->store = new \CityNexus\DataStore\Store();

    }
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testAnalyizeExcelFile()
    {
        $result = $this->store->analyizeFile(__DIR__ . '/exceltest.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', true);

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
        $result = $this->store->analyizeFile(__DIR__ . '/exceltest.csv', 'text/csv', true);
        $this->assertSame('integer', $result['id']['type']);
        $this->assertSame('string', $result['string']['type']);
        $this->assertSame('boolean', $result['boolean']['type']);
        $this->assertSame('text', $result['text']['type']);
        $this->assertSame('float', $result['float']['type']);
        $this->assertSame('integer', $result['int']['type']);
        $this->assertSame(count($result), 6);
    }

}

