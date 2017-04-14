<?php

namespace Tests\Feature;

use App\SearchResult;
use App\Services\IndexSearch;
use App\Services\MultiTenant;
use CityNexus\DataStore\DataSet;
use CityNexus\PropertyMgr\File;
use CityNexus\PropertyMgr\Tag;
use Carbon\Carbon;
use CityNexus\PropertyMgr\Address;
use CityNexus\PropertyMgr\Property;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexSearchTest extends TestCase
{

    private $client;

    public function setUp()
    {
        parent::setUp();
        $multiTenant = new MultiTenant();
        $this->client = $multiTenant->createClient('Test Client', 'testclient');
        $this->client->logInAsClient();
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->client->delete();
    }


    /**
     * A test that a property has been added to the search results
     *
     * @return void
     */
    public function testIndexProperties()
    {
        Property::create([
            'address' => '123 MAIN STREET',
            'is_building' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Artisan::call('citynexus:searchindex', ['client_id' => $this->client->id]);

        $results = SearchResult::where('type', 'House')->count();

        $this->assertSame($results, 1);
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

        $this->assertSame($results, 1);
    }

    /**
     * A test that a dataset has been added to the search results
     *
     * @return void
     */
    public function testIndexDataSets()
    {
        DataSet::create(['name' => 'This is a Test']);

        Artisan::call('citynexus:searchindex', ['client_id' => $this->client->id]);

        $results = SearchResult::where('type', 'Data Set')->count();

        $this->assertSame($results, 1);
    }


    /**
     * A test that a dataset has been added to the search results
     *
     * @return void
     */
    public function testIndexFiles()
    {
        DB::table('cn_files')->insert([
           'caption' => 'This is a test',
            'cn_fileable_id' => 1,
            'cn_fileable_type' => 'test',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Artisan::call('citynexus:searchindex', ['client_id' => $this->client->id]);

        $results = SearchResult::where('type', 'File')->count();

        $this->assertSame($results, 1);
    }

    /**
     * A test that a dataset has been added to the search results
     *
     * @return void
     */
    public function testIndexComments()
    {
        DB::table('cn_comments')->insert([
            'title' => 'This is a test',
            'comment' => 'This is a comment',
            'cn_commentable_id' => 1,
            'cn_commentable_type' => 'test',
            'posted_by' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Artisan::call('citynexus:searchindex', ['client_id' => $this->client->id]);

        $results = SearchResult::where('type', 'Comment')->count();

        $this->assertSame($results, 1);
    }


}
