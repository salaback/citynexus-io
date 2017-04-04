<?php

namespace Tests\Feature;

use CityNexus\DataStore\Helper\Uploader;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UploaderTest extends TestCase
{

    use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testMapData()
    {
        $uploader = new Uploader();
        $map = [
            'one' => 'ten',
            'two' => 'twenty',
            'three' => 'thirty'
        ];

        $data[] = [
            'one' => 10,
            'two' => 20,
            'three' => 30
        ];

        $expected[] = [
            'ten' => 10,
            'twenty' => 20,
            'thirty' => 30
        ];

        $result = $uploader->mapData($data, $map);

        $this->assertSame($expected, $result);
    }


    public function testMapDataWithExtraData()
    {
        $uploader = new Uploader();
        $map = [
            'one' => 'ten',
            'two' => 'twenty',
            'three' => 'thirty'
        ];

        $data[] = [
            'one' => 10,
            'two' => 20,
            'three' => 30,
            'forty' => 40
        ];

        $expected[] = [
            'ten' => 10,
            'twenty' => 20,
            'thirty' => 30
        ];

        $result = $uploader->mapData($data, $map);

        $this->assertSame($expected, $result);
    }

    public function testMapDataWithMissingData()
    {
        $uploader = new Uploader();
        $map = [
            'one' => 'ten',
            'two' => 'twenty',
            'three' => 'thirty'
        ];

        $data[] = [
            'one' => 10,
            'two' => 20,
            'forty' => 40
        ];

        $expected[] = [
            'ten' => 10,
            'twenty' => 20,
        ];

        $result = $uploader->mapData($data, $map);

        $this->assertSame($expected, $result);
    }

    public function testCheckTable()
    {
        $uploader = new Uploader();

        Schema::create('check_table_test', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();

        });

        $table_name = 'check_table_test';

        $schema = [
            'name' => [
                'show' => 'on',
                'name' => 'Name',
                'key' => 'name',
                'type' => 'string'
            ],
            'email' => [
                'show' => 'on',
                'name' => 'Name',
                'key' => 'name',
                'type' => 'string'
            ],
        ];

        $this->assertTrue($uploader->checkTable($table_name, $schema));
    }

    public function testCheckTableMissingColumn()
    {
        $uploader = new Uploader();

        Schema::create('check_table_test_missing', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();

        });

        $table_name = 'check_table_test';

        $schema = [
            'name' => [
                'show' => 'on',
                'name' => 'Name',
                'key' => 'name',
                'type' => 'string'
            ],
            'email' => [
                'show' => 'on',
                'name' => 'Name',
                'key' => 'name',
                'type' => 'string'
            ],
            'missing' => [
                'show' => 'on',
                'name' => 'Name',
                'key' => 'name',
                'type' => 'string'
            ],
        ];

        $this->assertFalse($uploader->checkTable($table_name, $schema));
    }

    public function testCheckTableMissingTable()
    {
        $uploader = new Uploader();

        $table_name = 'check_table_test';

        $schema = [
            'name' => [
                'show' => 'on',
                'name' => 'Name',
                'key' => 'name',
                'type' => 'string'
            ],
            'email' => [
                'show' => 'on',
                'name' => 'Name',
                'key' => 'name',
                'type' => 'string'
            ],
        ];

        $this->assertFalse($uploader->checkTable($table_name, $schema));
    }
}
