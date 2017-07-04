<?php

namespace Tests\Feature;

use App\Client;
use App\SearchResult;
use App\Services\IndexSearch;
use App\Services\MultiTenant;
use App\DataStore\Model\DataSet;
use App\PropertyMgr\Model\File;
use App\PropertyMgr\Model\Tag;
use Carbon\Carbon;
use App\PropertyMgr\Model\Address;
use App\PropertyMgr\Model\Property;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexSearchTest extends TestCase
{


    use DatabaseTransactions;

    protected  $connectionsToTransact = [
        'public',
        'tenant'
    ];

    public function setUp()
    {
        parent::setUp();
        $this->client->logInAsClient();
    }


    /**
     * A test that a property has been added to the search results
     *
     * @return void
     */
    public function testIndexProperties()
    {
        $address = random_int(10, 500000);
        Property::create([
            'address' => $address,
            'is_building' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Artisan::call('citynexus:searchindex', ['client_id' => $this->client->id]);

        $this->assertDatabaseHas('search_results', ['type' => 'House', 'search' => $address]);
    }

    /**
     * A test that a tags has been added to the search results
     *
     * @return void
     */
    public function testIndexTags()
    {
        Tag::firstOrCreate(['tag' => 'Test Tag']);

        Artisan::call('citynexus:searchindex', ['client_id' => $this->client->id]);

        $results = SearchResult::where('type', 'Tag')->count();
    }

    /**
     * A test that a dataset has been added to the search results
     *
     * @return void
     */
    public function testIndexDataSets()
    {
        $dataset = factory(DataSet::class)->create();

        Artisan::call('citynexus:searchindex', ['client_id' => $this->client->id]);

        $this->assertDatabaseHas('search_results', [
            'type' => 'Data Set',
            'search' => $dataset->name
        ]);

//        $results = SearchResult::where('type', 'Data Set')->->count();
//
//        $this->assertSame($results, 1);
    }


    /**
     * A test that a dataset has been added to the search results
     *
     * @group failing
     * @return void
     */
    public function testIndexFiles()
    {
        $caption = str_random();
        DB::table('cn_files')->insert([
            'caption' => $caption,
            'description' => 'This is a file',
            'cn_fileable_id' => 1,
            'cn_fileable_type' => 'test',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Artisan::call('citynexus:searchindex', ['client_id' => $this->client->id]);

        $this->assertDatabaseHas('search_results', [
            'type' => 'File',
            'search' => $caption . ' This is a file'
        ]);

    }

    /**
     * A test that a dataset has been added to the search results
     *
     * @return void
     */
    public function testIndexComments()
    {

        $title = str_random();
        DB::table('cn_comments')->insert([
            'title' => $title,
            'comment' => 'This is a comment',
            'cn_commentable_id' => 1,
            'cn_commentable_type' => 'test',
            'posted_by' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Artisan::call('citynexus:searchindex', ['client_id' => $this->client->id]);

        $this->assertDatabaseHas('search_results', [
            'type' => 'Comment',
            'search' => $title . ' This is a comment'
        ]);
    }


}
