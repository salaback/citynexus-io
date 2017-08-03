<?php

namespace Tests\Feature;

use App\AnalysisMgr\Model\Score;
use App\AnalysisMgr\ScoreProcessor;
use App\Client;
use App\PropertyMgr\Model\Property;
use App\PropertyMgr\Model\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ScoreProcessorTest extends TestCase
{
    use DatabaseTransactions;
    protected $processor;
    protected $client;

    protected  $connectionsToTransact = [
        'public',
        'tenant'
    ];

    public function setUp()
    {
        parent::setUp();
        $this->processor = new ScoreProcessor();
        $this->client = Client::where('domain', 'testclient.citynexus-io.app:8000')->first();

    }

    public function testPreLoadDataTags()
    {
        $this->client->logInAsClient();

        $tag = factory(Tag::class)->create();

        $property = factory(Property::class)->create();

        $property->tags()->attach($tag);

        $score = factory(Score::class)->create([
            'elements' => [
                [
                    'type' => 'tag',
                    'tag_id' => $tag->id,
                    'trailing' => 365,
                    'timeRange' => [
                        'to' => null,
                        'from' => null,
                    ],
                    'effect' => [
                        'type' => 'add',
                        'factor' => 1,
                    ],
                    'tags' => [
                        'tagged' => "true",
                        'trashed' => "false",
                        'trashed_range' => "false",
                        'tagged_range' => "false",
                    ]
                ]
            ]]
        );

        $actual = $this->invokeMethod($this->processor, 'preLoadData', [$score]);

        $expected = [
            'tags' => [$tag->id => false],
            'datasets' => []
        ];

        $this->assertSame($expected, $actual);

        Schema::dropIfExists('cn_score_' . $score->id);


    }

    public function testLoadDataTags()
    {
        $this->client->logInAsClient();

        $tag = factory(Tag::class)->create();
        $tag2 = factory(Tag::class)->create();
        $property = factory(Property::class)->create();
        $property2 = factory(Property::class)->create();
        $property3 = factory(Property::class)->create();
        $property->tags()->attach($tag);
        $property2->tags()->attach($tag2);
        $property3->tags()->attach($tag2);

        $score = factory(Score::class)->create([
                'elements' => [
                    [
                        'type' => 'tag',
                        'tag_id' => $tag->id,
                        'trailing' => 365,
                        'timeRange' => [
                            'to' => null,
                            'from' => null,
                        ],
                        'effect' => [
                            'type' => 'add',
                            'factor' => 1,
                        ],
                        'tags' => [
                            'tagged' => "true",
                            'trashed' => "false",
                            'trashed_range' => "false",
                            'tagged_range' => "false",
                        ]
                    ],
                        [
                        'type' => 'tag',
                        'tag_id' => $tag2->id,
                            'trailing' => 365,

                            'timeRange' => [
                            'to' => null,
                            'from' => null,
                        ],
                        'effect' => [
                            'type' => 'add',
                            'factor' => 1,
                        ],
                        'tags' => [
                            'tagged' => "true",
                            'trashed' => "false",
                            'trashed_range' => "false",
                            'tagged_range' => "false",
                        ]
                    ]
                ]]
        );

        $result = $this->invokeMethod($this->processor, 'loadData', [$score]);

        $this->assertTrue(count($result['tags']) == 3);

        Schema::dropIfExists('cn_score_' . $score->id);

    }

    public function testCreateTagScoreElement()
    {
        $this->client->logInAsClient();

        $tag = factory(Tag::class)->create();
        $tag2 = factory(Tag::class)->create();

        $property = factory(Property::class)->create();
        $property->tags()->attach($tag);
        $property->tags()->attach($tag2);
        $score = factory(Score::class)->create([
                'elements' => [
                    [
                        'type' => 'tag',
                        'tag_id' => $tag->id,
                        'trailing' => 365,
                        'timeRange' => [
                            'to' => null,
                            'from' => null,
                        ],
                        'effect' => [
                            'type' => 'add',
                            'factor' => 2,
                        ],
                        'tags' => [
                            'tagged' => "true",
                            'trashed' => "false",
                            'trashed_range' => "false",
                            'tagged_range' => "false",
                        ]
                    ],
                    [
                    'type' => 'tag',
                    'tag_id' => $tag->id,
                        'trailing' => 365,

                        'timeRange' => [
                        'to' => null,
                        'from' => null,
                        ],
                    'effect' => [
                        'type' => 'subtract',
                        'factor' => 1,
                        ],
                    'tags' => [
                        'tagged' => "true",
                        'trashed' => "false",
                        'trashed_range' => "false",
                        'tagged_range' => "false",
                        ]
                    ]
                ]
            ]
        );

        $result = $this->invokeMethod($this->processor, 'createScore', [$score]);

        $this->assertTrue(count($result[$property->id]['tags']) == 2);

        Schema::dropIfExists('cn_score_' . $score->id);

    }

    public function testProcessTagScoreElement()
    {
        $this->client->logInAsClient();

        $tag = factory(Tag::class)->create();
        $tag2 = factory(Tag::class)->create();

        $property = factory(Property::class)->create();
        $property->tags()->attach($tag);
        $property->tags()->attach($tag2);

        $score = factory(Score::class)->create([
                'elements' => [
                    [
                        'type' => 'tag',
                        'tag_id' => $tag->id,
                        'trailing' => 365,
                        'effect' => [
                            'type' => 'add',
                            'factor' => 2,
                        ],
                        'tags' => [
                            'tagged' => "true",
                            'trashed' => "false",
                            'trashed_range' => "false",
                            'tagged_range' => "false",
                        ]
                    ],
                    [
                        'type' => 'tag',
                        'tag_id' => $tag2->id,
                        'trailing' => 365,

                        'effect' => [
                            'type' => 'subtract',
                            'factor' => 1,
                        ],
                        'tags' => [
                            'tagged' => "true",
                            'trashed' => "false",
                            'trashed_range' => "false",
                            'tagged_range' => "false",
                        ]
                    ]
                ]
            ]
        );


        $result = DB::table('cn_score_' . $score->id)->where('property_id', $property->id)->first();

        $this->assertEquals($result->score, 1);

        Schema::dropIfExists('cn_score_' . $score->id);

    }

    public function testUpdateScore()
    {
        $old = (object) [
            'property_id' => 1,
            'score' => 1,
            'elements' => json_encode([
                [
                    'type' => 'tag',
                    'tag_id' => 1,
                    'effect' => 2
                ],
                [
                    'type' => 'tag',
                    'tag_id' => 2,
                    'effect' => -1
                ],
            ]),
            'history' => null];

        $new = [
            "tags" => [
                0 => [
                    "type" => "tag",
                    "tag_id" => 1,
                    "effect" => 2
                ],
                1 => [
                    "type" => "tag",
                    "tag_id" => 2,
                    "effect" => -1
                ]
            ]
        ];

        $result = $this->invokeMethod($this->processor, 'updateScore', [$old, $new]);

        $this->assertSame($result['score'], 1);

        Schema::dropIfExists('cn_score_' . $score->id);

    }

    public function testMakeScore()
    {
        $elements = [
            "tags" => [
                0 => [
                    "type" => "tag",
                    "tag_id" => 1,
                    "effect" => 2
                ],
                1 => [
                    "type" => "tag",
                    "tag_id" => 2,
                    "effect" => -1
                ]
            ]
        ];

        $result = $this->invokeMethod($this->processor, 'makeScore', [$elements]);

        $this->assertSame($result, 1);

    }

    public function testMakeScoreWithAnIgnoreElement()
    {
        $elements = [
            "tags" => [
                0 => [
                    "type" => "tag",
                    "tag_id" => 1,
                    "effect" => 2
                ],
                1 => [
                    "type" => "tag",
                    "tag_id" => 2,
                    "effect" => 'ignore'
                ]
            ],
            "datapoints" => [
                0 => [
                    "type" => "datapoint",
                    "dataset_id" => 1,
                    "key" => 'dumb',
                    "effect" => 2
                ],
                1 => [
                    "type" => "datapoint",
                    "dataset_id" => 2,
                    "key" => 'dumb',
                    "effect" => 5
                ]
            ]
        ];

        $result = $this->invokeMethod($this->processor, 'makeScore', [$elements]);

        $this->assertSame($result, null);

    }
}
