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

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateScoreTable()
    {
        $this->client->logInAsClient();
        $score = factory(Score::class)->create();
        $result = $this->invokeMethod($this->processor, 'createScoreTable', ['score' => $score]);

        $this->assertTrue($result);
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
                    'timeRange' => [
                        'to' => null,
                        'from' => null,
                    ],
                    'effect' => [
                        'type' => 'add',
                        'factor' => 1,
                    ],
                    'tags' => [
                        'tagged' => true,
                        'trashed' => false,
                        'trashedRange' => false,
                        'taggedRange' => false,
                    ]
                ]
            ]]
        );

        $actual = $this->invokeMethod($this->processor, 'preLoadData', [$score]);

        $expected = [
            'tags' => [$tag->id]
        ];

        $this->assertSame($expected, $actual);
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
                        'timeRange' => [
                            'to' => null,
                            'from' => null,
                        ],
                        'effect' => [
                            'type' => 'add',
                            'factor' => 1,
                        ],
                        'tags' => [
                            'tagged' => true,
                            'trashed' => false,
                            'trashedRange' => false,
                            'taggedRange' => false,
                        ]
                    ],
                    [
                    'type' => 'tag',
                    'tag_id' => $tag2->id,
                    'timeRange' => [
                        'to' => null,
                        'from' => null,
                    ],
                    'effect' => [
                        'type' => 'add',
                        'factor' => 1,
                    ],
                    'tags' => [
                        'tagged' => true,
                        'trashed' => false,
                        'trashedRange' => false,
                        'taggedRange' => false,
                    ]
                ]
                ]]
        );

        $result = $this->invokeMethod($this->processor, 'loadData', [$score]);

        $this->assertTrue(count($result['tags']) == 3);
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
                        'timeRange' => [
                            'to' => null,
                            'from' => null,
                        ],
                        'effect' => [
                            'type' => 'add',
                            'factor' => 2,
                        ],
                        'tags' => [
                            'tagged' => true,
                            'trashed' => false,
                            'trashedRange' => false,
                            'taggedRange' => false,
                        ]
                    ],
                    [
                    'type' => 'tag',
                    'tag_id' => $tag->id,
                    'timeRange' => [
                        'to' => null,
                        'from' => null,
                        ],
                    'effect' => [
                        'type' => 'subtract',
                        'factor' => 1,
                        ],
                    'tags' => [
                        'tagged' => true,
                        'trashed' => false,
                        'trashedRange' => false,
                        'taggedRange' => false,
                        ]
                    ]
                ]
            ]
        );

        $result = $this->invokeMethod($this->processor, 'createScore', [$score]);

        $this->assertTrue(count($result[$property->id]['tags']) == 2);
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
                        'timeRange' => [
                            'to' => null,
                            'from' => null,
                        ],
                        'effect' => [
                            'type' => 'add',
                            'factor' => 2,
                        ],
                        'tags' => [
                            'tagged' => true,
                            'trashed' => false,
                            'trashedRange' => false,
                            'taggedRange' => false,
                        ]
                    ],
                    [
                        'type' => 'tag',
                        'tag_id' => $tag->id,
                        'timeRange' => [
                            'to' => null,
                            'from' => null,
                        ],
                        'effect' => [
                            'type' => 'subtract',
                            'factor' => 1,
                        ],
                        'tags' => [
                            'tagged' => true,
                            'trashed' => false,
                            'trashedRange' => false,
                            'taggedRange' => false,
                        ]
                    ]
                ]
            ]
        );

        $this->invokeMethod($this->processor, 'createScoreTable', ['score' => $score]);

        DB::table('cn_score_' . $score->id)->insert([
            'property_id' => $property->id,
            'score' => 1,
            'elements' => \GuzzleHttp\json_encode([
                [
                    'type' => 'tag',
                    'tag_id' => $tag->id,
                    'effect' => 2
                ],
                [
                    'type' => 'tag',
                    'tag_id' => $tag2->id,
                    'effect' => -1
                ],
            ])
        ]);


        $scores = [
            $property->id =>  [
                "tags" => [
                    0 => [
                        "type" => "tag",
                        "tag_id" => $tag->id,
                        "effect" => 2
                    ],
                    1 => [
                        "type" => "tag",
                        "tag_id" => $tag2->id,
                        "effect" => -1
                    ]
                ]
            ]
        ];

        $this->invokeMethod($this->processor, 'processScore', [$score->id]);

        $result = DB::table('cn_score_' . $score->id)->where('property_id', $property->id)->first();
        $this->assertSame($result->score, 1);
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
