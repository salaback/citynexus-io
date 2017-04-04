<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PropertySyncTest extends TestCase
{
    use DatabaseTransactions;

    public function __construct()
    {
        $this->address_array = [
              "building" => null,
              "house_num" => "23",
              "predir" => null,
              "qual" => null,
              "pretype" => null,
              "name" => "MONMOUTH",
              "suftype" => "ST",
              "sufdir" => null,
              "ruralroute" => null,
              "extra" => null,
              "city" => "SOMERVILLE",
              "state" => "MA",
              "country" => "USA",
              "postcode" => "02143",
              "box" => null,
              "unit" => "APARTMENT 1R",
            ];

        $this->address = '23 Monmouth Street, Apt. 1R, Somerville, MA 02143';

    }

    public function invokeMethod($methodName, array $parameters = array())
    {
        $object = new \CityNexus\PropertyMgr\PropertySync();
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public function testParseFullAddress()
    {
        $result = $this->invokeMethod('parseFullAddress', [$this->address]);

        $this->assertEquals($this->address_array, $result);
    }

    public function testMakeAddress()
    {
        $results = $this->invokeMethod('makeAddress', [$this->address_array]);
        $expected = '23 MONMOUTH ST';

        $this->assertSame($expected, $results);
    }

    public function testRawUnparsedAddress()
    {
        $row = [
            'full_address'  => '23 Monmouth Street, Apt 1R'
        ];
        $sync = (object) [
            'full_address'          => 'full_address',
            'city'                  => 'city',
            'default_city'          => 'SOMERVILLE',
            'state'                 => 'state',
            'default_state'         => 'MA',
            'postal_code'           => 'postal_code',
            'default_postal_code'   => '02143'
        ];

        $results = $this->invokeMethod('rawUnparsedAddress', [$row, $sync]);

        $this->assertSame($this->address_array, $results);
    }

    public function testParsedAddress()
    {
        $row = [
            'street_number'         => '23',
            'street_name'           => 'Monmouth',
            'street_type'           => 'street',
            'unit'                  => 'apt. 1R'
        ];
        $sync = (object) [
            'street_number'         => 'street_number',
            'street_name'           => 'street_name',
            'street_type'           => 'street_type',
            'unit'                  => 'unit',
            'city'                  => 'city',
            'default_city'          => 'SOMERVILLE',
            'state'                 => 'state',
            'default_state'         => 'MA',
            'postal_code'           => 'postal_code',
            'default_postal_code'   => '02143'
        ];

        $results = $this->invokeMethod('parsedAddress', [$row, $sync]);

        $expected = \CityNexus\PropertyMgr\Property::where('address', '23 MONMOUTH ST')
            ->where('unit', 'APARTMENT 1R')
            ->first();

        $this->assertSame($expected->id, $results);
    }

    public function testGetPropertyId()
    {
        $building = \CityNexus\PropertyMgr\Property::create([
            'address'       => '23 MONMOUTH ST',
            'city'          => 'SOMERVILLE',
            'state'         => 'MA',
            'postcode'   => '02143',
            'country'        => 'USA',
            'is_building'   => true
        ]);

        $unit = \CityNexus\PropertyMgr\Property::create([
            'unit'          => 'APARTMENT 1R',
            'building_id'   => $building->id,
            'address'       => '23 MONMOUTH ST',
            'city'          => 'SOMERVILLE',
            'state'         => 'MA',
            'postcode'      => '02143',
            'country'        => 'USA',
            'is_unit'       => true
        ]);

        $result = $this->invokeMethod('getPropertyId', [$this->address_array]);

        $this->assertSame($unit->id, $result);
    }

    public function testUnparsedAddress()
    {
        $row = [
            'full_address'  => '23 Monmouth Street, Apt 1R'
        ];
        $sync = (object) [
            'full_address'         => 'full_address',
            'city'                  => 'city',
            'default_city'          => 'SOMERVILLE',
            'state'                 => 'state',
            'default_state'         => 'MA',
            'postal_code'           => 'postal_code',
            'default_postal_code'   => '02143'
        ];

        $results = $this->invokeMethod('unparsedAddress', [$row, $sync]);

        $expected = \CityNexus\PropertyMgr\Property::where('address', '23 MONMOUTH ST')
            ->where('unit', 'APARTMENT 1R')
            ->first();

        $this->assertSame($expected->id, $results);
    }


}

