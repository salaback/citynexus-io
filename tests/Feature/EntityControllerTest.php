<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EntityControllerTest extends TestCase
{

    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();
        $this->client->logInAsClient();
    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRemoveRelationship()
    {
        $relationship_id = DB::table('cn_entitables')->insertGetId([
            'entitables_type' => 'test',
            'entitables_id' => 1,
            'upload_id' => 9999,
            'entity_id' => 999,
            'role' => 'owner'
        ]);

        $relationship = DB::table('cn_entitables')->where('id', $relationship_id)->first();

        $this->assertNull($relationship->deleted_at);

        $this->get(route('entity.removeRelationship', [$relationship_id]));

        $relationship = DB::table('cn_entitables')->where('id', $relationship_id)->first();

        $this->assertNotNull($relationship->deleted_at);
    }
}
