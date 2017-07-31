<?php

namespace Tests\Feature;

use App\AnalysisMgr\Jobs\ProcessScore;
use App\AnalysisMgr\MapHelper;
use App\AnalysisMgr\Model\Score;
use App\Client;
use App\DataStore\Model\DataSet;
use App\DataStore\TableBuilder;
use App\PropertyMgr\Model\Property;
use App\PropertyMgr\Model\Tag;
use App\User;
use App\UserGroup;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;


class ScoreTest extends TestCase
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
        $this->tableBuilder = new TableBuilder();

    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateNewScore()
    {
        $this->client->logInAsClient();
        $user = factory(User::class)->create();
        $user->addMembership($this->client->domain);
        $group = UserGroup::create(['name' => 'testGroup', 'permissions' => ['analytics' => ['score-create' => true]]]);
        DB::table('user_user_group')->insert(['user_id' => $user->id, 'user_group_id' => $group->id]);
        $this->be($user);

        $this->get('analytics/score/create')->assertSee('CityNexus | Create New Score');
    }

    public function testSaveAndUpdateDispachesProcessScore()
    {
        $this->expectsJobs(ProcessScore::class);

        $score = Score::create([
            'elements' => [],
            'name' => 'Test Score',
            'type' => 'building',
            'period' => null,
            'timeseries' => null,
            'owned_by' => 1
        ]);

    }

    public function testDatapointElementAddValue()
    {
        $schema = json_decode('{"test_key":{"show":"on","name":"Test Key","key":"test_key","type":"integer"}}');

        $this->client->logInAsClient();
        $dataset = factory(DataSet::class)->create([
            'schema' => $schema
        ]);

        DB::table($dataset->table_name)->insert([
            ['test_key' => 1, '__property_id' => 1, '__building_id' => 1, '__created_at' => Carbon::now()->subDays(10), '__upload_id' => 1],
            ['test_key' => 2, '__property_id' => 2, '__building_id' => 2, '__created_at' => Carbon::now()->subDays(20), '__upload_id' => 1],
            ['test_key' => 3, '__property_id' => 3, '__building_id' => 3, '__created_at' => Carbon::now()->subDays(50), '__upload_id' => 1],
            ['test_key' => 4, '__property_id' => 4, '__building_id' => 4, '__created_at' => Carbon::now()->subDays(300), '__upload_id' => 1],
            ['test_key' => 4, '__property_id' => 4, '__building_id' => 4, '__created_at' => Carbon::now()->subDays(600), '__upload_id' => 1],
            ['test_key' => 4, '__property_id' => 5, '__building_id' => 5, '__created_at' => Carbon::now()->subDays(600), '__upload_id' => 1]

        ]);

        $elements = json_decode('[{"type":"datapoint","dataset_id":' . $dataset->id . ',"key":"test_key","properties":{"units":"total","property":"true","propertyRange":"false"},"trailing":365,"effect":{"type":"value","effect":"add","range":{"greaterThan":null,"lessThan":"false","equalTo":"false","add":null}}}]', true);

        $score = Score::create([
            'elements' => $elements,
            'name' => 'Test Score',
            'type' => 'building',
            'period' => null,
            'timeseries' => null,
            'owned_by' => 1
        ]);

        $this->assertDatabaseHas('cn_score_' . $score->id, ['property_id' => 1, 'score' => 1]);
        $this->assertDatabaseHas('cn_score_' . $score->id, ['property_id' => 4, 'score' => 4]);
        $this->assertDatabaseMissing('cn_score_' . $score->id, ['property_id' => 5]);

        Schema::dropIfExists($dataset->table_name);
        Schema::dropIfExists('cn_score_' . $score->id);

    }



    public function testDatapointUnitsAddValue()
    {
        $schema = json_decode('{"test_key":{"show":"on","name":"Test Key","key":"test_key","type":"integer"}}');

        $this->client->logInAsClient();
        $dataset = factory(DataSet::class)->create([
            'schema' => $schema
        ]);

        DB::table($dataset->table_name)->insert([
            ['test_key' => 1, '__property_id' => 1, '__building_id' => 1, '__created_at' => Carbon::now()->subDays(10), '__upload_id' => 1],
            ['test_key' => 2, '__property_id' => 2, '__building_id' => 1, '__created_at' => Carbon::now()->subDays(20), '__upload_id' => 1],
            ['test_key' => 3, '__property_id' => 3, '__building_id' => 1, '__created_at' => Carbon::now()->subDays(50), '__upload_id' => 1],
            ['test_key' => 4, '__property_id' => 4, '__building_id' => 1, '__created_at' => Carbon::now()->subDays(300), '__upload_id' => 1],
            ['test_key' => 4, '__property_id' => 4, '__building_id' => 1, '__created_at' => Carbon::now()->subDays(600), '__upload_id' => 1],
            ['test_key' => 5, '__property_id' => 5, '__building_id' => 5, '__created_at' => Carbon::now()->subDays(300), '__upload_id' => 1]

        ]);

        $elements = json_decode('[{"type":"datapoint","dataset_id":' . $dataset->id . ',"key":"test_key","properties":{"units":"total","property":"true","propertyRange":"false"},"trailing":365,"effect":{"type":"value","effect":"add","range":{"greaterThan":null,"lessThan":"false","equalTo":"false","add":null}}}]', true);

        $score = Score::create([
            'elements' => $elements,
            'name' => 'Test Score',
            'type' => 'building',
            'period' => null,
            'timeseries' => null,
            'owned_by' => 1
        ]);

        $this->assertDatabaseHas('cn_score_' . $score->id, ['property_id' => 1, 'score' => 10]);
        $this->assertDatabaseHas('cn_score_' . $score->id, ['property_id' => 5, 'score' => 5]);

        Schema::dropIfExists($dataset->table_name);
        Schema::dropIfExists('cn_score_' . $score->id);

    }


    public function testDatapointUnitsAddValueMostRecent()
    {
        $schema = json_decode('{"test_key":{"show":"on","name":"Test Key","key":"test_key","type":"integer"}}');

        $this->client->logInAsClient();
        $dataset = factory(DataSet::class)->create([
            'schema' => $schema
        ]);

        DB::table($dataset->table_name)->insert([
            ['test_key' => 1, '__property_id' => 1, '__building_id' => 1, '__created_at' => Carbon::now()->subDays(10), '__upload_id' => 1],
            ['test_key' => 2, '__property_id' => 2, '__building_id' => 1, '__created_at' => Carbon::now()->subDays(20), '__upload_id' => 1],
            ['test_key' => 3, '__property_id' => 3, '__building_id' => 1, '__created_at' => Carbon::now()->subDays(50), '__upload_id' => 1],
            ['test_key' => 4, '__property_id' => 4, '__building_id' => 1, '__created_at' => Carbon::now()->subDays(300), '__upload_id' => 1],
            ['test_key' => 5, '__property_id' => 5, '__building_id' => 5, '__created_at' => Carbon::now()->subDays(600), '__upload_id' => 1],
            ['test_key' => 6, '__property_id' => 6, '__building_id' => 6, '__created_at' => Carbon::now()->subDays(300), '__upload_id' => 1]

        ]);

        $elements = json_decode('[{"type":"datapoint","dataset_id":' . $dataset->id . ',"recent":"recent","key":"test_key","properties":{"units":"total","property":"true","propertyRange":"false"},"trailing":365,"effect":{"type":"value","effect":"add","range":{"greaterThan":null,"lessThan":"false","equalTo":"false","add":null}}}]', true);

        $score = Score::create([
            'elements' => $elements,
            'name' => 'Test Score',
            'type' => 'building',
            'period' => null,
            'timeseries' => null,
            'owned_by' => 1
        ]);

        $this->assertDatabaseHas('cn_score_' . $score->id, ['property_id' => 1, 'score' => 1]);
        $this->assertDatabaseHas('cn_score_' . $score->id, ['property_id' => 6, 'score' => 6]);
        $this->assertDatabaseMissing('cn_score_' . $score->id, ['property_id' => 5]);

        Schema::dropIfExists($dataset->table_name);
        Schema::dropIfExists('cn_score_' . $score->id);

    }

    public function testDatapointElementSubtractValue()
    {
        $schema = json_decode('{"test_key":{"show":"on","name":"Test Key","key":"test_key","type":"integer"}}');

        $this->client->logInAsClient();
        $dataset = factory(DataSet::class)->create([
            'schema' => $schema
        ]);

        DB::table($dataset->table_name)->insert([
            ['test_key' => 1, '__property_id' => 1, '__building_id' => 1, '__created_at' => Carbon::now()->subDays(10), '__upload_id' => 1],
            ['test_key' => 2, '__property_id' => 2, '__building_id' => 2, '__created_at' => Carbon::now()->subDays(20), '__upload_id' => 1],
            ['test_key' => 3, '__property_id' => 3, '__building_id' => 3, '__created_at' => Carbon::now()->subDays(50), '__upload_id' => 1],
            ['test_key' => 4, '__property_id' => 4, '__building_id' => 4, '__created_at' => Carbon::now()->subDays(300), '__upload_id' => 1],
            ['test_key' => 4, '__property_id' => 4, '__building_id' => 4, '__created_at' => Carbon::now()->subDays(600), '__upload_id' => 1],
            ['test_key' => 4, '__property_id' => 5, '__building_id' => 5, '__created_at' => Carbon::now()->subDays(600), '__upload_id' => 1]

        ]);

        $elements = json_decode('[{"type":"datapoint","dataset_id":' . $dataset->id . ',"key":"test_key","properties":{"units":"total","property":"true","propertyRange":"false"},"trailing":365,"effect":{"type":"value","effect":"subtract","range":{"greaterThan":null,"lessThan":"false","equalTo":"false","add":null}}}]', true);

        $score = Score::create([
            'elements' => $elements,
            'name' => 'Test Score',
            'type' => 'building',
            'period' => null,
            'timeseries' => null,
            'owned_by' => 1
        ]);

        $this->assertDatabaseHas('cn_score_' . $score->id, ['property_id' => 1, 'score' => -1]);
        $this->assertDatabaseHas('cn_score_' . $score->id, ['property_id' => 4, 'score' => -4]);
        $this->assertDatabaseMissing('cn_score_' . $score->id, ['property_id' => 5]);

        Schema::dropIfExists($dataset->table_name);
        Schema::dropIfExists('cn_score_' . $score->id);

    }

    public function testDatapointElementSquareValue()
    {
        $schema = json_decode('{"test_key":{"show":"on","name":"Test Key","key":"test_key","type":"integer"}}');

        $this->client->logInAsClient();
        $dataset = factory(DataSet::class)->create([
            'schema' => $schema
        ]);

        DB::table($dataset->table_name)->insert([
            ['test_key' => 1, '__property_id' => 1, '__building_id' => 1, '__created_at' => Carbon::now()->subDays(10), '__upload_id' => 1],
            ['test_key' => 2, '__property_id' => 2, '__building_id' => 2, '__created_at' => Carbon::now()->subDays(20), '__upload_id' => 1],
            ['test_key' => 3, '__property_id' => 3, '__building_id' => 3, '__created_at' => Carbon::now()->subDays(50), '__upload_id' => 1],
            ['test_key' => 4, '__property_id' => 4, '__building_id' => 4, '__created_at' => Carbon::now()->subDays(300), '__upload_id' => 1],
            ['test_key' => 4, '__property_id' => 4, '__building_id' => 4, '__created_at' => Carbon::now()->subDays(600), '__upload_id' => 1],
            ['test_key' => 4, '__property_id' => 5, '__building_id' => 5, '__created_at' => Carbon::now()->subDays(600), '__upload_id' => 1]

        ]);

        $elements = json_decode('[{"type":"datapoint","dataset_id":' . $dataset->id . ',"key":"test_key","properties":{"units":"total","property":"true","propertyRange":"false"},"trailing":365,"effect":{"type":"square","effect":"add","range":{"greaterThan":null,"lessThan":"false","equalTo":"false","add":null}}}]', true);

        $score = Score::create([
            'elements' => $elements,
            'name' => 'Test Score',
            'type' => 'building',
            'period' => null,
            'timeseries' => null,
            'owned_by' => 1
        ]);

        $this->assertDatabaseHas('cn_score_' . $score->id, ['property_id' => 1, 'score' => 1]);
        $this->assertDatabaseHas('cn_score_' . $score->id, ['property_id' => 4, 'score' => 16]);
        $this->assertDatabaseMissing('cn_score_' . $score->id, ['property_id' => 5]);

        Schema::dropIfExists($dataset->table_name);
        Schema::dropIfExists('cn_score_' . $score->id);

    }

    public function testDatapointElementRootValue()
    {
        $schema = json_decode('{"test_key":{"show":"on","name":"Test Key","key":"test_key","type":"integer"}}');

        $this->client->logInAsClient();
        $dataset = factory(DataSet::class)->create([
            'schema' => $schema
        ]);

        DB::table($dataset->table_name)->insert([
            ['test_key' => 1, '__property_id' => 1, '__building_id' => 1, '__created_at' => Carbon::now()->subDays(10), '__upload_id' => 1],
            ['test_key' => 2, '__property_id' => 2, '__building_id' => 2, '__created_at' => Carbon::now()->subDays(20), '__upload_id' => 1],
            ['test_key' => 3, '__property_id' => 3, '__building_id' => 3, '__created_at' => Carbon::now()->subDays(50), '__upload_id' => 1],
            ['test_key' => 16, '__property_id' => 4, '__building_id' => 4, '__created_at' => Carbon::now()->subDays(300), '__upload_id' => 1],
            ['test_key' => 4, '__property_id' => 4, '__building_id' => 4, '__created_at' => Carbon::now()->subDays(600), '__upload_id' => 1],
            ['test_key' => 4, '__property_id' => 5, '__building_id' => 5, '__created_at' => Carbon::now()->subDays(600), '__upload_id' => 1]

        ]);

        $elements = json_decode('[{"type":"datapoint","dataset_id":' . $dataset->id . ',"key":"test_key","properties":{"units":"total","property":"true","propertyRange":"false"},"trailing":365,"effect":{"type":"root","effect":"add","range":{"greaterThan":null,"lessThan":"false","equalTo":"false","add":null}}}]', true);

        $score = Score::create([
            'elements' => $elements,
            'name' => 'Test Score',
            'type' => 'building',
            'period' => null,
            'timeseries' => null,
            'owned_by' => 1
        ]);

        $this->assertDatabaseHas('cn_score_' . $score->id, ['property_id' => 1, 'score' => 1]);
        $this->assertDatabaseHas('cn_score_' . $score->id, ['property_id' => 4, 'score' => 4]);
        $this->assertDatabaseMissing('cn_score_' . $score->id, ['property_id' => 5]);

        Schema::dropIfExists($dataset->table_name);
        Schema::dropIfExists('cn_score_' . $score->id);

    }

    public function testDatapointElementCubeValue()
    {
        $schema = json_decode('{"test_key":{"show":"on","name":"Test Key","key":"test_key","type":"integer"}}');

        $this->client->logInAsClient();
        $dataset = factory(DataSet::class)->create([
            'schema' => $schema
        ]);

        DB::table($dataset->table_name)->insert([
            ['test_key' => 1, '__property_id' => 1, '__building_id' => 1, '__created_at' => Carbon::now()->subDays(10), '__upload_id' => 1],
            ['test_key' => 2, '__property_id' => 2, '__building_id' => 2, '__created_at' => Carbon::now()->subDays(20), '__upload_id' => 1],
            ['test_key' => 3, '__property_id' => 3, '__building_id' => 3, '__created_at' => Carbon::now()->subDays(50), '__upload_id' => 1],
            ['test_key' => 4, '__property_id' => 4, '__building_id' => 4, '__created_at' => Carbon::now()->subDays(300), '__upload_id' => 1],
            ['test_key' => 4, '__property_id' => 4, '__building_id' => 4, '__created_at' => Carbon::now()->subDays(600), '__upload_id' => 1],
            ['test_key' => 4, '__property_id' => 5, '__building_id' => 5, '__created_at' => Carbon::now()->subDays(600), '__upload_id' => 1]

        ]);

        $elements = json_decode('[{"type":"datapoint","dataset_id":' . $dataset->id . ',"key":"test_key","properties":{"units":"total","property":"true","propertyRange":"false"},"trailing":365,"effect":{"type":"cube","effect":"add","range":{"greaterThan":null,"lessThan":"false","equalTo":"false","add":null}}}]', true);

        $score = Score::create([
            'elements' => $elements,
            'name' => 'Test Score',
            'type' => 'building',
            'period' => null,
            'timeseries' => null,
            'owned_by' => 1
        ]);

        $this->assertDatabaseHas('cn_score_' . $score->id, ['property_id' => 1, 'score' => 1]);
        $this->assertDatabaseHas('cn_score_' . $score->id, ['property_id' => 4, 'score' => 64]);
        $this->assertDatabaseMissing('cn_score_' . $score->id, ['property_id' => 5]);

        Schema::dropIfExists($dataset->table_name);
        Schema::dropIfExists('cn_score_' . $score->id);

    }
    public function testDatapointElementCubeRootValue()
    {
        $schema = json_decode('{"test_key":{"show":"on","name":"Test Key","key":"test_key","type":"integer"}}');

        $this->client->logInAsClient();
        $dataset = factory(DataSet::class)->create([
            'schema' => $schema
        ]);

        DB::table($dataset->table_name)->insert([
            ['test_key' => 1, '__property_id' => 1, '__building_id' => 1, '__created_at' => Carbon::now()->subDays(10), '__upload_id' => 1],
            ['test_key' => 2, '__property_id' => 2, '__building_id' => 2, '__created_at' => Carbon::now()->subDays(20), '__upload_id' => 1],
            ['test_key' => 3, '__property_id' => 3, '__building_id' => 3, '__created_at' => Carbon::now()->subDays(50), '__upload_id' => 1],
            ['test_key' => 64, '__property_id' => 4, '__building_id' => 4, '__created_at' => Carbon::now()->subDays(300), '__upload_id' => 1],
            ['test_key' => 4, '__property_id' => 4, '__building_id' => 4, '__created_at' => Carbon::now()->subDays(600), '__upload_id' => 1],
            ['test_key' => 4, '__property_id' => 5, '__building_id' => 5, '__created_at' => Carbon::now()->subDays(600), '__upload_id' => 1]

        ]);

        $elements = json_decode('[{"type":"datapoint","dataset_id":' . $dataset->id . ',"key":"test_key","properties":{"units":"total","property":"true","propertyRange":"false"},"trailing":365,"effect":{"type":"cuberoot","effect":"add","range":{"greaterThan":null,"lessThan":"false","equalTo":"false","add":null}}}]', true);

        $score = Score::create([
            'elements' => $elements,
            'name' => 'Test Score',
            'type' => 'building',
            'period' => null,
            'timeseries' => null,
            'owned_by' => 1
        ]);

        $this->assertDatabaseHas('cn_score_' . $score->id, ['property_id' => 1, 'score' => 1]);
        $this->assertDatabaseHas('cn_score_' . $score->id, ['property_id' => 4, 'score' => 4]);
        $this->assertDatabaseMissing('cn_score_' . $score->id, ['property_id' => 5]);

        Schema::dropIfExists($dataset->table_name);
        Schema::dropIfExists('cn_score_' . $score->id);

    }

    public function testDatapointElementLogValue()
    {
        $schema = json_decode('{"test_key":{"show":"on","name":"Test Key","key":"test_key","type":"integer"}}');

        $this->client->logInAsClient();
        $dataset = factory(DataSet::class)->create([
            'schema' => $schema
        ]);

        DB::table($dataset->table_name)->insert([
            ['test_key' => 1, '__property_id' => 1, '__building_id' => 1, '__created_at' => Carbon::now()->subDays(10), '__upload_id' => 1],
            ['test_key' => 2, '__property_id' => 2, '__building_id' => 2, '__created_at' => Carbon::now()->subDays(20), '__upload_id' => 1],
            ['test_key' => 10, '__property_id' => 4, '__building_id' => 4, '__created_at' => Carbon::now()->subDays(300), '__upload_id' => 1],
            ['test_key' => 4, '__property_id' => 4, '__building_id' => 4, '__created_at' => Carbon::now()->subDays(600), '__upload_id' => 1],
            ['test_key' => 4, '__property_id' => 5, '__building_id' => 5, '__created_at' => Carbon::now()->subDays(600), '__upload_id' => 1]

        ]);

        $elements = json_decode('[{"type":"datapoint","dataset_id":' . $dataset->id . ',"key":"test_key","properties":{"units":"total","property":"true","propertyRange":"false"},"trailing":365,"effect":{"type":"log","effect":"add","range":{"greaterThan":null,"lessThan":"false","equalTo":"false","add":null}}}]', true);

        $score = Score::create([
            'elements' => $elements,
            'name' => 'Test Score',
            'type' => 'building',
            'period' => null,
            'timeseries' => null,
            'owned_by' => 1
        ]);

        $this->assertDatabaseHas('cn_score_' . $score->id, ['property_id' => 1, 'score' => null]);
        $this->assertDatabaseHas('cn_score_' . $score->id, ['property_id' => 4, 'score' => 1]);
        $this->assertDatabaseMissing('cn_score_' . $score->id, ['property_id' => 5]);

        Schema::dropIfExists($dataset->table_name);
        Schema::dropIfExists('cn_score_' . $score->id);

    }

    public function testDatapointStringMatch()
    {
        $schema = json_decode('{"test_key":{"show":"on","name":"Test Key","key":"test_key","type":"string"}}');

        $this->client->logInAsClient();
        $dataset = factory(DataSet::class)->create([
            'schema' => $schema
        ]);

        DB::table($dataset->table_name)->insert([
            ['test_key' => 'asdf asf hello', '__property_id' => 1, '__building_id' => 1, '__created_at' => Carbon::now()->subDays(10), '__upload_id' => 1],
            ['test_key' => 'hello a asf ', '__property_id' => 2, '__building_id' => 1, '__created_at' => Carbon::now()->subDays(20), '__upload_id' => 1],
            ['test_key' => 'hello', '__property_id' => 3, '__building_id' => 1, '__created_at' => Carbon::now()->subDays(50), '__upload_id' => 1],
            ['test_key' => 'hello', '__property_id' => 4, '__building_id' => 4, '__created_at' => Carbon::now()->subDays(300), '__upload_id' => 1],
            ['test_key' => 'hello', '__property_id' => 4, '__building_id' => 4, '__created_at' => Carbon::now()->subDays(600), '__upload_id' => 1],
            ['test_key' => 'hello asfdasf', '__property_id' => 6, '__building_id' => 6, '__created_at' => Carbon::now()->subDays(300), '__upload_id' => 1]

        ]);

        $elements = json_decode('[{"type":"datapoint","dataset_id":"' . $dataset->id . '","key":"test_key","recent":"recent","properties":{"units":"total","property":"true","propertyRange":"false"},"trailing":"365","effect":{"type":"string","method":"contains","test":"hello","effect":"6"}}]', true);

        $score = Score::create([
            'elements' => $elements,
            'name' => 'Test Score',
            'type' => 'building',
            'period' => null,
            'timeseries' => null,
            'owned_by' => 1
        ]);

        $this->assertDatabaseHas('cn_score_' . $score->id, ['property_id' => 1, 'score' => 6]);
        $this->assertDatabaseHas('cn_score_' . $score->id, ['property_id' => 6, 'score' => 6]);
        $this->assertDatabaseMissing('cn_score_' . $score->id, ['property_id' => 5]);

        Schema::dropIfExists($dataset->table_name);
        Schema::dropIfExists('cn_score_' . $score->id);

    }


    public function testDatapointRange()
    {
        $schema = json_decode('{"test_key":{"show":"on","name":"Test Key","key":"test_key","type":"integer"}}');

        $this->client->logInAsClient();
        $dataset = factory(DataSet::class)->create([
            'schema' => $schema
        ]);

        DB::table($dataset->table_name)->insert([
            ['test_key' => 15, '__property_id' => 1, '__building_id' => 1, '__created_at' => Carbon::now()->subDays(10), '__upload_id' => 1],
            ['test_key' => 15, '__property_id' => 2, '__building_id' => 2, '__created_at' => Carbon::now()->subDays(20), '__upload_id' => 1],
            ['test_key' => 2000, '__property_id' => 3, '__building_id' => 3, '__created_at' => Carbon::now()->subDays(300), '__upload_id' => 1],
            ['test_key' => 50, '__property_id' => 4, '__building_id' => 4, '__created_at' => Carbon::now()->subDays(300), '__upload_id' => 1],
            ['test_key' => 2000, '__property_id' => 4, '__building_id' => 4, '__created_at' => Carbon::now()->subDays(300), '__upload_id' => 1]

        ]);

        $elements = json_decode('[{"type":"datapoint","dataset_id":"' . $dataset->id . '","key":"test_key","recent":"recent","properties":{"units":"total","property":"true","propertyRange":"false"},"trailing":365,"effect":{"type":"range","effect":"add","range":{"greaterThan":"10","lessThan":"30000","equalTo":"false","add":"5"}}}]', true);

        $score = Score::create([
            'elements' => $elements,
            'name' => 'Test Score',
            'type' => 'building',
            'period' => null,
            'timeseries' => null,
            'owned_by' => 1
        ]);

        $this->assertDatabaseHas('cn_score_' . $score->id, ['property_id' => 1, 'score' => 5]);
        $this->assertDatabaseHas('cn_score_' . $score->id, ['property_id' => 2, 'score' => 5]);
        $this->assertDatabaseHas('cn_score_' . $score->id, ['property_id' => 4, 'score' => 5]);


        Schema::dropIfExists($dataset->table_name);
        Schema::dropIfExists('cn_score_' . $score->id);

    }

    public function testDatapointStringNotMatch()
    {
        $schema = json_decode('{"test_key":{"show":"on","name":"Test Key","key":"test_key","type":"string"}}');

        $this->client->logInAsClient();
        $dataset = factory(DataSet::class)->create([
            'schema' => $schema
        ]);

        DB::table($dataset->table_name)->insert([
            ['test_key' => 'hello', '__property_id' => 1, '__building_id' => 1, '__created_at' => Carbon::now()->subDays(10), '__upload_id' => 1],
            ['test_key' => 'hello', '__property_id' => 2, '__building_id' => 1, '__created_at' => Carbon::now()->subDays(20), '__upload_id' => 1],
            ['test_key' => 'hello', '__property_id' => 3, '__building_id' => 1, '__created_at' => Carbon::now()->subDays(50), '__upload_id' => 1],
            ['test_key' => 'hello', '__property_id' => 4, '__building_id' => 4, '__created_at' => Carbon::now()->subDays(300), '__upload_id' => 1],
            ['test_key' => 'hello', '__property_id' => 4, '__building_id' => 4, '__created_at' => Carbon::now()->subDays(600), '__upload_id' => 1],
            ['test_key' => 'hello', '__property_id' => 6, '__building_id' => 6, '__created_at' => Carbon::now()->subDays(300), '__upload_id' => 1]

        ]);

        $elements = json_decode('[{"type":"datapoint","dataset_id":"' . $dataset->id . '","key":"test_key","recent":"recent","properties":{"units":"total","property":"true","propertyRange":"false"},"trailing":"365","effect":{"type":"string","method":"contains","test":"hello","effect":"6"}}]', true);

        $score = Score::create([
            'elements' => $elements,
            'name' => 'Test Score',
            'type' => 'building',
            'period' => null,
            'timeseries' => null,
            'owned_by' => 1
        ]);

        $this->assertDatabaseHas('cn_score_' . $score->id, ['property_id' => 1, 'score' => 6]);
        $this->assertDatabaseHas('cn_score_' . $score->id, ['property_id' => 6, 'score' => 6]);
        $this->assertDatabaseMissing('cn_score_' . $score->id, ['property_id' => 5]);

        Schema::dropIfExists($dataset->table_name);
        Schema::dropIfExists('cn_score_' . $score->id);

    }

    public function testTagCurrentlyTagged()
    {

        DB::table('cn_tags')->truncate();
        DB::table('cn_tagables')->truncate();
        DB::table('cn_properties')->truncate();


        $schema = json_decode('{"test_key":{"show":"on","name":"Test Key","key":"test_key","type":"string"}}');

        $this->client->logInAsClient();

        $tag = Tag::firstOrCreate(['tag' => 'New Test Tag']);

        $property = factory(Property::class)->create();

        $property->tags()->attach($tag);

        $elements = json_decode('[{"type":"tag","tag_id":"' . $tag->id . '","trailing":"365","effect":{"type":"add","factor":"5"},"tags":{"tagged":"true","trashed":"false","tagged_range":"false","trashed_range":"false"}}]', true);

        $score = Score::create([
            'elements' => $elements,
            'name' => 'Test Score',
            'type' => 'building',
            'period' => null,
            'timeseries' => null,
            'owned_by' => 1
        ]);

        $this->assertDatabaseHas('cn_score_' . $score->id, ['property_id' => $property->id, 'score' => 5]);

        Schema::dropIfExists('cn_score_' . $score->id);
    }

    public function testTagFormerlyTagged()
    {

        $schema = json_decode('{"test_key":{"show":"on","name":"Test Key","key":"test_key","type":"string"}}');

        $this->client->logInAsClient();
        DB::table('cn_tags')->truncate();
        DB::table('cn_tagables')->truncate();
        DB::table('cn_properties')->truncate();

        $tag = Tag::firstOrCreate(['tag' => 'New Test Tag']);

        $property = factory(Property::class)->create();

        DB::table('cn_tagables')->insert(['tagables_type' => 'App\\PropertyMgr\\Model\\Property', 'tagables_id' => $property->id, 'tag_id' => $tag->id, 'created_at' => Carbon::now()]);

        DB::table('cn_tagables')->insert(['tagables_type' => 'App\\PropertyMgr\\Model\\Property', 'tagables_id' => $property->id, 'tag_id' => $tag->id, 'created_at' => Carbon::now()->subDays(500), 'deleted_at' => Carbon::now()]);

        DB::table('cn_tagables')->insert(['tagables_type' => 'App\\PropertyMgr\\Model\\Property', 'tagables_id' => $property->id, 'tag_id' => $tag->id, 'created_at' => Carbon::now(), 'deleted_at' => Carbon::now()]);

        DB::table('cn_tagables')->insert(['tagables_type' => 'App\\PropertyMgr\\Model\\Property', 'tagables_id' => $property->id, 'tag_id' => ($tag->id + 1), 'created_at' => Carbon::now(), 'deleted_at' => Carbon::now()]);

        DB::table('cn_tagables')->insert(['tagables_type' => 'App\\PropertyMgr\\Model\\Property', 'tagables_id' => $property->id, 'tag_id' => ($tag->id + 1), 'created_at' => Carbon::now()]);

        $elements = json_decode('[{"type":"tag","tag_id":"' . $tag->id . '","trailing":"365","effect":{"type":"add","factor":"5"},"tags":{"tagged":"false","trashed":"true","tagged_range":"false","trashed_range":"false"}}, {"type":"tag","tag_id":"' . ($tag->id + 1) . '","trailing":"365","effect":{"type":"add","factor":"2"},"tags":{"tagged":"false","trashed":"true","tagged_range":"false","trashed_range":"false"}}]', true);

        $score = Score::create([
            'elements' => $elements,
            'name' => 'Test Score',
            'type' => 'building',
            'period' => null,
            'timeseries' => null,
            'owned_by' => 1
        ]);

        $this->assertDatabaseHas('cn_score_' . $score->id, ['property_id' => $property->id, 'score' => 7]);

        Schema::dropIfExists('cn_score_' . $score->id);
    }
}
