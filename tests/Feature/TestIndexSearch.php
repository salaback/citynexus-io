<?php

namespace Tests\Feature;

use App\SearchResult;
use App\Services\IndexSearch;
use CityNexus\PropertyMgr\Address;
use CityNexus\PropertyMgr\Property;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TestIndexSearch extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexProperties()
    {
        factory(Address::class)->create(50);


        $results = SearchResult::where('type', 'Building')->count();

        $this->assertSame($results, 50);
    }
}
