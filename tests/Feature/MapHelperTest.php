<?php

namespace Tests\Feature;

use App\AnalysisMgr\MapHelper;
use App\PropertyMgr\Model\Tag;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MapHelperTest extends TestCase
{

    protected $client;
    protected $mapHelper;

    protected  $connectionsToTransact = [
        'public',
        'tenant'
    ];

    public function setUp()
    {
        parent::setUp();
        $this->client->logInAsClient();
        $this->mapHelper = new MapHelper();
    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testScorePoints()
    {

        DB::table('cn_scores')->truncate();
        DB::table('cn_properties')->truncate();
        Schema::dropIfExists('cn_score_1');

        $property_1 = DB::table('cn_properties')->insertGetId([
            'location' => '0101000020E6100000B8B7B64B768C5DC025F37E92F1EC4040',
            'cords' => '{"lat":33.8511222,"lng":-118.1947202}',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $property_2 = DB::table('cn_properties')->insertGetId([
            'location' => '0101000020E6100000E83EEF7C758C5DC078F1D995F1EC4040',
            'cords' => '{"lat":33.8511226,"lng":-118.1946709}',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $property_3 = DB::table('cn_properties')->insertGetId([
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $score = DB::table('cn_scores')->insertGetId([
            'name' => 'Test Score',
            'owned_by' => 1
        ]);

        Schema::create('cn_score_' . $score, function (Blueprint $table) {
            $table->integer('property_id')->unique()->unsigned();
            $table->float('score')->nullable();
            $table->json('elements')->nullable();
            $table->json('history')->nullable();
        });

        DB::table('cn_score_' . $score)->insert([
            [
                'property_id' => $property_1,
                'score' => 1,
            ],
            [
                'property_id' => $property_2,
                'score' => 5,
            ],
            [
                'property_id' => $property_3,
                'score' => 3,
            ]
        ]);

        $response = $this->mapHelper->createScorePoints($score);

        Schema::dropIfExists('cn_score_' . $score);

        $expected = [
            "points" => [
                [
                    "value" => "1",
                    "count" => 1,
                    "lat" => 33.8511222,
                    "lng" => -118.1947202,
                    "message" => '(1) - <a href="http://tenant.citynexus-io.app:8000/properties/1" target="_blank"></a></br>',
                ],
                [
                    "value" => "5",
                    "count" => 1,
                    "lat" => 33.8511226,
                    "lng" => -118.1946709,
                    "message" => '(5) - <a href="http://tenant.citynexus-io.app:8000/properties/2" target="_blank"></a></br>'
                ]
            ],
            "max" => "5",
            "title" => "Test Score",
            "handle" => "score_1",
        ];

        $this->assertSame($response, $expected);

    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testTagPoints()
    {

        DB::table('cn_tags')->truncate();
        DB::table('cn_tagables')->truncate();
        DB::table('cn_properties')->truncate();
        Schema::dropIfExists('cn_score_1');

        $property_1 = DB::table('cn_properties')->insertGetId([
            'location' => '0101000020E6100000B8B7B64B768C5DC025F37E92F1EC4040',
            'cords' => '{"lat":33.8511222,"lng":-118.1947202}',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $property_2 = DB::table('cn_properties')->insertGetId([
            'location' => '0101000020E6100000E83EEF7C758C5DC078F1D995F1EC4040',
            'cords' => '{"lat":33.8511226,"lng":-118.1946709}',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $property_3 = DB::table('cn_properties')->insertGetId([
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $tag = Tag::firstOrCreate(['tag' => 'Test']);

        DB::table('cn_tagables')->insert([
            [
                'tagables_id' => $property_1,
                'tagables_type' => 'App\\PropertyMgr\\Model\\Property',
                'tag_id' => $tag->id,
                'created_at' => Carbon::now()
            ],
            [
                'tagables_id' => $property_3,
                'tagables_type' => 'App\\PropertyMgr\\Model\\Property',
                'tag_id' => $tag->id,
                'created_at' => Carbon::now()
            ]
        ]);

        $response = $this->mapHelper->createTagPoints($tag->id);

        $expected = [
            "points" => [
                [
                    "count" => 1,
                    "lat" => 33.8511222,
                    "lng" => -118.1947202,
                    "message" => '<a href="http://tenant.citynexus-io.app:8000/properties/1" target="_blank"></a></br>',
                ],
            ],
            "max" => null,
            "title" => "Test Tag",
            "handle" => "tag_" . $tag->id,
        ];

        $this->assertSame($response, $expected);

    }
}
