<?php

namespace Tests\Feature;

use App\Client;
use App\DataStore\Model\DataSet;
use App\DataStore\UploadHelper;
use App\User;
use App\UserGroup;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UploaderTest extends TestCase
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
    public function testMapData()
    {
        $uploader = new UploadHelper();
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
        $uploader = new UploadHelper();
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
        $uploader = new UploadHelper();
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
        $uploader = new UploadHelper();

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
        $uploader = new UploadHelper();

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
        $uploader = new UploadHelper();

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

    public function testViewCreateCSVUploader()
    {

        $this->client->loginAsClient();
        $user = factory(User::class)->create();
        $user->addMembership($this->client->domain);
        $group = UserGroup::create(['name' => 'testGroup', 'permissions' => ['datasets' => ['create-uploader' => true]]]);
        DB::table('user_user_group')->insert(['user_id' => $user->id, 'user_group_id' => $group->id]);
        $this->be($user);

        $dataset = factory(DataSet::class)->create(['owner_id' => $user->id]);

        $this->actingAs($user)->get('/uploader/create?dataset_id=' . $dataset->id . '&type=csv')->assertSee('CityNexus | Create CSV/Excel Uploader');
    }




}
