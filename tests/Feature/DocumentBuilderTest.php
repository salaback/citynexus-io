<?php

namespace Tests\Feature;

use App\DocumentMgr\DocumentBuilder;
use App\DocumentMgr\Model\DocumentTemplate;
use App\PropertyMgr\Model\Entity;
use App\PropertyMgr\Model\Property;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DocumentBuilderTest extends TestCase
{
    private $documentBuilder;

    use DatabaseTransactions;

    protected  $connectionsToTransact = [
        'public',
        'tenant'
    ];

    public function setUp()
    {
        parent::setUp();
        $this->documentBuilder = new DocumentBuilder();
    }
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateDataArray()
    {
        $body = "<p>&lt;&lt;- entity:full_name -&gt;&gt;</p>
                <p>&lt;&lt;- entity:mailing_address -&gt;&gt;</p>
                <p><br></p>
                <p>&lt;&lt;- entity:last_name -&gt;&gt;;</p>
                <p><br></p>
                <p>This letter is to inform you of an issue in unit  &lt;&lt;- unit:unit -&gt;&gt; of your building at  &lt;&lt;- building:address -&gt;&gt;.</p>
                <p><br></p>
                <p>Please contact me at your earliest convenience.</p>
                <p><br></p>
                <p>Regards,</p>
                <p><br></p>
                <p>&lt;&lt;- sender:full_name -&gt;&gt;</p>
                <p>&lt;&lt;- sender:title -&gt;&gt;, &lt;&lt;- sender:department -&gt;&gt;</p>";

        $result = $this->documentBuilder->createDataArray($body);

        $expected = [
            'entity' => ['full_name' => null, 'mailing_address' => null, 'last_name' => null],
            'unit' => ['unit' => null],
            'building' => ['address' => null],
            'sender' => ['full_name' => null, 'title' => null, 'department' => null],
        ];

        $this->assertSameSize($expected, $result);
    }

    public function testLoadDataArrayWithBuilding()
    {
        $this->client->logInAsClient();
        $body = "
                    <p>&lt;&lt;- building:address -&gt;&gt;</p>
                    <p>&lt;&lt;- building:units -&gt;&gt;</p>
                    <p>&lt;&lt;- building:city -&gt;&gt;</p>
                    <p>&lt;&lt;- building:state -&gt;&gt;</p>
                    <p>&lt;&lt;- building:postcode -&gt;&gt;</p>
                ";

        $property = factory(Property::class)->create(['is_building' => true]);
        $unit = factory(Property::class)->create(['is_unit' => true, 'building_id' => $property->id, 'unit' => random_int(1,1000)]);
        $data = $this->documentBuilder->createDataArray($body);
        $result = $this->documentBuilder->loadDataArray($data, ['property' => $property,]);

        $this->assertSame(strtoupper($property->oneLineAddress), $result['building']['address'], $property->oneLineAddress);
        $this->assertSame(1, $result['building']['units'], 1);
        $this->assertSame(strtoupper($property->city), $result['building']['city'], $property->city);
        $this->assertSame(strtoupper($property->state), $result['building']['state'], $property->state);
        $this->assertSame(strtoupper($property->postcode), $result['building']['postcode'], $property->postcode);
    }

    public function testLoadDataArrayWithUnit()
    {
        $this->client->logInAsClient();
        $body = "
                    <p>&lt;&lt;- building:address -&gt;&gt;</p>
                    <p>&lt;&lt;- building:units -&gt;&gt;</p>
                    <p>&lt;&lt;- building:city -&gt;&gt;</p>
                    <p>&lt;&lt;- building:state -&gt;&gt;</p>
                    <p>&lt;&lt;- building:postcode -&gt;&gt;</p>
                    <p>&lt;&lt;- unit:unit -&gt;&gt;</p>
                    <p>&lt;&lt;- unit:address -&gt;&gt;</p>
                ";

        $property = factory(Property::class)->create(['is_building' => true]);
        $unit = factory(Property::class)->create(['is_unit' => true, 'building_id' => $property->id, 'unit' => random_int(1,1000)]);
        $data = $this->documentBuilder->createDataArray($body);
        $result = $this->documentBuilder->loadDataArray($data, ['property' => $unit,]);

        $this->assertSame(strtoupper($property->oneLineAddress), $result['building']['address'], $property->oneLineAddress, 'Property one line address');
        $this->assertSame(1, $result['building']['units'], 1);
        $this->assertSame(strtoupper($property->city), $result['building']['city'], $property->city);
        $this->assertSame(strtoupper($property->state), $result['building']['state'], $property->state);
        $this->assertSame(strtoupper($property->postcode), $result['building']['postcode'], $property->postcode);
        $this->assertSame(strtoupper($unit->unit), $result['unit']['unit'], $unit->unit, 'Unit number');
        $this->assertSame(strtoupper($unit->oneLineAddress), $result['unit']['address'], $unit->oneLineAddress, 'Unit one line address');
    }

    public function testLoadDataArrayWithEntity()
    {
        $this->client->logInAsClient();
        $body = "
                    <p>&lt;&lt;- entity:first_name -&gt;&gt;</p>
                    <p>&lt;&lt;- entity:last_name -&gt;&gt;</p>
                    <p>&lt;&lt;- entity:full_name -&gt;&gt;</p>
                    <p>&lt;&lt;- entity:mailing_address -&gt;&gt;</p>
                ";

        $data = $this->documentBuilder->createDataArray($body);
        $entity = factory(Entity::class)->create();
        $result = $this->documentBuilder->loadDataArray($data, ['entity' => $entity,]);

        $this->assertSame(strtoupper($entity->first_name), $result['entity']['first_name'], 'First name of entity');
        $this->assertSame(strtoupper($entity->last_name), $result['entity']['last_name'], 'Last name of entity');
        $this->assertSame(strtoupper($entity->name), $result['entity']['full_name'], 'Full name of entity');



    }

    public function testBuildDocument()
    {
        $this->client->logInAsClient();
        $user = factory(User::class)->create();
        $this->be($user);

        $property = factory(Property::class)->create(['is_building' => true]);

        $body = "<p>This is a building address &lt;&lt;- building:address -&gt;&gt;.</p>";


        $expected = "<p>This is a building address " . strtoupper($property->oneLineAddress) . ".</p>";

        $template = factory(DocumentTemplate::class)->create(['body' => $body]);
        $result = $this->documentBuilder->buildDocument($template->id, ['property' => $property]);

        $this->assertSame($result->body, $expected);
    }

    public function testReplaceKey()
    {
        $key = 'test:key';
        $data = [
            'test' => [
                'key' => 'result'
            ]
        ];

        $result = $this->invokeMethod($this->documentBuilder, 'replaceKey', [$key, $data]);

        $this->assertSame($result, 'result');
    }

}
