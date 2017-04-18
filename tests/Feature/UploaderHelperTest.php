<?php

namespace Tests\Feature;

use App\Services\MultiTenant;
use Carbon\Carbon;
use CityNexus\CityNexus\ProcessData;
use CityNexus\DataStore\DataSet;
use CityNexus\DataStore\Helper\UploadHelper;
use CityNexus\DataStore\Upload;
use CityNexus\DataStore\Uploader;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UploaderHelperTest extends TestCase
{

    use DatabaseTransactions;

    private $client;

    public function setUp()
    {
        parent::setUp();
        $multiTenant = new MultiTenant();
        $this->client = $multiTenant->createClient('Test Client', 'testclient');
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->client->delete();
    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCSVUpload()
    {
        Queue::fake();

        $uploadHelper = new UploadHelper();

        $dataset = DataSet::create([
            'name' => 'Test Data Set',
            'table_name' => 'test_data_set',
            'schema' => [
                    'id' => [
                        'name' => 'Id',
                        'key' => 'id',
                        'type' => 'integer',
                        'show' => 'on'
                    ],
                    'data' => [
                        'name' => 'Data',
                        'key' => 'data',
                        'type' => 'data',
                        'show' => 'on'
                    ]
            ]
        ]);

        $uploader = Uploader::create([
            'dataset_id' => $dataset->id,
            'name' => 'Test Uploader',
            'type'  => 'profile',
            'map' => [
                'id' => 'id',
                'data' => 'data'
            ]
        ]);

        $upload = Upload::create([
            'uploader_id' => $uploader->id,
            'source' => 'test',
            'size' => 1,
            'file_type' => 'csv',
            'user_id' => 1,
        ]);

        $uploadHelper->csvUpload($upload->id, []);

        Queue::assertPushed(ProcessData::class, function($job) use ($upload)
        {
           return $job->upload_id == $upload->id;
        });
    }
}
