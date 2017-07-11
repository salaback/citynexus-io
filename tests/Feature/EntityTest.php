<?php

namespace Tests\Feature;

use App\PropertyMgr\EntitySync;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EntityTest extends TestCase
{

    protected $entitySync;
    protected $client;

    use DatabaseTransactions;

    protected  $connectionsToTransact = [
        'public',
        'tenant'
    ];

    public function setUp()
    {
        parent::setUp();
        $this->client->logInAsClient();
        $this->entitySync = new EntitySync();
    }

    public function testBasicParsedAddress()
    {
        $sync = [
            'type' => 'parsed',
            'house_number' => 'house_number',
            'street_name' => 'street_name',
            'street_type' => 'street_type',
            'unit' => 'unit',
            'city' => 'city',
            'state' => 'state',
            'postcode' => 'postcode'

        ];

        $address = [
            'house_number' => random_int(10, 1000),
            'street_name' => 'Test Name',
            'street_type' => 'Street',
            'unit' => random_int(1, 100),
            'city' => 'Boston',
            'state' => 'MA',
            'postcode' => '12345'
        ];

        $return = $this->entitySync->syncAddress($address, $sync);

        $expected = [
            'address' => $address['house_number'] . ' Test Name Street, Unit ' . $address['unit'],
            'city' => $address['city'],
            'state' => 'MA',
            'postcode' => '12345'
        ];

        $this->assertSame($return, $expected);
    }

    public function testBasicParsedAddressWithOnlyDefaults()
    {
        $sync = [
            'type' => 'parsed',
            'house_number' => 'house_number',
            'street_name' => 'street_name',
            'street_type' => null,
            'unit' => 'unit',
            'city' =>  null,
            'state' => null,
            'postcode' => null,
            'default_city' => 'Boston',
            'default_state' => 'MA',
            'default_postcode' => '12345',

        ];

        $address = [
            'house_number' => random_int(10, 1000),
            'street_name' => 'Test Name Street',
            'unit' => random_int(1, 100),
        ];

        $return = $this->entitySync->syncAddress($address, $sync);

        $expected = [
            'address' => $address['house_number'] . ' Test Name Street, Unit ' . $address['unit'],
            'city' => 'Boston',
            'state' => 'MA',
            'postcode' => '12345'
        ];

        $this->assertSame($return, $expected);
    }

    public function testBasicParsedAddressWithStateAndPostcodeInCity()
    {
        $sync = [
            'type' => 'parsed',
            'house_number' => 'house_number',
            'street_name' => 'street_name',
            'street_type' => null,
            'unit' => 'unit',
            'city' =>  'city',
            'state' => null,
            'postcode' => null,
            'default_city' => null,
            'default_state' => null,
            'default_postcode' => null,
            'StateInCity' => true,
            'PostcodeInCity' => true,
        ];

        $address = [
            'house_number' => random_int(10, 1000),
            'street_name' => 'Test Name Street',
            'unit' => random_int(1, 100),
            'city' => 'Boston, MA 12345'
        ];

        $return = $this->entitySync->syncAddress($address, $sync);

        $expected = [
            'address' => $address['house_number'] . ' Test Name Street, Unit ' . $address['unit'],
            'city' => 'Boston',
            'state' => 'MA',
            'postcode' => '12345'
        ];

        $this->assertSame($return, $expected);
    }
}