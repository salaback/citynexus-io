<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EntitySyncTest extends TestCase
{
    use DatabaseTransactions;

    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

        /**
     * A basic functional test example.
     *
     * @return void
     */

    public function testParseName()
    {
        $sync = new \CityNexus\PropertyMgr\EntitySync();
        $test = [
            'title'         => 'MR',
            'first_name'    => 'SEAN',
            'middle_name'   => 'MUIR',
            'last_name'     => 'ALABACK',
            'suffix'     => 'SR'
        ];

        $result = $sync->parseName('Mister Sean Muir Alaback Senior');

        $this->assertEquals($test, $result, "Stringed full name");

        $test = [
            'first_name'    => 'SEAN',
            'middle_name'   => 'MUIR',
            'last_name'     => 'ALABACK',
        ];

        $result = $sync->parseName('Alaback, Sean Muir');

        $this->assertEquals($test, $result, "LastName, FirstName MiddleName");


    }

    public function testCompanyName()
    {
        $sync = new \CityNexus\PropertyMgr\EntitySync();

        $test = [
            'company_name'    => 'NORTH OF MAIN',
            'company_structure'   => 'CORP'
        ];

        $result = $sync->parseName("North of Main, Corp.");

        $this->assertEquals($test, $result, "Company Name, Corp.");
    }

    public function testRawNameCheck()
    {

        $property = \CityNexus\PropertyMgr\Property::create(['street_number' => 50, 'street_name' => 'MAIN', 'street_type' => 'STREET']);
        $raw = \CityNexus\PropertyMgr\RawEntity::create(['full_name' => 'TEST FULL NAME']);
        $sync['full_name'] = 'full_name';
        $sync['type'] = 'unparsed';
        $sync['role'] = 'Owner';

        $testData = new stdClass();

        $testData->property_id = $property->id;
        $testData->full_name = 'test full name';

        $data = [
          $testData
        ];

        $syncObject = new \CityNexus\PropertyMgr\Sync();

        $this->invokeMethod($syncObject, 'entitySync', [$data, $sync, '99999']);

        $test = \CityNexus\PropertyMgr\Entity::where('first_name', 'TEST')->where('middle_name', 'FULL')->where('last_name', 'NAME')->first();

        $raw = \CityNexus\PropertyMgr\RawEntity::find($raw->id);

        $this->assertEquals($raw->entity_id, $test->id);
    }

}

