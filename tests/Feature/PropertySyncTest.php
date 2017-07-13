<?php

namespace Tests\Feature;

use App\PropertyMgr\Model\Address;
use App\PropertyMgr\Model\Property;
use App\PropertyMgr\PropertySync;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PropertySyncTest extends TestCase
{
    use DatabaseTransactions;

    protected  $connectionsToTransact = [
        'public',
        'tenant'
    ];

    protected $propSync;

    public function setUp()
    {
        parent::setUp();
        $this->client->logInAsClient();
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
            "unit" => "1R",
        ];

        $this->propSync = new PropertySync();
        $this->address = '23 Monmouth Street, Apt. 1R, Somerville, MA 02143';
    }

    public function testParseFullAddress()
    {
        $result = $this->invokeMethod($this->propSync, 'parseFullAddress', [$this->address]);

        $this->assertEquals($this->address_array, $result);
    }

    public function testMakeAddress()
    {
        $results = $this->invokeMethod($this->propSync, 'makeAddress', [$this->address_array]);
        $expected = '23 MONMOUTH ST';

        $this->assertSame($expected, $results);
    }

    public function testRawUnparsedAddress()
    {
        $row = [
            'full_address'  => '23 Monmouth Street, Apt 1R'
        ];
        $sync = [
            'full_address'          => 'full_address',
            'city'                  => 'city',
            'default_city'          => 'SOMERVILLE',
            'state'                 => 'state',
            'default_state'         => 'MA',
            'postcode'              => 'postcode',
            'default_postal_code'   => '02143'
        ];

        $results = $this->invokeMethod($this->propSync, 'rawUnparsedAddress', [$row, $sync]);

        $this->assertSame($this->address_array, $results);
    }

    public function testParsedAddress()
    {
        $row = [
            'house_number'         => '23',
            'street_name'           => 'Monmouth',
            'street_type'           => 'street',
            'unit'                  => 'apt. 1R'
        ];
        $sync = [
            'house_number'         => 'house_number',
            'street_name'           => 'street_name',
            'street_type'           => 'street_type',
            'unit'                  => 'unit',
            'city'                  => 'city',
            'default_city'          => 'SOMERVILLE',
            'state'                 => 'state',
            'default_state'         => 'MA',
            'postcode'           => 'postcode',
            'default_postal_code'   => '02143'
        ];

        $results = $this->invokeMethod($this->propSync, 'parsedAddress', [$row, $sync]);

        $expected = \App\PropertyMgr\Model\Property::where('address', '23 MONMOUTH ST')
            ->where('unit', '1 R')
            ->first();

        $this->assertSame($expected->id, $results);
    }

    public function testGetPropertyId()
    {
        $building = \App\PropertyMgr\Model\Property::firstOrCreate([
            'address'       => '23 MONMOUTH ST',
            'city'          => 'SOMERVILLE',
            'state'         => 'MA',
            'postcode'   => '02143',
            'country'        => 'USA',
            'is_building'   => true
        ]);

        $unit = \App\PropertyMgr\Model\Property::firstOrCreate([
            'unit'          => '1R',
            'building_id'   => $building->id,
            'is_unit'       => true
        ]);

        $address = Address::firstOrCreate($this->address_array);
        $address->property_id = $unit->id;
        $address->save();

        $result = $this->invokeMethod($this->propSync, 'getPropertyId', [$this->address_array]);

        $this->assertSame($unit->id, $result);
    }

    public function testUnparsedAddress()
    {
        $row = [
            'full_address'  => strtoupper('23 Monmouth Street, Apt 1R')
        ];
        $sync = [
            'full_address'         => 'full_address',
            'city'                  => 'city',
            'default_city'          => 'SOMERVILLE',
            'state'                 => 'state',
            'default_state'         => 'MA',
            'postcode'           => 'postcode',
            'default_postal_code'   => '02143'
        ];

        $result = $this->invokeMethod($this->propSync, 'unparsedAddress', [$row, $sync]);

        $this->assertDatabaseHas('cn_properties', [
            'unit' => '1R',
            'id' => $result
        ]);
    }

    public function testUnparsedAddressWithoutState()
    {

        DB::table('cn_properties')->truncate();
        DB::table('cn_addresses')->truncate();

        $row = [
            'full_address'  => strtoupper('23 Monmouth Street')
        ];

        $sync = [
            'full_address'         => 'full_address',
            'city'                  => 'city',
            'default_city'          => 'SOMERVILLE',
            'state'                 => 'state',
            'default_state'         => 'MA',
            'postcode'           => 'postcode',
            'default_postal_code'   => '02143'
        ];

        $property = Property::firstOrCreate(['address' => '23 MONMOUTH ST', 'is_building' => true]);


        $result = $this->invokeMethod($this->propSync, 'unparsedAddress', [$row, $sync]);

        $this->assertSame($result, $property->id);
    }
}
